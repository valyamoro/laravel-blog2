<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AdminUserRequest;
use App\Models\AdminUser;
use App\Services\AdminUsers\AdminUserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminUserController extends BaseController
{
    public function __construct(private readonly AdminUserService $adminUserService) {}

    public function index(): View
    {
        $title = 'Администраторы';

        $perPage = config('pagination.pagination_5');
        $adminUsers = $this->adminUserService->getAllWithPagination($perPage);

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
            $errorSave = config('messages.error.save');
            return back()->withErrors(['error' => $errorSave]);
        }

        $successSave = config('messages.success.save');
        return redirect()->route('admin-users.index')->with('success', $successSave);
    }

    public function show(AdminUser $adminUser)
    {
        //
    }

    public function edit(AdminUser $adminUser)
    {
        //
    }

    public function update(AdminUserRequest $request, AdminUser $adminUser)
    {
        //
    }

    public function destroy(AdminUser $adminUser)
    {
        //
    }

}
