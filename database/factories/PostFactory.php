<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->word,
            'content' => $this->faker->word,
            'slug' => Str::slug($this->faker->word),
            'user_id' => function () {
                return User::factory()->create()->id;
            },
        ];
    }
}
