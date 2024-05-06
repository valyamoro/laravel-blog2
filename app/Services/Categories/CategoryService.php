<?php

namespace App\Services\Categories;

use App\Enums\CategoryFile;
use App\Models\Category;
use App\Traits\FileUploader;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

final class CategoryService
{
    use FileUploader;

    public function __construct(
        private readonly CategoryRepository $categoryRepository,
    )
    {
    }

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
        $request = $this->uploadImage($request, CategoryFile::FILE_PATH, CategoryFile::FILE_DISK, CategoryFile::MODEL_IMAGE_NAME);

        $request->merge(['is_active' => (bool)$request->input('is_active')]);

        return $this->categoryRepository->create($request);
    }

    public function update(Request $request, Category $category): ?Category
    {
        $imageObjectFromForm = Arr::first($request->file());

        if ($imageObjectFromForm && !empty($category->{CategoryFile::MODEL_IMAGE_NAME})) {
            $result = $this->deleteImage($category, CategoryFile::FILE_DISK, CategoryFile::MODEL_IMAGE_NAME);

            if (!$result) {
                return null;
            }
        }

        $request = $this->uploadImage($request, CategoryFile::FILE_PATH, CategoryFile::FILE_DISK, CategoryFile::MODEL_IMAGE_NAME);

        $request->merge(['is_active' => (bool)$request->input('is_active')]);

        return $this->categoryRepository->update($request, $category);
    }

    public function destroy(Category $category): ?bool
    {
        return $this->categoryRepository->destroy($category);
    }

}
