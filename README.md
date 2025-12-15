# アプリケーション名

Laravel お問い合わせフォームアプリ（contactform-test1）

## 概要

Laravel を用いて実装したお問い合わせフォームアプリケーションです。  
ユーザーは公開ページからお問い合わせを送信でき、管理者はログインしてお問い合わせ一覧の確認・検索・CSV エクスポート・削除などを行うことができます。

認証機能は Laravel Fortify をベースに、FormRequest を用いたバリデーションとカスタムエラーメッセージで要件を満たすように実装しています。

---

## 環境構築

### 前提 - Docker Desktop がインストールされていること

### 1. リポジトリをクローン

```bash
git clone git@github.com:TAKAMASA-otk/contactform-test1.git
cd contactform-test1
```

### 2. Docker コンテナ起動

```bash
docker compose up -d --build
```

### 3. PHP コンテナで Laravel 初期設定

```bash
docker compose exec app composer install
docker compose exec app cp .env.example .env
docker compose exec app php artisan key:generate
```

### 4.マイグレーション・シーディング

```bash
docker compose exec app php artisan migrate --seed
```

### 5. アクセス確認

-   お問い合わせ入力フォーム  
    http://localhost/
-   会員登録ページ（Fortify）  
    http://localhost/register
-   ログインページ  
    http://localhost/login
-   管理画面（ログイン後）  
    http://localhost/admin
-   phpMyAdmin  
    http://localhost:8080/

---

## 使用技術（実行環境）

-   PHP 8.2.11
-   Laravel 8.83.8
-   Laravel Fortify
-   MySQL 8.0
-   nginx 1.21
-   jQuery 3.7.1（管理画面モーダルに使用）
-   Docker / docker-compose

---

## 機能一覧（要件対応）

### US001〜US003：お問い合わせフォーム〜確認〜サンクス

#### お問い合わせ入力（`/`）

-   姓・名に分かれたお名前入力
-   性別（「男性」「女性」「その他」）
    -   値は `1:男性 / 2:女性 / 3:その他`
-   メールアドレス
-   電話番号（3 項目に分割入力）
-   住所
-   建物名（任意）
-   お問い合わせの種類（categories テーブルを参照）
-   お問い合わせ内容

#### 確認画面（`/confirm`）

-   入力内容の表示
    -   姓名の間にはスペースを入れて表示
    -   性別は「男性」「女性」「その他」で表示
    -   電話番号はハイフンなしで表示
-   「送信」「修正」ボタン
    -   修正ボタンで入力画面に戻る際、入力値は保持

#### サンクスページ（`/thanks`）

-   送信完了メッセージ表示
-   HOME ボタンで初期状態のフォームへ遷移

#### バリデーション

-   `App\Http\Requests\ContactRequest`（FormRequest）
-   仕様書に指定された日本語エラーメッセージを全て実装
-   エラーは各項目の直下に赤文字で表示

---

### US004〜US005：会員登録・ログイン（Fortify）

#### 会員登録（`/register`）

-   必須項目
    -   お名前
    -   メールアドレス
    -   パスワード
-   バリデーション（RegisterRequest）
-   エラーメッセージ
    -   「お名前を入力してください」
    -   「メールアドレスを入力してください」
    -   「メールアドレスはメール形式で入力してください」
    -   「パスワードを入力してください」
-   登録後 `/admin` へ遷移

#### ログイン（`/login`）

-   必須項目：メールアドレス / パスワード
-   FormRequest（LoginRequest）＋ Fortify の authenticateUsing
-   認証失敗時：
    -   パスワード欄へ  
        **「ログイン情報が登録されていません」**

#### 認証動線

-   Register ↔ Login の相互リンク
-   ログイン成功で `/admin` へ
-   ログアウトは POST `/logout`

---

### US006〜US007：管理画面（一覧・検索・詳細・削除・CSV）

#### 管理画面トップ（`/admin`）

-   認証必須
-   一覧表示（7 件ずつのページネーション）
-   Bootstrap 風のカスタムページネーションデザイン
-   表示内容
    -   お名前
    -   性別
    -   メールアドレス
    -   お問い合わせ種別
    -   詳細ボタン

#### 検索（`GET /search`）

-   キーワード（姓・名・フルネーム・メール部分一致）
-   性別（全て/男性/女性/その他）
-   お問い合わせ種別
-   日付（date 入力）
-   検索後もページネーション有
-   リセットボタンで初期化

#### 詳細モーダル

-   お名前／性別／メール／電話番号／住所／建物名／種別／内容
-   × または背景クリックで閉じる

#### 削除

-   モーダル内から削除
-   削除後 `/admin` へリダイレクト

#### CSV エクスポート

-   現在の表示データを CSV 出力
-   検索結果も対象
-   UTF-8 BOM 付きで文字化け防止

---

## ルーティング一覧（主要）

```php
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;

Route::get('/', [ContactController::class, 'showInput'])->name('contact.input');
Route::post('/confirm', [ContactController::class, 'confirm'])->name('contact.confirm');
Route::post('/thanks', [ContactController::class, 'send'])->name('contact.send');

// 認証
Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// 管理画面
Route::middleware('auth')->group(function () {
    Route::get('/admin',  [AdminController::class, 'index'])->name('admin.index');
    Route::get('/search', [AdminController::class, 'search'])->name('admin.search');
    Route::get('/reset',  [AdminController::class, 'reset'])->name('admin.reset');
    Route::get('/export', [AdminController::class, 'export'])->name('admin.export');
    Route::post('/delete', [AdminController::class, 'delete'])->name('admin.delete');
});
```

---

## ER 図

![ER Diagram](er_contact_system.png)

---
