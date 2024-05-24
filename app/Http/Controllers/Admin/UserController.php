<?php

namespace App\Http\Controllers\Admin;

use App\Events\UserRegisteredEvent;
use App\Http\Requests\UserRequest;
use App\Models\AdminUser;
use App\Models\User;
use App\Services\Users\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends BaseController
{
    public function __construct(private readonly UserService $userService) {}

    public function index(Request $request): View
    {
        $title = 'Пользователи';

        $perPage = config('pagination.pagination_5');
        $users = $this->userService->getAllWithPagination($request, $perPage);

        return view('admin.users.index', [
            'title' => $title,
            'paginator' => $users,
        ]);
    }

    public function create(): View
    {
        $title = 'Добавить';

        return view('admin.users.create', [
            'title' => $title,
        ]);
    }

    public function store(UserRequest $request): RedirectResponse
    {
        $result = $this->userService->create($request);

        if (!$result) {
            $errorSave = config('messages.error.save');
            return back()->withErrors(['error' => $errorSave]);
        }

        event(new UserRegisteredEvent(new User($request->input())));

        $successSave = config('messages.success.save');
        return redirect()->route('users.index')->with('success', $successSave);
    }

    public function show(User $user): View
    {
        $title = 'Профиль пользователя: ' . $user->username;

        return view('admin.users.show', [
            'title' => $title,
            'item' => $user,
        ]);
    }

    public function edit(User $user): View
    {
        $title = 'Редактировать: ' . $user->username;

        return view('admin.users.edit', [
            'title' => $title,
            'item' => $user,
        ]);
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $result = $this->userService->update($request, $user);

        if (!$result) {
            $errorSave = config('messages.error.save');
            return back()->withErrors(['error' => $errorSave]);
        }

        $successSave = config('messages.success.save');
        return redirect()->route('users.index')->with('success', $successSave);
    }

    public function destroy(User $user): RedirectResponse
    {
        $result = $this->userService->destroy($user);

        if (!$result) {
            $errorDestroy = config('messages.error.destroy');
            return back()->withErrors(['error' => $errorDestroy]);
        }

        $successDestroy = config('messages.success.destroy');
        return redirect()->route('users.index')->with('success', $successDestroy);
    }

}
