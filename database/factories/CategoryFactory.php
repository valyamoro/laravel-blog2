<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    public function definition(): array
    {
        static $i = 0;

        $name = fake()->unique()->words(mt_rand(1, 2), true);

        return [
            'parent_id' => (++$i > 4) ? mt_rand(1, 3) : 0,
            'slug' => Str::slug($name),
            'name' => $name,
            'content' => fake()->realText(mt_rand(100, 200)),
            'thumbnail' => '',
            'view' => '0',
            'is_active' => '1',
        ];
    }

}
