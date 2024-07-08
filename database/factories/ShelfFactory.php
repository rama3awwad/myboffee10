<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shelf>
 */
class ShelfFactory extends Factory
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
        while($book->shelves()->where('user_id',$user->id)->exists()){
            $user = User::inRandomOrder()->first();
            $book = Book::inRandomOrder()->first();
        }

        $status = fake()->randomElement(['reading','finished','read_later']);

        $progress = 0;
        if ($status == 'finished'){
            $progress = $book->total_pages;
        }
        if ($status == 'reading'){
            $progress = fake()->numberBetween(1,$book->total_pages-1);
        }
        if($status == 'read_later'){
            $progress = 0;
        }
        return [
            'user_id'=>$user->id,
            'book_id'=>$book->id,
            'status'=>$status,
            'progress'=>$progress,
        ];
    }

}
