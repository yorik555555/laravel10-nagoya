<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Cashier;

class SubscriptionController extends Controller
{
    // createアクション（有料プラン登録ページ）
    public function create()
    {
        $intent = Auth::user()->createSetupIntent();
        return view('subscription.create', [
            'intent' => $intent,
        ]);
    }

    // storeアクション（有料プラン登録機能）
    public function store(Request $request)
    {
        $user = $request->user();
        $user->newSubscription('premium_plan', 'price_1PbhHf2KZsCYqRbYwlsdtNSH')->create();

        return redirect('/')->with('flash_message', '有料プランへの登録が完了しました。');
    }

    // editアクション（お支払い方法編集ページ）
    public function edit()
    {
        $user = Auth::user();
        $intent = $user->createSetupIntent();

        return view('subscription.edit', [
            'user' => $user,
            'intent' => $intent,
        ]);
    }

    // updateアクション（お支払い方法更新機能）
    public function update(Request $request)
    {
        $user = $request->user();
        $user->updateDefaultPaymentMethod($request->paymentMethodId);

        return redirect('/')->with('flash_message', 'お支払い方法を変更しました。');
    }

    // cancelアクション（有料プラン解約ページ）
    public function cancel()
    {
        return view('subscription.cancel');
    }

    // destroyアクション（有料プラン解約機能）
    public function destroy(Request $request)
    {
        $user = $request->user();
        $user->subscription('premium_plan')->cancelNow();

        return redirect('/')->with('flash_message', '有料プランを解約しました。');
    }
}
