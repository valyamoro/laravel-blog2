<?php

namespace Database\Factories;

use App\Models\AdminUser;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'comment' => fake()->paragraph(1),
            'user_id' => mt_rand(1, AdminUser::get()->count()),
            'article_id' => mt_rand(1, Article::get()->count()),
        ];
    }

    public function withParent(Comment $parentComment): CommentFactory
    {
        return $this->state(function () use ($parentComment) {
            return [
                'parent_id' => $parentComment->id,
                'article_id' => $parentComment->article_id,
            ];
        });
    }

}
