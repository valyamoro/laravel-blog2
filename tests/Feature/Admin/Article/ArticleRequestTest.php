<?php

namespace Admin\Article;

use App\Models\AdminUser;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class ArticleRequestTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->actingAs(AdminUser::factory()->create(), 'admin');
    }

    public function testInvalidName(): void
    {
        $invalidRequestData = [
            'category_id' => '#@!',
            'title' => '$#$#',
            'thumbnail' => 'dqqdw#!@#',
            'is_active' => '$!@#!@',
        ];
        $fieldErrorMessage = [
            'category_id' => 'Значение поля category id должно быть целым числом.',
            'title' => 'Значение поля title имеет некорректный формат.',
            'content' => 'Поле описания обязательно.',
            'thumbnail' => [
                'Пожалуйста, выберите изображение!',
                'Файл, указанный в поле изображения, должен быть одного из следующих типов: jpeg, jpg, png.',
                'Изображение с недопустимым размером.',
            ],
            'is_active' => 'Вы должны принять is active.',
        ];

        $this->post(route('articles.store'), $invalidRequestData)
            ->assertInvalid($fieldErrorMessage);

        $this->assertTrue(session()->hasOldInput('title'));
    }

    public function testArticleValidateWithUniqueTitle(): void
    {
        Category::factory()->create(['id' => 1]);
        Article::factory()->create([
            'title' => 'test',
            'is_active' => true,
        ]);
        $invalidRequestData = [
            'category_id' => 1,
            'title' => 'test',
        ];
        $fieldErrorMessage = [
            'title' => 'Такое значение поля title уже существует.',
        ];

        $this->post(route('articles.store'), $invalidRequestData)
            ->assertInvalid($fieldErrorMessage);

        $this->assertTrue(session()->hasOldInput('title'));
    }

    public function testThumbnailValidateWithIncorrectFormat(): void
    {
        Category::factory()->create(['id' => 1]);
        $file = UploadedFile::fake()->image('file.qwddqw', 150, 150);
        $invalidRequestData = [
            'category_id' => 1,
            'title' => 'test',
            'thumbnail' => $file,
        ];
        $fieldErrorMessage = [
            'thumbnail' => [
                'Пожалуйста, выберите изображение!',
                'Файл, указанный в поле изображения, должен быть одного из следующих типов: jpeg, jpg, png.',
            ],
        ];

        $this->post(route('articles.store'), $invalidRequestData)
            ->assertInvalid($fieldErrorMessage);

        $this->assertTrue(session()->hasOldInput('title'));
    }

    public function testMoreThanFields(): void
    {
        $invalidRequestData = [
            'title' => Str::repeat('n', 10000000),
            'content' => Str::repeat('n', 10000000),
        ];
        $fieldErrorMessage = [
            'title' => 'Количество символов в значении поля title не может превышать 255.',
            'content' => 'Количество символов в значении поля описания не может превышать 1000000.',
        ];

        $this->post(route('articles.store', $invalidRequestData))
            ->assertInvalid($fieldErrorMessage);

        $this->assertTrue(session()->hasOldInput('title'));
        $this->assertTrue(session()->hasOldInput('content'));
    }

    public function testAllFieldsEmpty(): void
    {
        $invalidRequestData = [
            'category_id' => '',
            'title' => '',
            'annotation' => '',
            'content' => '',
            'is_active' => '',
        ];
        $fieldErrorMessages = [
            'category_id' => 'Поле category id обязательно.',
            'title' => 'Поле title обязательно.',
            'content' => 'Поле описания обязательно.',
        ];

        $this->post(route('articles.store', $invalidRequestData))
            ->assertInvalid($fieldErrorMessages);
    }

    public function testCategoryValidateSuccess(): void
    {
        Category::factory()->create(['id' => 1]);
        $file = UploadedFile::fake()->image('avatar.png', 215, 215);
        $validRequestData = [
            'category_id' => 1,
            'title' => 'test',
            'thumbnail' => $file,
            'content' => Str::repeat('n', 1000),
        ];

        $response = $this->post(route('articles.store'), $validRequestData);
        $article = Article::get()->last();

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseCount(Article::class,  1);
        Storage::disk('public')->assertExists($article->thumbnail);
    }

}
