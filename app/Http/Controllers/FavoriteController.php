<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the user's favorite favoriteRestaurants.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 現在認証されているユーザーを取得
        $user = Auth::user();
        
        // お気に入りのレストランを取得し、作成日時が新しい順に並べ替えて15件ずつページネーションする
        $favorite_restaurants = $user->favoriteRestaurants()->orderBy('restaurant_user.created_at', 'desc')->paginate(15);
        
        return view('favorites.index', compact('favorite_restaurants'));
    }

    /**
     * Store a newly created favorite relationship in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Restaurant  $restaurant
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Restaurant $restaurant)
    {
        // 現在認証されているユーザーを取得
        $user = Auth::user();

        // Attach the restaurant to the user's favorites
        $user->favoriteRestaurants()->attach($restaurant);

        return redirect()->back()->with('flash_message', 'お気に入りに追加しました。');
    }

    /**
     * Remove the specified favorite relationship from storage.
     *
     * @param  \App\Models\Restaurant  $restaurant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Restaurant $restaurant)
    {
        // 現在認証されているユーザーを取得
        $user = Auth::user();

        // Detach the restaurant from the user's favorites
        $user->favoriteRestaurants()->detach($restaurant);

        return redirect()->back()->with('flash_message', 'お気に入りを解除しました。');
    }
}
