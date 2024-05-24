<?php

namespace Admin\User;

use App\Models\AdminUser;
use App\Models\User;
use App\Services\Users\UserRepository;
use App\Services\Users\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    private UserService $adminUserService;

    public function setUp(): void
    {
        parent::setUp();

        $this->userService = new UserService(new UserRepository());

        $this->actingAs(AdminUser::factory()->create(), 'admin');
    }

    public function testGetViewUsersIndex(): void
    {
        $title = 'Пользователи';
        $perPage = 5;
        $request = new Request();

        $response = $this->get(route('users.index'));
        $users = $this->userService->getAllWithPagination($request, $perPage);

        $response->assertSuccessful();
        $response->assertViewIs('admin.users.index');
        $response->assertViewHas([
            'title' => $title,
            'paginator' => $users,
        ]);
    }

    public function testGetViewUsersCreate(): void
    {
        $title = 'Добавить';

        $response = $this->get(route('users.create'));

        $response->assertSuccessful();
        $response->assertViewIs('admin.users.create');
        $response->assertViewHas([
            'title' => $title,
        ]);
    }

    public function testUserCreate(): void
    {
        $userData = [
            'username' => 'Test',
            'email' => 'test@example.com',
            'is_banned' => false,
        ];
        $requestData = [
            'username' => 'Test',
            'email' => 'test@example.com',
            'password' => '123456j',
            'password_confirmation' => '123456j',
            'is_banned' => false,
        ];

        $response = $this->post(route('users.store'), $requestData);

        $this->assertDatabaseCount(User::class, 1);
        $this->assertDatabaseHas(User::class, $userData);
        $response->assertSessionHas('success', 'Успешно сохранено.');
        $response->assertRedirect(route('users.index'));
    }

    public function testGetViewUsersShow(): void
    {
        $user = User::factory()->create([
            'username' => 'Test',
            'email' => 'test@gmail.com',
            'password' => bcrypt('123456j'),
            'is_banned' => true,
        ]);
        $title = 'Профиль пользователя: Test';

        $response = $this->get(route('users.show', $user));

        $response->assertSuccessful();
        $response->assertViewIs('admin.users.show');
        $response->assertViewHas([
            'title' => $title,
            'item' => $user,
        ]);
    }

    public function testGetViewUsersEdit(): void
    {
        $userData = [
            'username' => 'Test',
            'email' => 'test@gmail.com',
            'password' => '123456j',
            'is_banned' => true,
        ];
        $user = User::factory()->create($userData);
        $title = 'Редактировать: Test';

        $response = $this->get(route('users.edit', $user));

        $response->assertSuccessful();
        $response->assertViewIs('admin.users.edit');
        $response->assertViewHas([
            'title' => $title,
            'item' => $user,
        ]);
    }

    public function testUserUpdate(): void
    {
        $requestData = [
            'username' => 'Test2',
            'email' => 'test@example2.com',
            'is_banned' => false,
        ];
        $userData = [
            'username' => 'Test',
            'email' => 'test@example.com',
            'password' => bcrypt('123456j'),
            'is_banned' => false,
        ];
        $user = User::factory()->create($userData);

        $response = $this->put(route('users.update', $user), $requestData);

        $this->assertDatabaseCount(User::class, 1);
        $this->assertDatabaseHas(User::class, $requestData);
        $response->assertSessionHas('success', 'Успешно сохранено.');
        $response->assertRedirect(route('users.index'));
    }

    public function testUserDestroy(): void
    {
        $userData = [
            'username' => 'Test',
            'email' => 'test@example.com',
            'password' => bcrypt('123456j'),
            'is_banned' => true,
        ];
        $user = User::factory()->create($userData);

        $response = $this->delete(route('users.destroy', $user));

        $this->assertDatabaseCount(User::class, 0);
        $this->assertDatabaseMissing(User::class, $userData);
        $response->assertSessionHas('success', 'Успешно удалено.');
        $response->assertRedirect(route('users.index'));
    }

}
