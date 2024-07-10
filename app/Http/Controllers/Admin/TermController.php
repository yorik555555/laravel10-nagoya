<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Term; 

class TermController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // termsテーブルの最初のデータを取得
        $term = Term::first();

        // ビューにデータを渡して表示
        return view('admin.terms.index', compact('term'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Term $term)
    {
        // 編集するtermsテーブルのデータを取得
        return view('admin.terms.edit', compact('term'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Term $term)
    {
        // バリデーションルール
        $rules = [
            'content' => 'required',
        ];

        // バリデーション実行
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // termsテーブルのデータを更新
        $term->update($request->all());

        return redirect()->route('admin.terms.index')
            ->with('flash_message', '利用規約を編集しました。');
    }
}
