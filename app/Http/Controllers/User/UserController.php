<?php

namespace App\Http\Controllers\User;
use App\Http\Requests\AuthRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Level;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class UserController extends BaseController
{    use HasApiTokens, Notifiable;

    public function register(AuthRequest $request):JsonResponse
    {
        $user = User::create([
        'user_name' => $request->user_name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'age' => (int) $request->age,
        'my_points' => 40,
        'image' => $request->image,
        'gendre_id' => (int) $request->gendre_id,
         'role_id' => $request->role_id,
    ]);

        $newLevel = Level::create([
            'user_id' => $user->id,
            'books' => 0,
            'level' => 'first',
        ]);

    $token = $user->createToken("API TOKEN")->plainTextToken;

    $success = [
        'id' => $user->id,
        'user_name' => $user->user_name,
        'age' => (int) $user->age,
        'email' => $user->email,
        'token' => $token,
        'my_points' => $user->my_points,
        'gendre_id' => $user->gendre_id,
        'user_id' => $user->role_id,

    ];

        return $this->sendResponse($success,'User sing up successfully');
    }

    public function login(Request $request):JsonResponse{
        if(Auth::attempt(['user_name'=> $request->user_name ,'password' => $request->password]))
        {
            $user =Auth::user();
            $success = [
                'id' => $user->id,
                'user_name' => $user->user_name,
                'password' => $user->password,
                'token' => $user->createToken("API TOKEN")->plainTextToken,
            ];
            return $this->sendResponse($success,'User logged in successfully');
        }
        else{
            return $this->sendError('Please check phone number or password',['error'=>'Unauthorized']);
        }
    }

    public function logout() :JsonResponse {
        Auth::user()->currentAccessToken()->delete();
        return $this->sendResponse(null,'User logged out successfully');
    }

    public function show(){
        $userId = Auth::user()->id;
        $user = User::findOrFail($userId);

        $gender = $user->gendre_id == 1 ? 'male' : ($user->gendre_id == 2 ? 'female' : 'unknown');

        $response = [
            'id' => $user->id,
            'user_name' => $user->user_name,
            'email' => $user->email,
            'my_points' => (int) $user->my_points,
            'age' => (int) $user->age,
            'gender' => $gender,
            'gendre_id' => $user->gendre_id,
        ];

        return $this->sendResponse($response, 'User details retrieved successfully');
    }

    public function countUsers() {

        $countAll = User::count();
        $count = $countAll-1;

    }

    public function showAges() {



    }
}
