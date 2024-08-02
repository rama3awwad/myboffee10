<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\FavoriteBook;
use App\Models\Level;
use App\Models\Note;
use App\Models\Post;
use App\Models\Rating;
use App\Models\Report;
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
        Shelf::factory(10)->create();
        Rating::factory(50)->create();
        Level::factory(20)->create();
        FavoriteBook::factory(50)->create();
        Note::factory(70)->create();
        Report::factory(60)->create();
        Post::factory(20)->create();



        $this->call([

            GendreSeeder::class,
            UserSeeder::class,
            TypeSeeder::class,
            BookSeeder::class,
            ShelfSeeder::class,
            RatingSeeder::class,
            LevelSeeder::class,
            FavoriteSeeder::class,
            NoteSeeder::class,
            ReportSeeder::class,
            PostSeeder::class,

        ]);

    }
}
