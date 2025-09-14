# レシピ管理システム

## 概要
このプロジェクトは、料理のレシピを効率的に管理するためのWebアプリケーションです。Laravel 11とVue.js 3を使用したモダンなフルスタックアプリケーションとして設計されています。

## 仕様要件

### 5つの機能カテゴリ

1. **レシピの基本情報管理**
   - レシピ名
   - 説明
   - 人数分
   - 調理時間

2. **材料情報管理**
   - 材料名
   - 使用量
   - 動的な材料追加・削除

3. **調理手順管理**
   - 順序付きステップ
   - 手順の説明
   - 動的なステップ追加・削除

4. **分類情報管理**
   - カテゴリによる分類
   - 複数カテゴリ選択対応
   - 主食、主菜、副菜等の定義済みカテゴリ

5. **栄養情報管理**
   - カロリー
   - 三大栄養素（タンパク質、脂質、炭水化物）
   - 追加栄養素（食物繊維、ナトリウム、糖質）

## 技術スタック

### バックエンド
- **Laravel 11** - PHPフレームワーク
- **Eloquent ORM** - データベース操作
- **MySQL** - データベース
- **RESTful API** - フロントエンドとの通信

### フロントエンド
- **Vue.js 3** - JavaScript フレームワーク
- **Composition API** - Vue 3の新しいAPI
- **TypeScript** - 型安全性の確保
- **Vue Router** - SPA ルーティング
- **Tailwind CSS** - ユーティリティファーストCSS

## 設計思想

### 1. 最小限設計（Minimalist Design）
- 仕様要件のみに焦点を当てた実装
- 不要な機能の排除（難易度、画像、作成者情報等を削除）
- シンプルで理解しやすいコード構造

### 2. コンポーネント駆動開発（Component-Driven Development）
- 再利用可能なUIコンポーネント（`PageHeader`, `BaseButton`）
- 一貫したデザインシステム
- メンテナンスしやすいコンポーネント構造

### 3. RESTful API 設計
```
GET    /api/recipes      # レシピ一覧取得
GET    /api/recipes/{id} # レシピ詳細取得  
POST   /api/recipes      # レシピ作成
PUT    /api/recipes/{id} # レシピ更新
DELETE /api/recipes/{id} # レシピ削除
```

### 4. データベース正規化
- **recipes** - レシピ基本情報
- **ingredients** - 材料情報（1:N）
- **steps** - 調理手順（1:N）
- **categories** - カテゴリマスター
- **category_recipe** - カテゴリ関連（N:M）

## こだわりポイント

### 1. UI統一性
- 全画面で統一されたデザインパターン
- カード型レイアウトの採用
- レスポンシブデザイン対応
- グラデーションを使用した視覚的差別化

### 2. ユーザビリティ
- 直感的な操作フロー
- 適切なフィードバック（ローディング、エラー、成功メッセージ）
- 確認ダイアログによる誤操作防止
- 検索・フィルタリング機能（将来拡張可能）

### 3. 堅牢なエラーハンドリング
- フロントエンドでの例外処理
- バックエンドでの適切なHTTPステータスコード
- ユーザーフレンドリーなエラーメッセージ
- データ整合性の保証

### 4. 保守性重視
- Repository パターンによるAPI抽象化
- 明確な責任分離
- TypeScriptによる型安全性
- 一貫したコーディングスタイル

## 実装の意図

### 1. スケーラブルなアーキテクチャ
```
├── app/Http/Controllers/     # API コントローラー
├── app/Models/              # Eloquent モデル
├── resources/js/components/ # 再利用可能コンポーネント
├── resources/js/features/   # 機能別コンポーネント
├── resources/js/composables/# ビジネスロジック
└── database/migrations/     # データベーススキーマ
```

### 2. 関心の分離
- **Controller**: HTTPリクエスト処理とレスポンス
- **Model**: データ構造とリレーション定義
- **Repository**: API通信の抽象化
- **Component**: UI表示とユーザーインタラクション

### 3. パフォーマンス最適化
- Eager Loading によるN+1問題の回避
- 必要な場合のみデータ再取得
- 軽量なコンポーネント設計

## 機能一覧

### 基本機能
- [x] レシピ作成
- [x] レシピ一覧表示
- [x] レシピ詳細表示
- [x] レシピ編集
- [x] レシピ削除
- [x] カテゴリ管理

### 画面構成
- **レシピ一覧** (`/`) - レシピの検索・表示・削除
- **レシピ作成** (`/create`) - 新規レシピ作成フォーム
- **レシピ詳細** (`/detail/{id}`) - レシピ詳細表示・編集

## データモデル

### Recipe（レシピ）
```php
- id: int (主キー)
- name: string (レシピ名)
- description: text (説明)
- servings: int (人数分)
- cooking_time: int (調理時間/分)
- calories: float (カロリー)
- protein: float (タンパク質/g)
- fat: float (脂質/g)
- carbs: float (炭水化物/g)
- fiber: float (食物繊維/g)
- sodium: float (ナトリウム/mg)
- sugar: float (糖質/g)
```

### Ingredient（材料）
```php
- id: int
- recipe_id: int (外部キー)
- name: string (材料名)
- amount: string (使用量)
```

### Step（手順）
```php
- id: int
- recipe_id: int (外部キー)
- order: int (順序)
- instruction: text (手順説明)
```

### Category（カテゴリ）
```php
- id: int
- name: string (カテゴリ名)
```

## 今後の拡張可能性

### 機能拡張
- [ ] ユーザー認証・認可
- [ ] レシピのお気に入り機能
- [ ] 検索・フィルター機能の強化
- [ ] 画像アップロード機能
- [ ] 栄養計算の自動化
- [ ] レシピの評価・レビュー
- [ ] 買い物リスト生成

### 技術的改善
- [ ] キャッシュ機能の実装
- [ ] 全文検索の導入
- [ ] PWA対応
- [ ] テストカバレッジの向上
- [ ] CI/CD パイプライン構築

## 開発環境のセットアップ

### 必要環境
- PHP 8.1以上
- Node.js 16以上
- MySQL 8.0以上
- Composer

### インストール手順
```bash
# 依存関係のインストール
composer install
npm install

# 環境設定
cp .env.example .env
php artisan key:generate

# データベースマイグレーション
php artisan migrate

# フロントエンドビルド
npm run dev

# 開発サーバー起動
php artisan serve
```

## まとめ

このレシピ管理システムは、**シンプルさと拡張性のバランス**を重視した設計となっています。5つの要求仕様を完全に満たしながら、将来的な機能追加にも柔軟に対応できるアーキテクチャを採用しました。

特に、**コンポーネントベースの設計**と**明確な責任分離**により、保守性と再利用性を確保し、開発効率の向上を図っています。