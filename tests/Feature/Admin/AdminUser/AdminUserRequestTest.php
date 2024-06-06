<?php

namespace Admin\AdminUser;

use App\Models\AdminUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class AdminUserRequestTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->actingAs(AdminUser::factory()->create(), 'admin');
    }

    public function testInvalidAdminUsername(): void
    {
        $invalidRequestData = [
            'username' => '#!#@!#@11',
            'email' => 'valid@email.example',
            'password' => '123456j',
            'password_confirmation' => '123456j',
            'is_banned' => '0',
        ];
        $fieldErrorMessage = [
            'username' => 'Значение поля имя имеет некорректный формат.',
        ];

        $this->post(route('admin-users.store', $invalidRequestData))
            ->assertInvalid($fieldErrorMessage);

        $this->assertTrue(session()->hasOldInput('username'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
    }

    public function testUniquenessEmail(): void
    {
        AdminUser::factory()->create([
            'username' => 'Test',
            'email' => 'mail@mail.ru',
            'password' => '123456j',
            'is_banned' => true,
        ]);
        $invalidRequestData = [
            'username' => 'User name',
            'email' => 'mail@mail.ru',
            'password' => '123456j',
            'password_confirmation' => '123456j',
            'is_banned' => 1,
        ];
        $fieldErrorMessage = [
            'email' => 'Такое значение поля почты уже существует.',
        ];

        $this->post(route('admin-users.store', $invalidRequestData))
            ->assertInvalid($fieldErrorMessage);

        $this->assertTrue(session()->hasOldInput('username'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
    }

    public function testInvalidEmail(): void
    {
        $invalidRequestData = [
            'username' => 'User name',
            'email' => '@@',
            'password' => '123456j',
            'password_confirmation' => '123456j',
            'is_banned' => 1,
        ];
        $fieldErrorMessage = [
            'email' => 'Значение поля почты должно быть действительным электронным адресом.',
        ];

        $this->post(route('admin-users.store', $invalidRequestData))
            ->assertInvalid($fieldErrorMessage);

        $this->assertTrue(session()->hasOldInput('username'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
    }

    public function testInvalidPassword(): void
    {
        $invalidRequestData = [
            'username' => 'valid name',
            'email' => 'valid@email.example',
            'password' => '#$@$!@$',
            'password_confirmation' => '#$@$!@$',
            'is_banned' => '0',
        ];
        $fieldErrorMessage = [
            'password' => 'Значение поля пароль имеет некорректный формат.',
        ];

        $this->post(route('admin-users.store', $invalidRequestData))
            ->assertInvalid($fieldErrorMessage);

        $this->assertTrue(session()->hasOldInput('username'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
    }

    public function testMismatchPassword(): void
    {
        $invalidRequestData = [
            'username' => 'valid name',
            'email' => 'valid@email.example',
            'password' => 'helllo',
            'password_confirmation' => '',
            'is_banned' => '0',
        ];
        $fieldErrorMessage = [
            'password' => 'Значение поля пароль не совпадает с подтверждаемым.',
        ];

        $this->post(route('admin-users.store', $invalidRequestData))
            ->assertInvalid($fieldErrorMessage);

        $this->assertTrue(session()->hasOldInput('username'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
    }

    public function testLessThanFields(): void
    {
        $invalidRequestData = [
            'username' => 'n',
            'email' => 'mail@mail.ru',
            'password' => 'nnnn',
            'password_confirmation' => 'nnnn',
            'is_banned' => '0',
        ];
        $fieldErrorMessages = [
            'username' => 'Количество символов в поле имя должно быть не меньше 2.',
            'password' => 'Количество символов в поле пароль должно быть не меньше 5.',
        ];

        $this->post(route('admin-users.store', $invalidRequestData))
            ->assertInvalid($fieldErrorMessages);

        $this->assertTrue(session()->hasOldInput('username'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
    }

    public function testMoreThanFields(): void
    {
        $invalidRequestData = [
            'username' => Str::repeat('n', 256),
            'email' => Str::repeat('n', 256) . '@gmail.com',
            'password' => Str::repeat('n', 256),
            'password_confirmation' => Str::repeat('n', 256),
            'is_banned' => '0',
        ];
        $fieldErrorMessages = [
            'username' => 'Количество символов в значении поля имя не может превышать 255.',
            'email' => 'Количество символов в значении поля почты не может превышать 255.',
            'password' => 'Количество символов в значении поля пароль не может превышать 255.',
        ];

        $this->post(route('admin-users.store', $invalidRequestData))
            ->assertInvalid($fieldErrorMessages);

        $this->assertTrue(session()->hasOldInput('username'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
    }

    public function testAllFieldsEmpty(): void
    {
        $invalidRequestData = [
            'username' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
            'is_banned' => '',
        ];
        $fieldErrorMessages = [
            'username' => 'Поле имя обязательно.',
            'email' => 'Поле почты обязательно.',
            'password' => 'Поле пароль обязательно.',
        ];

        $this->post(route('admin-users.store', $invalidRequestData))
            ->assertInvalid($fieldErrorMessages);

        $this->assertFalse(session()->hasOldInput('password'));
    }

}
