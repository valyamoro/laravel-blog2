<?php

namespace Admin\Article;

use App\Models\AdminUser;
use App\Models\Article;
use App\Models\Category;
use App\Services\Articles\ArticleRepository;
use App\Services\Articles\ArticleService;
use App\Services\Categories\CategoryRepository;
use App\Services\Categories\CategoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Tests\TestCase;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase;

    private ArticleService $articleService;
    private CategoryService $categoryService;

    public function setUp(): void
    {
        parent::setUp();

        $categoryRepository = new CategoryRepository();
        $this->categoryService = new CategoryService($categoryRepository);

        $articleRepository = new ArticleRepository();
        $this->articleService = new ArticleService($articleRepository);

        $this->actingAs(AdminUser::factory()->create(), 'admin');
    }

    public function testGetViewArticleIndex(): void
    {
        $title = 'Статьи';
        $perPage = 5;
        $request = new Request();

        $response = $this->get(route('articles.index'));
        $articles = $this->articleService->getAllWithPagination($request, $perPage);

        $response->assertSuccessful();
        $response->assertViewIs('admin.articles.index');
        $response->assertViewHas([
            'title' => $title,
            'paginator' => $articles,
        ]);
    }

    public function testGetViewArticleCreate(): void
    {
        $title = 'Добавить';
        $categories = $this->categoryService->getForSelect();

        $response = $this->get(route('articles.create'));

        $response->assertSuccessful();
        $response->assertViewIs('admin.articles.create');
        $response->assertViewHas([
            'title' => $title,
            'categories' => $categories,
        ]);
    }

    public function testArticleCreate(): void
    {
        Category::factory()->create(['id' => 1]);
        $articleData = [
            'category_id' => 1,
            'title' => 'test',
            'is_active' => true,
        ];
        $requestData = [
            'category_id' => 1,
            'title' => 'test',
            'annotation' => 'test description of article',
            'content' => Str::repeat('n', 10000),
            'is_active' => true,
        ];

        $response = $this->post(route('articles.store'), $requestData);

        $this->assertDatabaseCount(Article::class, 1);
        $this->assertDatabaseHas(Article::class, $articleData);
        $response->assertSessionHas('success', 'Успешно сохранено.');
        $response->assertRedirect(route('articles.index'));
    }

    public function testGetViewArticleEdit(): void
    {
        Category::factory()->create(['id' => 1]);
        $articleData = [
            'category_id' => 1,
            'title' => 'test',
            'is_active' => true,
        ];
        $article = Article::factory()->create($articleData);
        $title = 'Редактировать: test';
        $categories = $this->categoryService->getForSelect();

        $response = $this->get(route('articles.edit', $article));

        $response->assertSuccessful();
        $response->assertViewIs('admin.articles.edit');
        $response->assertViewHas([
            'title' => $title,
            'item' => $article,
            'categories' => $categories,
        ]);
    }

    public function testArticleUpdate(): void
    {
        Category::factory()->create(['id' => 1]);
        $articleData = [
            'category_id' => 1,
            'title' => 'test',
            'is_active' => true,
        ];
        $requestData = [
            'category_id' => 1,
            'title' => 'test',
            'annotation' => 'test description of article',
            'content' => Str::repeat('n', 10000),
            'is_active' => true,
        ];
        $article = Article::factory()->create($articleData);

        $response = $this->patch(route('articles.update', $article), $requestData);

        $this->assertDatabaseCount(Article::class, 1);
        $this->assertDatabaseHas(Article::class, $requestData);
        $response->assertSessionHas('success', 'Успешно сохранено.');
        $response->assertRedirect(route('articles.index'));
    }
    public function testArticleDestroy(): void
    {
        Category::factory()->create(['id' => 1]);
        $articleData = [
            'category_id' => 1,
            'title' => 'test',
            'is_active' => true,
        ];
        $article = Article::factory()->create($articleData);

        $response = $this->delete(route('articles.destroy', $article));

        $this->assertDatabaseCount(Article::class, 0);
        $this->assertDatabaseMissing(Article::class, $articleData);
        $response->assertSessionHas('success', 'Успешно удалено.');
        $response->assertRedirect(route('articles.index'));
    }

}
