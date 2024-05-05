<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminUserRequestSearch;
use App\Http\Requests\TagRequest;
use App\Models\Tag;
use App\Services\Tags\TagService;
use Illuminate\Http\RedirectResponse;
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

    public function create(): View
    {
        $title = 'Добавить';

        return view('admin.tags.create', [
            'title' => $title,
        ]);
    }

    public function store(TagRequest $request): RedirectResponse
    {
        $result = $this->tagService->create($request);

        if (!$result) {
            return back()->withErrors(['error' => 'Ошибка сохранения.']);
        }

        return redirect()->route('tags.index')->with('success', 'Успешно сохранено.');
    }

    public function show(Tag $tag)
    {
        abort(404);
    }

    public function edit(Tag $tag): View
    {
        $title = 'Редактировать: ' . $tag->name;

        return view('admin.tags.edit', [
            'title' => $title,
            'item' => $tag,
        ]);
    }

    public function update(TagRequest $request, Tag $tag): RedirectResponse
    {
        $result = $this->tagService->update($request, $tag);

        if (!$result) {
            return back()->withErrors(['error' => 'Ошибка сохранения']);
        }

        return redirect()->route('tags.index')->with('success', 'Успешно сохранено.');
    }

    public function destroy(Tag $tag): RedirectResponse
    {
        $result = $this->tagService->destroy($tag);

        if (!$result) {
            return back()->withErrors(['error' => 'Ошибка удаления']);
        }

        return redirect()->route('tags.index')->with('success', 'Успешно удалено.');
    }

}
