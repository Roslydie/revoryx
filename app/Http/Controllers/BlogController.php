<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    /**
     * Récupérer tous les blogs pour l'administration.
     */
    public function index()
    {
        $data = Blog::with(['user', 'tags'])
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * Récupérer les blogs publiés pour la partie publique.
     */
    public function allBlogs()
    {
        $locale = App::getLocale();

        $data = Blog::with([
            'tags',
            'user'
        ])
        ->where('status', 'published')
        ->orderBy('id', 'desc')
        ->paginate(9);

        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * Récupérer les trois derniers blogs publiés pour la homepage.
     */
    public function recentBlogs()
    {
        $data = Blog::with(['tags', 'user'])
            ->where('status', 'published')
            ->orderByDesc('id')
            ->limit(3)
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * Afficher un blog par son ID.
     */
    public function show($id)
    {
        $data = Blog::with([
            'tags',
            'user'
        ])->find($id);

        if (!$data) {
            return response()->json([
                'message' => 'Blog introuvable'
            ], 404);
        }

        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * Afficher un blog publié par son slug.
     */
    public function showBlog($slug)
    {
        $locale = App::getLocale();

        $data = Blog::with([
            'tags',
            'user'
        ])
        ->where('slug', $slug)
        ->where('status', 'published')
        ->first();

        if (!$data) {
            return response()->json([
                'message' => "No blog found for slug: {$slug}"
            ], 404);
        }

        // Récupérer les 5 derniers articles publiés
        // en excluant l'article actuellement consulté.
        $recent_blogs = Blog::where('status', 'published')
            ->where('id', '!=', $data->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->with([
                'tags',
                'user'
            ])
            ->get();

        return response()->json([
            'data' => $data,
            'recent' => $recent_blogs
        ]);
    }

    /**
     * Afficher un blog en mode preview.
     *
     * Permet notamment de prévisualiser un brouillon
     * avant sa publication.
     */
    public function showBlogPreview($slug)
    {
        $locale = App::getLocale();

        $data = Blog::with([
            'tags',
            'user'
        ])
        ->where('slug', $slug)
        ->first();

        if (!$data) {
            return response()->json([
                'message' => "No blog found for slug: {$slug}"
            ], 404);
        }

        $recent_blogs = Blog::where('status', 'published')
            ->where('id', '!=', $data->id)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->with([
                'tags',
                'user'
            ])
            ->get();

        return response()->json([
            'data' => $data,
            'recent' => $recent_blogs
        ]);
    }

    /**
     * Créer un nouveau blog.
     *
     * Tout nouveau blog est créé avec le statut "draft".
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5048',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        // Upload de l'image
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('blogs', 'public');
            $validated['image'] = $path;
        }

        // Génération du slug
        $validated['slug'] = Str::slug($validated['title']);

        // Tous les nouveaux blogs sont des brouillons.
        $validated['status'] = 'draft';

        // Création du blog
        $blog = Blog::create($validated);

        // Association des tags
        if (!empty($validated['tags'])) {
            $blog->tags()->sync($validated['tags']);
        }

        return response()->json([
            'message' => 'Blog créé avec succès',
            'data' => $blog->load([
                'tags',
                'user'
            ])
        ], 201);
    }

    /**
     * Modifier un blog.
     */
    public function edit(Request $request, $id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json([
                'message' => 'Article introuvable'
            ], 404);
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'required|string',
            'image' => 'nullable',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        // Gestion de la nouvelle image
        if ($request->hasFile('image')) {

            // Supprimer l'ancienne image
            if ($blog->image && Storage::disk('public')->exists($blog->image)) {
                Storage::disk('public')->delete($blog->image);
            }

            // Enregistrer la nouvelle image
            $path = $request->file('image')->store('blogs', 'public');
            $data['image'] = $path;

        } else {
            // Conserver l'ancienne image
            unset($data['image']);
        }

        // Mettre à jour le slug
        $data['slug'] = Str::slug($data['title']);

        // Mise à jour
        $blog->update($data);

        // Synchronisation des tags
        if ($request->has('tags')) {
            $blog->tags()->sync($request->tags);
        }

        return response()->json([
            'message' => 'Article mis à jour avec succès',
            'data' => $blog->load([
                'tags',
                'user'
            ])
        ]);
    }

    /**
     * Publier directement un blog.
     *
     * Un blog doit être en draft avant de pouvoir être publié.
     */
    public function publish($id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json([
                'message' => 'Blog introuvable'
            ], 404);
        }

        if ($blog->status === 'published') {
            return response()->json([
                'message' => 'Ce blog est déjà publié'
            ], 400);
        }

        $blog->status = 'published';
        $blog->save();

        return response()->json([
            'message' => 'Blog publié avec succès',
            'data' => $blog->load([
                'tags',
                'user'
            ])
        ]);
    }

    /**
     * Remettre un blog en brouillon.
     *
     * Utile si l'administrateur veut dépublier un article.
     */
    public function unpublish($id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json([
                'message' => 'Blog introuvable'
            ], 404);
        }

        $blog->status = 'draft';
        $blog->save();

        return response()->json([
            'message' => 'Blog remis en brouillon avec succès',
            'data' => $blog->load([
                'tags',
                'user'
            ])
        ]);
    }

    /**
     * Supprimer un blog.
     */
    public function destroy($id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json([
                'message' => 'Blog introuvable'
            ], 404);
        }

        // Supprimer l'image
        if ($blog->image) {
            if (Storage::disk('public')->exists($blog->image)) {
                Storage::disk('public')->delete($blog->image);
            }
        }

        // Supprimer les relations avec les tags
        $blog->tags()->detach();

        // Supprimer le blog
        $blog->delete();

        return response()->json([
            'message' => 'Blog et image supprimés avec succès'
        ]);
    }

    /**
     * Upload d'une image dans public/images/Blogs.
     *
     * Cette méthode peut être utilisée par un éditeur de texte
     * ou un composant frontend qui nécessite un upload séparé.
     */
    public function uploadImg(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5048'
        ]);

        if ($request->hasFile('image')) {

            $picName = time() . '.' . $request->file('image')->extension();

            $request->file('image')->move(
                public_path('images/Blogs'),
                $picName
            );

            return response()->json([
                'image_url' => "/images/Blogs/$picName"
            ]);
        }

        return response()->json([
            'error' => 'Aucune image n\'a été téléchargée.'
        ], 400);
    }

    /**
     * Supprimer une image.
     */
    public function deleteImage(Request $request)
    {
        $request->validate([
            'image' => 'required|string'
        ]);

        $imagePath = $request->image;

        if (Storage::disk('public')->exists($imagePath)) {

            Storage::disk('public')->delete($imagePath);

            return response()->json([
                'message' => 'Image supprimée avec succès'
            ], 200);
        }

        return response()->json([
            'message' => 'Fichier introuvable'
        ], 404);
    }
}

