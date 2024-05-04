<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AdminLoginRequest;
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

    public function login(AdminLoginRequest $request): RedirectResponse
    {
        if (!auth('admin')->attempt($request->only('email', 'password'))) {
            return back()->withErrors(['email' => 'Почта или пароль неверны.']);
        }

        if (auth('admin')->user()->is_banned) {
            auth('admin')->logout();
            abort(403);
        }

        return redirect()->route('admin.dashboard')->with('success', 'Вы успешно вошли в систему!');
    }

}
