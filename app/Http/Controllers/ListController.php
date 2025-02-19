<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListRequest;
use Illuminate\Http\Request;
use Exception;
use App\Models\ListModel;

class ListController extends Controller
{
    // GET /lists
    public function AllLists(ListRequest $request){
        try{
            $lists = ListModel::all()->load('productList.product');
            
            if($request->header('Accept') === 'application/json'){
                return response()->json($lists);
            } else {
                return response("Le format demandé n'est pas disponible", 406);
            }

        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la récupération des listes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // GET /list/{ID_store}
    public function ListByStore(ListRequest $request, $ID_store){
        try{
            $lists = ListModel::where('ID_store', $ID_store)->load('productList.product')->get();
            
            if($lists){
                if($request->header('Accept') === 'application/json'){
                    return response()->json($lists);
                } else {
                    return response("Le format demandé n'est pas disponible", 406);
                }
            } else {
                return response()->json([
                    'message' => 'Liste non trouvée'
                ], 404);
            }

        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la récupération de la liste',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // GET /list/{ID_list}
    public function ListByID(ListRequest $request, $ID_list){
        try{
            $list = ListModel::find($ID_list)->load('productList.product');
            
            if($list){
                if($request->header('Accept') === 'application/json'){
                    return response()->json($list);
                } else {
                    return response("Le format demandé n'est pas disponible", 406);
                }
            } else {
                return response()->json([
                    'message' => 'Liste non trouvée'
                ], 404);
            }

        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la récupération de la liste',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // POST /list
    public function CreateList(ListRequest $request){
        try{
            $request->validated();
            $products = $request->products;

            $list = new ListModel;
            $list->ID_store = $request->ID_store;
            $list->Creation_date = $request->Creation_date;
            $list->save();

            foreach($products as $product){
                $list->productList()->create([
                    'ID_product' => $product['ID_product'],
                    'Quantity' => $product['Quantity']
                ]);
            }
            
            return response()->json($list, 201);

        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la création de la liste',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // PUT /list/{ID_list}
    public function UpdateList(ListRequest $request, $ID_list){
        try{
            $request->validated();
            $list = ListModel::find($ID_list);
            $products = $request->products;

            if($list){
                $list->ID_store = $request->ID_store;
                $list->Creation_date = $request->Creation_date;
                $list->save();

                foreach($products as $product){
                    $list->productList()->updateOrCreate([
                        'ID_product' => $product['ID_product'] ? $product['ID_product'] : $product['ID_product']
                    ], [
                        'Quantity' => $product['Quantity'] ? $product['Quantity'] : $product['Quantity']
                    ]);
                }
                
                return response()->json($list, 200);
            } else {
                return response()->json([
                    'message' => 'Liste non trouvée'
                ], 404);
            }

        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la mise à jour de la liste',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // DELETE /list
    public function DeleteList(ListRequest $request){
        try{
            $ID_list = $request->ID_list;
            $productLists = $request->productLists;

            $list = ListModel::find($ID_list);
            
            if($list){
                $list->delete();

                foreach($productLists as $productList){
                    $list->productList()->where('ID_product', $productList['ID_product'])->delete();
                }
                
                return response()->json([
                    'message' => 'Liste supprimée'
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Liste non trouvée'
                ], 404);
            }

        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la suppression de la liste',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
