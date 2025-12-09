<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->bigIncrements('id');                 // bigint unsigned, PK

            // お問い合わせの種類（カテゴリ）
            $table->unsignedBigInteger('category_id');   // categories(id) へのFK

            // お名前（姓・名）
            $table->string('first_name', 255);           // 名
            $table->string('last_name', 255);            // 姓

            // 性別 1:男性 2:女性 3:その他
            $table->tinyInteger('gender');               // tinyint

            // 連絡先
            $table->string('email', 255);
            $table->string('tel', 255);                  // 「xxx-xxx-xxxx」などをまとめて保存

            // 住所
            $table->string('address', 255);
            $table->string('building', 255)->nullable(); // 任意入力

            // お問い合わせ内容
            $table->text('detail');

            // タイムスタンプ
            $table->timestamps();                        // created_at / updated_at

            // 外部キー制約
            $table->foreign('category_id')
                  ->references('id')->on('categories')
                  ->onDelete('cascade');                 // カテゴリ削除時の挙動はお好みで
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
