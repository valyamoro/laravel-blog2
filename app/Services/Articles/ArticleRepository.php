<?php

namespace App\Services\Articles;

use App\Models\Article;
use App\Services\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

final class ArticleRepository extends BaseRepository
{
    public function getAllWithPagination(
        Request $request,
        int $perPage,
    ): LengthAwarePaginator
    {
        $currentPage = $request->input('page') ?? 1;
        $builder = Article::query();

        $builderSearch = $this->search($request, Article::query());

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
