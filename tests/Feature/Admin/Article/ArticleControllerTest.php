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

        $this->articleService = new ArticleService(new ArticleRepository());
        $this->categoryService = new CategoryService(new CategoryRepository());

        $this->actingAs(AdminUser::factory()->create(), 'admin');
    }

    public function testGetViewArticleIndex(): void
    {
        $request = new Request();
        $perPage = 5;
        $title = 'Статьи';

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
        $category = Category::factory()->create(['id' => 1]);
        $articleData = [
            'category_id' => $category->id,
            'title' => 'test',
            'is_active' => true,
        ];
        $requestData = [
            'category_id' => $category->id,
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

    public function testGetViewArticlesShow(): void
    {
        $article = Article::factory()->create([
            'category_id' =>  Category::factory()->create(['id' => 1])->id,
            'user_id' => AdminUser::factory()->create(['id' => 2])->id,
            'title' => 'test article title',
            'annotation' => 'test annotation article',
            'content' => Str::repeat('n', 1000),
            'is_active' => true,
        ]);
        $title = 'Статья: test article title';

        $response = $this->get(route('articles.show', $article));

        $response->assertSuccessful();
        $response->assertViewIs('admin.articles.show');
        $response->assertViewHas([
            'item' => $article,
            'title' => $title,
        ]);
    }

    public function testGetViewArticleEdit(): void
    {
        $article = Article::factory()->create([
            'category_id' => Category::factory()->create(['id' => 1])->id,
            'title' => 'test',
            'is_active' => true,
        ]);
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
        $category = Category::factory()->create(['id' => 1]);
        $requestData = [
            'category_id' => $category->id,
            'title' => 'test',
            'annotation' => 'test description of article',
            'content' => Str::repeat('n', 10000),
            'is_active' => true,
        ];
        $article = Article::factory()->create([
            'category_id' => $category->id,
            'title' => 'test',
            'is_active' => true,
        ]);

        $response = $this->patch(route('articles.update', $article), $requestData);

        $this->assertDatabaseCount(Article::class, 1);
        $this->assertDatabaseHas(Article::class, $requestData);
        $response->assertSessionHas('success', 'Успешно сохранено.');
        $response->assertRedirect(route('articles.index'));
    }

    public function testArticleDestroy(): void
    {
        $article = Article::factory()->create([
            'category_id' => Category::factory()->create(['id' => 1])->id,
            'title' => 'test',
            'is_active' => true,
        ]);

        $response = $this->delete(route('articles.destroy', $article));

        $this->assertDatabaseCount(Article::class, 0);
        $this->assertDatabaseMissing(Article::class, $article->toArray());
        $response->assertSessionHas('success', 'Успешно удалено.');
        $response->assertRedirect(route('articles.index'));
    }

}
