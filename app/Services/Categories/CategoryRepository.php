<?php

namespace App\Services\Categories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class CategoryRepository
{
    public function getAllWithPagination(Request $request, int $perPage): LengthAwarePaginator
    {
        $builder = Category::query();

        $paginator = $builder
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        $paginatorWithSearch = $this->search($request, $builder);

        if ($paginatorWithSearch->count() === 0 || ($request->has('q') && empty($request->input('q')))) {
            $paginator->isEmptyItems = true;

            return $paginator;
        }

        return $paginatorWithSearch
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();
    }

    private function search(Request $request, Builder $builder): Builder
    {
        if ($request->filled('q')) {
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

    public function update(Request $request, Category $category): ?Category
    {
        $result = $category->update($request->input());

        return $result ? $category : null;
    }

    public function destroy(Category $category): ?bool
    {
        return $category->delete();
    }

}
