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

        $builderSearch = clone $builder;
        $builderSearch = $this->search($request, $builderSearch);

        if ($builderSearch->count() === 0) {
            $request->merge(['is_exists' => false]);

            return $builder
                ->orderByDesc('id')
                ->paginate($perPage)
                ->withQueryString();
        }

        return $builderSearch
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();
    }

    private function search(Request $request, Builder $builder): Builder
    {
        if ($request->filled('q') && !empty($request->input('q'))) {
            $like = mb_strtolower('%' . $request->input('q') . '%');
            $builder->orWhere(DB::raw('lower(username)'), 'like', $like);
        }

        return $builder;
    }

    public function create(Request $request): ?AdminUser
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
