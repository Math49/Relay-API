<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockRequest;
use Illuminate\Http\Request;
use Exception;
use App\Models\Stock;

class StockController extends Controller
{
    // GET /stocks
    public function AllStocks(StockRequest $request){
        try{
            $stocks = Stock::all()->load('product');
            
            if($request->header('Accept') === 'application/json'){
                return response()->json($stocks);
            } else {
                return response("Le format demandé n'est pas disponible", 406);
            }

        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la récupération des stocks',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // GET /stock/{ID_store}
    public function StockByStore(StockRequest $request, $ID_store){
        try{
            $stocks = Stock::where('ID_store', $ID_store)->with('product')->get();
            
            if($stocks){
                if($request->header('Accept') === 'application/json'){
                    return response()->json($stocks);
                } else {
                    return response("Le format demandé n'est pas disponible", 406);
                }
            } else {
                return response()->json([
                    'message' => 'Stock non trouvé'
                ], 404);
            }

        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la récupération du stock',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // GET /stock/{ID_store}/{ID_product}
    public function StockByStoreAndProduct(StockRequest $request, $ID_store, $ID_product){
        try{
            $stock = Stock::where('ID_store', $ID_store)->where('ID_product', $ID_product)->get();
            
            if($stock){
                if($request->header('Accept') === 'application/json'){
                    return response()->json($stock);
                } else {
                    return response("Le format demandé n'est pas disponible", 406);
                }
            } else {
                return response()->json([
                    'message' => 'Stock non trouvé'
                ], 404);
            }

        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la récupération du stock',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // POST /stock
    public function CreateStock(StockRequest $request){
        try{
            
            $request->validated();

            $stock = new Stock();
            $stock->ID_product = $request->ID_product;
            $stock->ID_store = $request->ID_store;
            $stock->Quantity = $request->Quantity;
            $stock->save();

            return response()->json($stock, 201);

        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la création du stock',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // POST /stocks
    public function CreateStocks(StockRequest $request){
        try{
            $stocks = $request->stocks;
            $stocksCreated = [];

            foreach($stocks as $stock){
                $newStock = new Stock();
                $newStock->ID_product = $stock['ID_product'];
                $newStock->ID_store = $stock['ID_store'];
                $newStock->Quantity = $stock['Quantity'];
                $newStock->save();
                array_push($stocksCreated, $newStock);
            }

            return response()->json($stocksCreated, 201);

        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la création des stocks',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // PUT /stock/{ID_store}/{ID_product}
    public function UpdateStock(StockRequest $request, $ID_store, $ID_product){
        try{
            $stock = Stock::where('ID_store', $ID_store)->where('ID_product', $ID_product)->first();
            
            if($stock){
                $stock->Quantity = $request->Quantity;
                $stock->save();
                return response()->json($stock);
            } else {
                return response()->json([
                    'message' => 'Stock non trouvé'
                ], 404);
            }

        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la mise à jour du stock',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // PUT /stocks/{ID_store}
    public function UpdateStocks(StockRequest $request, $ID_store){
        try{
            $stocks = $request->stocks;
            $stocksUpdated = [];

            foreach($stocks as $stock){
                $stockToUpdate = Stock::where('ID_store', $ID_store)->where('ID_product', $stock['ID_product'])->first();
                if($stockToUpdate){
                    $stockToUpdate->Quantity = $stock['Quantity'];
                    $stockToUpdate->save();
                    array_push($stocksUpdated, $stockToUpdate);
                }
            }

            return response()->json($stocksUpdated);

        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la mise à jour des stocks',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // DELETE /stock
    public function DeleteStock(StockRequest $request){
        try{
            $stock = Stock::where('ID_store', $request->ID_store)->where('ID_product', $request->ID_product)->first();
            
            if($stock){
                $stock->delete();
                return response()->json([
                    'message' => 'Stock supprimé'
                ]);
            } else {
                return response()->json([
                    'message' => 'Stock non trouvé'
                ], 404);
            }

        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la suppression du stock',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
