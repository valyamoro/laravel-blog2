<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Services\Categories\CategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends BaseController
{
    public function __construct(private readonly CategoryService $categoryService) {}

    public function index(Request $request): View
    {
        $title = 'Категории';

        $paginationValues = config('pagination');
        $categories = $this->categoryService->getAllWithPagination($request, $paginationValues[$request->input('pagination') ?? $this->defaultPerPage]);

        return view('admin.categories.index', [
            'title' => $title,
            'paginator' => $categories,
            'paginationValues' => $paginationValues,
        ]);
    }

    public function create(): View
    {
        $title = 'Добавить';

        $categories = $this->categoryService->getForSelect();

        return view('admin.categories.create', [
            'title' => $title,
            'categories' => $categories,
        ]);
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        $result = $this->categoryService->create($request);

        if (!$result) {
            return back()->withErrors(['error' => trans('messages.error.save')]);
        }

        return redirect()->route('categories.index')->with('success', trans('messages.success.save'));
    }

    public function show(Category $category): View
    {
        $title = 'Категория: '  . $category->name;

        return view('admin.categories.show', [
            'title' => $title,
            'item' => $category,
        ]);
    }

    public function edit(Category $category): View
    {
        $title = 'Редактировать: ' . $category->name;

        $categories = $this->categoryService->getForSelect();

        return view('admin.categories.edit', [
            'title' => $title,
            'item' => $category,
            'categories' => $categories,
        ]);
    }

    public function update(
        CategoryRequest $request,
        Category $category,
    ): RedirectResponse
    {
        $result = $this->categoryService->update($request, $category);

        if (!$result) {
            return back()->withErrors(['error' => trans('messages.error.save')]);
        }

        return redirect()->route('categories.index')->with('success', trans('messages.success.save'));
    }

    public function destroy(Category $category): RedirectResponse
    {
        $result = $this->categoryService->destroy($category);

        if (!$result) {
            return back()->withErrors(['error' => trans('messages.error.destroy')]);
        }

        return redirect()->route('categories.index')->with('success', trans('messages.success.destroy'));
    }

}
