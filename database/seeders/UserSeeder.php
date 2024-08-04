<?php

namespace Database\Seeders;

use App\Models\Gendre;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $email = 'rama.3awwad112@gmail.com';

         $existingUser = User::where('email', $email)->first();

         if (!$existingUser) {

         User::Create([
             'user_name' => 'admin',
             'email' => 'rama.3awwad112@gmail.com',
             'password' => bcrypt('admin11'),
             'my_points' => '999999999',
             'age' => '40',
             'gendre_id' => '1',
             'role_id' => '1',
         ]);

         //    User::factory(20)->create();

         }}
}
