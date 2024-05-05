<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminUserRequestSearch;
use App\Http\Requests\TagRequest;
use App\Models\Tag;
use App\Services\Tags\TagService;
use Illuminate\View\View;

class TagController extends Controller
{
    public function __construct(private readonly TagService $tagService) {}

    public function index(AdminUserRequestSearch $request): View
    {
        $title = 'Тэги';

        $perPage = config('pagination.pagination_5');
        $tags = $this->tagService->getAllWithPagination($request, $perPage);

        return view('admin.tags.index', [
            'title' => $title,
            'paginator' => $tags,
        ]);
    }

    public function create()
    {
        //
    }

    public function store(TagRequest $request)
    {
        //
    }

    public function show(Tag $tag)
    {
        //
    }

    public function edit(Tag $tag)
    {
        //
    }

    public function update(TagRequest $request, Tag $tag)
    {
        //
    }

    public function destroy(Tag $tag)
    {
        //
    }

}
