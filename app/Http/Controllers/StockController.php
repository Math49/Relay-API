<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockRequest;
use Illuminate\Http\Request;
use Exception;
use App\Models\Stock;

class StockController extends Controller
{
    // GET /stocks
    public function AllStocks(StockRequest $request)
    {
        $stocks = Stock::all();

        if ($stocks->isEmpty()) {
            return response()->json([
                'message' => 'Aucun stock trouvé'
            ], 404);
        }

        $stocks->load('product');

        if ($request->header('Accept') === 'application/json') {
            return response()->json($stocks);
        } else {
            return response("Le format demandé n'est pas disponible", 406);
        }
    }

    // GET /stock/{ID_store}
    public function StockByStore(StockRequest $request, $ID_store)
    {
        $stocks = Stock::where('ID_store', $ID_store)
            ->where('is_empty', '!=', 1)
            ->get();

        if (!$stocks->isEmpty()) {

            $stocks->load('product');

            if ($request->header('Accept') === 'application/json') {
                return response()->json($stocks);
            } else {
                return response("Le format demandé n'est pas disponible", 406);
            }
        } else {
            return response()->json([
                'message' => 'Stock non trouvé'
            ], 404);
        }
    }

    // GET /stock/{ID_store}/{ID_product}
    public function StockByStoreAndProduct(StockRequest $request, $ID_store, $ID_product)
    {
        $stock = Stock::where('ID_store', $ID_store)->where('ID_product', $ID_product)->get();

        if (!$stock->isEmpty()) {
            if ($request->header('Accept') === 'application/json') {
                return response()->json($stock);
            } else {
                return response("Le format demandé n'est pas disponible", 406);
            }
        } else {
            return response()->json([
                'message' => 'Stock non trouvé'
            ], 404);
        }
    }

    // POST /stock
    public function CreateStock(StockRequest $request)
    {

        $request->validated();

        $stock = new Stock();
        $stock->ID_product = $request->ID_product;
        $stock->ID_store = $request->ID_store;
        $stock->Quantity = $request->Quantity;
        $stock->Nmb_boxes = $request->Nmb_boxes;
        $stock->Nmb_on_shelves = $request->Nmb_on_shelves;
        $stock->Is_empty = false;
        $stock->save();

        return response()->json($stock, 201);
    }

    // POST /stocks
    public function CreateStocks(StockRequest $request)
    {
        $stocks = $request->stocks;
        $stocksCreated = [];


        foreach ($stocks as $stock) {


            $newStock = new Stock();
            $newStock->ID_product = $stock['ID_product'];
            $newStock->ID_store = $stock['ID_store'];
            $newStock->Quantity = $stock['Quantity'];
            $newStock->Nmb_boxes = $stock['Nmb_boxes'];
            $newStock->Nmb_on_shelves = $stock['Nmb_on_shelves'];
            $newStock->Is_empty = false;
            $newStock->save();
            array_push($stocksCreated, $newStock);
        }

        return response()->json($stocksCreated, 201);
    }

    // PUT /stock/{ID_store}/{ID_product}
    public function UpdateStock(StockRequest $request, $ID_store, $ID_product)
    {
        $stock = Stock::where('ID_store', $ID_store)->where('ID_product', $ID_product)->first();
        if ($stock) {
            $stock->Quantity = $request->Quantity ?? $stock->Quantity;
            $stock->Nmb_boxes = $request->Nmb_boxes ? $request->Nmb_boxes : $stock->Nmb_boxes;
            $stock->Nmb_on_shelves = $request->Nmb_on_shelves ?? $stock->Nmb_on_shelves;
            $stock->save();
            return response()->json($stock);
        } else {
            return response()->json([
                'message' => 'Stock non trouvé'
            ], 404);
        }
    }

    // PUT /stocks/{ID_store}
    public function UpdateStocks(StockRequest $request, $ID_store)
    {
        $stocks = $request->stocks;
        $stocksUpdated = [];

        foreach ($stocks as $stock) {
            if ($stock['Quantity'] == 0 && $stock['Nmb_boxes'] == 0) {
                $Is_empty = true;
            } else {
                $Is_empty = false;
            }

            $stockToUpdate = Stock::where('ID_store', $ID_store)->where('ID_product', $stock['ID_product'])->first();
            if ($stockToUpdate) {
                $stockToUpdate->Quantity = $stock['Quantity'] ?? $stockToUpdate->Quantity;
                $stockToUpdate->Nmb_boxes = $stock['Nmb_boxes'] ?? $stockToUpdate->Nmb_boxes;
                $stockToUpdate->Nmb_on_shelves = $stock['Nmb_on_shelves'] ?? $stockToUpdate->Nmb_on_shelves;
                $stockToUpdate->Is_empty = $Is_empty;
                $stockToUpdate->save();
                array_push($stocksUpdated, $stockToUpdate);
            } else {
                return response()->json([
                    'message' => 'Stock non trouvé'
                ], 404);
            }
        }

        return response()->json($stocksUpdated);
    }

    // DELETE /stock
    public function DeleteStock(StockRequest $request)
    {
        $stock = Stock::where('ID_store', $request->ID_store)->where('ID_product', $request->ID_product)->first();
        if ($stock) {
            $stock->delete();
            return response()->json([
                'message' => 'Stock supprimé'
            ]);
        }
    }
}
