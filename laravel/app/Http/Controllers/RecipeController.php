<?php

namespace App\Http\Controllers;

use App\Models\Recipe;

class RecipeController extends Controller
{
    public function index()
    {
        $recipes = Recipe::with([
            'ingredients',
            'steps',
            'categories'
        ])->get();
        return response()->json($recipes);
    }

    public function store()
    {
        //
    }
}
