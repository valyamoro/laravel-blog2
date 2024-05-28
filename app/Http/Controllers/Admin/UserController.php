<?php

namespace App\Http\Controllers\Admin;

use App\Events\UserRegisteredEvent;
use App\Http\Requests\UserRequest;
use App\Models\AdminUser;
use App\Models\User;
use App\Services\Comments\CommentService;
use App\Services\Users\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends BaseController
{
    public function __construct(
        private readonly UserService $userService,
        private readonly CommentService $commentService,
    ) {}

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
            return back()->withErrors(['error' => trans('messages.error.save')]);
        }

        return redirect()->route('users.index')->with('success', trans('messages.success.save'));
    }

    public function show(Request $request, User $user): View
    {
        $title = 'Профиль пользователя: ' . $user->username;

        $comments = $this->commentService->getAllWithPagination($request, config('pagination.pagination_5'));

        return view('admin.users.show', [
            'title' => $title,
            'item' => $user,
            'paginator' => $comments,
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
            return back()->withErrors(['error' => trans('messages.error.save')]);
        }

        return redirect()->route('users.index')->with('success', trans('messages.success.save'));
    }

    public function destroy(User $user): RedirectResponse
    {
        $result = $this->userService->destroy($user);

        if (!$result) {
            return back()->withErrors(['error' => trans('messages.error.destroy')]);
        }

        return redirect()->route('users.index')->with('success', trans('messages.success.destroy'));
    }

}
