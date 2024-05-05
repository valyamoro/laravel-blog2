<?php

namespace Admin\Tag;

use App\Models\AdminUser;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagRequestTest extends TestCase
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
            'name' => '$#$#',
        ];
        $fieldErrorMessage = [
            'name' => 'Значение поля названия имеет некорректный формат.',
        ];

        $this->post(route('tags.store'), $invalidRequestData)
            ->assertInvalid($fieldErrorMessage);

        $this->assertTrue(session()->hasOldInput('name'));
    }

    public function testTagValidateWithUniqueName(): void
    {
        Tag::factory()->create([
            'name' => 'test',
            'is_active' => true,
        ]);
        $invalidRequestData = [
            'name' => 'test',
        ];
        $fieldErrorMessage = [
            'name' => 'Такое значение поля названия уже существует.',
        ];

        $this->post(route('tags.store'), $invalidRequestData)
            ->assertInvalid($fieldErrorMessage);

        $this->assertTrue(session()->hasOldInput('name'));
    }

    public function testTagValidateWithEmptyFields(): void
    {
        $invalidRequestData = [
            'name' => '',
        ];
        $fieldErrorMessage = [
            'name' => 'Поле названия обязательно.',
        ];

        $this->post(route('tags.store'), $invalidRequestData)
            ->assertInvalid($fieldErrorMessage);

        $this->assertFalse(session()->hasOldInput('name'));
    }

}
