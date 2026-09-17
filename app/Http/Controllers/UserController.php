<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCreatedMail;

class UserController extends Controller
{
    /**
     * Récupérer tous les utilisateurs
     */
    public function index()
    {
        $users = User::with('role')
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'users' => $users
        ]);
    }

    /**
     * Récupérer un utilisateur
     */
    public function show($id)
    {
        $user = User::with('role')->find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'user' => $user
        ]);
    }

    /**
     * Créer un utilisateur
     */
   public function store(Request $request)
{
    $request->merge([
        'email' => Str::lower(trim($request->input('email')))
    ]);

    $request->validate([
        'full_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'role_id' => 'required|exists:roles,id',
        'is_active' => 'boolean',
    ]);

    // Génération automatique du mot de passe
    $password = Str::password(
        12,
        letters: true,
        numbers: true,
        symbols: false,
        spaces: false
    );

    // Création du compte avec le mot de passe hashé
    $user = User::create([
        'full_name' => $request->full_name,
        'email' => $request->email,
        'role_id' => $request->role_id,
        'is_active' => $request->is_active ?? true,
        'password' => Hash::make($password),
    ]);

    // Envoi automatique de l'e-mail
    try {
    Mail::to($user->email)->send(
        new UserCreatedMail(
            $user,
            $password,
                url('/admin/login')
        )
    );

    $emailSent = true;

    Log::info('WELCOME EMAIL SENT', [
        'user_id' => $user->id,
        'email' => $user->email,
    ]);

} catch (\Throwable $exception) {

    $emailSent = false;

    Log::error('WELCOME EMAIL FAILED', [
        'user_id' => $user->id,
        'email' => $user->email,
        'error' => $exception->getMessage(),
    ]);
} catch (\Throwable $exception) {

        Log::error('User welcome email failed.', [
            'user_id' => $user->id,
            'email' => $user->email,
            'error' => $exception->getMessage(),
        ]);

        return response()->json([
            'status' => 201,
            'message' => 'User created successfully, but the welcome email could not be sent.',
            'email_sent' => false,
            'email_error' => config('app.debug') ? $exception->getMessage() : 'SMTP delivery failed.',
            'user' => $user->load('role'),
        ], 201);
    }
}

    /**
     * Modifier un utilisateur
     */
    public function update(Request $request, $id)
    {
        if ($request->has('email')) {
            $request->merge([
                'email' => Str::lower(trim($request->input('email')))
            ]);
        }

        $rules = [
            'full_name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'role_id' => 'sometimes|exists:roles,id',
            'is_active' => 'sometimes|boolean',
            'password' => 'sometimes|string|min:8|confirmed',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        // Mise à jour des champs autorisés
        if ($request->has('full_name')) {
            $user->full_name = $request->full_name;
        }

        if ($request->has('email')) {
            $user->email = $request->email;
        }

        if ($request->has('role_id')) {
            $user->role_id = $request->role_id;
        }

        if ($request->has('is_active')) {
            $user->is_active = $request->is_active;
        }

        // Modification du mot de passe uniquement s'il est fourni
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json([
            'status' => 200,
            'message' => 'User updated successfully.',
            'user' => $user->load('role')
        ]);
    }

    /**
     * Supprimer un utilisateur
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        $user->delete();

        return response()->json([
            'status' => 200,
            'message' => 'User deleted successfully.'
        ]);
    }

    /**
     * Connexion
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $email = Str::lower(trim($request->input('email')));
        $password = $request->input('password');
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json([
                'message' => 'The email or password is incorrect.'
            ], 401);
        }

        if (!$user->is_active) {
            return response()->json([
                'message' => 'This account is inactive.'
            ], 401);
        }

        Auth::login($user);
        $user = $user->load('role');

        $token = $user->createToken('auth_token', [], now()->addMinutes(10))->plainTextToken;

        return response()->json([
            'message' => 'Connexion réussie',
            'user' => $user,
            'token' => $token
        ]);
    }

    /**
     * Récupérer l'utilisateur connecté
     */
    public function getUser(Request $request)
    {
        $user = User::with('role')
            ->find(Auth::id());

        return response()->json([
            'user' => $user,
        ]);
    }

    /**
     * Récupérer les informations et statistiques
     * de l'utilisateur connecté
     */
    public function getUserInfo(Request $request)
    {
        $user = User::with('role.permissions')
            ->find(Auth::id());

        if (!$user) {
            return response()->json([
                'message' => 'Utilisateur introuvable'
            ], 404);
        }

        $blogsCount = $user->blogs()->count();
        $eventsCount = $user->events()->count();

        // Les 5 derniers blogs
        $latestBlogs = $user->blogs()
            ->select('id', 'title', 'created_at')
            ->orderByDesc('created_at')
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'day' => $item->created_at->format('d'),
                    'month' => $item->created_at->format('M'),
                    'created_at' => $item->created_at,
                    'type' => 'Blog',
                ];
            });

        // Les 5 derniers événements
        $latestEvents = $user->events()
            ->select('id', 'title', 'created_at')
            ->orderByDesc('created_at')
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'day' => $item->created_at->format('d'),
                    'month' => $item->created_at->format('M'),
                    'created_at' => $item->created_at,
                    'type' => 'Event',
                ];
            });

        // Fusionner et trier les blogs et événements
        $latestItems = $latestBlogs
            ->merge($latestEvents)
            ->sortByDesc(fn($item) => $item['created_at'])
            ->take(5)
            ->values()
            ->map(fn($item) => collect($item)->except('created_at'));

        return response()->json([
            'user' => $user,
            'stats' => [
                'blogs' => $blogsCount,
                'events' => $eventsCount,
            ],
            'recent' => $latestItems,
        ]);
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            $request->user()
                ->currentAccessToken()
                ->delete();

            return response()->json([
                'message' => 'Deconnexion'
            ], 200);
        }

        return response()->json([
            'message' => 'Utilisateur non authentifié'
        ], 401);
    }
}

