<?php

namespace App\Services\Tags;

use App\Http\Requests\AdminUserRequestSearch;
use App\Http\Requests\TagRequest;
use App\Models\Tag;
use Illuminate\Pagination\LengthAwarePaginator;

final class TagService
{
    public function __construct(private readonly TagRepository $tagRepository) {}

    public function getAllWithPagination(AdminUserRequestSearch $request, int $perPage): LengthAwarePaginator
    {
        return $this->tagRepository->getAllWithPagination($request, $perPage);
    }

    public function create(TagRequest $request): ?Tag
    {
        $request->merge(['is_active' => (bool)$request->is_active]);

        return $this->tagRepository->create($request);
    }

}
