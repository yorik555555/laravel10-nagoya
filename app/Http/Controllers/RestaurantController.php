<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\Category;

class RestaurantController extends Controller
{
    public function index(Request $request)
    {
        // フォームからのデータを取得
        $keyword = $request->input('keyword');
        $category_id = $request->input('category_id');
        $price = $request->input('price');

        // 並べ替えの選択肢
        $sorts = [
            '掲載日が新しい順' => 'created_at desc',
            '価格が安い順' => 'lowest_price asc',
            '評価が高い順' => 'rating desc',
            '予約数が多い順' => 'popular desc', 
        ];

        // 現在の並べ替え状態
        $sorted = $request->input('select_sort', 'created_at desc');

        // レストランデータを取得
        $query = Restaurant::query();

        if ($keyword) {
            $query->where(function ($query) use ($keyword) {
                $query->where('name', 'like', "%{$keyword}%")
                      ->orWhere('address', 'like', "%{$keyword}%")
                      ->orWhereHas('categories', function ($query) use ($keyword) {
                          $query->where('name', 'like', "%{$keyword}%");
                      });
            });
        }

        if ($category_id) {
            $query->whereHas('categories', function ($query) use ($category_id) {
                // カテゴリーテーブルのidを明示的に指定
                $query->where('categories.id', $category_id);
            });
        }

        if ($price) {
            $query->where('lowest_price', '<=', $price);
        }

        // 並べ替え条件の設定
        $sort_query = [];
        if ($request->has('select_sort')) {
            $slices = explode(' ', $request->input('select_sort'));
            $sort_query[$slices[0]] = $slices[1];
        }

        // ソートとページネーションを適用
        if (empty($sort_query)) {
            // 並べ替え条件がない場合はデフォルトで作成日時が新しい順
            $restaurants = $query->orderBy('created_at', 'desc')->paginate(15);
        } else {
            // 並べ替え条件が予約数の多い順の場合
            if (array_key_exists('popular', $sort_query)) {
                $restaurants = $query->popularSortable($sort_query['popular'])->paginate(15);
            } else {
                $restaurants = $query->sortable($sort_query)->paginate(15);
            }
        }


        // カテゴリデータを取得
        $categories = Category::all();

        // データの総数
        $total = $restaurants->total();


        return view('restaurants.index', compact('keyword', 'category_id', 'price', 'sorts', 'sorted', 'restaurants', 'categories', 'total'));
    }

    public function show(Restaurant $restaurant)
    {      
        // レストランのカテゴリをロードする
        $restaurant->load('categories');

        // ビューにデータを渡す
        return view('restaurants.show', compact('restaurant'));
    }
}
