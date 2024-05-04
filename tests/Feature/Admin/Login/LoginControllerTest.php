<?php

namespace Admin\Login;

use App\Models\AdminUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testGetViewAdminLogin(): void
    {
        $title = 'Войти';

        $response = $this->get(route('admin.login.form'));

        $response->assertSuccessful();
        $response->assertViewIs('admin.auth.login');
        $response->assertViewHas([
            'title' => $title,
        ]);
    }

    public function testAdminUserLogin(): void
    {
        $password = '123456j';
        $adminUser = AdminUser::factory()->create([
            'password' => bcrypt($password),
        ]);
        $adminUserData = [
            'email' => $adminUser->email,
            'password' => $password,
        ];

        $response = $this->post(route('admin.login.handler'), $adminUserData);

        $response->assertSessionHas(['success' => 'Вы успешно вошли в систему!']);
        $response->assertRedirect(route('admin.dashboard'));
        $this->assertTrue(auth('admin')->check());
    }

}
