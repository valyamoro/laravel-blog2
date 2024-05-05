<?php

namespace App\Services\Tags;

use App\Http\Requests\AdminUserRequestSearch;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

final class TagRepository
{
    public function getAllWithPagination(AdminUserRequestSearch $request, int $perPage): LengthAwarePaginator
    {
        $builder = Tag::query();

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
            $builder->orWhere(DB::raw('lower(name)'), 'like', $like);
        }

        return $builder;
    }

}
