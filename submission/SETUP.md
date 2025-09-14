# セットアップガイド

## 必要な環境
- Docker, Docker Compose
- Make

## Dockerセットアップ
```bash
# プロジェクトクローン
git clone [リポジトリURL]
cd recipe_app

# 環境構築・起動（ワンコマンド）
make init

# 開発サーバー起動
make up
```

## Makefileコマンド
```bash
# 初回セットアップ
make init

# サービス起動
make up

# サービス停止
make down

# キャッシュクリア
make clear

# 完全リセット
make reset

# 完全削除
make destroy
```

http://localhost:8000 でアクセス可能

## 手動セットアップ（Docker未使用）
```bash
# 必要な環境: PHP 8.1+, Node.js 16+, MySQL 8.0+

composer install && npm install
cp .env.example .env && php artisan key:generate
php artisan migrate && php artisan db:seed
php artisan serve & npm run dev
```