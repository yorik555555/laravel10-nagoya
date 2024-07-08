<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // 会員一覧ページのアクション
    public function index(Request $request)
    {
        // 検索キーワードを取得
        $keyword = $request->input('keyword');

        // クエリビルダーを初期化
        $query = User::query();

        // キーワードが存在する場合、氏名またはフリガナで部分一致検索
        if (!empty($keyword)) {
            $query->where(function($query) use ($keyword) {
                $query->where('name', 'LIKE', "%{$keyword}%")
                      ->orWhere('kana', 'LIKE', "%{$keyword}%");
            });
        }

        // ページネーションを適用
        $users = $query->paginate(10);

        // 取得したデータの総数を取得
        $total = $users->total();

        // ビューにデータを渡す
        return view('admin.users.index', [
            'users' => $users,
            'keyword' => $keyword,
            'total' => $total,
        ]);
    }

    // 会員詳細ページのアクション
    public function show(User $user)
    {
        // ビューにユーザー詳細を渡す
        return view('admin.users.show', ['user' => $user]);
    }
}
