<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AdminUserRequestSearch;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Services\Categories\CategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends BaseController
{
    public function __construct(private readonly CategoryService $categoryService) {}

    public function index(AdminUserRequestSearch $request): View
    {
        $title = 'Категории';

        $perPage = config('pagination.pagination_5');
        $categories = $this->categoryService->getAllWithPagination($request, $perPage);

        return view('admin.categories.index', [
            'title' => $title,
            'paginator' => $categories,
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
            return back()->withErrors(['error' => 'Ошибка сохранения!']);
        }

        return redirect()->route('categories.index')->with('success', 'Успешно сохранено.');
    }

    public function show(Category $category)
    {
        //
    }

    public function edit(Category $category)
    {
        //
    }

    public function update(CategoryRequest $request, Category $category)
    {
        //
    }

    public function destroy(Category $category)
    {
        //
    }

}
