<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use Illuminate\Http\Request;
use Exception;
use App\Models\Category;

class CategoryController extends Controller
{
    // GET /categories
    public function AllCategory(CategoryRequest $request){
        try{

            $categories = Category::all();
            
            if($request->header('Accept') === 'application/json'){
                return response()->json($categories);
            } else {
                return response("Le format demandé n'est pas disponible", 406);
            }

        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la récupération des catégories',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // GET /category/{ID_category}
    public function CategoryByID(CategoryRequest $request, $id_category){
        try{

            $category = Category::find($id_category);

            if(!$category){
                return response()->json([
                    'message' => 'Catégorie non trouvée'
                ], 404);
            }

            $category->load('products');
            
            if($request->header('Accept') === 'application/json'){
                return response()->json($category);
            } else {
                return response("Le format demandé n'est pas disponible", 406);
            }
            

        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la récupération de la catégorie',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // POST /category
    public function CreateCategory(CategoryRequest $request){
        try{

            $request->validated();

            $category = new Category();
            $category->Label = $request->Label;
            $category->save();
            
            return response()->json($category, 201);

        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la création de la catégorie',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // PUT /category/{ID_category}
    public function UpdateCategory(CategoryRequest $request, $id_category){
        try{

            $category = Category::find($id_category);

            if($category){
                $request->validated();

                $category->Label = $request->Label ? $request->Label : $category->Label;
                $category->save();
                
                return response()->json($category, 200);
            } else {
                return response()->json([
                    'message' => 'Catégorie non trouvée'
                ], 404);
            }

        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la mise à jour de la catégorie',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    // DELETE /category
    public function DeleteCategory(CategoryRequest $request){
        try{
            $category = Category::find($request->ID_category);

            if($category){
                $category->delete();
                
                return response()->json([
                    'message' => 'Catégorie supprimée avec succès'
                ]);
            } else {
                return response()->json([
                    'message' => 'Catégorie non trouvée'
                ], 404);
            }

        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la suppression de la catégorie',
                'error' => $e->getMessage()
            ], 500);
        }
    }

   
}
