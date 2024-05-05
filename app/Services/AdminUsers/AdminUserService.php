<?php

namespace App\Services\AdminUsers;

use App\Http\Requests\AdminUserRequest;
use App\Models\AdminUser;
use Illuminate\Pagination\LengthAwarePaginator;

final class AdminUserService
{
    public function __construct(private readonly AdminUserRepository $adminUserRepository) {}

    public function getAllWithPagination(int $perPage): LengthAwarePaginator
    {
        return $this->adminUserRepository->getAllWithPagination($perPage);
    }

    public function create(AdminUserRequest $request): ?AdminUser
    {
        $request->merge(['is_banned' => (bool)$request->input('is_banned')]);

        return $this->adminUserRepository->create($request);
    }

    public function update(AdminUserRequest $request, AdminUser $adminUser): ?AdminUser
    {
        $request->merge(['is_banned' => (bool)$request->input('is_banned')]);
        $request->merge(['password' => $request->filled('password') ? $request->input('password') : $adminUser->password]);

        return $this->adminUserRepository->update($request, $adminUser);
    }

}
