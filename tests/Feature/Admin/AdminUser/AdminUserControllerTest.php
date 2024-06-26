<?php

namespace Admin\AdminUser;

use App\Models\AdminUser;
use App\Services\AdminUsers\AdminUserRepository;
use App\Services\AdminUsers\AdminUserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class AdminUserControllerTest extends TestCase
{
    use RefreshDatabase;

    private AdminUserService $adminUserService;

    public function setUp(): void
    {
        parent::setUp();

        $this->adminUserService = new AdminUserService(new AdminUserRepository());

        $this->actingAs(AdminUser::factory()->create(), 'admin');
    }

    public function testGetViewAdminUsersIndex(): void
    {
        $title = 'Администраторы';
        $request = new Request();
        $perPage = 20;

        $response = $this->get(route('admin-users.index'));
        $adminUsers = $this->adminUserService->getAllWithPagination($request, $perPage);

        $response->assertSuccessful();
        $response->assertViewIs('admin.admin_users.index');
        $response->assertViewHas([
            'title' => $title,
            'paginator' => $adminUsers,
        ]);
    }

    public function testGetViewAdminUsersCreate(): void
    {
        $title = 'Добавить';

        $response = $this->get(route('admin-users.create'));

        $response->assertSuccessful();
        $response->assertViewIs('admin.admin_users.create');
        $response->assertViewHas([
            'title' => $title,
        ]);
    }

    public function testAdminUserCreate(): void
    {
        $requestData = [
            'username' => 'Test',
            'email' => 'test@example.com',
            'password' => '123456j',
            'password_confirmation' => '123456j',
            'is_banned' => false,
        ];
        $adminUserData = [
            'username' => 'Test',
            'email' => 'test@example.com',
            'is_banned' => false,
        ];

        $response = $this->post(route('admin-users.store'), $requestData);

        $this->assertDatabaseCount(AdminUser::class, 2);
        $this->assertDatabaseHas(AdminUser::class, $adminUserData);
        $response->assertSessionHas('success', 'Успешно сохранено.');
        $response->assertRedirect(route('admin-users.index'));
    }

    public function testGetViewAdminUsersShow(): void
    {
        $adminUser = AdminUser::factory()->create([
            'username' => 'Test',
            'email' => 'test@gmail.com',
            'password' => bcrypt('123456j'),
            'is_banned' => true,
        ]);
        $title = 'Профиль администратора: Test';

        $response = $this->get(route('admin-users.show', $adminUser));

        $response->assertSuccessful();
        $response->assertViewIs('admin.admin_users.show');
        $response->assertViewHas([
            'title' => $title,
            'item' => $adminUser,
        ]);
    }

    public function testGetViewAdminUsersEdit(): void
    {
        $title = 'Редактировать: Test';
        $adminUser = AdminUser::factory()->create([
            'username' => 'Test',
            'email' => 'test@gmail.com',
            'password' => '123456j',
            'is_banned' => true,
        ]);

        $response = $this->get(route('admin-users.edit', $adminUser));

        $response->assertSuccessful();
        $response->assertViewIs('admin.admin_users.edit');
        $response->assertViewHas([
            'title' => $title,
            'item' => $adminUser,
        ]);
    }

    public function testAdminUserUpdate(): void
    {
        $adminUser = AdminUser::factory()->create([
            'username' => 'Test',
            'email' => 'test@example.com',
            'password' => bcrypt('123456j'),
            'is_banned' => false,
        ]);
        $requestData = [
            'username' => 'Test2',
            'email' => 'test@example2.com',
            'is_banned' => true,
        ];

        $response = $this->put(route('admin-users.update', $adminUser), $requestData);

        $this->assertDatabaseCount(AdminUser::class, 2);
        $this->assertDatabaseHas(AdminUser::class, $requestData);
        $response->assertSessionHas('success', 'Успешно сохранено.');
        $response->assertRedirect(route('admin-users.index'));
    }

    public function testAdminUserDestroy(): void
    {
        $adminUser = AdminUser::factory()->create([
            'username' => 'Test',
            'email' => 'test@example.com',
            'password' => bcrypt('123456j'),
            'is_banned' => true,
        ]);

        $response = $this->delete(route('admin-users.destroy', $adminUser));

        $this->assertDatabaseCount(AdminUser::class, 1);
        $this->assertDatabaseMissing(AdminUser::class, $adminUser->toArray());
        $response->assertSessionHas('success', 'Успешно удалено.');
        $response->assertRedirect(route('admin-users.index'));
    }

}
