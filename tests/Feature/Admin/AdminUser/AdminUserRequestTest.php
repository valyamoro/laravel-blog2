<?php

namespace Admin\AdminUser;

use App\Models\AdminUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserRequestTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->actingAs(AdminUser::factory()->create(), 'admin');
    }

    public function testInvalidUsername(): void
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

        $response = $this->post(route('admin-users.store', $invalidRequestData));

        $response->assertStatus(302);
        $response->assertSessionHasErrors($fieldErrorMessage);
        $this->assertDatabaseCount(AdminUser::class, 1);
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

        $response = $this->post(route('admin-users.store', $invalidRequestData));

        $response->assertStatus(302);
        $response->assertSessionHasErrors($fieldErrorMessage);
        $this->assertDatabaseCount(AdminUser::class, 2);
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

        $response = $this->post(route('admin-users.store', $invalidRequestData));

        $response->assertStatus(302);
        $response->assertSessionHasErrors($fieldErrorMessage);
        $this->assertDatabaseCount(AdminUser::class, 1);
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

        $response = $this->post(route('admin-users.store', $invalidRequestData));

        $response->assertStatus(302);
        $response->assertSessionHasErrors($fieldErrorMessage);
        $this->assertDatabaseCount(AdminUser::class, 1);
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

        $response = $this->post(route('admin-users.store', $invalidRequestData));

        $response->assertStatus(302);
        $response->assertSessionHasErrors($fieldErrorMessage);
        $this->assertDatabaseCount(AdminUser::class, 1);
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

        $response = $this->post(route('admin-users.store', $invalidRequestData));

        $response->assertStatus(302);
        $response->assertSessionHasErrors($fieldErrorMessages);
        $this->assertDatabaseCount(AdminUser::class, 1);
        $this->assertTrue(session()->hasOldInput('username'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
    }

    public function testMoreThanFields(): void
    {
        $longString = str_repeat('n', 256);
        $longStringUsername = $longString;
        $longStringEmail = $longString . '@example.com';
        $longStringPassword = $longString;
        $invalidRequestData = [
            'username' => $longStringUsername,
            'email' => $longStringEmail,
            'password' => $longStringPassword,
            'password_confirmation' => $longStringPassword,
            'is_banned' => '0',
        ];
        $fieldErrorMessages = [
            'username' => 'Количество символов в значении поля имя не может превышать 255.',
            'email' => 'Количество символов в значении поля почты не может превышать 255.',
            'password' => 'Количество символов в значении поля пароль не может превышать 255.',
        ];

        $response = $this->post(route('admin-users.store', $invalidRequestData));

        $response->assertStatus(302);
        $response->assertSessionHasErrors($fieldErrorMessages);
        $this->assertDatabaseCount(AdminUser::class, 1);
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

        $response = $this->post(route('admin-users.store', $invalidRequestData));

        $response->assertStatus(302);
        $response->assertSessionHasErrors($fieldErrorMessages);
        $this->assertDatabaseCount(AdminUser::class, 1);
        $this->assertFalse(session()->hasOldInput('password'));
    }

}
