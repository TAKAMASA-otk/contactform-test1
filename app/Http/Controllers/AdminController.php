<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * PG04 管理画面トップ（一覧）
     * 初期表示：全件（新しい順）を 7件ずつ表示
     */
    public function index()
    {
        $categories = Category::all();

        $contacts = Contact::with('category')
            ->orderBy('created_at', 'desc')
            ->paginate(7);

        return view('admin.index', compact('categories', 'contacts'));
    }

    /**
     * PG05 検索
     *
     * 仕様（FN022）：
     *  - 名前：姓・名・フルネーム、部分一致 / 全一致（LIKEで部分一致対応）
     *  - メール：部分一致 / 全一致
     *  - 性別：未選択なら条件なし、1/2/3 を指定したら該当のみ
     *  - お問い合わせの種類：プルダウンで選択
     *  - 日付：input type="date" で指定された日付と created_at の日付が一致するもの
     *  - 全ブランクでも検索可（＝全件表示）
     */
    public function search(Request $request)
    {
        $categories = Category::all();

        $query = Contact::with('category');

        // ① キーワード（名前 or メール）
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {
                // 姓
                $q->where('last_name', 'LIKE', "%{$keyword}%")
                    // 名
                    ->orWhere('first_name', 'LIKE', "%{$keyword}%")
                    // フルネーム（姓＋名）
                    ->orWhere(DB::raw("CONCAT(last_name, first_name)"), 'LIKE', "%{$keyword}%")
                    // メールアドレス
                    ->orWhere('email', 'LIKE', "%{$keyword}%");
            });
        }

        // ② 性別
        // フォーム側は value=""
        //              "1"：男性, "2"：女性, "3"：その他
        if ($request->filled('gender')) {
            $gender = $request->gender; // "1" / "2" / "3"
            $query->where('gender', $gender);
        }

        // ③ お問い合わせの種類（categories.id）
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // ④ 日付（created_at の日付部分で絞り込み）
        if ($request->filled('created_at')) {
            $query->whereDate('created_at', $request->created_at);
        }

        // 並び順 & ページネーション（仕様：7件ごと）
        // appends() で検索条件をページングリンクに引き継ぐ
        $contacts = $query
            ->orderBy('created_at', 'desc')
            ->paginate(7)
            ->appends($request->query());

        return view('admin.index', compact('categories', 'contacts'));
    }

    /**
     * PG06 検索リセット
     * 検索条件をクリアして一覧に戻す
     */
    public function reset()
    {
        return redirect()->route('admin.index');
    }

    /**
     * PG07 お問い合わせ削除
     */
    public function delete(Request $request)
    {
        Contact::findOrFail($request->id)->delete();

        return redirect()
            ->route('admin.index')
            ->with('status', 'お問い合わせを削除しました。');
    }

    /**
     * PG11 エクスポート（ここは後で一緒に仕上げてもOK）
     */
    public function export(Request $request)
    {
    // 検索結果と同じ条件でクエリを組み立てる
    $query = Contact::with('category');

    // ① キーワード（名前 or メール）
    if ($request->filled('keyword')) {
        $keyword = $request->keyword;

        $query->where(function ($q) use ($keyword) {
            $q->where('last_name', 'LIKE', "%{$keyword}%")
                ->orWhere('first_name', 'LIKE', "%{$keyword}%")
                ->orWhere(DB::raw("CONCAT(last_name, first_name)"), 'LIKE', "%{$keyword}%")
                ->orWhere('email', 'LIKE', "%{$keyword}%");
        });
    }

    // ② 性別
    if ($request->filled('gender')) {
        $query->where('gender', $request->gender);
    }

    // ③ お問い合わせの種類
    if ($request->filled('category_id')) {
        $query->where('category_id', $request->category_id);
    }

    // ④ 日付
    if ($request->filled('created_at')) {
        $query->whereDate('created_at', $request->created_at);
    }

    // 全件取得（ページネーションなし）
    $contacts = $query
        ->orderBy('created_at', 'desc')
        ->get();

    $genderLabels = [1 => '男性', 2 => '女性', 3 => 'その他'];

    $filename = 'contacts_' . now()->format('Ymd_His') . '.csv';

    $headers = [
        'Content-Type'        => 'text/csv; charset=UTF-8',
        'Content-Disposition' => "attachment; filename=\"{$filename}\"",
    ];

    $callback = function () use ($contacts, $genderLabels) {
        $handle = fopen('php://output', 'w');

        // Excel で文字化けしないように BOM 付与
        fwrite($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

        // ヘッダー行
        fputcsv($handle, [
            'お名前',
            '性別',
            'メールアドレス',
            'お問い合わせの種類',
            'お問い合わせ内容',
            '作成日',
        ]);

        // データ行
        foreach ($contacts as $contact) {
            fputcsv($handle, [
                $contact->last_name . ' ' . $contact->first_name,
                $genderLabels[$contact->gender] ?? '',
                $contact->email,
                optional($contact->category)->content,
                $contact->detail,
                optional($contact->created_at)->format('Y-m-d H:i'),
            ]);
        }

        fclose($handle);
    };

    return response()->streamDownload($callback, $filename, $headers);
    }

}
