<?php

namespace Admin\Comment;

use App\Models\AdminUser;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CommentRequestTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->actingAs(AdminUser::factory()->create(), 'admin');
    }

    public function testMoreThanFields(): void
    {
        $invalidRequestData = [
            'comment' => Str::repeat('n', 256),
        ];
        $fieldErrorMessage = [
            'comment' => 'Количество символов в значении поля comment не может превышать 255.',
        ];

        $this->post(route('comments.store', $invalidRequestData))
            ->assertInvalid($fieldErrorMessage);

        $this->assertTrue(session()->hasOldInput('comment'));
    }

    public function testLessThanFields(): void
    {
        $invalidRequestData = [
            'comment' => 'nnnn',
        ];
        $fieldErrorMessage = [
            'comment' => 'Количество символов в поле comment должно быть не меньше 5.',
        ];

        $this->post(route('comments.store', $invalidRequestData))
            ->assertInvalid($fieldErrorMessage);

        $this->assertTrue(session()->hasOldInput('comment'));
    }

    public function testValidateSuccess(): void
    {
        Category::factory()->create();
        $article = Article::factory()->create(['id' => 1]);
        $validRequestData = [
            'article_id' => $article->id,
            'comment' => 'Test data comment',
        ];

        $response = $this->post(route('comments.store'), $validRequestData);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseCount(Article::class,  1);
    }

}
