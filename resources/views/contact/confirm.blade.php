<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Confirm | FashionablyLate</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="{{ asset('css/contact.css') }}?v=10">
</head>
<body>
<div class="contact-page">

    <header class="contact-header">
        <div class="contact-brand">FashionablyLate</div>
        <h1 class="contact-title">Confirm</h1>
    </header>

    <main class="contact-main">

        {{-- 送信用フォーム --}}
        <form action="{{ route('contact.send') }}" method="post" class="confirm-form">
            @csrf

            @php
                // 性別の表示用ラベル
                $genderText = [
                    '1' => '男性',
                    '2' => '女性',
                    '3' => 'その他',
                ];
            @endphp

            <table class="confirm-table">
                {{-- お名前 --}}
                <tr>
                    <th>お名前</th>
                    <td>
                        {{ $inputs['last_name'] }}　{{ $inputs['first_name'] }}
                        <input type="hidden" name="last_name" value="{{ $inputs['last_name'] }}">
                        <input type="hidden" name="first_name" value="{{ $inputs['first_name'] }}">
                    </td>
                </tr>

                {{-- 性別 --}}
                <tr>
                    <th>性別</th>
                    <td>
                        {{ $genderText[$inputs['gender']] ?? '' }}
                        <input type="hidden" name="gender" value="{{ $inputs['gender'] }}">
                    </td>
                </tr>

                {{-- メールアドレス --}}
                <tr>
                    <th>メールアドレス</th>
                    <td>
                        {{ $inputs['email'] }}
                        <input type="hidden" name="email" value="{{ $inputs['email'] }}">
                    </td>
                </tr>

                {{-- 電話番号（ハイフンなし表示） --}}
                <tr>
                    <th>電話番号</th>
                    <td>
                        {{ $inputs['tel1'] . $inputs['tel2'] . $inputs['tel3'] }}
                        <input type="hidden" name="tel1" value="{{ $inputs['tel1'] }}">
                        <input type="hidden" name="tel2" value="{{ $inputs['tel2'] }}">
                        <input type="hidden" name="tel3" value="{{ $inputs['tel3'] }}">
                    </td>
                </tr>

                {{-- 住所 --}}
                <tr>
                    <th>住所</th>
                    <td>
                        {{ $inputs['address'] }}
                        <input type="hidden" name="address" value="{{ $inputs['address'] }}">
                    </td>
                </tr>

                {{-- 建物名 --}}
                <tr>
                    <th>建物名</th>
                    <td>
                        {{ $inputs['building'] ?? '' }}
                        <input type="hidden" name="building" value="{{ $inputs['building'] ?? '' }}">
                    </td>
                </tr>

                {{-- お問い合わせの種類 --}}
                <tr>
                    <th>お問い合わせの種類</th>
                    <td>
                        {{ $categoryName }}
                        <input type="hidden" name="category_id" value="{{ $inputs['category_id'] }}">
                    </td>
                </tr>

                {{-- お問い合わせ内容 --}}
                <tr>
                    <th>お問い合わせ内容</th>
                    <td class="confirm-table__content">
                        {{ $inputs['content'] }}
                        <input type="hidden" name="content" value="{{ $inputs['content'] }}">
                    </td>
                </tr>
            </table>

            <div class="confirm-buttons">
                {{-- 送信 --}}
                <button type="submit" class="confirm-button confirm-button--submit">
                    送信
                </button>

                {{-- 修正（入力画面に戻る、値はブラウザが保持） --}}
                <button type="button"
                        class="confirm-button confirm-button--back"
                        onclick="history.back();">
                    修正
                </button>
            </div>

        </form>
    </main>
</div>
</body>
</html>
