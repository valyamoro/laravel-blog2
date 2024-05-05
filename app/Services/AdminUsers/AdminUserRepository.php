<?php

namespace App\Services\AdminUsers;

use App\Models\AdminUser;
use Illuminate\Pagination\LengthAwarePaginator;

final class AdminUserRepository
{
    public function getAllWithPagination(int $perPage): LengthAwarePaginator
    {
        $builder = AdminUser::query();

        return $builder
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();
    }

}
