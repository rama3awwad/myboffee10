<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

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
        $user = User::doesntHave('level')->inRandomOrder()->first();

        if (!$user) {
            return [];
        }

        $countFinish = DB::table('shelves')
            ->where('user_id', $user->id)
            ->where('status', 'finished')
            ->count();

        echo "User ID: {$user->id}, Finished Shelves Count: {$countFinish}\n";

        $level = 'first';
        if ($countFinish >= 10 && $countFinish < 20) {
            $level = 'second';
        } elseif ($countFinish >= 20) {
            $level = 'third';
        }

        return [
            'user_id' => $user->id,
            'books' => $countFinish,
            'level' => $level,
        ];
    }
}
