<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Requests\CommentStatusRequest;
use App\Models\Comment;
use App\Services\Comments\CommentService;
use Illuminate\Http\RedirectResponse;

class UpdateCommentStatusController extends BaseController
{
    public function __construct(private readonly CommentService $commentService) {}

    public function __invoke(CommentStatusRequest $request, Comment $comment): RedirectResponse
    {
        $result = $this->commentService->update($request, $comment);

        if (!$result) {
            return back()->withErrors(['error' => 'Ошибка сохранения!']);
        }

        return redirect()->route('comments.index');
    }

}
