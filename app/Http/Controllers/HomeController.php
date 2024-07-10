<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\Category;

class HomeController extends Controller
{
    /**
    * Display the home page.
    */
    public function index()
    {
        // restaurantsテーブルの6つのデータ（評価が高い順に並べ替える予定）
        $highly_rated_restaurants = Restaurant::take(6)->get();

        // categoriesテーブルのすべてのデータ
        $categories = Category::all();

        // restaurantsテーブルの6つのデータ（作成日時が新しい順に並べ替える）
        $new_restaurants = Restaurant::orderBy('created_at', 'desc')->take(6)->get();

        // ビューにデータを渡して表示
        return view('home', compact('highly_rated_restaurants', 'categories', 'new_restaurants'));
    }
}
