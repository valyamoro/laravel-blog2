<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\AdminUser;
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
    }

}
