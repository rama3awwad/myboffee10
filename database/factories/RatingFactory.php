<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rating>
 */
class RatingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $user = User::inRandomOrder()->first();
        $book = Book::inRandomOrder()->first();

        while ($book->ratings()->where('user_id', $user->id)->exists()) {
            $user = User::inRandomOrder()->first();
            $book = Book::inRandomOrder()->first();
        }

        return [
            'user_id'=>$user->id,
            'book_id'=>$book->id,
            'rate'=>fake()->numberBetween(1,5),
        ];
    }


}
