<?php

namespace App\Services\Tags;

use App\Http\Requests\TagRequest;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

final class TagService
{
    public function __construct(private readonly TagRepository $tagRepository) {}

    public function getAllWithPagination(
        Request $request,
        int $perPage,
    ): LengthAwarePaginator
    {
        return $this->tagRepository->getAllWithPagination($request, $perPage);
    }

    public function getForSelect(): Collection
    {
        return $this->tagRepository->getForSelect();
    }

    public function create(TagRequest $request): ?Tag
    {
        $request->merge(['is_active' => (bool)$request->input('is_active')]);

        return $this->tagRepository->create($request);
    }

    public function update(
        Request $request,
        Tag $tag,
    ): ?Tag
    {
        $request->merge(['is_active' => (bool)$request->input('is_active')]);

        return $this->tagRepository->update($request, $tag);
    }

    public function destroy(Tag $tag): ?bool
    {
        return $this->tagRepository->destroy($tag);
    }

}
