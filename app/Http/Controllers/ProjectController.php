<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\App;

class ProjectController extends Controller
{
    /**
     * Récupérer tous les projets pour l'administration.
     */
    public function index()
    {
        $data = Project::with([
            'category',
            'user'
        ])
        ->orderBy('id', 'desc')
        ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * Récupérer tous les projets publiés pour la partie publique.
     */
    public function allproject()
    {
        $data = Project::with([
            'category',
            'user',
        ])
        ->where('status', 'published')
        ->orderBy('id', 'desc')
        ->paginate(9);

        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * Récupérer les projets récents publiés.
     */
    public function recentProjects()
    {
        $data = Project::with([
            'category',
            'user'
        ])
        ->where('status', 'published')
        ->orderBy('id', 'desc')
        ->limit(8)
        ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * Afficher un projet par son ID.
     */
    public function show($id)
    {
        $data = Project::with([
            'category',
            'user'
        ])->find($id);

        if (!$data) {
            return response()->json([
                'message' => 'Projet introuvable'
            ], 404);
        }

        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * Afficher un projet par son slug.
     */
    public function showSlug($slug)
    {
        $data = Project::with([
            'category',
            'user',
        ])
        ->where('slug', $slug)
        ->where('status', 'published')
        ->first();

        if (!$data) {
            return response()->json([
                'message' => "No project found for slug: {$slug}"
            ], 404);
        }

        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * Prévisualiser un projet.
     *
     * Cette méthode permet de consulter un projet même
     * s'il est encore en draft.
     */
    public function showProjectPreview($slug)
    {
        $data = Project::with([
            'category',
            'user'
        ])
        ->where('slug', $slug)
        ->first();

        if (!$data) {
            return response()->json([
                'message' => "No project found for slug: {$slug}"
            ], 404);
        }

        $recent_projects = Project::where('status', 'published')
            ->where('id', '!=', $data->id)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        return response()->json([
            'data' => $data,
            'recent' => $recent_projects
        ]);
    }

    /**
     * Créer un nouveau projet.
     *
     * Tout nouveau projet est créé avec le statut "draft".
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'client' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'years' => 'required|string|max:4',
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'description' => 'required|string',
            'content' => 'required|string',
            'user_id' => 'required|exists:users,id',
        ]);

        // Préparer les images
        $images = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('projects', 'public');
            }
        }

        // Création du projet
        $project = Project::create([
            'slug' => Str::slug($validated['title']),
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'client' => $validated['client'],
            'location' => $validated['location'],
            'years' => $validated['years'],
            'images' => $images,
            'description' => $validated['description'],
            'content' => $validated['content'],
            'status' => 'draft',
            'user_id' => $validated['user_id'],
        ]);

        return response()->json([
            'message' => 'Projet créé avec succès',
            'data' => $project->load([
                'category',
                'user'
            ])
        ], 201);
    }

    /**
     * Modifier un projet.
     */
    public function edit(Request $request, $id)
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json([
                'message' => 'Projet introuvable'
            ], 404);
        }

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'client' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'years' => 'required|string|max:4',
            'description' => 'required|string',
            'content' => 'required|string',
            'existing_images' => 'nullable|array',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        // Images existantes conservées
        $images = $request->input('existing_images', []);

        // Ajouter les nouvelles images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('projects', 'public');
            }
        }

        // Supprimer les anciennes images qui ne sont plus conservées
        if (is_array($project->images)) {
            foreach ($project->images as $oldImage) {
                if (!in_array($oldImage, $images)) {
                    if (Storage::disk('public')->exists($oldImage)) {
                        Storage::disk('public')->delete($oldImage);
                    }
                }
            }
        }

        // Mise à jour du projet
        $project->update([
            'slug' => Str::slug($validated['title']),
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'client' => $validated['client'],
            'location' => $validated['location'],
            'years' => $validated['years'],
            'description' => $validated['description'],
            'content' => $validated['content'],
            'images' => $images,
        ]);

        return response()->json([
            'message' => 'Projet mis à jour avec succès',
            'data' => $project->load([
                'category',
                'user'
            ])
        ]);
    }

    /**
     * Publier directement un projet.
     *
     * Un projet passe directement de draft à publish.
     */
    public function publish($id)
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json([
                'message' => 'Projet introuvable'
            ], 404);
        }

        if ($project->status === 'published') {
            return response()->json([
                'message' => 'Ce projet est déjà publié'
            ], 400);
        }

        $project->status = 'published';
        $project->save();

        return response()->json([
            'message' => 'Projet publié avec succès',
            'data' => $project->load([
                'category',
                'user'
            ])
        ]);
    }

    /**
     * Remettre un projet en brouillon.
     */
    public function unpublish($id)
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json([
                'message' => 'Projet introuvable'
            ], 404);
        }

        $project->status = 'draft';
        $project->save();

        return response()->json([
            'message' => 'Projet remis en brouillon avec succès',
            'data' => $project->load([
                'category',
                'user'
            ])
        ]);
    }

    /**
     * Supprimer un projet.
     */
    public function destroy($id)
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json([
                'message' => 'Projet introuvable'
            ], 404);
        }

        // Supprimer les images associées
        if (is_array($project->images)) {
            foreach ($project->images as $image) {
                if (Storage::disk('public')->exists($image)) {
                    Storage::disk('public')->delete($image);
                }
            }
        }

        // Supprimer le projet
        $project->delete();

        return response()->json([
            'message' => 'Projet et images supprimés avec succès'
        ]);
    }
}

