<?php

namespace App\Services\Categories;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

final class CategoryService
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository,
    )
    {
    }

    public function getAllWithPagination(Request $request, int $perPage): LengthAwarePaginator
    {
        return $this->categoryRepository->getAllWithPagination($request, $perPage);
    }

}
