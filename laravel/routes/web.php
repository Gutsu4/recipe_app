<?php

use App\Http\Controllers\RecipeController;
use Illuminate\Support\Facades\Route;

/**********************************************************************
 * API
 **********************************************************************/
Route::get("/api/recipes", [RecipeController::class, 'index']);
Route::get("/api/recipes/{id}", [RecipeController::class, 'show']);
Route::post("/api/recipes", [RecipeController::class, 'store']);
Route::delete("/api/recipes/{id}", [RecipeController::class, 'destroy']);


/**********************************************************************
 * ルーティング
 ***********************************************************************/
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
