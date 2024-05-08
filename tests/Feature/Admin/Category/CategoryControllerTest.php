<?php

namespace Admin\Category;

use App\Http\Requests\AdminUserRequestSearch;
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

        $categoryRepository = new CategoryRepository();
        $this->categoryService = new CategoryService($categoryRepository);

        $this->actingAs(AdminUser::factory()->create(), 'admin');
    }

    public function testGetViewCategoryIndex(): void
    {
        $title = 'Категории';
        $perPage = 5;
        $request = new AdminUserRequestSearch();

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
        Category::factory()->create(['id' => 1]);
        $tagData = [
            'parent_id' => 1,
            'name' => 'test',
            'is_active' => true,
        ];
        $requestData = [
            'parent_id' => 1,
            'name' => 'test',
            'is_active' => true,
        ];

        $response = $this->post(route('categories.store'), $requestData);

        $this->assertDatabaseCount(Category::class, 2);
        $this->assertDatabaseHas(Category::class, $tagData);
        $response->assertSessionHas('success', 'Успешно сохранено.');
        $response->assertRedirect(route('categories.index'));
    }

    public function testGetViewCategoryEdit(): void
    {
        $categoryData = [
            'parent_id' => 2,
            'name' => 'test',
            'is_active' => false,
        ];
        $category = Category::factory()->create($categoryData);
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
        Category::factory()->create(['id' => 1]);
        $requestData = [
            'parent_id' => 1,
            'name' => 'test',
            'is_active' => false,
        ];
        $categoryData = [
            'parent_id' => 2,
            'name' => 'test2',
            'is_active' => true,
        ];
        $category = Category::factory()->create($categoryData);

        $response = $this->patch(route('categories.update', $category), $requestData);

        $this->assertDatabaseCount(Category::class, 2);
        $this->assertDatabaseHas(Category::class, $requestData);
        $response->assertSessionHas('success', 'Успешно сохранено.');
        $response->assertRedirect(route('categories.index'));
    }
    public function testCategoryDestroy(): void
    {
        $categoryData = [
            'parent_id' => 2,
            'name' => 'test',
            'is_active' => true,
        ];
        $category = Category::factory()->create($categoryData);

        $response = $this->delete(route('categories.destroy', $category));

        $this->assertDatabaseCount(Category::class, 0);
        $this->assertDatabaseMissing(Category::class, $categoryData);
        $response->assertSessionHas('success', 'Успешно удалено.');
        $response->assertRedirect(route('categories.index'));
    }

}
