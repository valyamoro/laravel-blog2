<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use App\Services\Articles\ArticleService;
use App\Services\Categories\CategoryService;
use App\Services\Tags\TagService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function __construct(
        private readonly ArticleService $articleService,
        private readonly CategoryService $categoryService,
        private readonly TagService $tagService,
    )
    {
    }

    public function index(Request $request): View
    {
        $title = 'Статьи';

        $perPage = config('pagination.pagination_5');
        $categories = $this->articleService->getAllWithPagination($request, $perPage);

        return view('admin.articles.index', [
            'title' => $title,
            'paginator' => $categories,
        ]);
    }

    public function create(): View
    {
        $title = 'Добавить';

        $categories = $this->categoryService->getForSelect();
        $tags = $this->tagService->getForSelect();

        return view('admin.articles.create', [
            'title' => $title,
            'categories' => $categories,
            'tagList' => $tags,
        ]);
    }

    public function store(ArticleRequest $request): RedirectResponse
    {
        $result = $this->articleService->create($request);

        if (!$result) {
            return back()->withErrors(['error' => trans('messages.error.save')]);
        }

        return redirect()->route('articles.index')->with('success', trans('messages.success.save'));
    }

    public function show(Article $article): View
    {
        $title = 'Статья: ' . $article->title;

        return view('admin.articles.show', [
            'title' => $title,
            'item' => $article,
        ]);
    }

    public function edit(Article $article): View
    {
        $title = 'Редактировать: ' . $article->title;

        $categories = $this->categoryService->getForSelect();
        $selectedTagIds = $this->articleService->getSelectedTagIds($article);
        $tags = $this->tagService->getForSelect();

        return view('admin.articles.edit', [
            'item' => $article,
            'title' => $title,
            'categories' => $categories,
            'selectedTagIds' => $selectedTagIds,
            'tagList' => $tags,
        ]);
    }

    public function update(ArticleRequest $request, Article $article): RedirectResponse
    {
        $result = $this->articleService->update($request, $article);

        if (!$result) {
            return back()->withErrors(['error' => trans('messages.error.save')]);
        }

        return redirect()->route('articles.index')->with('success', trans('messages.success.save'));
    }

    public function destroy(Article $article): RedirectResponse
    {
        $result = $this->articleService->destroy($article);

        if (!$result) {
            return back()->withErrors(['error' => trans('messages.error.destroy')]);
        }

        return redirect()->route('articles.index')->with('success', trans('messages.success.destroy'));
    }

}
