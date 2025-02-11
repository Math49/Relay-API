<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use app\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // GET /users
    public function allUsers(Request $request){
        try{
            $users = User::all();
            
            if($request->header('Accept') === 'application/json'){
                return response()->json($users);
            } else {
                return response("Le format demandé n'est pas disponible", 406);
            }

        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la récupération des utilisateurs',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // GET /user/{id}
    public function userByID(Request $request, $id){
        try{
            $user = User::find($id);
            
            if($user){
                if($request->header('Accept') === 'application/json'){
                    return response()->json($user);
                } else {
                    return response("Le format demandé n'est pas disponible", 406);
                }
            } else {
                return response()->json([
                    'message' => 'Utilisateur non trouvé'
                ], 404);
            }

        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la récupération de l\'utilisateur',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // POST /user
    public function createUser(Request $request){
        try{
            $request->validate([
                'Name' => 'required|string|max:50',
                'Password' => 'required|string|min:6',
                'Is_admin' => 'boolean',
                'Id_store' => 'integer',
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

    // PUT /user/{id}
    public function updateUser(Request $request, $id){
        try{

            $user = User::find($id);

            if($user){
                $request->validate([
                    'Name' => 'string|max:255',
                    'Password' => 'string|min:6',
                    'Is_admin' => 'boolean',
                    'Id_store' => 'nullable|integer',
                ]);
                
                $user->Name = $request->Name ?? $user->Name;
                $user->Password = $request->Password ? Hash::make($request->Password) : $user->Password;
                $user->Is_admin = $request->Is_admin ?? $user->Is_admin;
                $user->Id_store = $request->Id_store ?? $user->Id_store;
                
                $user->save();
                
                return response()->json([
                    'message' => 'Utilisateur modifié avec succès',
                    'user' => $user
                ]);
            } else {
                return response()->json([
                    'message' => 'Utilisateur non trouvé'
                ], 404);
            }

        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la modification de l\'utilisateur',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // DELETE /user/{id}
    public function deleteUser($id){
        try{
            $user = User::find($id);

            if($user){
                $user->delete();
                
                return response()->json([
                    'message' => 'Utilisateur supprimé avec succès'
                ]);
            } else {
                return response()->json([
                    'message' => 'Utilisateur non trouvé'
                ], 404);
            }

        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la suppression de l\'utilisateur',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
