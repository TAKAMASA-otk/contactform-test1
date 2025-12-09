# アプリケーション名
Laravel お問い合わせフォーム管理システム

## 環境構築

### Docker ビルド

- `git clone <リポジトリURL>`
- `docker-compose up -d --build`

### Laravel 環境構築

docker-compose exec php bash
composer install
cp .env.example .env   # 環境変数ファイルを作成して内容を編集
php artisan key:generate
php artisan migrate
php artisan db:seed

## 開発環境 URL

- お問い合わせ入力フォーム  
    http://localhost/
- 会員登録ページ（Fortify）  
    http://localhost/register
- ログインページ  
    http://localhost/login
- 管理画面（ログイン後）  
    http://localhost/admin
- phpMyAdmin  
    http://localhost:8080/


## ## 使用技術（実行環境）
- PHP 8.2.11
- Laravel 8.83.8
- Laravel Fortify
- MySQL 8.0
- nginx 1.21
- jQuery 3.7.1（管理画面モーダルに使用）
- Docker / docker-compose

## ER図

![ER Diagram](./er_contact_system.png)
