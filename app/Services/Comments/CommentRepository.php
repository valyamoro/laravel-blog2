<?php

namespace App\Services\Comments;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Services\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

final class CommentRepository extends BaseRepository
{
    public function getAllWithPagination(
        Request $request,
        int $perPage,
    ): LengthAwarePaginator
    {
        $currentPage = $request->input('page') ?? 1;
        $builder = Comment::query();

        $builderSearch = $this->search($request, Comment::query());

        if ($builderSearch->count() === 0) {
            $request->merge(['is_exists' => false]);

            $paginator = $this->getPaginatorByBuilder($request, $builder, [$perPage]);
        } else {
            $paginator = $this->getPaginatorByBuilder($request, $builderSearch, [$perPage]);
        }

        if ($paginator->count() === 0 && $currentPage > 1) {
            $paginator = $this->getPaginatorByBuilder($request, $builderSearch, [$perPage, ['*'], 'page', 1]);
        }

        return $paginator;
    }

    private function search(
        Request $request,
        Builder $builder,
    ): Builder
    {
        if ($request->filled('q') && !empty($request->input('q'))) {
            $like = mb_strtolower('%' . $request->input('q') . '%');
            $builder->orWhere(DB::raw('lower(content)'), 'like', $like);
        }

        return $builder;
    }

    public function create(CommentRequest $request): ?Comment
    {
        $result = Comment::create($request->only((new Comment())->getFillable()));

        return $result ?? null;
    }

    public function update(
        Request $request,
        Comment $comment,
    ): ?Comment
    {
        $result = $comment->update($request->only($comment->getFillable()));

        return $result ? $comment : null;
    }

    public function destroy(Comment $comment): ?bool
    {
        return $comment->delete();
    }

}
