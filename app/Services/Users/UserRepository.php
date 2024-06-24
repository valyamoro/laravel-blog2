<?php

namespace App\Services\Users;

use App\Models\User;
use App\Services\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

final class UserRepository extends BaseRepository
{
    public function getAllWithPagination(
        Request $request,
        int $perPage,
    ): LengthAwarePaginator
    {
        $currentPage = $request->input('page') ?? 1;
        $builder = User::query();

        $builderSearch = $this->search($request, User::query());

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

    public function create(Request $request): ?User
    {
        $result = User::create($request->only((new User())->getFillable()));

        return $result ?? null;
    }

    public function update(
        Request $request,
        User $user,
    ): ?User
    {
        $result = $user->update($request->only($user->getFillable()));

        return $result ? $user : null;
    }

    public function destroy(User $user): ?bool
    {
        return $user->delete();
    }

}
