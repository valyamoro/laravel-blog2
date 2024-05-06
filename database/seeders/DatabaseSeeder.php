<?php

namespace Database\Seeders;

use App\Models\AdminUser;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        AdminUser::factory()->create([
            'username' => 'Admin',
            'email' => 'admin@mail.ru',
            'password' => '123456j',
            'is_banned' => false,
        ]);

        AdminUser::factory(25)->create();
        Tag::factory(12)->create();
        Category::factory(10)->create();
    }

}
