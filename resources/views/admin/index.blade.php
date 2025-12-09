<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Admin | FashionablyLate</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v=22">
</head>
<body>
<div class="admin-page">

    {{-- ヘッダー --}}
    <header class="admin-header">
        <div class="admin-header-inner">
            <div class="admin-header-brand">FashionablyLate</div>

            <div class="admin-header-logout">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="admin-logout-btn">logout</button>
                </form>
            </div>
        </div>
        <h1 class="admin-header-title">Admin</h1>
    </header>

    <main class="admin-main">

        {{-- フラッシュメッセージ --}}
        @if(session('status'))
            <div class="admin-flash">
                {{ session('status') }}
            </div>
        @endif

        {{-- 検索フォーム --}}
        <form action="{{ route('admin.search') }}" method="get" class="admin-search-form">
            <div class="admin-search-row">

                {{-- キーワード --}}
                <input type="text"
                       name="keyword"
                       class="admin-search-input"
                       placeholder="名前やメールアドレスを入力してください"
                       value="{{ request('keyword') }}">

                {{-- 性別 --}}
                <select name="gender" class="admin-search-select">
                    <option value="">性別</option>
                    <option value="1" {{ request('gender')=='1'?'selected':'' }}>男性</option>
                    <option value="2" {{ request('gender')=='2'?'selected':'' }}>女性</option>
                    <option value="3" {{ request('gender')=='3'?'selected':'' }}>その他</option>
                </select>

                {{-- カテゴリ --}}
                <select name="category_id" class="admin-search-select">
                    <option value="">お問い合わせの種類</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id')==$category->id?'selected':'' }}>
                            {{ $category->content }}
                        </option>
                    @endforeach
                </select>

                {{-- 日付 --}}
                <input type="date"
                       name="created_at"
                       class="admin-search-select admin-search-date"
                       value="{{ request('created_at') }}">

                {{-- ボタン --}}
                <button type="submit" class="admin-search-btn admin-search-btn--search">検索</button>
                <a href="{{ route('admin.reset') }}" class="admin-search-btn admin-search-btn--reset">リセット</a>

            </div>
        </form>

{{-- ▼ 検索の下：エクスポート + ページネーション --}}
<div class="admin-top-actions">
    <div class="admin-export-wrap">
        <a href="{{ route('admin.export') }}?{{ request()->getQueryString() }}"
           class="admin-export-btn">
            エクスポート
        </a>
    </div>

    <div class="admin-pagination">
        {{-- ★ ここがポイント：bootstrap-4 のビューを指定する --}}
        {{ $contacts->onEachSide(1)->links('pagination::bootstrap-4') }}
    </div>
</div>
{{-- ▲ ここまでをテーブルの上に置く --}}
        {{-- 一覧テーブル --}}
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>お名前</th>
                        <th>性別</th>
                        <th>メールアドレス</th>
                        <th>お問い合わせの種類</th>
                        <th class="admin-table-header-action"></th>
                    </tr>
                </thead>
                <tbody>

                @php
                    $genderLabels = [1=>'男性',2=>'女性',3=>'その他'];
                @endphp

                @forelse($contacts as $contact)
                    <tr>
                        <td>{{ $contact->last_name }}　{{ $contact->first_name }}</td>
                        <td>{{ $genderLabels[$contact->gender] ?? '' }}</td>
                        <td>{{ $contact->email }}</td>
                        <td>{{ optional($contact->category)->content }}</td>

                        <td class="admin-table-cell-action">
                            <button
                                type="button"
                                class="admin-detail-btn"
                                onclick="openContactModal(this)"
                                data-id="{{ $contact->id }}"
                                data-name="{{ $contact->last_name }}　{{ $contact->first_name }}"
                                data-gender="{{ $genderLabels[$contact->gender] ?? '' }}"
                                data-email="{{ $contact->email }}"
                                data-tel="{{ $contact->tel }}"
                                data-address="{{ $contact->address }}"
                                data-building="{{ $contact->building }}"
                                data-category="{{ optional($contact->category)->content }}"
                                data-detail="{{ $contact->detail }}"
                            >
                                詳細
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="admin-table-empty">該当するお問い合わせはありません。</td>
                    </tr>
                @endforelse

                </tbody>
            </table>
        </div>

        {{-- ========= モーダル ========= --}}
        <div class="admin-modal-overlay" id="contact-modal">
            <div class="admin-modal">

                <button type="button" class="admin-modal-close" onclick="closeContactModal()">&times;</button>

                <table class="admin-modal-table">
                    <tr><th>お名前</th><td id="modal-name"></td></tr>
                    <tr><th>性別</th><td id="modal-gender"></td></tr>
                    <tr><th>メールアドレス</th><td id="modal-email"></td></tr>
                    <tr><th>電話番号</th><td id="modal-tel"></td></tr>
                    <tr><th>住所</th><td id="modal-address"></td></tr>
                    <tr><th>建物名</th><td id="modal-building"></td></tr>
                    <tr><th>お問い合わせの種類</th><td id="modal-category"></td></tr>
                    <tr><th>お問い合わせ内容</th><td id="modal-detail" class="admin-modal-detail"></td></tr>
                </table>

                <form method="POST" action="{{ route('admin.delete') }}" class="admin-modal-delete-form">
                    @csrf
                    <input type="hidden" name="id" id="modal-contact-id">
                    <button type="submit" class="admin-modal-delete-btn">削除</button>
                </form>

            </div>
        </div>

    </main>
</div>

{{-- ========= モーダル用 JavaScript（完全版） ========= --}}
<script>
function openContactModal(btn) {
    const overlay = document.getElementById('contact-modal');
    overlay.classList.add('is-open');

    document.getElementById('modal-name').textContent = btn.dataset.name;
    document.getElementById('modal-gender').textContent = btn.dataset.gender;
    document.getElementById('modal-email').textContent = btn.dataset.email;
    document.getElementById('modal-tel').textContent = btn.dataset.tel;
    document.getElementById('modal-address').textContent = btn.dataset.address;
    document.getElementById('modal-building').textContent = btn.dataset.building;
    document.getElementById('modal-category').textContent = btn.dataset.category;
    document.getElementById('modal-detail').textContent = btn.dataset.detail;

    document.getElementById('modal-contact-id').value = btn.dataset.id;
}

function closeContactModal() {
    document.getElementById('contact-modal').classList.remove('is-open');
}

// グレー背景クリックで閉じる
document.addEventListener('DOMContentLoaded', () => {
    const overlay = document.getElementById('contact-modal');
    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) {
            closeContactModal();
        }
    });
});
</script>

</body>
</html>
