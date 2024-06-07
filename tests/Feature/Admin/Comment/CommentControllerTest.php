<?php

namespace Admin\Comment;

use App\Models\AdminUser;
use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
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
    private Article $article;

    public function setUp(): void
    {
        parent::setUp();

        $this->commentService = new CommentService(new CommentRepository());

        $this->actingAs(AdminUser::factory()->create(), 'admin');

        $this->adminUser = AdminUser::factory()->create();
        Category::factory()->create();
        $this->article = Article::factory()->create();
    }

    public function testGetViewCommentsIndex(): void
    {
        $perPage = 20;
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
        $commentData = [
            'comment' => 'test comment data',
            'article_id' => $this->article->id,
        ];

        $response = $this->post(route('comments.store'), $commentData);

        $this->assertDatabaseCount(Comment::class, 1);
        $this->assertDatabaseHas(Comment::class, $commentData);
        $response->assertSessionHas('success', 'Ваш комментарий был успешно принят, но будет опубликован после проверки модератором.');
    }

    public function testGetViewCommentsShow(): void
    {
        $comment = Comment::factory()->create([
            'id' => 1,
            'comment' => 'Test data comment',
            'article_id' => $this->article->id,
            'user_id' => $this->adminUser->id,
        ]);
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
        $requestData = [
            'comment' => 'Changed test data comment',
            'article_id' => $this->article->id,
            'user_id' => $this->adminUser->id,
        ];
        $comment = Comment::factory()->create([
            'comment' => 'Test data comment',
            'article_id' => $this->article->id,
            'user_id' => $this->adminUser->id,
        ]);

        $response = $this->put(route('comments.update', $comment), $requestData);

        $this->assertDatabaseCount(Comment::class, 1);
        $this->assertDatabaseHas(Comment::class, $requestData);
        $response->assertSessionHas('success', 'Успешно сохранено.');
        $response->assertRedirect(route('comments.index'));
    }

    public function testCommentDestroy(): void
    {
        $comment = Comment::factory()->create([
            'comment' => 'Test data comment',
            'article_id' => $this->article->id,
            'user_id' => $this->adminUser->id,
        ]);

        $response = $this->delete(route('comments.destroy', $comment));

        $this->assertDatabaseCount(Comment::class, 0);
        $this->assertDatabaseMissing(Comment::class, $comment->toArray());
        $response->assertSessionHas('success', 'Успешно удалено.');
        $response->assertRedirect(route('comments.index'));
    }

}
