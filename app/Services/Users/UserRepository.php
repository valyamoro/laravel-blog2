<?php

namespace App\Services\Users;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

final class UserRepository
{
    public function getAllWithPagination(Request $request, int $perPage): LengthAwarePaginator
    {
        $builder = User::query();

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

    public function create(Request $request): ?User
    {
        $result = User::create($request->only((new User())->getFillable()));

        return $result ?? null;
    }

    public function update(UserRequest $request, User $user): ?User
    {
        $result = $user->update($request->only($user->getFillable()));

        return $result ? $user : null;
    }

    public function destroy(User $user): ?bool
    {
        return $user->delete();
    }

}
