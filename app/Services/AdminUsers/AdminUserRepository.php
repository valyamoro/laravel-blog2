<?php

namespace App\Services\AdminUsers;

use App\Http\Requests\AdminUserRequest;
use App\Models\AdminUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

final class AdminUserRepository
{
    public function getAllWithPagination(Request $request, int $perPage): LengthAwarePaginator
    {
        $builder = AdminUser::query();

        $builder = $this->search($request, $builder);
        return $builder
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();
    }

    private function search(Request $request, Builder $builder): Builder
    {
        if ($request->filled('q')) {
            $like = mb_strtolower("%{$request->input('q')}%");
            $builder->orWhere(DB::raw('lower(username)'), 'like', $like);
            $builder->orWhere(DB::raw('lower(email)'), 'like', $like);
        }

        return $builder;
    }

    public function create(AdminUserRequest $request): ?AdminUser
    {
        $result = AdminUser::create($request->only((new AdminUser())->getFillable()));

        return $result ?? null;
    }

    public function update(AdminUserRequest $request, AdminUser $adminUser): ?AdminUser
    {
        $result = $adminUser->update($request->only($adminUser->getFillable()));

        return $result ? $adminUser : null;
    }

    public function destroy(AdminUser $adminUser): ?bool
    {
        return $adminUser->delete();
    }

}
