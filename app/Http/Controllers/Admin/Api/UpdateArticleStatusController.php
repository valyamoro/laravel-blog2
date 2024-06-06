<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Requests\IsActiveRequest;
use App\Models\Article;
use App\Services\Articles\ArticleService;
use Illuminate\Http\JsonResponse;

class UpdateArticleStatusController extends BaseController
{
    public function __construct(private readonly ArticleService $articleService) {}

    public function __invoke(
        IsActiveRequest $request,
        Article $article,
    ): JsonResponse
    {
        $result = $this->articleService->update($request, $article);

        if (!$result) {
            return response()->json(['error' => 'Ошибка сохранения.'])->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }

        return response()->json(['success' => 'Успешно сохранено!!'])->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

}
