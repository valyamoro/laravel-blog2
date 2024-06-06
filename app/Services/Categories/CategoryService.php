<?php

namespace App\Services\Categories;

use App\Models\Category;
use App\Traits\FileUploader;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

final class CategoryService
{
    use FileUploader;

    public function __construct(private readonly CategoryRepository $categoryRepository) {}

    public function getAllWithPagination(Request $request, int $perPage): LengthAwarePaginator
    {
        return $this->categoryRepository->getAllWithPagination($request, $perPage);
    }

    public function getForSelect(): Collection
    {
        return $this->categoryRepository->getForSelect();
    }

    public function create(Request $request): ?Category
    {
        $request->merge(['thumbnail' => $request->hasFile('thumbnail')
            ? $this->uploadImage($request, 'uploads', 'public')
            : '',
        ]);
        $request->merge(['is_active' => (bool)$request->input('is_active')]);

        return $this->categoryRepository->create($request);
    }

    public function update(
        Request $request,
        Category $category,
    ): ?Category
    {
        if ($request->hasFile('thumbnail') && !empty($category->thumbnail)) {
            $result = $this->deleteImage($category, 'public', 'thumbnail');

            if (!$result) {
                return null;
            }
        }

        if ($request->hasFile('thumbnail')) {
            $request->merge(['thumbnail' => $this->uploadImage($request, 'uploads', 'public')]);
        }
        $request->merge(['is_active' => (bool)$request->input('is_active')]);

        return $this->categoryRepository->update($request, $category);
    }

    public function destroy(Category $category): ?bool
    {
        if (!empty($category->thumbnail)) {
            $result = $this->deleteImage($category, 'public', 'thumbnail');
            if (!$result) {
                return null;
            }
        }

        return $this->categoryRepository->destroy($category);
    }

}
