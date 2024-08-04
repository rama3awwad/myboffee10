<?php

namespace App\Http\Controllers\Level;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\LevelResource;
use App\Models\Level;
use App\Models\Shelf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LevelController extends BaseController
{
    public function show(Request $request)
    {
        $userId = Auth::user()->id;
        $level = Level::where('user_id', $userId)->first();

        $count = $level->books;
        $ratio = 0.0;
        $ratio = round(($count / 20), 2); //* 100, 2);

        if ($count == 0)
            $image = '/levels/zero.png';
        if ($count > 0 && $count < 3)
            $image = '/levels/one.png';
        if ($count == 3 && $count < 5)
            $image = '/levels/two.png';
        if ($count == 5 && $count < 7)
            $image = '/levels/three.png';
        if ($count == 5 && $count < 7)
            $image = '/levels/four.png';
        if ($count == 7 && $count < 9)
            $image = '/levels/five.png';
        if ($count == 9 && $count < 11)
            $image = '/levels/six.png';
        if ($count == 11 && $count < 13)
            $image = '/levels/seven.png';
        if ($count == 13 && $count < 15)
            $image = '/levels/eight.png';
        if ($count == 15 && $count < 17)
            $image = '/levels/nine.png';
        if ($count == 17 && $count < 19)
            $image = '/levels/ten.png';
        if ($count >= 20) {
            $image = '/levels/final.png';
            $ratio = 1;
        }

        /*elseif ($count >= 10 && $count < 20) {
            $ratio = (100 * ($count - 10)) / 10;
        } elseif ($count >= 20 && $count <=30) {
            $ratio = (100 * ($count - 20)) / 10;
        } elseif ($count > 30) {
            $ratio = 100;
        }*/

            return $this->sendResponse([
                'level' => new LevelResource($level),
                'ratio' => $ratio,//. '%',
                'image' => $image,
            ], 'Level updated successfully');
        }



    public function getUsersByLevel($level, Request $request)
    {
        $countAll = Level::count();
        $numberOfUsers = Level::where('level', $level)->count();

        $usersDetails = DB::table('levels')
            ->join('users', 'levels.user_id', '=', 'users.id')
            ->select('users.id as user_id', 'users.user_name', 'levels.books as book_value')
            ->where('levels.level', $level)
            ->get();

        $response = [];
        foreach ($usersDetails as $user) {
            $response[] = [
                'user_id' => $user->user_id,
                'user_name' => $user->user_name,
                'book_value' => $user->book_value,
            ];
        }
        return response()->json([
            'number_of_users' => $numberOfUsers,
            'users' => $response
        ]);
    }

    public function countlevelusers(Request $request){

        $countAll = Level::count();

        $countFirst = Level::where('level', 'first')->count();
        $countSecond = Level::where('level', 'second')->count();
        $countThird = Level::where('level', 'third')->count();

        if ($countAll > 0) {
            $ratioFirst = round($countFirst / $countAll, 2);
            $ratioSecond = round($countSecond / $countAll, 2);
            $ratioThird = round($countThird / $countAll, 2);
        } else {
            $ratioFirst = round(0, 2);
            $ratioSecond = round(0, 2);
            $ratioThird = round(0, 2);
        }

        return response()->json([
            'counts' => [
                'count_all' => $countAll,
                'count_first' => $countFirst,
                'count_second' => $countSecond,
                'count_third' => $countThird
            ],
            'ratios' => [
                'ratio_first' => $ratioFirst,
                'ratio_second' => $ratioSecond,
                'ratio_third' => $ratioThird
            ]
        ]);
    }
}

