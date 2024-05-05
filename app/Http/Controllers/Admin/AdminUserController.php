<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminUserRequest;
use App\Models\AdminUser;
use App\Services\AdminUsers\AdminUserService;
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

    public function create()
    {
        //
    }

    public function store(AdminUserRequest $request)
    {
        //
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
