<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Requests\IsActiveRequest;
use App\Models\Comment;
use App\Services\Comments\CommentService;
use Illuminate\Http\JsonResponse;

class UpdateCommentStatusController extends BaseController
{
    public function __construct(private readonly CommentService $commentService) {}

    public function __invoke(
        IsActiveRequest $request,
        Comment $comment,
    ): JsonResponse
    {
        $result = $this->commentService->update($request, $comment);

        if (!$result) {
            return response()->json(['error' => 'Ошибка сохранения.'])->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }

        return response()->json(['success' => 'Успешно сохранено!!'])->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

}
