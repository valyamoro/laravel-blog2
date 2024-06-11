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
        int $perPage,
    ): LengthAwarePaginator
    {
        $currentPage = $request->input('page') ?? 1;
        $builder = AdminUser::query();

        $builderSearch = $this->search($request, AdminUser::query());

        if ($builderSearch->count() === 0) {
            $request->merge(['is_exists' => false]);

            $paginator = $this->getPaginatorByBuilder($request, $builder, [$perPage]);
        } else {
            $paginator = $this->getPaginatorByBuilder($request, $builderSearch, [$perPage]);
        }

        if ($paginator->count() === 0 && $currentPage > 1) {
            $paginator = $this->getPaginatorByBuilder($request, $builderSearch, [$perPage, ['*'], 'page', 1]);
        }

        return $paginator;
    }

    private function getPaginatorByBuilder(
        Request $request,
        Builder $builder,
        array $paginateOptions,
    ): LengthAwarePaginator
    {
        return $builder
            ->orderBy('id', ($request->input('order') ?? 'desc'))
            ->paginate(...$paginateOptions)
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
