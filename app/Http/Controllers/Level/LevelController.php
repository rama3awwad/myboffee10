<?php

namespace App\Http\Controllers\Level;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\Shelf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LevelController extends Controller
{
    public function create(Request $request)
    {
        $userId = Auth::user()->id;
        $existing = Level::where('user_id', $userId)->first();

        if (!$existing) {
            $count = Shelf::where('user_id', $userId)->where('status', 'finished')->count();
            $newLevel = Level::create([
                'user_id' => $userId,
                'books' => $count,
                'level' => 'first',
            ]);

            return response()->json($newLevel,['message' => 'Level created successfully'], 200);
        } else {

            $count = Shelf::where('user_id', $userId)->where('status', 'finished')->count();

            if ($count < 10) {
                $existing->update([
                    'books' => $count,
                    'level' => 'first',
                ]);

            } elseif ($count >= 10 && $count < 20) {
                $existing->update([
                    'books' => $count,
                    'level' => 'second',
                ]);

            } elseif ($count >= 20) {
                $existing->update([
                    'books' => $count,
                    'level' => 'third',
                ]);
            }
            return response()->json($existing,['message' => 'Level updated successfully'], 200);
        }
    }
}
