<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Register | FashionablyLate</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="{{ asset('css/auth.css') }}?v=1">
</head>
<body>
<div class="auth-page">

    <header class="auth-header">
        <div class="auth-header-inner">
            <div class="auth-brand">FashionablyLate</div>
            {{-- login への動線 --}}
            <a href="{{ url('/login') }}" class="auth-header-link">login</a>
        </div>
        <h1 class="auth-title">Register</h1>
    </header>

    <main class="auth-main">
        <div class="auth-card">
            <form method="POST" action="{{ url('/register') }}">
                @csrf

                {{-- お名前 --}}
                <div class="auth-field">
                    <div class="auth-label">お名前</div>
                    <input type="text"
                           name="name"
                           class="auth-input"
                           placeholder="例: 山田　太郎"
                           value="{{ old('name') }}">
                    @error('name')
                        <div class="auth-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- メールアドレス --}}
                <div class="auth-field">
                    <div class="auth-label">メールアドレス</div>
                    <input type="email"
                           name="email"
                           class="auth-input"
                           placeholder="例: test@example.com"
                           value="{{ old('email') }}">
                    @error('email')
                        <div class="auth-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- パスワード --}}
                <div class="auth-field">
                    <div class="auth-label">パスワード</div>
                    <input type="password"
                           name="password"
                           class="auth-input"
                           placeholder="例: coachtech1106">
                    @error('password')
                        <div class="auth-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="auth-submit-wrap">
                    <button type="submit" class="auth-submit">登録</button>
                </div>
            </form>
        </div>
    </main>
</div>
</body>
</html>
