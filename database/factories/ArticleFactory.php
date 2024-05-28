<?php

namespace Database\Factories;

use App\Models\AdminUser;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->unique()->words(mt_rand(1, 2), true);

        return [
            'category_id' => mt_rand(1, Category::get()->count()),
            'user_id' => mt_rand(1, AdminUser::get()->count()),
            'title' => $title,
            'slug' => Str::slug($title),
            'annotation' => fake()->realText(mt_rand(10, 100)),
            'content' => fake()->realText(mt_rand(1000, 10000)),
            'thumbnail' => '',
            'views' => '0',
            'is_active' => '1',
        ];
    }

}
