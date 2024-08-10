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
            ['name' => 'Children'],
            ['name' => 'Scientific'],
            ['name' => 'Horror'],
            ['name' => 'Human Development'],
        ];

        foreach ($types as $type) {

            $existingType = Type::where('name', $type['name'])->first();

            if ($existingType) {
                continue;
            }
            Type::create($type);
        }


    }}
