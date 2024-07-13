<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display the user information page.
     */
    public function index()
    {
        $user = Auth::user();
        return view('user.index', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        if ($user->id !== Auth::id()) {
            return redirect()->route('user.index')->with('error_message', '不正なアクセスです。');
        }

        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        if ($user->id !== Auth::id()) {
            return redirect()->route('user.index')->with('error_message', '不正なアクセスです。');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'kana' => 'required|string|regex:/^[ァ-ヶー]+$/u|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'address' => 'required|string|max:255',
            'phone_number' => 'required|digits_between:10,11',
            'birthday' => 'nullable|digits:8',
            'occupation' => 'nullable|string|max:255',
        ]);

        $user->update($request->all());

        return redirect()->route('user.index')->with('flash_message', '会員情報を編集しました。');
    }

    
}
