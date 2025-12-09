<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Category;

class ContactController extends Controller
{
    /**
     * PG01 お問い合わせ入力画面表示
     */
    public function showInput()
    {
        $categories = Category::all();
        return view('contact.input', compact('categories'));
    }

    /**
     * PG02 お問い合わせ内容確認画面
     */
    public function confirm(ContactRequest $request)
    {
        // バリデーション済データを取得
        $inputs = $request->validated();

        // 電話番号を結合（ハイフンなしが仕様）
        $inputs['tel'] = $inputs['tel1'] . $inputs['tel2'] . $inputs['tel3'];

        // カテゴリ名取得
        $category = Category::find($inputs['category_id']);
        $categoryName = $category ? $category->content : '';

        return view('contact.confirm', [
            'inputs'       => $inputs,
            'categoryName' => $categoryName,
        ]);
    }

    /**
     * PG03 お問い合わせ送信処理（完了画面表示）
     */
    public function send(ContactRequest $request)
    {
        // 再バリデーション（改ざん防止）
        $inputs = $request->validated();

        // 電話番号結合
        $inputs['tel'] = $inputs['tel1'] . $inputs['tel2'] . $inputs['tel3'];

        // detail に保存（フォームでは content を使うが DB は detail）
        $inputs['detail'] = $inputs['content'];

        // DBへ保存
        Contact::create($inputs);

        return view('contact.thanks');
    }
}
