<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class FavoritePostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $user = User::inRandomOrder()->first();
        $post = Post::inRandomOrder()->first();

        while ($user->favoritePosts()->where('post_id', $post->id)->exists()) {
            $user = User::inRandomOrder()->first();
            $post = Post::inRandomOrder()->first();
        }

        return [
            'user_id'=>$user->id,
            'post_id'=>$post->id,
        ];
    }
}
