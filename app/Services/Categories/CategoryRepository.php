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
    public function getAllWithPagination(
        Request $request,
        int $perPage,
    ): LengthAwarePaginator
    {
        $order = $request->input('order') ?? 'desc';
        $builder = Category::query();

        $builderSearch = clone $builder;
        $builderSearch = $this->search($request, $builderSearch);

        if ($builderSearch->count() === 0) {
            $request->merge(['is_exists' => false]);

            return $builder
                ->orderBy('id', $order)
                ->paginate($perPage)
                ->withQueryString();
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
