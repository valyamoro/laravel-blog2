<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoginController extends BaseController
{
    public function showLoginForm(): View
    {
        $title = 'Войти';

        return view('admin.auth.login', [
            'title' => $title,
        ]);
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|max:1000',
        ]);

        if (!auth('admin')->attempt($credentials)) {
            return back()->withErrors(['email' => 'Почта или пароль неверны.']);
        }

        if (auth('admin')->user()->is_banned) {
            auth('admin')->logout();
            abort(403);
        }

        return redirect()->route('admin.dashboard')->with('success', 'Вы успешно вошли в систему!');
    }

}
