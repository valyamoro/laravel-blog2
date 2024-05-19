<?php

namespace Database\Factories;

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
            'category_id' => mt_rand(1, 5),
            'admin_user_id' => mt_rand(1, 5),
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
