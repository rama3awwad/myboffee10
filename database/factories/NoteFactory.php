<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Note>
 */
class NoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        $book = Book::all()->random();
        return [
            'user_id'=>User::all()->random()->id,
            'book_id'=>$book->id,
            'page_num'=>fake()->numberBetween(1,$book->total_pages),
            'body'=>fake()->text(200),
            'color'=>fake()->numberBetween(1,4),

        ];
    }

}
