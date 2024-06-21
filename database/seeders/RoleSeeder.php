<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['name' => 'Admin'],

        ];

        foreach ($roles as $role) {

            $existing = Role::where('name', $role['name'])->first();

            if ($existing) {
                continue;
            }

            Role::create($role);
}}}
