<?php

use App\Http\Controllers\RecipeController;
use Illuminate\Support\Facades\Route;

/**********************************************************************
 * API
 **********************************************************************/
Route::get("/api/recipes", [RecipeController::class, 'index']);
Route::post("/api/recipes", [RecipeController::class, 'store']);


/**********************************************************************
 * ルーティング
 ***********************************************************************/
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
