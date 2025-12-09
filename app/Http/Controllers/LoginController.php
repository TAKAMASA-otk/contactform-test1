<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        // 認証トライ
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('admin.index');
        }

        // 認証失敗 → password にエラーメッセージを返す
        return back()
            ->withErrors([
                'password' => 'ログイン情報が登録されていません',
            ])
            ->withInput();
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
