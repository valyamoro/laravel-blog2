<?php

namespace App\Services\AdminUsers;

use App\Http\Requests\AdminUserRequest;
use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

final class AdminUserService
{
    public function __construct(private readonly AdminUserRepository $adminUserRepository) {}

    public function getAllWithPagination(int $perPage): LengthAwarePaginator
    {
        return $this->adminUserRepository->getAllWithPagination($perPage);
    }

}
