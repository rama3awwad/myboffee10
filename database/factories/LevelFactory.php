<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Level>
 */
class LevelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $user = User::inRandomOrder()->first();
        $countfinish = $user->shelves()->where('status', 'reading')->count();

        $level = match(true) {
            $countfinish < 10 => 'first',
            $countfinish >= 10 && $countfinish < 20 => 'second',
            default => 'third',
        };

        return [
            'user_id' => $user->id,
            'books' => rand(1, 32),
            'level' => $level,
        ];
    }
    }

