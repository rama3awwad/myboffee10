<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            ['name' => 'Novels'],
            ['name' => 'Islamic'],
            ['name' => 'Chlidren'],
            ['name' => 'Scientific'],
            ['name' => 'Horror'],
            ['name' => 'Human Development'],
        ];

        foreach ($types as $type) {
            Type::create($type);
        }
    }


}
