<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TagRequest;
use App\Models\Tag;
use App\Services\Tags\TagService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TagController extends Controller
{
    public function __construct(private readonly TagService $tagService) {}

    public function index(Request $request): View
    {
        $title = 'Тэги';

        $perPages = config('pagination');
        $tags = $this->tagService->getAllWithPagination($request, $perPages[$request->input('pagination') ?? 'pagination_20']);

        return view('admin.tags.index', [
            'title' => $title,
            'paginator' => $tags,
            'perPages' => $perPages,
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
            return back()->withErrors(['error' => trans('messages.error.save')]);
        }

        return redirect()->route('tags.index')->with('success', trans('messages.success.save'));
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

    public function update(
        TagRequest $request,
        Tag $tag,
    ): RedirectResponse
    {
        $result = $this->tagService->update($request, $tag);

        if (!$result) {
            return back()->withErrors(['error' => trans('messages.error.save')]);
        }

        return redirect()->route('tags.index')->with('success', trans('messages.success.save'));
    }

    public function destroy(Tag $tag): RedirectResponse
    {
        $result = $this->tagService->destroy($tag);

        if (!$result) {
            return back()->withErrors(['error' => trans('messages.error.destroy')]);
        }

        return redirect()->route('tags.index')->with('success', trans('messages.success.destroy'));
    }

}
