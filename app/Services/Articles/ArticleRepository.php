<?php

namespace App\Services\Articles;

use App\Models\Article;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

final class ArticleRepository
{
    public function getAllWithPagination(
        Request $request,
        int     $perPage
    ): LengthAwarePaginator
    {
        $order = $request->input('order') ?? 'desc';
        $currentPage = $request->input('page') ?? 1;
        $builder = Article::query();

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
            $builder->orWhere(DB::raw('lower(title)'), 'like', $like);
        }

        return $builder;
    }

    public function create(Request $request): ?Article
    {
        $result = Article::create($request->input());
        $result ? $this->createTags($request, $result) : null;

        return $result ? $result : null;
    }

    public function update(
        Request $request,
        Article $article,
    ): ?Article
    {
        $result = $article->update($request->input());
        $result ? $this->createTags($request, $article) : null;

        return $result ? $article : null;
    }

    private function createTags(
        Request $request,
        Article $article,
    ): void
    {
        $article->tags()->sync($request->input('tags'));
    }

    public function destroy(Article $article): ?bool
    {
        return $article->delete();
    }

}
