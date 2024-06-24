<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseRepository
{
    protected function getPaginatorByBuilder(
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

}
