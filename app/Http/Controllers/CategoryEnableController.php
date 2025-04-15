<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryEnableRequest;
use Illuminate\Http\Request;
use App\Models\CategoryEnable;
use Exception;

class CategoryEnableController extends Controller
{

    // GET /categoryEnables
    public function AllCategoryEnable(CategoryEnableRequest $request){
        try{

            $categories = CategoryEnable::all()->load('category');
            
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

    // GET /categoryEnable/{id_store}
    public function CategoryEnable(CategoryEnableRequest $request, $id_store){
        try{


            $categories = CategoryEnable::where('ID_store', $id_store)->get();

            if($categories->isEmpty()){
                return response()->json([
                    'message' => 'Catégorie non trouvée'
                ], 404);
            }
            
            $categories->load('category');

            if($request->header('Accept') === 'application/json'){
                return response()->json($categories);
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

    // POST /categoryEnable/{ID_store}
    public function CreateCategoryEnable(CategoryEnableRequest $request, $id_store){
        try{

            $request->validated();

            $categoryEnable = new CategoryEnable();

            $categoryEnable->ID_category = $request->ID_category;
            $categoryEnable->ID_store = $id_store;
            $categoryEnable->Category_position = $request->Category_position;
            
            return response()->json($categoryEnable, 201);

        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la création de la catégorie',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // PUT /categoryEnable/{ID_store}/{ID_category}
    public function UpdatecategoryEnable(CategoryEnableRequest $request, $id_store, $id_category){
        try{

            $categoryEnable = CategoryEnable::where('ID_store', $id_store)->where('ID_category', $id_category)->first();

            if($categoryEnable){
                $request->validated();

                $categoryEnable->Category_position = $request->Category_position;
                $categoryEnable->save();
                
                return response()->json($categoryEnable, 200);
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

     // DELETE /categoryEnable
     public function DeleteCategoryEnable(CategoryEnableRequest $request){
        try{
            $categoryEnable = CategoryEnable::where('ID_category', $request->ID_category)->where('ID_store', $request->ID_store)->first();

            if($categoryEnable){
                $categoryEnable->delete();
                
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
