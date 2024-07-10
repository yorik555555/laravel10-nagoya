<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');

        // ページネーション適用済みのデータを取得
        $query = Category::query();
        if (!empty($keyword)) {
            $query->where('name', 'like', '%' . $keyword . '%');
        }
        $categories = $query->paginate(10); // 1ページあたり10件表示

        $total = $categories->total();

        return view('admin.categories.index', compact('categories', 'keyword', 'total'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // バリデーションルール
        $rules = [
            'name' => 'required',
        ];

        // バリデーション実行
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // データベースに新規登録
        $category = new Category();
        $category->name = $request->input('name');
        $category->save();

        return redirect()->route('admin.categories.index')
            ->with('flash_message', 'カテゴリを登録しました。');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        // バリデーションルール
        $rules = [
            'name' => 'required',
        ];

        // バリデーション実行
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // データベースを更新
        $category->name = $request->input('name');
        $category->save();

        return redirect()->route('admin.categories.index')
            ->with('flash_message', 'カテゴリを編集しました。');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // カテゴリを削除
        $category->delete();

        // フラッシュメッセージを設定し、カテゴリ一覧ページにリダイレクト
        return redirect()->route('admin.categories.index')
            ->with('flash_message', 'カテゴリを削除しました。');
    }
}

