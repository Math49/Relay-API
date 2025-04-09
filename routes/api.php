<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CategoryEnableController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\ListController;

Route::get('/csrf-token', function (Request $request) {
    return response()->json(['csrf_token' => csrf_token()]);
});

// Routes d'authetifications
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    // Routes user
    Route::get('/users', [UserController::class, 'allUsers']);
    Route::get('/user/{id}', [UserController::class, 'userByID']);
    Route::post('/user', [UserController::class, 'createUser']);
    Route::put('/user/{id}', [UserController::class, 'updateUser']);
    Route::delete('/user', [UserController::class, 'deleteUser']);

    //Routes magasins
    Route::get('/stores', [StoreController::class, 'AllStores']);
    Route::get('/store/{id}', [StoreController::class, 'StoreByID']);
    Route::post('/store', [StoreController::class, 'createStore']);
    Route::put('/store/{id}', [StoreController::class, 'updateStore']);
    Route::delete('/store', [StoreController::class, 'deleteStore']);

    //Routes categories
    Route::get('/categories', [CategoryController::class, 'AllCategory']);
    Route::get('/category/{id}', [CategoryController::class, 'CategoryByID']);
    Route::post('/category', [CategoryController::class, 'CreateCategory']);
    Route::put('/category/{id}', [CategoryController::class, 'UpdateCategory']);
    Route::delete('/category', [CategoryController::class, 'DeleteCategory']);

    //Routes categories enable
    Route::get('/categoryEnables', [CategoryEnableController::class, 'AllCategoryEnable']);
    Route::get('/categoryEnable/{id_store}', [CategoryEnableController::class, 'CategoryEnable']);
    Route::post('/categoryEnable/{id_store}', [CategoryEnableController::class, 'CreateCategoryEnable']);
    Route::put('/categoryEnable/{id_store}/{id_category}', [CategoryEnableController::class, 'UpdateCategoryEnable']);
    Route::delete('/categoryEnable', [CategoryEnableController::class, 'DeleteCategoryEnable']);

    //Routes products
    Route::get('/products', [ProductController::class, 'AllProducts']);
    Route::get('/product/{id}', [ProductController::class, 'ProductByID']);
    Route::post('/product', [ProductController::class, 'CreateProduct']);
    Route::put('/product/{id}', [ProductController::class, 'UpdateProduct']);
    Route::delete('/product', [ProductController::class, 'DeleteProduct']);

    //Routes messages
    Route::get('/messages', [MessageController::class, 'AllMessages']);
    Route::get('/messages/{ID_store}', [MessageController::class, 'MessagesByStore']);
    Route::get('/message/{ID_store}/{ID_message}', [MessageController::class, 'MessageByID']);
    Route::post('/message', [MessageController::class, 'CreateMessage']);
    Route::put('/message/{ID_message}', [MessageController::class, 'UpdateMessage']);
    Route::delete('/message', [MessageController::class, 'DeleteMessage']);

    //Routes stocks
    Route::get('/stocks', [StockController::class, 'AllStocks']);
    Route::get('/stock/{ID_store}', [StockController::class, 'StockByStore']);
    Route::get('/stock/{ID_store}/{ID_product}', [StockController::class, 'StockByStoreAndProduct']);
    Route::post('/stock', [StockController::class, 'CreateStock']);
    Route::post('/stocks', [StockController::class, 'CreateStocks']);
    Route::put('/stock/{ID_store}/{ID_product}', [StockController::class, 'UpdateStock']);
    Route::put('/stocks/{ID_store}', [StockController::class, 'UpdateStocks']);
    Route::delete('/stock', [StockController::class, 'DeleteStock']);

    //Routes lists
    Route::get('/lists', [ListController::class, 'AllLists']);
    Route::get('/list/{ID_store}', [ListController::class, 'ListByStore']);
    Route::get('/list/{ID_store}/{ID_list}', [ListController::class, 'ListByID']);
    Route::post('/list', [ListController::class, 'CreateList']);
    Route::put('/list', [ListController::class, 'UpdateList']);
    Route::delete('/list', [ListController::class, 'DeleteList']);
});
