<?php

namespace Database\Seeders;

use App\Models\Gendre;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GendreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Gendre::Create([
            'name' => 'man',
            ]);

        Gendre::Create([
            'name' => 'woman',
            ]);
    }
}
