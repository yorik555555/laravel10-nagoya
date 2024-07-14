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
use App\Http\Controllers\ReviewController; 
use App\Http\Controllers\CompanyController as UserCompanyController;
use App\Http\Controllers\TermController as UserTermController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\SubscriptionController;



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
// 未認証ユーザがアクセス可能なルート
Route::group(['middleware' => 'guest:admin'], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
});

// 一般ユーザー用のルート（ログインユーザー向け）
Route::middleware(['auth', 'verified', 'session.timeout:120'])->group(function () {
    // Home ルートを再定義
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // restaurants 一覧と詳細
    Route::get('/restaurants', [RestaurantController::class, 'index'])->name('restaurants.index');
    Route::get('/restaurants/{restaurant}', [RestaurantController::class, 'show'])->name('restaurants.show');

    // User リソースのルートを追加
    Route::get('user', [UserController::class, 'index'])->name('user.index');
    Route::get('user/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::patch('user/{user}', [UserController::class, 'update'])->name('user.update');

    // Reviewのルート
    Route::prefix('restaurants')->group(function () {
        Route::get('{restaurant}/reviews', [ReviewController::class, 'index'])->name('restaurants.reviews.index');
        Route::post('{restaurant}/reviews', [ReviewController::class, 'store'])->name('restaurants.reviews.store');
        Route::get('{restaurant}/reviews/create', [ReviewController::class, 'create'])->name('restaurants.reviews.create');
        Route::get('{restaurant}/reviews/{review}', [ReviewController::class, 'show'])->name('restaurants.reviews.show');
        Route::put('{restaurant}/reviews/{review}', [ReviewController::class, 'update'])->name('restaurants.reviews.update');
        Route::delete('{restaurant}/reviews/{review}', [ReviewController::class, 'destroy'])->name('restaurants.reviews.destroy');
        Route::get('{restaurant}/reviews/{review}/edit', [ReviewController::class, 'edit'])->name('restaurants.reviews.edit');
    });

    // Reservationのルート
    Route::prefix('restaurants')->group(function () {
        Route::get('{restaurant}/reservations/create', [ReservationController::class, 'create'])->name('restaurants.reservations.create');
        Route::post('{restaurant}/reservations', [ReservationController::class, 'store'])->name('restaurants.reservations.store');
    });

    // お気に入りのルート
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/{restaurant}', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{restaurant}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');

    // 予約のルート
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');
});

// Subscription コントローラのルート
Route::middleware(['auth', 'verified'])->group(function () {
    // 有料プランに未登録の場合のみアクセス可能なルート
    Route::get('subscription/create', [SubscriptionController::class, 'create'])
        ->name('subscription.create')
        ->middleware('notsubscribed');

    Route::post('subscription', [SubscriptionController::class, 'store'])
        ->name('subscription.store')
        ->middleware('notsubscribed');

    // 有料プランに登録済みの場合のみアクセス可能なルート
    Route::get('subscription/edit', [SubscriptionController::class, 'edit'])
        ->name('subscription.edit')
        ->middleware('subscribed');

    Route::match(['put', 'patch'], 'subscription', [SubscriptionController::class, 'update'])
        ->name('subscription.update')
        ->middleware('subscribed');

    Route::get('subscription/cancel', [SubscriptionController::class, 'cancel'])
        ->name('subscription.cancel')
        ->middleware('subscribed');

    Route::delete('subscription', [SubscriptionController::class, 'destroy'])
        ->name('subscription.destroy')
        ->middleware('subscribed');
});

// 未認証ユーザがアクセス可能なルート（会社概要ページと利用規約ページ）
Route::middleware(['guest:admin'])->group(function () {
    Route::get('company', [UserCompanyController::class, 'index'])->name('company.index');
    Route::get('terms', [UserTermController::class, 'index'])->name('terms.index');
});

// 管理者用のルート
Route::prefix('admin')->middleware('auth:admin')->group(function () {
    Route::get('home', [Admin\HomeController::class, 'index'])->name('admin.home');
    Route::get('users', [Admin\UserController::class, 'index'])->name('admin.users.index');
    Route::get('users/{user}', [Admin\UserController::class, 'show'])->name('admin.users.show');

    // Admin 用の restaurants ルート
    Route::resource('restaurants', Admin\RestaurantController::class, ['as' => 'admin']);

    // Categoryリソースのルートを追加
    Route::resource('categories', Admin\CategoryController::class, ['as' => 'admin']);

    // Companyリソースのルートを追加
    Route::get('company', [Admin\CompanyController::class, 'index'])->name('admin.company.index');
    Route::get('company/{company}/edit', [Admin\CompanyController::class, 'edit'])->name('admin.company.edit');
    Route::put('company/{company}', [Admin\CompanyController::class, 'update'])->name('admin.company.update');

    // Termリソースのルートを追加
    Route::get('terms', [Admin\TermController::class, 'index'])->name('admin.terms.index');
    Route::get('terms/{term}/edit', [Admin\TermController::class, 'edit'])->name('admin.terms.edit');
    Route::put('terms/{term}', [Admin\TermController::class, 'update'])->name('admin.terms.update');

    // ログアウト
    Route::post('logout', [Admin\Auth\AuthenticatedSessionController::class, 'destroy'])->name('admin.logout');
});

require __DIR__.'/auth.php';