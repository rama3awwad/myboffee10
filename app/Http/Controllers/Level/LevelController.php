<?php

namespace App\Http\Controllers\Level;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\LevelResource;
use App\Models\Level;
use App\Models\Shelf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LevelController extends BaseController
{
    public function create(Request $request)
    {
        $userId = Auth::user()->id;
        $existing = Level::where('user_id', $userId)->first();
        $count = Shelf::where('user_id', $userId)->where('status', 'finished')->count();
        $ratio = 0;
        $ratio = round(($count / 30) * 100, 2);
        if ($count > 30 ) {
            $ratio = 1;
        } /*elseif ($count >= 10 && $count < 20) {
            $ratio = (100 * ($count - 10)) / 10;
        } elseif ($count >= 20 && $count <=30) {
            $ratio = (100 * ($count - 20)) / 10;
        } elseif ($count > 30) {
            $ratio = 100;
        }*/

        if (!$existing) {
            $newLevel = Level::create([
                'user_id' => $userId,
                'books' => $count,
                'level' => 'first',
            ]);

            return $this->sendResponse([
                'level' => new LevelResource($newLevel),
                'ratio' => $ratio. '%',
            ], 'Level created successfully');
        }
        else {
            $level = 'first';
            if ($count >= 10 && $count < 20) {
                $level = 'second';
            } elseif ($count >= 20) {
                $level = 'third';
            }

            $existing->update([
                'books' => $count,
                'level' => $level,
            ]);

            return $this->sendResponse([
                'level' => new LevelResource($existing),
                'ratio' => $ratio. '%',
            ], 'Level updated successfully');
        }
    }}

