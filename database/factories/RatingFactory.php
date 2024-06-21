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
        // Retrieve a random user and book instance
        $user = User::all()->random();
        $book = Book::all()->random();

        // Check if the user-book combination already exists in the pivot table
        while ($book->ratings()->wherePivot('user_id', $user->id)->exists()) {
            $user = User::all()->random();
            $book = Book::all()->random();
        }

        // Generate a random rating
        $rate = rand(1, 5);

        return [
            'user_id'=>$user->id,
            'book_id'=>$book->id,
            'rate'=>$rate
        ];
    }

}
