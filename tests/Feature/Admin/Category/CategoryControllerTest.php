<?php

namespace Admin\Category;

use App\Models\AdminUser;
use App\Models\Category;
use App\Services\Categories\CategoryRepository;
use App\Services\Categories\CategoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    private CategoryService $categoryService;

    public function setUp(): void
    {
        parent::setUp();

        $this->categoryService = new CategoryService(new CategoryRepository());

        $this->actingAs(AdminUser::factory()->create(), 'admin');
    }

    public function testGetViewCategoryIndex(): void
    {
        $request = new Request();
        $perPage = 5;
        $title = 'Категории';

        $response = $this->get(route('categories.index'));
        $categories = $this->categoryService->getAllWithPagination($request, $perPage);

        $response->assertSuccessful();
        $response->assertViewIs('admin.categories.index');
        $response->assertViewHas([
            'title' => $title,
            'paginator' => $categories,
        ]);
    }

    public function testGetViewCategoryCreate(): void
    {
        $title = 'Добавить';

        $response = $this->get(route('categories.create'));

        $response->assertSuccessful();
        $response->assertViewIs('admin.categories.create');
        $response->assertViewHas([
            'title' => $title,
        ]);
    }

    public function testCategoryCreate(): void
    {
        $category = Category::factory()->create(['id' => 1]);
        $requestData = [
            'parent_id' => $category->id,
            'name' => 'test',
            'is_active' => true,
        ];
        $categoryData = [
            'parent_id' => $category->id,
            'name' => 'test',
            'is_active' => true,
        ];

        $response = $this->post(route('categories.store'), $requestData);

        $this->assertDatabaseCount(Category::class, 2);
        $this->assertDatabaseHas(Category::class, $categoryData);
        $response->assertSessionHas('success', 'Успешно сохранено.');
        $response->assertRedirect(route('categories.index'));
    }

    public function testGetViewCategoryEdit(): void
    {
        $category = Category::factory()->create([
            'name' => 'test',
            'is_active' => false,
        ]);
        $title = 'Редактировать: test';

        $response = $this->get(route('categories.edit', $category));

        $response->assertSuccessful();
        $response->assertViewIs('admin.categories.edit');
        $response->assertViewHas([
            'title' => $title,
            'item' => $category,
        ]);
    }

    public function testCategoryUpdate(): void
    {
        $category = Category::factory()->create([
            'parent_id' => 2,
            'name' => 'test2',
            'is_active' => true,
        ]);
        $requestData = [
            'parent_id' => Category::factory()->create(['id' => 1])->id,
            'name' => 'test',
        ];

        $response = $this->put(route('categories.update', $category), $requestData);

        $this->assertDatabaseCount(Category::class, 2);
        $this->assertDatabaseHas(Category::class, $requestData);
        $response->assertSessionHas('success', 'Успешно сохранено.');
        $response->assertRedirect(route('categories.index'));
    }

    public function testCategoryDestroy(): void
    {
        $category = Category::factory()->create([
            'parent_id' => 2,
            'name' => 'test',
            'is_active' => true,
        ]);

        $response = $this->delete(route('categories.destroy', $category));

        $this->assertDatabaseCount(Category::class, 0);
        $this->assertDatabaseMissing(Category::class, $category->toArray());
        $response->assertSessionHas('success', 'Успешно удалено.');
        $response->assertRedirect(route('categories.index'));
    }

}
