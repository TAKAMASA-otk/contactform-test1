<?php

namespace App\Providers;

use App\Models\User;
use App\Actions\Fortify\CreateNewUser;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        /**
         * ログイン処理（US005 / FN016〜FN020）
         * - バリデーションは LoginRequest のルール＆メッセージを流用
         * - 失敗時メッセージ「ログイン情報が登録されていません」
         */
        Fortify::authenticateUsing(function (Request $request) {
            // LoginRequest のルール・メッセージを使ってバリデーション
            $loginRequest = new LoginRequest();

            $validator = Validator::make(
                $request->all(),
                $loginRequest->rules(),
                $loginRequest->messages()
            );

            $data = $validator->validate();

            $user = User::where('email', $data['email'])->first();

            if ($user && Hash::check($data['password'], $data['password'])) {
                return $user;
            }

            // 入力情報が誤っている場合
            throw ValidationException::withMessages([
                'password' => ['ログイン情報が登録されていません'],
            ]);
        });

        /**
         * 新規登録処理（US004 / FN010〜FN014）
         * 実際の処理は App\Actions\Fortify\CreateNewUser に委譲
         */
        Fortify::createUsersUsing(CreateNewUser::class);
    }
}
