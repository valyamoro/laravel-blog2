<?php

namespace App\Services\Articles;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class ArticleRepository
{
    public function getAllWithPagination(Request $request, int $perPage): LengthAwarePaginator
    {
        $builder = Article::query();

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
            $builder->orWhere(DB::raw('lower(title)'), 'like', $like);
        }

        return $builder;
    }

    public function create(Request $request): ?Article
    {
        $result = Article::create($request->input());

        return $result ? $result : null;
    }

    public function update(Request $request, Article $article): ?Article
    {
        $result = $article->update($request->input());

        return $result ? $article : null;
    }

    public function destroy(Article $article): ?bool
    {
        return $article->delete();
    }

}
