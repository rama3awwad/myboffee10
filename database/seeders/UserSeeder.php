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
     //  $existingUser = User::where('user_name','admin');
      if(!( User::where('user_name','admin'))){
       User::Create([
           'user_name' => 'admin',
            'email' => 'rama.3awwad11@gmail.com',
            'password' => bcrypt('admin11'),
            'my_points' => '999999999',
            'age' => '40',
            'gendre_id' => '1'
        ]);

         }}

}
