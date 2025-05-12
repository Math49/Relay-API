<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequest;
use Illuminate\Http\Request;
use Exception;
use App\Models\Store;

class StoreController extends Controller
{
    // GET /stores
    public function AllStores(StoreRequest $request)
    {
        $stores = Store::all();

        if ($request->header('Accept') === 'application/json') {
            return response()->json($stores);
        } else {
            return response("Le format demandé n'est pas disponible", 406);
        }
    }

    // GET /store/{id}
    public function StoreByID(StoreRequest $request, $id)
    {
        $store = Store::find($id);

        if (!$store) { // Vérification avant d'exécuter load()
            return response()->json([
                'message' => 'Magasin non trouvé'
            ], 404);
        }

        $store->load('categoriesEnabled', 'stocks.product');


        if ($request->header('Accept') === 'application/json') {
            return response()->json($store);
        } else {
            return response("Le format demandé n'est pas disponible", 406);
        }
    }

    // POST /store
    public function createStore(StoreRequest $request)
    {
        $request->validated();

        $store = new Store();
        $store->Address = $request->Address;
        $store->Phone = $request->Phone;
        $store->Manager_name = $request->Manager_name;
        $store->Manager_phone = $request->Manager_phone;
        $store->save();

        return response()->json($store, 201);
    }

    // PUT /store/{id}
    public function updateStore(StoreRequest $request, $id)
    {

        $store = Store::find($id);

        if ($store) {
            $request->validated();

            $store->Address = $request->Address ? $request->Address : $store->Address;
            $store->Phone = $request->Phone ? $request->Phone : $store->Phone;
            $store->Manager_name = $request->Manager_name ? $request->Manager_name : $store->Manager_name;
            $store->Manager_phone = $request->Manager_phone ? $request->Manager_phone : $store->Manager_phone;
            $store->save();

            return response()->json($store, 200);
        } else {
            return response()->json([
                'message' => 'Magasin non trouvé'
            ], 404);
        }
    }

    // DELETE /store
    public function deleteStore(StoreRequest $request)
    {
        $store = Store::find($request->ID_store);

        if ($store) {
            $store->delete();
            return response()->json([
                'message' => 'Magasin supprimé avec succès'
            ]);
        } else {
            return response()->json([
                'message' => 'Magasin non trouvé'
            ], 404);
        }
    }
}
