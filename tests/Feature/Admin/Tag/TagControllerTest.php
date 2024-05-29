<?php

namespace Admin\Tag;

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

        $this->tagService = new TagService(new TagRepository());

        $this->actingAs(AdminUser::factory()->create(), 'admin');
    }

    public function testGetViewTagsIndex(): void
    {
        $request = new Request();
        $perPage = 5;
        $title = 'Тэги';

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
        $title = 'Редактировать: Test';
        $tag = Tag::factory()->create([
            'name' => 'Test',
            'is_active' => true,
        ]);

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
        $tag = Tag::factory()->create([
            'name' => 'Test',
            'is_active' => true,
        ]);
        $requestData = [
            'name' => 'Test',
            'is_active' => true,
        ];

        $response = $this->put(route('tags.update', $tag), $requestData);

        $this->assertDatabaseCount(Tag::class, 1);
        $this->assertDatabaseHas(Tag::class, $requestData);
        $response->assertSessionHas('success', 'Успешно сохранено.');
        $response->assertRedirect(route('tags.index'));
    }

    public function testTagDestroy(): void
    {
        $tag = Tag::factory()->create([
            'name' => 'Test',
            'is_active' => true,
        ]);

        $response = $this->delete(route('tags.destroy', $tag));

        $this->assertDatabaseCount(Tag::class, 0);
        $this->assertDatabaseMissing(Tag::class, $tag->toArray());
        $response->assertSessionHas('success', 'Успешно удалено.');
        $response->assertRedirect(route('tags.index'));
    }

}
