<?php

namespace Admin\Tag;

use App\Http\Requests\AdminUserRequestSearch;
use App\Models\AdminUser;
use App\Models\Tag;
use App\Services\Tags\TagRepository;
use App\Services\Tags\TagService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class TagControllerTest extends TestCase
{
    use RefreshDatabase;

    private TagService $tagService;

    public function setUp(): void
    {
        parent::setUp();

        $tagRepository = new TagRepository();
        $this->tagService = new TagService($tagRepository);

        $this->actingAs(AdminUser::factory()->create(), 'admin');
    }

    public function testGetViewTagsIndex(): void
    {
        $perPage = 5;
        $title = 'Тэги';
        $request = new Request();

        $response = $this->get(route('tags.index'));
        $tags = $this->tagService->getAllWithPagination($request, $perPage);

        $response->assertSuccessful();
        $response->assertViewIs('admin.tags.index');
        $response->assertViewHas([
            'title' => $title,
            'paginator' => $tags,
        ]);
    }

    public function testGetViewTagCreate(): void
    {
        $title = 'Добавить';

        $response = $this->get(route('tags.create'));

        $response->assertSuccessful();
        $response->assertViewIs('admin.tags.create');
        $response->assertViewHas([
            'title' => $title,
        ]);
    }

    public function testTagCreate(): void
    {
        $tagData = [
            'name' => 'test',
            'is_active' => true,
        ];
        $requestData = [
            'name' => 'test',
            'is_active' => true,
        ];

        $response = $this->post(route('tags.store'), $requestData);

        $this->assertDatabaseCount(Tag::class, 1);
        $this->assertDatabaseHas(Tag::class, $tagData);
        $response->assertSessionHas('success', 'Успешно сохранено.');
        $response->assertRedirect(route('tags.index'));
    }

    public function testGetViewTagEdit(): void
    {
        $tagData = [
            'name' => 'Test',
            'is_active' => true,
        ];
        $tag = Tag::factory()->create($tagData);
        $title = 'Редактировать: Test';

        $response = $this->get(route('tags.edit', $tag));

        $response->assertSuccessful();
        $response->assertViewIs('admin.tags.edit');
        $response->assertViewHas([
            'title' => $title,
            'item' => $tag,
        ]);
    }

    public function testTagUpdate(): void
    {
        $requestData = [
            'name' => 'Test',
            'is_active' => true,
        ];
        $tagData = [
            'name' => 'Test',
            'is_active' => true,
        ];
        $tag = Tag::factory()->create($tagData);

        $response = $this->put(route('tags.update', $tag), $requestData);

        $this->assertDatabaseCount(Tag::class, 1);
        $this->assertDatabaseHas(Tag::class, $requestData);
        $response->assertSessionHas('success', 'Успешно сохранено.');
        $response->assertRedirect(route('tags.index'));
    }

    public function testTagDestroy(): void
    {
        $tagData = [
            'name' => 'Test',
            'is_active' => true,
        ];
        $tag = Tag::factory()->create($tagData);

        $response = $this->delete(route('tags.destroy', $tag));

        $this->assertDatabaseCount(Tag::class, 0);
        $this->assertDatabaseMissing(Tag::class, $tagData);
        $response->assertSessionHas('success', 'Успешно удалено.');
        $response->assertRedirect(route('tags.index'));
    }

}
