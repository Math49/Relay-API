<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use app\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // GET /users
    public function allUsers(UserRequest $request)
    {

        $users = User::all()->load('store');

        if ($request->header('Accept') === 'application/json') {
            return response()->json($users);
        } else {
            return response("Le format demandé n'est pas disponible", 406);
        }
    }

    // GET /user/{id}
    public function userByID(UserRequest $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'Utilisateur non trouvé'
            ], 404);
        }

        $user->load('store');

        if ($request->header('Accept') === 'application/json') {
            return response()->json($user);
        } else {
            return response("Le format demandé n'est pas disponible", 406);
        }
    }


    // POST /user
    public function createUser(UserRequest $request)
    {
        $request->validated();

        $user = User::create([
            'Name' => $request->Name,
            'Password' => Hash::make($request->Password),
            'Is_admin' => $request->Is_admin ?? false,
            'ID_store' => $request->ID_store,
        ]);

        return response()->json([
            'message' => 'Utilisateur créé avec succès',
            'user' => $user,
            'token' => $user->createToken('auth-token')->plainTextToken
        ], 201);
    }

    // PUT /user/{id}
    public function updateUser(UserRequest $request, $id)
    {
        $user = User::find($id);

        if ($user) {
            $request->validated();

            $user->Name = $request->Name ?? $user->Name;
            $user->Password = $request->Password ? Hash::make($request->Password) : $user->Password;
            $user->Is_admin = $request->Is_admin ?? $user->Is_admin;
            $user->ID_store = $request->ID_store ?? $user->ID_store;

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
    }

    // DELETE /user
    public function deleteUser(UserRequest $request)
    {
        $user = User::find($request->ID_user);

        if ($user) {
            $user->delete();

            return response()->json([
                'message' => 'Utilisateur supprimé avec succès'
            ]);
        } else {
            return response()->json([
                'message' => 'Utilisateur non trouvé'
            ], 404);
        }
    }
}
