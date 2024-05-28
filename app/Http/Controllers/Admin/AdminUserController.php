<?php

namespace App\Http\Controllers\Admin;

use App\Events\UserRegisteredEvent;
use App\Http\Requests\AdminUserRequest;
use App\Models\AdminUser;
use App\Services\AdminUsers\AdminUserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminUserController extends BaseController
{
    public function __construct(private readonly AdminUserService $adminUserService) {}

    public function index(Request $request): View
    {
        $title = 'Администраторы';

        $perPage = config('pagination.pagination_5');
        $adminUsers = $this->adminUserService->getAllWithPagination($request, $perPage);

        return view('admin.admin_users.index', [
            'title' => $title,
            'paginator' => $adminUsers,
        ]);
    }

    public function create(): View
    {
        $title = 'Добавить';

        return view('admin.admin_users.create', [
            'title' => $title,
        ]);
    }

    public function store(AdminUserRequest $request): RedirectResponse
    {
        $result = $this->adminUserService->create($request);

        if (!$result) {
            return back()->withErrors(['error' => trans('messages.error.save')]);
        }

        event(new UserRegisteredEvent(new AdminUser($request->input())));

        return redirect()->route('admin-users.index')->with('success',trans('messages.success.save'));
    }

    public function show(AdminUser $adminUser): View
    {
        $title = 'Профиль администратора: ' . $adminUser->username;

        return view('admin.admin_users.show', [
            'title' => $title,
            'item' => $adminUser,
        ]);
    }

    public function edit(AdminUser $adminUser): View
    {
        $title = 'Редактировать: ' . $adminUser->username;

        return view('admin.admin_users.edit', [
            'title' => $title,
            'item' => $adminUser,
        ]);
    }

    public function update(AdminUserRequest $request, AdminUser $adminUser): RedirectResponse
    {
        $result = $this->adminUserService->update($request, $adminUser);

        if (!$result) {
            return back()->withErrors(['error' => trans('messages.error.save')]);
        }

        return redirect()->route('admin-users.index')->with('success', trans('messages.success.save'));
    }

    public function destroy(AdminUser $adminUser): RedirectResponse
    {
        $result = $this->adminUserService->destroy($adminUser);

        if (!$result) {
            return back()->withErrors(['error' => trans('messages.error.destroy')]);
        }

        return redirect()->route('admin-users.index')->with('success', trans('messages.success.destroy'));
    }

}
