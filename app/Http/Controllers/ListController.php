<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListRequest;
use Illuminate\Http\Request;
use Exception;
use App\Models\ListModel;
use DateTime;
use Illuminate\Support\Facades\Log;

class ListController extends Controller
{
    // GET /lists
    public function AllLists(ListRequest $request)
    {
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
    }

    // GET /list/{ID_store}
    public function ListByStore(ListRequest $request, $ID_store)
    {
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
    }

    // GET /list/{ID_store}/{ID_list}
    public function ListByID(ListRequest $request, $ID_store, $ID_list)
    {
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
    }

    // POST /list
    public function CreateList(ListRequest $request)
    {
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
    }

    // PUT /list
    public function UpdateList(ListRequest $request)
    {
        $request->validated();

        $ID_list = $request->ID_list;
        $list = ListModel::find($ID_list);

        if (!$list) {
            return response()->json([
                'message' => 'Liste non trouvée'
            ], 404);
        }

        $products = $request->products;

        foreach ($products as $product) {
            $list->productLists()
                ->where('ID_product', $product['ID_product'])
                ->where('ID_list', $ID_list)
                ->update([
                    'Quantity' => $product['Quantity']
                ]);
        }

        return response()->json($list, 200);
    }

    // DELETE /list
    public function DeleteList(ListRequest $request)
    {
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
    }
}
