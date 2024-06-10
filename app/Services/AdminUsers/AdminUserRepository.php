<?php

namespace App\Services\AdminUsers;

use App\Models\AdminUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

final class AdminUserRepository
{
    public function getAllWithPagination(
        Request $request,
        int $perPage
    ): LengthAwarePaginator
    {
        $order = $request->input('order') ?? 'desc';
        $currentPage = $request->input('page') ?? 1;
        $builder = AdminUser::query();

        $paginator = $builder->orderBy('id', $order)->paginate($perPage);

        if ($paginator->count() === 0 && $currentPage > 1) {
            return $builder->orderBy('id', $order)
                ->paginate($perPage, ['*'], 'page', 1)
                ->withQueryString();
        }

        $builderSearch = clone $builder;
        $builderSearch = $this->search($request, $builderSearch);

        if ($request->has('q')) {
            if ($builderSearch->count() === 0) {
                $request->merge(['is_exists' => false]);

                return $builder
                    ->orderBy('id', $order)
                    ->paginate($perPage)
                    ->withQueryString();
            }
        }

        return $builderSearch
            ->orderBy('id', $order)
            ->paginate($perPage)
            ->withQueryString();
    }

    private function search(
        Request $request,
        Builder $builder,
    ): Builder
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

    public function update(
        Request $request,
        AdminUser $adminUser,
    ): ?AdminUser
    {
        $result = $adminUser->update($request->only($adminUser->getFillable()));

        return $result ? $adminUser : null;
    }

    public function destroy(AdminUser $adminUser): ?bool
    {
        return $adminUser->delete();
    }

}
