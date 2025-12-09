# アプリケーション名

Laravel お問い合わせフォームアプリ（contactform-test1）

## 概要

Laravel を用いて実装したお問い合わせフォームアプリケーションです。  
ユーザーは公開ページからお問い合わせを送信でき、管理者はログインしてお問い合わせ一覧の確認・検索・CSV エクスポート・削除などを行うことができます。

認証機能は Laravel Fortify をベースに、FormRequest を用いたバリデーションと  
**仕様書通りのカスタムエラーメッセージ**で実装しています。

## 環境構築

### ① リポジトリをクローン

```bash
git clone git@github.com:TAKAMASA-otk/contactform-test1.git
cd contactform-test1

② .env を作成
cp .env.example .env

③ 依存パッケージのインストール
composer install
npm install   # 必要な場合

④ アプリケーションキーの生成
php artisan key:generate

⑤ .env（DB設定）
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=contactform_test1
DB_USERNAME=root
DB_PASSWORD=secret

⑥ マイグレーション & シーディング
php artisan migrate --seed

⑦ 開発サーバー起動
php artisan serve

開発環境 URL
お問い合わせフォーム	http://localhost/
会員登録（Fortify）	http://localhost/register
ログイン	http://localhost/login
管理画面	http://localhost/admin
phpMyAdmin	http://localhost:8080/

使用技術（実行環境）
PHP 8.2.11
Laravel 8.83.8
Laravel Fortify
MySQL 8.0
nginx 1.21
jQuery 3.7.1（モーダル機能）
Docker / docker-compose

機能一覧（要件対応）
US001〜US003：お問い合わせフォーム〜確認〜サンクス
● 入力画面（/）
姓・名の分割入力

性別（1:男性 / 2:女性 / 3:その他）
メールアドレス
電話番号（3分割入力）
住所
建物名（任意）
お問い合わせの種類（categories テーブル参照）
お問い合わせ内容

● 確認画面（/confirm）
姓名の間にスペース
性別は「男性」「女性」「その他」で表示
電話番号はハイフンなし
「送信」「修正」ボタン
修正時は入力値を保持

● サンクスページ（/thanks）
完了メッセージ
HOME ボタンで / へ遷移

● バリデーション（仕様書準拠）
App\Http\Requests\ContactRequest
全項目に指定された日本語メッセージを実装
エラーは項目ごとに赤文字表示

US004〜US005：会員登録・ログイン（Fortify）
● 会員登録（/register）
必須項目
お名前
メールアドレス
パスワード
RegisterRequest によるバリデーション
仕様通りのエラーメッセージ
登録後 /admin へ遷移

● ログイン（/login）
メールアドレス・パスワード必須
LoginRequest＋Fortify で認証
認証失敗時は
「ログイン情報が登録されていません」
をパスワード欄に表示（仕様準拠）

● 認証動線
register ⇄ login
ログイン成功時 /admin
管理画面の logout ボタンでログアウト

US006〜US007：管理画面（一覧・検索・詳細・削除・CSV）
● 一覧（/admin）
認証必須
表示項目
お名前
性別
メール
お問い合わせの種類
詳細ボタン
7件ごとのページネーション
行 hover の視覚効果

● 検索（/search）
条件
氏名（部分一致 / フルネーム対応）
メール
性別（性別 / 全て / 男性 / 女性 / その他）
種類
日付
絞り込み結果にもページネーション
リセットで /admin 初期表示に戻る

● 詳細モーダル
お名前
性別
メール
電話番号
住所
建物名
種類
内容
右上 × または背景クリックで閉じる

● 削除機能
モーダルから削除
削除後は一覧に戻りメッセージ表示

● CSV エクスポート（応用）
現在表示中の一覧を CSV 出力
絞り込み後の結果も対象
BOM 付き UTF-8 で文字化け対策済み

主要ルーティング
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;

// お問い合わせフォーム（PG01〜PG03）
Route::get('/', [ContactController::class, 'showInput'])->name('contact.input');
Route::post('/confirm', [ContactController::class, 'confirm'])->name('contact.confirm');
Route::post('/thanks', [ContactController::class, 'send'])->name('contact.send');

// 認証（PG08〜PG10）
Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// 管理画面（PG04〜PG07）
Route::middleware('auth')->group(function () {
    Route::get('/admin',  [AdminController::class, 'index'])->name('admin.index');
    Route::get('/search', [AdminController::class, 'search'])->name('admin.search');
    Route::get('/reset',  [AdminController::class, 'reset'])->name('admin.reset');
    Route::get('/export', [AdminController::class, 'export'])->name('admin.export');
    Route::post('/delete', [AdminController::class, 'delete'])->name('admin.delete');
});

ER図
![ER Diagram](./er_contact_system.png)