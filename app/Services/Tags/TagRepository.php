<?php

namespace App\Services\Tags;

use App\Http\Requests\TagRequest;
use App\Models\Tag;
use App\Services\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class TagRepository extends BaseRepository
{
    public function getAllWithPagination(
        Request $request,
        int $perPage,
    ): LengthAwarePaginator
    {
        $currentPage = $request->input('page') ?? 1;
        $builder = Tag::query();

        $builderSearch = $this->search($request, Tag::query());

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
