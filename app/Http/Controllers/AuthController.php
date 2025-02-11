<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Exception;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // POST /register
    public function register(Request $request)
    {
        try{
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
            
        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la création de l\'utilisateur',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // POST /login
    public function login(Request $request){
        try{

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

        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la connexion',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // POST /logout
    public function logout(Request $request){
        try{
            $request->user()->tokens()->delete();
            return response()->json(['message' => 'Déconnexion réussie']);
        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la déconnexion',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

