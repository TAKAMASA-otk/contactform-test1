<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Contact | FashionablyLate</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="{{ asset('css/contact.css') }}?v=1">
</head>
<body>
<div class="contact-page">

    <header class="contact-header">
        <div class="contact-brand">FashionablyLate</div>
        <h1 class="contact-title">Contact</h1>
    </header>

    {{-- バリデーションエラー（全体） --}}
    @if ($errors->any())
        <div class="contact-errors">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <main class="contact-main">
        <form action="{{ route('contact.confirm') }}" method="post" class="contact-form">
            @csrf

            {{-- お名前 --}}
            <div class="contact-row">
                <div class="contact-label">
                    お名前 <span class="contact-required">※</span>
                </div>
                <div class="contact-field">
                    <div class="contact-name-group">
                        <input type="text"
                               name="last_name"
                               class="contact-input"
                               placeholder="例: 山田"
                               value="{{ old('last_name') }}">
                        <input type="text"
                               name="first_name"
                               class="contact-input"
                               placeholder="例: 太郎"
                               value="{{ old('first_name') }}">
                    </div>
                    @error('last_name')
                        <div class="contact-field-error">{{ $message }}</div>
                    @enderror
                    @error('first_name')
                        <div class="contact-field-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- 性別（1:男性 2:女性 3:その他） --}}
            <div class="contact-row">
                <div class="contact-label">
                    性別 <span class="contact-required">※</span>
                </div>
                <div class="contact-field">
                    <div class="contact-gender-group">
                        <label>
                            <input type="radio"
                                   name="gender"
                                   value="1"
                                   {{ old('gender', '1') === '1' ? 'checked' : '' }}>
                            男性
                        </label>
                        <label>
                            <input type="radio"
                                   name="gender"
                                   value="2"
                                   {{ old('gender') === '2' ? 'checked' : '' }}>
                            女性
                        </label>
                        <label>
                            <input type="radio"
                                   name="gender"
                                   value="3"
                                   {{ old('gender') === '3' ? 'checked' : '' }}>
                            その他
                        </label>
                    </div>
                    @error('gender')
                        <div class="contact-field-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- メールアドレス --}}
            <div class="contact-row">
                <div class="contact-label">
                    メールアドレス <span class="contact-required">※</span>
                </div>
                <div class="contact-field">
                    <input type="email"
                           name="email"
                           class="contact-input"
                           placeholder="例: test@example.com"
                           value="{{ old('email') }}">
                    @error('email')
                        <div class="contact-field-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- 電話番号（3分割） --}}
            <div class="contact-row">
                <div class="contact-label">
                    電話番号 <span class="contact-required">※</span>
                </div>
                <div class="contact-field">
                    <div class="contact-tel-group">
                        <input type="text"
                               name="tel1"
                               class="contact-input contact-input--tel"
                               maxlength="5"
                               placeholder="080"
                               value="{{ old('tel1') }}">
                        <span class="contact-tel-separator">-</span>
                        <input type="text"
                               name="tel2"
                               class="contact-input contact-input--tel"
                               maxlength="5"
                               placeholder="1234"
                               value="{{ old('tel2') }}">
                        <span class="contact-tel-separator">-</span>
                        <input type="text"
                               name="tel3"
                               class="contact-input contact-input--tel"
                               maxlength="5"
                               placeholder="5678"
                               value="{{ old('tel3') }}">
                    </div>
                    {{-- 3つのどこでエラーしても同じ位置にまとめて表示 --}}
                    @if($errors->has('tel1') || $errors->has('tel2') || $errors->has('tel3'))
                        <div class="contact-field-error">
                            {{ $errors->first('tel1') ?: ($errors->first('tel2') ?: $errors->first('tel3')) }}
                        </div>
                    @endif
                </div>
            </div>

            {{-- 住所 --}}
            <div class="contact-row">
                <div class="contact-label">
                    住所 <span class="contact-required">※</span>
                </div>
                <div class="contact-field">
                    <input type="text"
                           name="address"
                           class="contact-input"
                           placeholder="例: 東京都渋谷区〇〇1-2-3"
                           value="{{ old('address') }}">
                    @error('address')
                        <div class="contact-field-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- 建物名（任意） --}}
            <div class="contact-row">
                <div class="contact-label">
                    建物名
                </div>
                <div class="contact-field">
                    <input type="text"
                           name="building"
                           class="contact-input"
                           placeholder="例: 〇〇マンション101"
                           value="{{ old('building') }}">
                    @error('building')
                        <div class="contact-field-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- お問い合わせの種類 --}}
            <div class="contact-row">
                <div class="contact-label">
                    お問い合わせの種類 <span class="contact-required">※</span>
                </div>
                <div class="contact-field">
                    <select name="category_id" class="contact-select">
                        <option value="">選択してください</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ (string)old('category_id') === (string)$category->id ? 'selected' : '' }}>
                                {{ $category->content }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="contact-field-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- お問い合わせ内容 --}}
            <div class="contact-row contact-row--textarea">
                <div class="contact-label">
                    お問い合わせ内容 <span class="contact-required">※</span>
                </div>
                <div class="contact-field">
                    <textarea name="content"
                              class="contact-textarea"
                              placeholder="お問い合わせ内容をご記載ください（120文字以内）">{{ old('content') }}</textarea>
                    @error('content')
                        <div class="contact-field-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="contact-submit">
                <button type="submit" class="contact-submit-button">確認画面</button>
            </div>

        </form>
    </main>
</div>
</body>
</html>
