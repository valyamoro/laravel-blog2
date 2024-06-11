<?php

namespace App\Services\Categories;

use App\Models\Category;
use App\Services\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class CategoryRepository extends BaseRepository
{
    public function getAllWithPagination(
        Request $request,
        int $perPage,
    ): LengthAwarePaginator
    {
        $currentPage = $request->input('page') ?? 1;
        $builder = Category::query();

        $builderSearch = $this->search($request, Category::query());

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
        return Category::orderBy('id')->get()->pluck('name', 'id');
    }

    public function create(Request $request): ?Category
    {
        $result = Category::create($request->input());

        return $result ? $result : null;
    }

    public function update(
        Request $request,
        Category $category,
    ): ?Category
    {
        $result = $category->update($request->input());

        return $result ? $category : null;
    }

    public function destroy(Category $category): ?bool
    {
        return $category->delete();
    }

}
