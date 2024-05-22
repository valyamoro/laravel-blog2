<?php

namespace App\Services\Comments;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

final class CommentRepository
{
    public function getAllWithPagination(Request $request, int $perPage): LengthAwarePaginator
    {
        $builder = Comment::query();

        $builderSearch = clone $builder;
        $builderSearch = $this->search($request, $builderSearch);

        if ($builderSearch->count() === 0) {
            $request->merge(['is_exists' => false]);

            return $builder
                ->orderByDesc('id')
                ->paginate($perPage)
                ->withQueryString();
        }

        return $builderSearch
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();
    }

    private function search(Request $request, Builder $builder): Builder
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

    public function update(CommentRequest $request, Comment $comment): ?Comment
    {
        $result = $comment->update($request->only($comment->getFillable()));

        return $result ? $comment : null;
    }

    public function destroy(Comment $comment): ?bool
    {
        return $comment->delete();
    }

}
