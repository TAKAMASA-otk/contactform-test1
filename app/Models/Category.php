<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * 対応テーブル
     */
    protected $table = 'categories';

    /**
     * 一括代入を許可するカラム
     */
    protected $fillable = [
        'content',
    ];

    /**
     * contacts とのリレーション
     * Category は 複数の Contact を持つ
     */
    public function contacts()
    {
        return $this->hasMany(Contact::class);
        // 外部キーを変えたいなら：
        // return $this->hasMany(Contact::class, 'category_id', 'id');
    }
}
