# データ構造設計ドキュメント

## 🏗️ 設計理念

### 正規化の原則
本システムでは、**第3正規形（3NF）**を基準としたデータベース設計を採用しています。これにより、データの重複を排除し、整合性を保ちながら効率的なデータ管理を実現しています。

### 拡張性の確保
将来的な機能拡張に対応できるよう、柔軟性のあるテーブル構造を設計しました。新しい属性の追加や関係性の変更に対して最小限の影響で対応できます。

## 📊 テーブル構造詳細

### 1. recipes テーブル（レシピ基本情報）

```sql
CREATE TABLE recipes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    servings INT NOT NULL,
    cooking_time INT NOT NULL,
    calories DECIMAL(8,2) DEFAULT 0,
    protein DECIMAL(8,2) DEFAULT 0,
    fat DECIMAL(8,2) DEFAULT 0,
    carbs DECIMAL(8,2) DEFAULT 0,
    fiber DECIMAL(8,2) DEFAULT 0,
    sodium DECIMAL(8,2) DEFAULT 0,
    sugar DECIMAL(8,2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

#### 設計根拠
- **主キー**: AUTO_INCREMENTによる単純な数値IDを採用
  - 理由: 高速なインデックス検索、外部キー参照の効率性
- **栄養情報**: DECIMAL(8,2)型を使用
  - 理由: 浮動小数点の精度問題を回避、正確な栄養計算が可能
- **調理時間**: INT型で分単位で格納
  - 理由: 計算処理の効率性、ソート処理の高速化

### 2. ingredients テーブル（材料情報）

```sql
CREATE TABLE ingredients (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    recipe_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    amount VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE
);
```

#### 設計根拠
- **1:N関係**: 1つのレシピに複数の材料を関連付け
- **CASCADE削除**: レシピ削除時に関連材料も自動削除
  - 理由: データ整合性の保証、孤立データの防止
- **amount VARCHAR型**: 「大さじ1」「適量」など多様な表記に対応
  - 理由: ユーザビリティ重視、柔軟な入力を可能に

### 3. steps テーブル（調理手順）

```sql
CREATE TABLE steps (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    recipe_id BIGINT UNSIGNED NOT NULL,
    order INT NOT NULL,
    instruction TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE,
    INDEX idx_recipe_order (recipe_id, order)
);
```

#### 設計根拠
- **order カラム**: 手順の順序を明示的に管理
  - 理由: 手順の並び替え、挿入、削除に対する柔軟性
- **複合インデックス**: (recipe_id, order)での高速検索
  - 理由: 手順表示時の性能向上
- **instruction TEXT型**: 長い手順説明にも対応

### 4. categories テーブル（カテゴリマスター）

```sql
CREATE TABLE categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

#### 設計根拠
- **マスターテーブル**: カテゴリ名の正規化
  - 理由: データの一貫性確保、管理の効率化
- **UNIQUE制約**: 同一カテゴリ名の重複防止
- **将来拡張**: カテゴリの説明、色、アイコンなどの属性追加が容易

### 5. category_recipe テーブル（多対多中間テーブル）

```sql
CREATE TABLE category_recipe (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    recipe_id BIGINT UNSIGNED NOT NULL,
    category_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    UNIQUE KEY unique_recipe_category (recipe_id, category_id)
);
```

#### 設計根拠
- **多対多関係**: 1つのレシピが複数カテゴリに、1つのカテゴリが複数レシピに
- **中間テーブル**: 関係性の独立管理
- **複合ユニーク制約**: 同じレシピ-カテゴリペアの重複防止

## 🔄 リレーション設計

### ER図の概念
```
[recipes] 1 ----< N [ingredients]
    |
    | N
    |
    M [category_recipe] M ----< 1 [categories]
    |
    | N
    |
[steps] N >---- 1
```

### 参照整合性の保証
- **外部キー制約**: 全ての関連付けで厳密な整合性を保証
- **CASCADE削除**: 親データ削除時の自動的な関連データクリーンアップ
- **インデックス戦略**: 検索性能を最適化

## 🎯 設計上の特徴的な判断

### 1. 栄養情報の非正規化
**判断**: recipesテーブルに栄養情報を直接格納

**理由**:
- 頻繁なアクセスパターンを考慮
- JOIN処理のオーバーヘッド削減
- ソート・フィルタ処理の高速化
- 栄養情報の更新頻度が低い

### 2. 材料の量表記を文字列で保存
**判断**: amount列をVARCHAR(255)で設計

**理由**:
- 「大さじ1」「適量」「少々」など多様な表記に対応
- ユーザーの直感的な入力を優先
- 数値変換は必要時にアプリケーション層で実行

### 3. カテゴリのマスター化
**判断**: categoriesテーブルとの正規化

**理由**:
- カテゴリ名の統一性確保
- 将来的なカテゴリ管理機能への拡張性
- 検索・フィルタリング性能の向上

### 4. 手順順序の明示的管理
**判断**: stepsテーブルにorderカラムを追加

**理由**:
- 手順の並び替え機能への対応
- 手順の挿入・削除時の整合性保証
- 表示順序の確実な制御

## 🚀 パフォーマンス最適化

### インデックス戦略
```sql
-- 主要なインデックス
CREATE INDEX idx_recipes_name ON recipes(name);
CREATE INDEX idx_recipes_calories ON recipes(calories);
CREATE INDEX idx_recipes_cooking_time ON recipes(cooking_time);
CREATE INDEX idx_ingredients_recipe_id ON ingredients(recipe_id);
CREATE INDEX idx_steps_recipe_order ON steps(recipe_id, order);
CREATE INDEX idx_category_recipe_recipe ON category_recipe(recipe_id);
CREATE INDEX idx_category_recipe_category ON category_recipe(category_id);
```

### クエリ最適化の考慮
- **Eager Loading**: N+1問題の回避
- **適切なJOIN**: 必要なデータのみ取得
- **ページネーション**: 大量データへの対応準備

## 🔧 制約と妥当性確保

### データベース制約
```sql
-- 栄養情報の非負制約
ALTER TABLE recipes ADD CONSTRAINT chk_calories_positive CHECK (calories >= 0);
ALTER TABLE recipes ADD CONSTRAINT chk_protein_positive CHECK (protein >= 0);
ALTER TABLE recipes ADD CONSTRAINT chk_cooking_time_positive CHECK (cooking_time >= 0);
ALTER TABLE recipes ADD CONSTRAINT chk_servings_positive CHECK (servings > 0);

-- 手順順序の正値制約
ALTER TABLE steps ADD CONSTRAINT chk_order_positive CHECK (`order` > 0);
```

### アプリケーション層での検証
- Laravel Validation Rulesによる入力検証
- フロントエンドでのリアルタイム検証
- APIレスポンス時の整合性確認

## 🎨 JSON API設計との整合性

### フロントエンド向けデータ構造
```typescript
interface Recipe {
  id: number;
  name: string;
  description: string;
  servings: number;
  cooking_time: number;
  calories: number;
  protein: number;
  fat: number;
  carbs: number;
  fiber: number;
  sodium: number;
  sugar: number;
  categories?: Category[];
  ingredients?: Ingredient[];
  steps?: Step[];
}
```

### データベースとAPIの橋渡し
- Eloquent ORMによる透明な変換
- 必要に応じた関連データのEager Loading
- レスポンス最適化のための選択的データ取得

## 🔮 将来拡張への対応

### 想定される拡張ポイント
1. **ユーザー管理**: usersテーブルとの関連付け
2. **レビュー機能**: reviewsテーブルの追加
3. **お気に入り**: favoritesテーブルによる多対多関係
4. **画像管理**: imagesテーブルでの画像データ管理
5. **栄養詳細**: nutrition_detailsテーブルでの詳細栄養情報

### 拡張時の影響最小化
- 既存テーブル構造を変更せずに新機能を追加
- 外部キー制約による整合性の自動保証
- マイグレーションによる段階的なスキーマ変更

## 📈 スケーラビリティ考慮

### 大規模データ対応
- **パーティショニング**: 大量レシピデータの分散
- **レプリケーション**: 読み取り性能の向上
- **キャッシュ戦略**: 頻繁にアクセスされるデータの高速化

### 分散システム対応
- **マイクロサービス化**: テーブル単位での独立性
- **API Gateway**: 統一されたインターフェース
- **データ同期**: 整合性保証メカニズム

この設計により、シンプルでありながら拡張性と保守性を両立したデータ構造を実現しています。