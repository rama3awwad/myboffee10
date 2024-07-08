<?php

namespace Database\Seeders;

use App\Models\Shelf;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShelfSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $shelves = [
            [
                'user_id' => '9',
                'book_id'=> 1,
                'status' => 'finished',
                'progress' => 4
            ],
            [
                'user_id' => '9',
                'book_id'=> 2,
                'status' => 'finished',
                'progress' => 4
            ],
            [
                'user_id' => '9',
                'book_id'=> 3,
                'status' => 'finished',
                'progress' => 4
            ],
            [
                'user_id' => '9',
                'book_id'=> 4,
                'status' => 'finished',
                'progress' => 4
            ],
            [
                'user_id' => '9',
                'book_id'=> 5,
                'status' => 'finished',
                'progress' => 4
            ],
            [
                'user_id' => '9',
                'book_id'=> 6,
                'status' => 'finished',
                'progress' => 4
            ],
            [
                'user_id' => '9',
                'book_id'=> 7,
                'status' => 'finished',
                'progress' => 4
            ],
            [
                'user_id' => '9',
                'book_id'=> 8,
                'status' => 'finished',
                'progress' => 4
            ],
            [
                'user_id' => '9',
                'book_id'=> 9,
                'status' => 'finished',
                'progress' => 4
            ],
            [
                'user_id' => '9',
                'book_id'=> 10,
                'status' => 'finished',
                'progress' => 4
            ],
            [
                'user_id' => '9',
                'book_id'=> 11,
                'status' => 'finished',
                'progress' => 4
            ],
        ];

        foreach ($shelves as $shelf) {

            $existing = Shelf::where('user_id', $shelf['user_id'])->where('book_id',$shelf['book_id'])->first();

            if ($existing) {
                continue;
            }

            Shelf::create($shelf);
        }
    }
}
