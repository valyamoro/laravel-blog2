<?php

namespace Admin\Comment;

use App\Models\AdminUser;
use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
use App\Services\AdminUsers\AdminUserRepository;
use App\Services\Comments\CommentRepository;
use App\Services\Comments\CommentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;

    private CommentService $commentService;
    private AdminUser $adminUser;

    public function setUp(): void
    {
        parent::setUp();

        $commentRepository = new CommentRepository();
        $adminUserRepository = new AdminUserRepository();
        $this->commentService = new CommentService($commentRepository, $adminUserRepository);

        $this->actingAs(AdminUser::factory()->create(), 'admin');

        Category::factory()->create();
        $this->adminUser = AdminUser::factory()->create();
    }

    public function testGetViewCommentsIndex(): void
    {
        $perPage = 5;
        $title = 'Комментарии';
        $request = new Request();

        $response = $this->get(route('comments.index'));
        $comments = $this->commentService->getAllWithPagination($request, $perPage);

        $response->assertSuccessful();
        $response->assertViewIs('admin.comments.index');
        $response->assertViewHas([
            'title' => $title,
            'paginator' => $comments,
        ]);
    }

    public function testCommentCreate(): void
    {
        $article = Article::factory()->create(['id' => 1]);
        $commentData = [
            'comment' => 'test comment data',
            'article_id' => $article->id,
        ];

        $response = $this->post(route('comments.store'), $commentData);

        $this->assertDatabaseCount(Comment::class, 1);
        $this->assertDatabaseHas(Comment::class, $commentData);
        $response->assertSessionHas('success', 'Ваш комментарий был успешно принят, но будет опубликован после проверки модератором.');
    }

    public function testGetViewCommentsShow(): void
    {
        $article = Article::factory()->create(['id' => 1]);
        $commentData = [
            'id' => 1,
            'comment' => 'Test data comment',
            'article_id' => $article->id,
            'user_id' => $this->adminUser->id,
        ];
        $comment = Comment::factory()->create($commentData);
        $title = 'Комментарий: #1';

        $response = $this->get(route('comments.show', $comment));

        $response->assertSuccessful();
        $response->assertViewIs('admin.comments.show');
        $response->assertViewHas([
            'item' => $comment,
            'title' => $title,
        ]);
    }

    public function testCommentUpdate(): void
    {
        $article = Article::factory()->create(['id' => 1]);
        $commentData = [
            'comment' => 'Test data comment',
            'article_id' => $article->id,
            'user_id' => $this->adminUser->id,
        ];
        $comment = Comment::factory()->create($commentData);

        $response = $this->put(route('comments.update', $comment), $commentData);

        $this->assertDatabaseCount(Comment::class, 1);
        $this->assertDatabaseHas(Comment::class, $commentData);
        $response->assertSessionHas('success', 'Успешно сохранено.');
        $response->assertRedirect(route('comments.index'));
    }

    public function testCommentDestroy(): void
    {
        $article = Article::factory()->create(['id' => 1]);
        $commentData = [
            'comment' => 'Test data comment',
            'article_id' => $article->id,
            'user_id' => $this->adminUser->id,
        ];
        $comment = Comment::factory()->create($commentData);

        $response = $this->delete(route('comments.destroy', $comment));

        $this->assertDatabaseCount(Comment::class, 0);
        $this->assertDatabaseMissing(Comment::class, $commentData);
        $response->assertSessionHas('success', 'Успешно удалено.');
        $response->assertRedirect(route('comments.index'));
    }

}
