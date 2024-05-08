<?php

namespace App\Services\AdminUsers;

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
        return $this->adminUserRepository->create($request);
    }

    public function update(AdminUserRequest $request, AdminUser $adminUser): ?AdminUser
    {
        $request->merge(['password' => $request->filled('password') ? $request->input('password') : $adminUser->password]);

        return $this->adminUserRepository->update($request, $adminUser);
    }

    public function destroy(AdminUser $adminUser): ?bool
    {
        return $this->adminUserRepository->destroy($adminUser);
    }

}
