<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Services\Comments\CommentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommentController extends BaseController
{
    public function __construct(private readonly CommentService $commentService) {}

    public function index(Request $request): View
    {
        $title = 'Комментарии';

        $paginationValues = config('pagination');
        $comments = $this->commentService->getAllWithPagination($request, $paginationValues[$request->input('pagination') ?? 'pagination_20']);

        return view('admin.comments.index', [
            'title' => $title,
            'paginator' => $comments,
            'paginationValues' => $paginationValues,
        ]);
    }

    public function create(): void
    {
        abort(404);
    }

    public function store(CommentRequest $request): RedirectResponse
    {
        $result = $this->commentService->create($request);

        if (!$result) {
            return back()->withErrors(['error' => trans('messages.error.save')]);
        }

        return redirect()->back()->with('success', 'Ваш комментарий был успешно принят, но будет опубликован после проверки модератором.');
    }

    public function show(Comment $comment): View
    {
        $title = 'Комментарий: #' . $comment->id;

        return view('admin.comments.show', [
            'title' => $title,
            'item' => $comment,
        ]);
    }

    public function edit(Comment $comment): View
    {
        abort(404);
    }

    public function update(CommentRequest $request, Comment $comment): RedirectResponse
    {
        $result = $this->commentService->update($request, $comment);

        if (!$result) {
            return back()->withErrors(['error' => trans('messages.error.save')]);
        }

        return redirect()->route('comments.index')->with('success', trans('messages.success.save'));
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        $result = $this->commentService->destroy($comment);

        if (!$result) {
            return back()->withErrors(['error' => trans('messages.error.destroy')]);
        }

        return redirect()->route('comments.index')->with('success', trans('messages.success.destroy'));
    }

}
