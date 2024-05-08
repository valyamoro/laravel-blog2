<?php

namespace App\Services\Tags;

use App\Http\Requests\TagRequest;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

final class TagService
{
    public function __construct(private readonly TagRepository $tagRepository) {}

    public function getAllWithPagination(Request $request, int $perPage): LengthAwarePaginator
    {
        return $this->tagRepository->getAllWithPagination($request, $perPage);
    }

    public function create(TagRequest $request): ?Tag
    {
        $request->merge(['is_active' => (bool)$request->is_active]);

        return $this->tagRepository->create($request);
    }

    public function update(TagRequest $request, Tag $tag): ?Tag
    {
        $request->merge(['is_active' => (bool)$request->is_active]);

        return $this->tagRepository->update($request, $tag);
    }

    public function destroy(Tag $tag): ?bool
    {
        return $this->tagRepository->destroy($tag);
    }

}
