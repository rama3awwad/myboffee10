<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Level;
use App\Models\Rating;
use App\Models\Shelf;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;



class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(22)->create();
        Book::factory(30)->create();
        Shelf::factory(1000)->create();
        Rating::factory(50)->create();
        Level::factory(20)->create();



        $this->call([

            GendreSeeder::class,
            UserSeeder::class,
            TypeSeeder::class,
            BookSeeder::class,
            ShelfSeeder::class,
            RatingSeeder::class,
            LevelSeeder::class,

        ]);

    }
}
