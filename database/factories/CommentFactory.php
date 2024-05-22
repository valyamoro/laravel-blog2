<?php

namespace Database\Factories;

use App\Models\AdminUser;
use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'comment' => fake()->paragraph,
            'admin_user_id' => AdminUser::factory(),
            'article_id' => Article::factory(),
        ];
    }

}
