<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // お名前（姓）
            'last_name' => ['required', 'string', 'max:255'],

            // お名前（名）
            'first_name' => ['required', 'string', 'max:255'],

            // 性別（1,2,3）
            'gender' => ['required', 'in:1,2,3'],

            // メール
            'email' => ['required', 'email', 'max:255'],

            // 電話番号（3分割）
            'tel1' => ['required', 'regex:/^[0-9]+$/', 'max:5'],
            'tel2' => ['required', 'regex:/^[0-9]+$/', 'max:5'],
            'tel3' => ['required', 'regex:/^[0-9]+$/', 'max:5'],

            // 住所
            'address' => ['required', 'string', 'max:255'],

            // 建物名（任意）
            'building' => ['nullable', 'string', 'max:255'],

            // カテゴリ
            'category_id' => ['required', 'exists:categories,id'],

            // 内容（120文字以内）
            'content' => ['required', 'string', 'max:120'],
        ];
    }

    public function messages(): array
    {
        return [
            // お名前
            'last_name.required' => '姓を入力してください',
            'first_name.required' => '名を入力してください',

            // 性別
            'gender.required' => '性別を選択してください',
            'gender.in' => '性別を選択してください',

            // メール
            'email.required' => 'メールアドレスを入力してください',
            'email.email' => 'メールアドレスはメール形式で入力してください',

            // 電話番号（3分割）
            'tel1.required' => '電話番号を入力してください',
            'tel2.required' => '電話番号を入力してください',
            'tel3.required' => '電話番号を入力してください',

            'tel1.regex' => '電話番号は 半角英数字で入力してください',
            'tel2.regex' => '電話番号は 半角英数字で入力してください',
            'tel3.regex' => '電話番号は 半角英数字で入力してください',

            'tel1.max' => '電話番号は 5桁まで数字で入力してください',
            'tel2.max' => '電話番号は 5桁まで数字で入力してください',
            'tel3.max' => '電話番号は 5桁まで数字で入力してください',

            // 住所
            'address.required' => '住所を入力してください',

            // カテゴリー
            'category_id.required' => 'お問い合わせの種類を選択してください',
            'category_id.exists' => 'お問い合わせの種類を選択してください',

            // 内容
            'content.required' => 'お問い合わせ内容を入力してください',
            'content.max' => 'お問い合わせ内容は120文字以内で入力してください',
        ];
    }
}
