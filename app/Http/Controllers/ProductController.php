<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;
use Exception;
use App\Models\Product;

class ProductController extends Controller
{
    // GET /products
    public function AllProduct(ProductRequest $request){
        try{
            $products = Product::all();
            
            if($request->header('Accept') === 'application/json'){
                return response()->json($products);
            } else {
                return response("Le format demandé n'est pas disponible", 406);
            }

        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la récupération des produits',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // GET /product/{id}
    public function ProductByID(ProductRequest $request, $id_product){
        try{
            $product = Product::find($id_product);
            
            if($product){
                if($request->header('Accept') === 'application/json'){
                    return response()->json($product);
                } else {
                    return response("Le format demandé n'est pas disponible", 406);
                }
            } else {
                return response()->json([
                    'message' => 'Produit non trouvé'
                ], 404);
            }

        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la récupération du produit',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // POST /product
    public function CreateProduct(ProductRequest $request){
        try{
            
            $request->validated();

            $product = new Product();
            $product->Label = $request->input('Label');
            $product->Box_quantity = $request->input('Box_quantity');
            $product->Image = $request->input('Image');
            $product->Packing = $request->input('Packing');
            $product->Barcode = $request->input('Barcode');
            $product->ID_category = $request->input('ID_category');
            $product->save();

            return response()->json($product, 201);

        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la création du produit',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // PUT /product/{id}
    public function UpdateProduct(ProductRequest $request, $id_product){
        try{
            $product = Product::find($id_product);
            
            if($product){
                $request->validated();

                $product->Label = $request->input('Label') ? $request->input('Label') : $product->Label;
                $product->Box_quantity = $request->input('Box_quantity') ? $request->input('Box_quantity') : $product->Box_quantity;
                $product->Image = $request->input('Image') ? $request->input('Image') : $product->Image;
                $product->Packing = $request->input('Packing') ? $request->input('Packing') : $product->Packing;
                $product->Barcode = $request->input('Barcode') ? $request->input('Barcode') : $product->Barcode;
                $product->ID_category = $request->input('ID_category') ? $request->input('ID_category') : $product->ID_category;
                $product->save();

                return response()->json($product, 200);
            } else {
                return response()->json([
                    'message' => 'Produit non trouvé'
                ], 404);
            }

        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la modification du produit',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // DELETE /product
    public function DeleteProduct(ProductRequest $request){
        try{
            $product = Product::find($request->input('ID_product'));
            
            if($product){
                $product->delete();
                return response()->json([
                    'message' => 'Produit supprimé'
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Produit non trouvé'
                ], 404);
            }

        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la suppression du produit',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
