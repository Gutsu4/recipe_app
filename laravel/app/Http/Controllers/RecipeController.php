<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RecipeController extends Controller
{
    public function index(Request $request)
    {
        $query = Recipe::with([
            'ingredients',
            'steps',
            'categories'
        ]);

        // ソート機能
        $orderBy = $request->get('OrderBy', 'id');
        $order = $request->get('Order', 'desc');
        
        // ソート可能なカラムを制限（仕様に合わせて）
        $allowedSortColumns = [
            'id', 'name', 'calories', 'cookingTime'
        ];
        
        // cookingTimeをcooking_timeにマッピング
        $columnMap = [
            'cookingTime' => 'cooking_time'
        ];
        
        if (in_array($orderBy, $allowedSortColumns)) {
            $actualColumn = $columnMap[$orderBy] ?? $orderBy;
            $query->orderBy($actualColumn, $order === 'asc' ? 'asc' : 'desc');
        }

        $recipes = $query->get();
        return response()->json($recipes);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'servings' => 'required|integer|min:1',
            'cooking_time' => 'required|integer|min:0',
            'ingredients' => 'required|array|min:1',
            'ingredients.*.name' => 'required|string|max:255',
            'ingredients.*.amount' => 'required|string|max:255',
            'steps' => 'required|array|min:1',
            'steps.*.instruction' => 'required|string',
            'categories' => 'array',
            'calories' => 'numeric|min:0',
            'protein' => 'numeric|min:0',
            'fat' => 'numeric|min:0',
            'carbs' => 'numeric|min:0',
            'fiber' => 'numeric|min:0',
            'sodium' => 'numeric|min:0',
            'sugar' => 'numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $recipeData = $request->only([
                'name', 'description',
                'servings', 'cooking_time',
                'calories', 'protein', 'fat', 'carbs', 'fiber', 'sodium', 'sugar'
            ]);
            $recipe = Recipe::create($recipeData);

            foreach ($request->ingredients as $ingredientData) {
                $recipe->ingredients()->create([
                    'name' => $ingredientData['name'],
                    'amount' => $ingredientData['amount']
                ]);
            }

            foreach ($request->steps as $index => $stepData) {
                $recipe->steps()->create([
                    'order' => $index + 1,
                    'instruction' => $stepData['instruction']
                ]);
            }

            if ($request->has('categories') && is_array($request->categories)) {
                $categoryIds = [];
                foreach ($request->categories as $categoryName) {
                    $category = Category::firstOrCreate(['name' => $categoryName]);
                    $categoryIds[] = $category->id;
                }
                $recipe->categories()->attach($categoryIds);
            }

            DB::commit();

            $recipe->load(['ingredients', 'steps', 'categories']);

            return response()->json($recipe, 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'レシピの作成中にエラーが発生しました。',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $recipe = Recipe::with([
            'ingredients',
            'steps' => function ($query) {
                $query->orderBy('order');
            },
            'categories'
        ])->findOrFail($id);

        return response()->json($recipe);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'servings' => 'required|integer|min:1',
            'cooking_time' => 'required|integer|min:0',
            'ingredients' => 'required|array|min:1',
            'ingredients.*.name' => 'required|string|max:255',
            'ingredients.*.amount' => 'required|string|max:255',
            'steps' => 'required|array|min:1',
            'steps.*.instruction' => 'required|string',
            'categories' => 'array',
            'calories' => 'numeric|min:0',
            'protein' => 'numeric|min:0',
            'fat' => 'numeric|min:0',
            'carbs' => 'numeric|min:0',
            'fiber' => 'numeric|min:0',
            'sodium' => 'numeric|min:0',
            'sugar' => 'numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $recipe = Recipe::findOrFail($id);

            // Update recipe basic info
            $recipeData = $request->only([
                'name', 'description',
                'servings', 'cooking_time',
                'calories', 'protein', 'fat', 'carbs', 'fiber', 'sodium', 'sugar'
            ]);
            $recipe->update($recipeData);

            // Delete existing ingredients and steps
            $recipe->ingredients()->delete();
            $recipe->steps()->delete();

            // Create new ingredients
            foreach ($request->ingredients as $ingredientData) {
                $recipe->ingredients()->create([
                    'name' => $ingredientData['name'],
                    'amount' => $ingredientData['amount']
                ]);
            }

            // Create new steps
            foreach ($request->steps as $index => $stepData) {
                $recipe->steps()->create([
                    'order' => $index + 1,
                    'instruction' => $stepData['instruction']
                ]);
            }

            // Update categories
            $recipe->categories()->detach();
            if ($request->has('categories') && is_array($request->categories)) {
                $categoryIds = [];
                foreach ($request->categories as $categoryName) {
                    $category = Category::firstOrCreate(['name' => $categoryName]);
                    $categoryIds[] = $category->id;
                }
                $recipe->categories()->attach($categoryIds);
            }

            DB::commit();

            // Load relationships for response
            $recipe->load(['ingredients', 'steps', 'categories']);

            return response()->json($recipe);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'レシピの更新中にエラーが発生しました。',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $recipe = Recipe::findOrFail($id);
            
            // カスケード削除により関連データも自動削除される
            $recipe->delete();
            
            return response()->json([
                'message' => 'レシピが削除されました。'
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'レシピの削除中にエラーが発生しました。',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
