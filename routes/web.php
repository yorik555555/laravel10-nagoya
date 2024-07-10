<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\RestaurantController as AdminRestaurantController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\TermController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RestaurantController; // ユーザー用のRestaurantController
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// 管理者としてログインしていない状態でのみアクセス＋未認証ユーザのアクセス
Route::group(['middleware' => 'guest:admin'], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
});

require __DIR__.'/auth.php';

// ログインユーザー向けのルート
Route::middleware(['auth', 'verified'])->group(function () {

    // restaurants 一覧と詳細
    Route::get('/restaurants', [RestaurantController::class, 'index'])->name('restaurants.index');
    Route::get('/restaurants/{restaurant}', [RestaurantController::class, 'show'])->name('restaurants.show');

    /*// ログインユーザーの処理
    Route::controller(UserController::class)->group(function () {
        Route::get('users/mypage', 'mypage')->name('mypage');
        Route::get('users/mypage/edit', 'edit')->name('mypage.edit');
        Route::put('users/mypage', 'update')->name('mypage.update');
        Route::get('users/mypage/password/edit', 'edit_password')->name('mypage.edit_password');
        Route::put('users/mypage/password', 'update_password')->name('mypage.update_password');
        Route::get('users/mypage/favorite', 'favorite')->name('mypage.favorite');
        Route::delete('users/mypage/delete', 'destroy')->name('mypage.destroy');
        Route::get('users/mypage/cart_history', 'cart_history_index')->name('mypage.cart_history');
        Route::get('users/mypage/cart_history/{num}', 'cart_history_show')->name('mypage.cart_history_show');
    });*/

    // User リソースのルートを追加
    Route::get('user', [UserController::class, 'index'])->name('user.index');
    Route::get('user/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::patch('user/{user}', [UserController::class, 'update'])->name('user.update');
});

// 管理者用 adminページ
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'auth:admin'], function () {
    Route::get('home', [Admin\HomeController::class, 'index'])->name('home');
    Route::get('users', [Admin\UserController::class, 'index'])->name('users.index');
    Route::get('users/{user}', [Admin\UserController::class, 'show'])->name('users.show');

    // Admin 用の restaurants ルート
    //※ポ!!!! 'admin/restaurants'で一般ユーザー向けの restaurants ルートと区別させる
    Route::resource('admin/restaurants', AdminRestaurantController::class);

    // Categoryリソースのルートを追加
    Route::resource('categories', CategoryController::class);

    // Companyリソースのルートを追加
    Route::get('company', [CompanyController::class, 'index'])->name('company.index');
    Route::get('company/{company}/edit', [CompanyController::class, 'edit'])->name('company.edit');
    Route::put('company/{company}', [CompanyController::class, 'update'])->name('company.update');

    // Termリソースのルートを追加
    Route::get('terms', [TermController::class, 'index'])->name('terms.index');
    Route::get('terms/{term}/edit', [TermController::class, 'edit'])->name('terms.edit');
    Route::put('terms/{term}', [TermController::class, 'update'])->name('terms.update');
});
