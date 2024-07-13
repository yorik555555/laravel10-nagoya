<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Restaurant $restaurant)
    {
        // Laravel Cashierなどのサブスクリプション管理パッケージに依存するメソッド
        //ユーザーが有料会員かどうかを仮定のプロパティからチェックしていますが、この部分は削除します。
        // $user = Auth::user();
        // $is_paid_member = $user->is_paid_member;

        // if ($is_paid_member) {
            $reviews = $restaurant->reviews()->orderBy('created_at', 'desc')->paginate(5);
        // } else {
        //     $reviews = $restaurant->reviews()->orderBy('created_at', 'desc')->take(3)->get();
        // }

        return view('reviews.index', compact('restaurant', 'reviews'));
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create(Restaurant $restaurant)
    {
        return view('reviews.create', compact('restaurant'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Restaurant $restaurant)
    {
        $request->validate([
            'score' => 'required|integer|between:1,5',
            'content' => 'required',
        ]);

        Review::create([
            'content' => $request->content,
            'score' => $request->score,
            'restaurant_id' => $restaurant->id,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('restaurants.reviews.index', $restaurant->id)
                         ->with('flash_message', 'レビューを投稿しました。');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Restaurant $restaurant, Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            return redirect()->route('restaurants.reviews.index', $restaurant->id)
                             ->with('error_message', '不正なアクセスです。');
        }

        return view('reviews.edit', compact('restaurant', 'review'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Restaurant $restaurant, Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            return redirect()->route('reviews.index', $restaurant->id)
                             ->with('error_message', '不正なアクセスです。');
        }

        $request->validate([
            'score' => 'required|integer|between:1,5',
            'content' => 'required',
        ]);

        $review->update([
            'content' => $request->content,
            'score' => $request->score,
        ]);

        return redirect()->route('reviews.index', $restaurant->id)
                         ->with('flash_message', 'レビューを編集しました。');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Restaurant $restaurant, Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            return redirect()->route('reviews.index', $restaurant->id)
                             ->with('error_message', '不正なアクセスです。');
        }

        $review->delete();

        return redirect()->route('reviews.index', $restaurant->id)
                         ->with('flash_message', 'レビューを削除しました。');
    }
}
