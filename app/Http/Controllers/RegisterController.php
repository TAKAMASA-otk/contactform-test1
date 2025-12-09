<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /**
     * 会員登録画面表示（PG08）
     */
    public function show()
    {
        return view('auth.register');
    }

    /**
     * 会員登録処理（PG08 → PG04 管理画面へ）
     */
    public function register(RegisterRequest $request)
    {
        // バリデーション済みデータ取得
        $data = $request->validated();

        // パスワードはハッシュ化
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        // Fortify / Auth を使ってログイン状態にする
        Auth::login($user);

        // 管理画面へ遷移（FN014）
        return redirect('/admin');
    }
}
