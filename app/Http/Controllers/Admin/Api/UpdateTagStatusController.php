<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Requests\IsActiveRequest;
use App\Models\Comment;
use App\Models\Tag;
use App\Services\Comments\CommentService;
use App\Services\Tags\TagService;
use Illuminate\Http\JsonResponse;

class UpdateTagStatusController extends BaseController
{
    public function __construct(private readonly TagService $tagService) {}

    public function __invoke(
        IsActiveRequest $request,
        Tag $tag,
    ): JsonResponse
    {
        $result = $this->tagService->update($request, $tag);

        if (!$result) {
            return response()->json(['error' => 'Ошибка сохранения.'])->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }

        return response()->json(['success' => 'Успешно сохранено!!'])->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

}
