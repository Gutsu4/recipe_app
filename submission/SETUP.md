# レシピ管理システム セットアップガイド

## 📋 必要な環境

### システム要件
- **PHP**: 8.1以上
- **Node.js**: 16.0以上
- **MySQL**: 8.0以上
- **Composer**: 2.0以上
- **Git**: 最新版

### 推奨環境
- **OS**: macOS, Linux, Windows (WSL2推奨)
- **メモリ**: 4GB以上
- **ストレージ**: 1GB以上の空き容量

## 🚀 インストール手順

### 1. プロジェクトのクローン
```bash
# リポジトリをクローン
git clone [リポジトリURL]
cd recipe_app/laravel
```

### 2. 依存関係のインストール
```bash
# PHP依存関係をインストール
composer install

# Node.js依存関係をインストール
npm install
```

### 3. 環境設定
```bash
# .envファイルを作成
cp .env.example .env

# アプリケーションキーを生成
php artisan key:generate
```

### 4. データベースの設定

#### .envファイルを編集
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=recipe_app
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

#### データベースを作成
```sql
-- MySQLにログインして実行
CREATE DATABASE recipe_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. データベースマイグレーション
```bash
# マイグレーションを実行
php artisan migrate

# (オプション) サンプルデータの投入
php artisan db:seed
```

### 6. フロントエンドのビルド
```bash
# 開発環境でのビルド
npm run dev

# 本番環境でのビルド（オプション）
npm run build
```

## 🔧 起動方法

### 開発環境での起動

#### 1. バックエンドサーバーの起動
```bash
# Laravel開発サーバーを起動
php artisan serve

# デフォルトで http://localhost:8000 で起動
# ポートを指定する場合
php artisan serve --port=8080
```

#### 2. フロントエンドの開発サーバー起動
```bash
# 別のターミナルで実行
npm run dev

# または hot reload を使用
npm run hot
```

#### 3. ブラウザでアクセス
- アプリケーション: http://localhost:8000
- API エンドポイント: http://localhost:8000/api/recipes

### 本番環境での設定

#### 1. 環境変数の設定
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
```

#### 2. 最適化コマンド
```bash
# 設定キャッシュ
php artisan config:cache

# ルートキャッシュ  
php artisan route:cache

# ビューキャッシュ
php artisan view:cache

# フロントエンドビルド
npm run build
```

## 🧪 テストデータの投入

### 方法1: JSONファイルから投入
```bash
# test-recipe-data.jsonを使用してデータを投入
# (カスタムコマンドが必要な場合は作成)
php artisan db:seed --class=RecipeSeeder
```

### 方法2: 手動でのAPI投入
```bash
# cURLを使用してAPIにテストデータを送信
curl -X POST http://localhost:8000/api/recipes \
  -H "Content-Type: application/json" \
  -d @test-recipe-data.json
```

### 方法3: 管理画面からの投入
1. ブラウザで http://localhost:8000 にアクセス
2. 「レシピ作成」ボタンをクリック
3. 手動でレシピを入力

## 📊 データベース構造の確認

### テーブル一覧の確認
```bash
# マイグレーション状態を確認
php artisan migrate:status

# データベース構造を確認
php artisan schema:dump
```

### データの確認
```sql
-- レシピ一覧を確認
SELECT id, name, servings, cooking_time, calories FROM recipes;

-- カテゴリ一覧を確認
SELECT * FROM categories;
```

## 🔍 トラブルシューティング

### よくある問題と解決方法

#### 1. データベース接続エラー
```bash
# データベースサーバーが起動しているか確認
brew services list | grep mysql  # macOSの場合

# 認証情報を確認
mysql -u your_username -p
```

#### 2. 権限エラー
```bash
# storageディレクトリの権限を修正
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

#### 3. Composer依存関係の問題
```bash
# Composerキャッシュをクリア
composer clear-cache
composer install --no-cache
```

#### 4. npm関連の問題
```bash
# node_modulesを削除して再インストール
rm -rf node_modules
rm package-lock.json
npm install
```

### ログの確認
```bash
# Laravelログを確認
tail -f storage/logs/laravel.log

# WebサーバーログやPHPログも確認
```

## 🔧 開発用コマンド

### よく使用するArtisanコマンド
```bash
# キャッシュクリア
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# マイグレーションのリセット
php artisan migrate:fresh

# シーダー実行
php artisan db:seed

# 新しいマイグレーション作成
php artisan make:migration create_new_table

# 新しいコントローラー作成
php artisan make:controller NewController
```

### フロントエンド開発コマンド
```bash
# 開発サーバー起動（ホットリロード）
npm run dev

# 本番ビルド
npm run build

# コード整形
npm run lint

# 型チェック（TypeScript使用時）
npm run type-check
```

## 📱 API テスト

### cURLを使用したテスト例
```bash
# レシピ一覧取得
curl -X GET http://localhost:8000/api/recipes

# ソート付き一覧取得
curl -X GET "http://localhost:8000/api/recipes?OrderBy=name&Order=asc"

# レシピ詳細取得
curl -X GET http://localhost:8000/api/recipes/1

# レシピ作成
curl -X POST http://localhost:8000/api/recipes \
  -H "Content-Type: application/json" \
  -d '{
    "name": "テストレシピ",
    "description": "テスト用のレシピです",
    "servings": 2,
    "cooking_time": 30,
    "calories": 300,
    "ingredients": [{"name": "材料1", "amount": "100g"}],
    "steps": [{"instruction": "手順1です"}],
    "categories": ["主菜"]
  }'
```

## 🏗️ 開発環境の拡張

### IDEの設定
#### VS Code推奨拡張機能
- PHP Intelephense
- Laravel Blade Snippets  
- Vetur (Vue.js)
- ESLint
- Prettier

#### PhpStorm設定
- Laravel plugin
- Vue.js plugin
- Database tools

### デバッグ環境
```bash
# Xdebugの設定（開発時）
# php.iniに以下を追加
zend_extension=xdebug
xdebug.mode=debug
xdebug.start_with_request=yes
```

## 📋 チェックリスト

### 起動前チェック
- [ ] PHP 8.1以上がインストール済み
- [ ] Node.js 16以上がインストール済み  
- [ ] MySQLサーバーが起動している
- [ ] .envファイルが正しく設定されている
- [ ] データベースが作成されている
- [ ] composer installが完了している
- [ ] npm installが完了している

### 起動後チェック  
- [ ] http://localhost:8000 にアクセス可能
- [ ] レシピ一覧が表示される
- [ ] レシピ作成フォームが動作する
- [ ] レシピの作成・編集・削除が可能
- [ ] ソート機能が動作する
- [ ] APIエンドポイントがレスポンスを返す

このガイドに従って環境を構築することで、レシピ管理システムを正常に動作させることができます。