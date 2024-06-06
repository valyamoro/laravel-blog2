<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Requests\IsActiveRequest;
use App\Models\Category;
use App\Models\Comment;
use App\Services\Categories\CategoryService;
use App\Services\Comments\CommentService;
use Illuminate\Http\JsonResponse;

class UpdateCategoryStatusController extends BaseController
{
    public function __construct(private readonly CategoryService $categoryService) {}

    public function __invoke(
        IsActiveRequest $request,
        Category $category,
    ): JsonResponse
    {
        $result = $this->categoryService->update($request, $category);

        if (!$result) {
            return response()->json(['error' => 'Ошибка сохранения.'])->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }

        return response()->json(['success' => 'Успешно сохранено!!'])->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

}
