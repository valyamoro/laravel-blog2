<?php

namespace App\Services\Tags;

use App\Http\Requests\AdminUserRequestSearch;
use Illuminate\Pagination\LengthAwarePaginator;

final class TagService
{
    public function __construct(private readonly TagRepository $tagRepository) {}

    public function getAllWithPagination(AdminUserRequestSearch $request, int $perPage): LengthAwarePaginator
    {
        return $this->tagRepository->getAllWithPagination($request, $perPage);
    }

}
