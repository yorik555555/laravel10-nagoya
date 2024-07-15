<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Company;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // companiesテーブルの全データを取得
        $company = Company::all();

        // ビューにデータを渡して表示
        return view('admin.company.index', compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        // 編集するcompaniesテーブルのデータを取得
        return view('admin.company.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        // バリデーションルール
        $rules = [
            'name' => 'required',
            'postal_code' => 'required|numeric|digits:7',
            'address' => 'required',
            'representative' => 'required',
            'establishment_date' => 'required|date',
            'capital' => 'required',
            'business' => 'required',
            'number_of_employees' => 'required|numeric',
        ];

        // バリデーション実行
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // companiesテーブルのデータを更新
        $company->update($request->all());

        return redirect()->route('admin.company.index')
            ->with('flash_message', '会社概要を編集しました。');
    }

}
