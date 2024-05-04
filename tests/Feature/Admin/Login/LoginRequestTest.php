<?php

namespace Admin\Login;

use App\Models\AdminUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginRequestTest extends TestCase
{
    use RefreshDatabase;

    private AdminUser $adminUser;
    private readonly string $password;

    public function setUp(): void
    {
        parent::setUp();

        $this->adminUser = AdminUser::factory()->create([
            'email' => 'test@gmail.com',
            'password' => bcrypt($this->password = '123456j'),
        ]);
    }

    public function testInvalidEmail(): void
    {
        $invalidRequestData = [
            'email' => '@@',
            'password' => $this->password,
        ];
        $fieldErrorMessages = [
            'email' => 'Значение поля почты должно быть действительным электронным адресом.',
        ];

        $response = $this->post(route('admin.login.handler', $invalidRequestData));

        $response->assertStatus(302);
        $response->assertSessionHasErrors($fieldErrorMessages);
    }

    public function testInvalidEmailOrPassword(): void
    {
        $invalidRequestData = [
            'email' => $this->adminUser->email,
            'password' => 'invalid_password',
        ];
        $fieldErrorMessages = [
            'email' => 'Почта или пароль неверны.',
        ];

        $response = $this->post(route('admin.login.handler', $invalidRequestData));

        $response->assertStatus(302);
        $response->assertSessionHasErrors($fieldErrorMessages);
    }

    public function testMoreThanFields(): void
    {
        $longString = str_repeat('n', 1001);
        $longStringEmail = $longString . '@example.com';
        $longStringPassword = $longString;
        $invalidRequestData = [
            'email' => $longStringEmail,
            'password' => $longStringPassword,
        ];
        $fieldErrorMessages = [
            'email' => 'Количество символов в значении поля почты не может превышать 255.',
            'password' => 'Количество символов в значении поля пароль не может превышать 1000.',
        ];

        $response = $this->post(route('admin.login.handler', $invalidRequestData));

        $response->assertStatus(302);
        $response->assertSessionHasErrors($fieldErrorMessages);
    }

    public function testAllFieldsEmpty(): void
    {
        $invalidRequestData = [
            'email' => '',
            'password' => '',
        ];
        $fieldErrorMessages = [
            'email' => 'Поле почты обязательно.',
            'password' => 'Поле пароль обязательно.',
        ];

        $response = $this->post(route('admin.login.handler', $invalidRequestData));

        $response->assertStatus(302);
        $response->assertSessionHasErrors($fieldErrorMessages);
    }

    public function testAdminUserCanLogin(): void
    {
        $validRequestData = [
            'email' => $this->adminUser->email,
            'password' => $this->password,
        ];

        $response = $this->post(route('admin.login.handler'), $validRequestData);

        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('success', 'Вы успешно вошли в систему!');
    }

}
