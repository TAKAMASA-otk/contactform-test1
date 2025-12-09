<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Thanks | FashionablyLate</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- contact.css を確実に読み込む --}}
    <link rel="stylesheet" href="{{ asset('css/contact.css') }}?v=10">
</head>
<body>

<div class="thanks-page">

    {{-- 背景の大きな Thank you --}}
    <div class="thanks-bg">Thank you</div>

    {{-- 中央のメッセージエリア --}}
    <div class="thanks-content">

        <p class="thanks-message">
            お問い合わせありがとうございました。
        </p>

        <a href="{{ route('contact.input') }}" class="thanks-home-button">
            HOME
        </a>

    </div>
</div>

</body>
</html>
