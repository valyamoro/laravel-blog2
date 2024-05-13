<?php

namespace App\Services\Articles;

use App\Models\Article;
use App\Traits\FileUploader;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

final class ArticleService
{
    use FileUploader;

    public function __construct(
        private readonly ArticleRepository $articleRepository,
    )
    {
    }

    public function getAllWithPagination(Request $request, int $perPage): LengthAwarePaginator
    {
        return $this->articleRepository->getAllWithPagination($request, $perPage);
    }

    public function getForSelect(): Collection
    {
        return $this->articleRepository->getForSelect();
    }

    public function create(Request $request): ?Article
    {
        $request->merge(['thumbnail' => $request->hasFile('thumbnail')
            ? $this->uploadImage($request, 'uploads', 'public')
            : null,
        ]);
        $request->merge(['is_active' => (bool)$request->input('is_active')]);
        $request->merge(['user_id' => auth()->guard('admin')->user()->id]);

        return $this->articleRepository->create($request);
    }

    public function update(Request $request, Article $article): ?Article
    {
        if ($request->hasFile('thumbnail') && !empty($article->thumbnail)) {
            $result = $this->deleteImage($article, 'public', 'thumbnail');

            if (!$result) {
                return null;
            }
        }

        $request->merge(['thumbnail' => $request->hasFile('thumbnail')
            ? $this->uploadImage($request, 'uploads', 'public')
            : null,
        ]);
        $request->merge(['is_active' => (bool)$request->input('is_active')]);

        return $this->articleRepository->update($request, $article);
    }

    public function destroy(Article $article): ?bool
    {
        if (!empty($article->thumbnail)) {
            $result = $this->deleteImage($article, 'public', 'thumbnail');
            if (!$result) {
                return null;
            }
        }

        return $this->articleRepository->destroy($article);
    }

}
