<?php

namespace Database\Factories;

use App\Enums\CommentStatuEnum;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->firstName . ' ' . $this->faker->lastName,
            'email' => $this->faker->email(),
            'comment' => $this->faker->text(500),
            'status' => $this->faker->randomElement(CommentStatuEnum::values()),
            'post_id' => Post::inRandomOrder()->first()->id
        ];
    }
}
