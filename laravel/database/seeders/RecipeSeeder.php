<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Recipe;
use App\Models\Ingredient;
use App\Models\Step;
use App\Models\Category;

class RecipeSeeder extends Seeder
{
    public function run(): void
    {
        // 事前にカテゴリを作成
        $categories = [
            '主食', '主菜', '副菜', '汁物', 'デザート',
        ];

        $categoryModels = [];
        foreach ($categories as $cat) {
            $categoryModels[$cat] = Category::firstOrCreate(['name' => $cat]);
        }

        // サンプルレシピデータ
        $recipes = [
            [
                'name' => 'サラダ',
                'description' => '新鮮野菜を使った簡単サラダ',
                'servings' => 2,
                'cooking_time' => 10,
                'calories' => 200,
                'protein' => 5,
                'fat' => 10,
                'carbs' => 15,
                'ingredients' => [
                    ['name' => 'レタス', 'amount' => '3枚'],
                    ['name' => 'トマト', 'amount' => '1個'],
                    ['name' => 'ドレッシング', 'amount' => '大さじ2'],
                ],
                'steps' => [
                    '野菜を洗う',
                    '食べやすい大きさに切る',
                    'ドレッシングをかける',
                ],
                'categories' => ['副菜'],
            ],
            [
                'name' => 'カレーライス',
                'description' => '定番のビーフカレー',
                'servings' => 4,
                'cooking_time' => 45,
                'calories' => 600,
                'protein' => 20,
                'fat' => 25,
                'carbs' => 70,
                'ingredients' => [
                    ['name' => '牛肉', 'amount' => '200g'],
                    ['name' => '玉ねぎ', 'amount' => '2個'],
                    ['name' => 'じゃがいも', 'amount' => '2個'],
                    ['name' => 'カレールー', 'amount' => '1/2箱'],
                ],
                'steps' => [
                    '野菜と肉を切る',
                    '鍋で炒める',
                    '水を加えて煮込む',
                    'ルーを溶かしてさらに煮込む',
                ],
                'categories' => ['主食', '主菜'],
            ],
            [
                'name' => 'パスタ',
                'description' => 'トマトソースのスパゲッティ',
                'servings' => 2,
                'cooking_time' => 20,
                'calories' => 700,
                'protein' => 18,
                'fat' => 12,
                'carbs' => 90,
                'ingredients' => [
                    ['name' => 'スパゲッティ', 'amount' => '200g'],
                    ['name' => 'トマトソース', 'amount' => '200ml'],
                    ['name' => 'オリーブオイル', 'amount' => '大さじ1'],
                ],
                'steps' => [
                    'パスタを茹でる',
                    'ソースを温める',
                    '茹で上がったパスタと和える',
                ],
                'categories' => ['主食'],
            ],
            [
                'name' => '味噌汁',
                'description' => '豆腐とわかめの味噌汁',
                'servings' => 2,
                'cooking_time' => 15,
                'calories' => 150,
                'protein' => 8,
                'fat' => 5,
                'carbs' => 10,
                'ingredients' => [
                    ['name' => '豆腐', 'amount' => '1/2丁'],
                    ['name' => 'わかめ', 'amount' => '適量'],
                    ['name' => '味噌', 'amount' => '大さじ2'],
                ],
                'steps' => [
                    '鍋に水を入れて加熱',
                    '豆腐とわかめを加える',
                    '味噌を溶かす',
                ],
                'categories' => ['汁物'],
            ],
            [
                'name' => 'プリン',
                'description' => 'なめらかなカスタードプリン',
                'servings' => 4,
                'cooking_time' => 60,
                'calories' => 250,
                'protein' => 6,
                'fat' => 12,
                'carbs' => 25,
                'ingredients' => [
                    ['name' => '卵', 'amount' => '3個'],
                    ['name' => '牛乳', 'amount' => '400ml'],
                    ['name' => '砂糖', 'amount' => '80g'],
                ],
                'steps' => [
                    '卵と砂糖を混ぜる',
                    '牛乳を加えて混ぜる',
                    '型に入れて蒸す',
                ],
                'categories' => ['デザート'],
            ],
        ];

        foreach ($recipes as $r) {
            $recipe = Recipe::create([
                'name' => $r['name'],
                'description' => $r['description'],
                'servings' => $r['servings'],
                'cooking_time' => $r['cooking_time'],
                'calories' => $r['calories'],
                'protein' => $r['protein'],
                'fat' => $r['fat'],
                'carbs' => $r['carbs'],
            ]);

            // 材料
            foreach ($r['ingredients'] as $ing) {
                Ingredient::create([
                    'recipe_id' => $recipe->id,
                    'name' => $ing['name'],
                    'amount' => $ing['amount'],
                ]);
            }

            // 手順
            foreach ($r['steps'] as $i => $step) {
                Step::create([
                    'recipe_id' => $recipe->id,
                    'order' => $i + 1,
                    'instruction' => $step,
                ]);
            }

            // カテゴリ
            $ids = [];
            foreach ($r['categories'] as $catName) {
                if (isset($categoryModels[$catName])) {
                    $ids[] = $categoryModels[$catName]->id;
                }
            }
            $recipe->categories()->sync($ids);
        }
    }
}

