<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Term;

class TermController extends Controller
{
    /**
     * Display the terms and conditions page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // termsテーブルの最初のデータを取得
        $term = Term::first();

        // ビューに変数を渡す
        return view('terms.index', compact('term'));
    }
}
