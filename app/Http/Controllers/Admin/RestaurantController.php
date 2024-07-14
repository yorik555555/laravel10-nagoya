<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\Category;
use App\Models\RegularHoliday; // RegularHolidayモデルをインポート
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');

        // ページネーション適用済みのデータを取得
        $query = Restaurant::query();
        if (!empty($keyword)) {
            $query->where('name', 'like', '%' . $keyword . '%');
        }
        $restaurants = $query->paginate(10); // 1ページあたり10件表示

        $total = $restaurants->total();

        return view('admin.restaurants.index', compact('restaurants', 'keyword', 'total'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all(); // カテゴリテーブルのすべてのデータを取得
        $regular_holidays = RegularHoliday::all(); // 定休日テーブルのすべてのデータを取得

        return view('admin.restaurants.create', compact('categories', 'regular_holidays'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // バリデーションルール
        $rules = [
            'name' => 'required',
            'description' => 'required',
            'lowest_price' => 'required|numeric|min:0',
            'highest_price' => 'required|numeric|min:0|gte:lowest_price',
            'postal_code' => 'required|numeric|digits:7',
            'address' => 'required',
            'opening_time' => 'required',
            'closing_time' => 'required|after:opening_time',
            'seating_capacity' => 'required|numeric|min:0',
            'image' => 'sometimes|image|max:2048', // 画像ファイル、最大2MB
        ];

        // バリデーション実行
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // 画像ファイル処理
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $image = $request->file('image')->store('public/restaurants');
            $imageFileName = basename($image);
        } else {
            $imageFileName = '';
        }


        // データベースに新規登録
        $restaurant = new Restaurant();
        $restaurant->name = $request->input('name');
        $restaurant->image = $imageFileName; // 画像ファイル名
        $restaurant->description = $request->input('description');
        $restaurant->lowest_price = $request->input('lowest_price');
        $restaurant->highest_price = $request->input('highest_price');
        $restaurant->postal_code = $request->input('postal_code');
        $restaurant->address = $request->input('address');
        $restaurant->opening_time = $request->input('opening_time');
        $restaurant->closing_time = $request->input('closing_time');
        $restaurant->seating_capacity = $request->input('seating_capacity');
        $restaurant->save();

        /// カテゴリの同期処理
        $category_ids = array_filter($request->input('category_ids'));
        $restaurant->categories()->sync($category_ids);

        // 定休日の同期処理
        $regular_holiday_ids = $request->input('regular_holiday_ids', []);
        $restaurant->regular_holidays()->sync($regular_holiday_ids);

        return redirect()->route('admin.restaurants.index')
            ->with('flash_message', '店舗を登録しました。');
    }

    /**
     * Display the specified resource.
     */
    public function show(Restaurant $restaurant)
    {
        return view('admin.restaurants.show', compact('restaurant'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Restaurant $restaurant)
    {

        $categories = Category::all(); // categoriesテーブルのすべてのデータを取得
        $category_ids = $restaurant->categories->pluck('id')->toArray(); // 店舗に設定されているカテゴリのIDの配列を取得
        $regular_holidays = RegularHoliday::all(); // regular_holidaysテーブルのすべてのデータを取得
        $regular_holiday_ids = $restaurant->regular_holidays->pluck('id')->toArray(); // 店舗に設定されている定休日のIDの配列を取得

        return view('admin.restaurants.edit', compact('restaurant', 'categories', 'category_ids', 'regular_holidays', 'regular_holiday_ids'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Restaurant $restaurant)
    {
        // バリデーションルール
        $rules = [
            'name' => 'required',
            'description' => 'required',
            'lowest_price' => 'required|numeric|min:0',
            'highest_price' => 'required|numeric|min:0|gte:lowest_price',
            'postal_code' => 'required|numeric|digits:7',
            'address' => 'required',
            'opening_time' => 'required',
            'closing_time' => 'required|after:opening_time',
            'seating_capacity' => 'required|numeric|min:0',
            'image' => 'sometimes|image|max:2048', // 画像ファイル、最大2MB
        ];

        // バリデーション実行
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // 画像ファイル処理
        $imageFileName = $restaurant->image; // 現在の画像ファイル名を取得

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            // 新しい画像がアップロードされた場合
            $image = $request->file('image')->store('public/restaurants');
            $imageFileName = basename($image); // 新しい画像ファイル名を取得
        }

        // データベースを更新
        $restaurant->name = $request->input('name');
        $restaurant->description = $request->input('description');
        $restaurant->lowest_price = $request->input('lowest_price');
        $restaurant->highest_price = $request->input('highest_price');
        $restaurant->postal_code = $request->input('postal_code');
        $restaurant->address = $request->input('address');
        $restaurant->opening_time = $request->input('opening_time');
        $restaurant->closing_time = $request->input('closing_time');
        $restaurant->seating_capacity = $request->input('seating_capacity');
        $restaurant->image = $imageFileName; // 更新後の画像ファイル名を設定
        $restaurant->save();

        // カテゴリの同期処理
        $category_ids = array_filter($request->input('category_ids', []));
        $restaurant->categories()->sync($category_ids);

        // 定休日の同期処理
        $regular_holiday_ids = array_filter($request->input('regular_holiday_ids', []));
        $restaurant->regular_holidays()->sync($regular_holiday_ids);

        // 配列のフィルタリング
        //$regular_holiday_ids = array_filter($regular_holiday_ids);

        //$restaurant->categories()->sync($category_ids);

        return redirect()->route('admin.restaurants.show', $restaurant->id)
        ->with('flash_message', '店舗を編集しました。');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Restaurant $restaurant)
    {
        // レストランを削除
        $restaurant->delete();

        // フラッシュメッセージを設定し、店舗一覧ページにリダイレクト
        return redirect()->route('admin.restaurants.index')
            ->with('flash_message', '店舗を削除しました。');
    }
}
