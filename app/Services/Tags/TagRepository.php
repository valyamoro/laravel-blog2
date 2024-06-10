<?php

namespace App\Services\Tags;

use App\Http\Requests\TagRequest;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class TagRepository
{
    public function getAllWithPagination(
        Request $request,
        int     $perPage
    ): LengthAwarePaginator
    {
        $order = $request->input('order') ?? 'desc';
        $currentPage = $request->input('page') ?? 1;
        $builder = Tag::query();

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
            $builder->orWhere(DB::raw('lower(name)'), 'like', $like);
        }

        return $builder;
    }

    public function getForSelect(): Collection
    {
        return Tag::orderBy('id')->get()->pluck('name', 'id');
    }

    public function create(TagRequest $request): ?Tag
    {
        $result = Tag::create($request->only((new Tag())->getFillable()));

        return $result ?? null;
    }

    public function update(
        Request $request,
        Tag $tag,
    ): ?Tag
    {
        $result = $tag->update($request->only($tag->getFillable()));

        return $result ? $tag : null;
    }

    public function destroy(Tag $tag): ?bool
    {
        return $tag->delete();
    }

}
