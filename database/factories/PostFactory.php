<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        $user = User::inRandomOrder()->first();

        return [
            'user_id' => $user->id,
            'body' => fake()->text(100),
            'user_name' => $user->user_name,
            'likes_count' => 0,
        ];
    }
}
