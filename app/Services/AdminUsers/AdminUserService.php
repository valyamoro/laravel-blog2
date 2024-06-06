<?php

namespace App\Services\AdminUsers;

use App\Events\AdminUserChangedPasswordEvent;
use App\Http\Requests\AdminUserRequest;
use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

final class AdminUserService
{
    public function __construct(private readonly AdminUserRepository $adminUserRepository) {}

    public function getAllWithPagination(Request $request, int $perPage): LengthAwarePaginator
    {
        return $this->adminUserRepository->getAllWithPagination($request, $perPage);
    }

    public function create(AdminUserRequest $request): ?AdminUser
    {
        $request->merge(['is_banned' => (bool)$request->input('is_banned')]);

        return $this->adminUserRepository->create($request);
    }

    public function update(Request $request, AdminUser $adminUser): ?AdminUser
    {
        if ($request->filled('password')) {
            event(new AdminUserChangedPasswordEvent($adminUser, $request->input('password')));
        }

        $request->merge(['is_banned' => (bool)$request->input('is_banned')]);
        $request->merge(['password' => $request->filled('password') ? $request->input('password') : $adminUser->password]);

        return $this->adminUserRepository->update($request, $adminUser);
    }

    public function destroy(AdminUser $adminUser): ?bool
    {
        return $this->adminUserRepository->destroy($adminUser);
    }

}
