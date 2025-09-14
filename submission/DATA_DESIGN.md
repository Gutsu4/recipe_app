# データ設計

## テーブル構造
### recipes（レシピ）
- id, name, description, servings, cooking_time
- 栄養情報: calories, protein, fat, carbs

### ingredients（材料）
- recipe_id（外部キー）, name, amount

### steps（手順）
- recipe_id（外部キー）, order, instruction

### categories（カテゴリ）
- name（UNIQUE制約）

### category_recipe（中間テーブル）
- recipe_id, category_id（多対多関係）

## 設計ポイント
- **正規化**: 第3正規形を採用
- **外部キー制約**: CASCADE削除でデータ整合性を保証
- **インデックス**: ソート対象カラムに設定
- **柔軟性**: amount列をVARCHARで多様な表記に対応