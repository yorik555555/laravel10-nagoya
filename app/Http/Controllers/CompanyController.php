<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;

class CompanyController extends Controller
{
     /**
     * Display the company overview page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // companiesテーブルの最初のデータを取得
        $company = Company::first();

        // ビューに変数を渡す
        return view('company.index', compact('company'));
    }
}
