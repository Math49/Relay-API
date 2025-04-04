<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListRequest;
use Illuminate\Http\Request;
use Exception;
use App\Models\ListModel;
use DateTime;

class ListController extends Controller
{
    // GET /lists
    public function AllLists(ListRequest $request){
        try {
            $lists = ListModel::all();

            if ($lists->isEmpty()) {
                return response()->json([
                    'message' => 'Aucune liste trouvée'
                ], 404);
            }

            $lists->load('productLists.product');

            if ($request->header('Accept') === 'application/json') {
                return response()->json($lists);
            } else {
                return response("Le format demandé n'est pas disponible", 406);
            }
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la récupération des listes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // GET /list/{ID_store}
    public function ListByStore(ListRequest $request, $ID_store){
        try {
            $lists = ListModel::where('ID_store', $ID_store)->get();

            if ($lists->isEmpty()) {
                return response()->json([
                    'message' => 'Liste non trouvée'
                ], 404);
            }

            $lists->load('productLists.product');

            if ($request->header('Accept') === 'application/json') {
                return response()->json($lists);
            } else {
                return response("Le format demandé n'est pas disponible", 406);
            }
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la récupération de la liste',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // GET /list/{ID_store}/{ID_list}
    public function ListByID(ListRequest $request, $ID_store, $ID_list){
        try {
            $list = ListModel::find($ID_list);

            if (!$list) {
                return response()->json([
                    'message' => 'Liste non trouvée'
                ], 404);
            }

            $list->load('productLists.product');

            if ($request->header('Accept') === 'application/json') {
                return response()->json($list);
            } else {
                return response("Le format demandé n'est pas disponible", 406);
            }
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la récupération de la liste',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // POST /list
    public function CreateList(ListRequest $request){
        try {
            $request->validated();
            $products = $request->products;

            $list = new ListModel;
            $list->ID_store = $request->ID_store;
            $list->Creation_date = new DateTime();
            $list->save();

            foreach ($products as $product) {
                $list->productLists()->create([
                    'ID_product' => $product['ID_product'],
                    'Quantity' => $product['Quantity']
                ]);
            }

            return response()->json($list, 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la création de la liste',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // PUT /list/{ID_list}
    public function UpdateList(ListRequest $request, $ID_list){
        try {
            $request->validated();
            $list = ListModel::find($ID_list);

            if (!$list) {
                return response()->json([
                    'message' => 'Liste non trouvée'
                ], 404);
            }

            $products = $request->products;

            foreach ($products as $product) {
                $list->productLists()->updateOrCreate([
                    'ID_product' => $product['ID_product']
                ], [
                    'Quantity' => $product['Quantity']
                ]);
            }

            return response()->json($list, 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la mise à jour de la liste',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // DELETE /list
    public function DeleteList(ListRequest $request){
        try {
            $ID_list = $request->ID_list;

            $list = ListModel::find($ID_list);

            if (!$list) {
                return response()->json([
                    'message' => 'Liste non trouvée'
                ], 404);
            }

            $list->productLists()->delete();
            $list->delete();
            
            return response()->json([
                'message' => 'Liste supprimée'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la suppression de la liste',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
