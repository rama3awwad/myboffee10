<?php

namespace Database\Seeders;

use App\Models\Book;
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
      //  \App\Models\User::factory(10)->create();
       // Book::factory(100)->create();



        $this->call([

            GendreSeeder::class,
            UserSeeder::class,
            TypeSeeder::class,
            BookSeeder::class,

        ]);
    }
}
