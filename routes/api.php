<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

// Route d'inscription (register)
Route::post('/register', function (Request $request) {
    $request->validate([
        'Name' => 'required|string|max:255',
        'Password' => 'required|string|min:6',
        'Is_admin' => 'boolean',
        'Id_store' => 'nullable|integer',
    ]);

    $user = User::create([
        'Name' => $request->Name,
        'Password' => Hash::make($request->Password),
        'Is_admin' => $request->Is_admin ?? false,
        'Id_store' => $request->Id_store,
    ]);

    return response()->json([
        'message' => 'Utilisateur créé avec succès',
        'user' => $user,
        'token' => $user->createToken('auth-token')->plainTextToken
    ], 201);
});

// Route de connexion (login)
Route::post('/login', function (Request $request) {
    $request->validate([
        'Name' => 'required|string',
        'Password' => 'required|string',
    ]);

    $user = User::where('Name', $request->Name)->first();

    if (!$user || !Hash::check($request->Password, $user->Password)) {
        throw ValidationException::withMessages([
            'error' => ['Les informations de connexion sont incorrectes.'],
        ]);
    }

    return response()->json([
        'message' => 'Connexion réussie',
        'user' => $user,
        'token' => $user->createToken('auth-token')->plainTextToken
    ]);
});

// Route pour récupérer les utilisateurs (get users) - Protégée par Sanctum
Route::middleware('auth:sanctum')->get('/users', function () {
    return response()->json(User::all());
});

// Route de déconnexion (logout)
Route::middleware('auth:sanctum')->post('/logout', function (Request $request) {
    $request->user()->tokens()->delete();
    return response()->json(['message' => 'Déconnexion réussie']);
});

