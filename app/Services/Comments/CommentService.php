<?php

namespace App\Services\Comments;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Services\AdminUsers\AdminUserRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

final class CommentService
{
    public function __construct(
        private readonly CommentRepository $commentRepository,
        private readonly AdminUserRepository $adminUserRepository,
    ) {}

    public function getAllWithPagination(Request $request, int $perPage): LengthAwarePaginator
    {
        return $this->commentRepository->getAllWithPagination($request, $perPage);
    }

    public function create(CommentRequest $request): ?Comment
    {
        if ($request->filled('username') && $request->filled('email')) {
            $result = $this->adminUserRepository->create($request);

            if (!$result) {
                return null;
            }

            $request->merge(['admin_user_id' => $result->id]);
        } else {
            $request->merge(['admin_user_id' => auth()->guard('admin')->user()->id]);
        }
        $request->merge(['is_active' => 0]);

        return $this->commentRepository->create($request);
    }

    public function update(CommentRequest $request, Comment $tag): ?Comment
    {
        $request->merge(['is_active' => (bool)$request->input('is_active')]);

        return $this->commentRepository->update($request, $tag);
    }

    public function destroy(Comment $tag): ?bool
    {
        return $this->commentRepository->destroy($tag);
    }

}
