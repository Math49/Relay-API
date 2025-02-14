<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequest;
use Illuminate\Http\Request;
use Exception;
use App\Models\Store;

class StoreController extends Controller
{
    // GET /stores
    public function AllStores(Request $request){
        try{
            $stores = Store::all();
            
            if($request->header('Accept') === 'application/json'){
                return response()->json($stores);
            } else {
                return response("Le format demandé n'est pas disponible", 406);
            }

        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la récupération des magasins',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // GET /store/{id}
    public function StoreByID(Request $request, $id){
        try{
            $store = Store::find($id);
            
            if($store){
                if($request->header('Accept') === 'application/json'){
                    return response()->json($store);
                } else {
                    return response("Le format demandé n'est pas disponible", 406);
                }
            } else {
                return response()->json([
                    'message' => 'Magasin non trouvé'
                ], 404);
            }

        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la récupération du magasin',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // POST /store
    public function createStore(StoreRequest $request){
        try{
            $request->validated();

            $store = new Store();
            $store->Address = $request->input('Address');
            $store->Phone = $request->input('Phone');
            $store->Manager_name = $request->input('Manager_name');
            $store->Manager_phone = $request->input('Manager_phone');
            $store->save();

            return response()->json($store, 201);

        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la création du magasin',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // PUT /store/{id}
    public function updateStore(Request $request, $id){
        try{
            $store = Store::find($id);

            if($store){
                $request->validate([
                    
                ]);

                $store->Address = $request->input('Address') ? $request->input('Address') : $store->Address;
                $store->Phone = $request->input('Phone') ? $request->input('Phone') : $store->Phone;
                $store->Manager_name = $request->input('Manager_name') ? $request->input('Manager_name') : $store->Manager_name;
                $store->Manager_phone = $request->input('Manager_phone') ? $request->input('Manager_phone') : $store->Manager_phone;
                $store->save();

                return response()->json($store, 200);
            } else {
                return response()->json([
                    'message' => 'Magasin non trouvé'
                ], 404);
            }
        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la mise à jour du magasin',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // DELETE /store/{id}
    public function deleteStore($id){
        try{
            $store = Store::find($id);

            if($store){
                $store->delete();
                return response()->json([
                    'message' => 'Magasin supprimé avec succès'
                ]);
            } else {
                return response()->json([
                    'message' => 'Magasin non trouvé'
                ], 404);
            }
        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la suppression du magasin',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
