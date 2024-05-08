<?php

namespace Admin\Category;

use App\Models\AdminUser;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class CategoryRequestTest extends TestCase
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
            'parent_id' => 1,
            'name' => '$#$#',
        ];
        $fieldErrorMessage = [
            'name' => 'Значение поля названия имеет некорректный формат.',
        ];

        $this->post(route('categories.store'), $invalidRequestData)
            ->assertInvalid($fieldErrorMessage);

        $this->assertTrue(session()->hasOldInput('name'));
    }

    public function testCategoryValidateWithUniqueName(): void
    {
        Category::factory()->create([
            'name' => 'test',
            'is_active' => true,
        ]);
        $invalidRequestData = [
            'parent_id' => 1,
            'name' => 'test',
        ];
        $fieldErrorMessage = [
            'name' => 'Такое значение поля названия уже существует.',
        ];

        $this->post(route('categories.store'), $invalidRequestData)
            ->assertInvalid($fieldErrorMessage);

        $this->assertTrue(session()->hasOldInput('name'));
    }

    public function testThumbnailValidateWithIncorrectFormat(): void
    {
        $file = UploadedFile::fake()->image('file.qwddqw', 150, 150);
        $invalidRequestData = [
            'parent_id' => 1,
            'name' => 'test',
            'thumbnail' => $file,
        ];
        $fieldErrorMessage = [
            'thumbnail' => [
                'Пожалуйста, выберите изображение!',
                'Файл, указанный в поле изображения, должен быть одного из следующих типов: jpeg, jpg, png.',
            ],
        ];

        $this->post(route('categories.store'), $invalidRequestData)
            ->assertInvalid($fieldErrorMessage);

        $this->assertTrue(session()->hasOldInput('name'));
    }

    public function testMoreThanFields(): void
    {
        $longString = Str::repeat('n', 10000000);
        $longStringName = $longString;
        $longStringContent = $longString;
        $invalidRequestData = [
            'name' => $longStringName,
            'content' => $longStringContent,
        ];
        $fieldErrorMessage = [
            'name' => 'Количество символов в значении поля названия не может превышать 255.',
            'content' => 'Количество символов в значении поля описания не может превышать 1000000.',
        ];

        $this->post(route('categories.store', $invalidRequestData))
            ->assertInvalid($fieldErrorMessage);

        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('content'));
    }

    public function testAllFieldsEmpty(): void
    {
        $invalidRequestData = [
            'parent_id' => '',
            'name' => '',
            'content' => '',
            'is_active' => '',
        ];
        $fieldErrorMessages = [
            'parent_id' => 'Поле parent id обязательно.',
            'name' => 'Поле названия обязательно.',
        ];

        $this->post(route('categories.store', $invalidRequestData))
            ->assertInvalid($fieldErrorMessages);
    }

    public function testCategoryValidateSuccess(): void
    {
        Category::factory()->create(['id' => 1]);
        $file = UploadedFile::fake()->image('avatar.png', 215, 215);
        $validRequestData = [
            'parent_id' => 1,
            'name' => 'test',
            'thumbnail' => $file,
        ];

        $response = $this->post(route('categories.store'), $validRequestData);
        $category = Category::get()->last();

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseCount(Category::class, 2);
        Storage::disk('public')->assertExists($category->thumbnail);
    }

}
