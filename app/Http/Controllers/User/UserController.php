<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\BaseController as BaseController;
use App\Http\Requests\AuthRequest;
use App\Mail\SendCodeResetPassword;
use App\Models\Level;
use App\Models\ResetCodePassword;
use App\Models\User;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule as ValidationRule;
use Laravel\Sanctum\HasApiTokens;

class UserController extends BaseController
{
    use HasApiTokens, Notifiable;

    public function register(AuthRequest $request): JsonResponse
    {
        $user = User::create([
            'user_name' => $request->user_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'age' => (int) $request->age,
            'lang' => $request->lang ?? 'en',
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
            'lang' => $user->lang,
            'my_points' => $user->my_points,
            'gendre_id' => $user->gendre_id,
            'user_id' => $user->role_id,

        ];

        return $this->sendResponse($success, 'User sing up successfully');
    }

    public function login(Request $request): JsonResponse
    {
        if (Auth::attempt(['user_name' => $request->user_name, 'password' => $request->password])) {
            $user = Auth::user();
            $success = [
                'id' => $user->id,
                'user_name' => $user->user_name,
                'password' => $user->password,
                'token' => $user->createToken("API TOKEN")->plainTextToken,
            ];
            return $this->sendResponse($success, 'User logged in successfully');
        } else {
            return $this->sendError('Please check phone number or password', ['error' => 'Unauthorized']);
        }
    }

    public function updateUserLang(Request $request)
    {
        $validatedData = $request->validate([
            'lang' => ['required', ValidationRule::in(['ar', 'en'])],
        ]);
        $userId = auth()->user()->id;
        $user = auth()->user();
        if (is_null($userId)) {
            return $this->sendError('User not found');
        }
        $user->update(['lang' => $validatedData['lang']]);
        return $this->sendResponse($user, 'User language updated');
    }

    public function logout(): JsonResponse
    {
        Auth::user()->currentAccessToken()->delete();
        return $this->sendResponse(null, 'User logged out successfully');
    }

    public function userForgotPassword(Request $request): JsonResponse
    {

        $data = $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        // Delete all old code that user send before.
        ResetCodePassword::where('email', $request->email)->delete();

        // Generate random code
        $data['code'] = mt_rand(100000, 999999);

        // Create a new code
        $codeData = ResetCodePassword::create($data);

        // Send email to user
        Mail::to($request->email)->send(new SendCodeResetPassword($codeData->code));

        return $this->sendResponse(['message' => trans('code.sent')], 200);
    }

    public function userCheckCode(Request $request)
    {

        $request->validate([
            'code' => 'required|string|exists:reset_code_passwords',
        ]);

        // find the code
        $passwordReset = ResetCodePassword::firstWhere('code', $request->code);

        // check if it does not expired: the time is one hour
        if ($passwordReset->created_at > now()->addHour()) {
            $passwordReset->delete();
            return response(['message' => trans('passwords.code_is_expire')], 422);
        }

        return response([
            'code' => $passwordReset->code,
            'message' => trans('passwords.code_is_valid'),
        ], 200);
    }

    public function userResetPassword(Request $request)
    {
        $request->validate([
            'code' => 'required|string|exists:reset_code_passwords',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // find the code
        $passwordReset = ResetCodePassword::firstWhere('code', $request->code);

        if (!$passwordReset) {
            return response(['message' => trans('passwords.invalid_code')], 404);
        }

        // check if it is not expired: the time is one hour
        if ($passwordReset->created_at < now()->subHour()) {
            // Ensure passwordReset is not null before deleting
            if ($passwordReset) {
                $passwordReset->delete();
            }
            return response(['message' => trans('passwords.code_is_expire')], 422);
        }

        // find user's email
        $user = User::firstWhere('email', $passwordReset->email);

        if (!$user) {
            return response(['message' => trans('passwords.user_not_found')], 404);
        }

        // update user password
        $user->update(['password' => bcrypt($request->password)]);

        // delete the current code if exists
        if ($passwordReset) {
            $passwordReset->delete();
        }

        return response(['message' => 'Password has been successfully reset'], 200);
    }

    public function show()
    {
        $userId = Auth::user()->id;
        $user = User::findOrFail($userId);

        $gender = $user->gendre_id == 1 ? 'male' : ($user->gendre_id == 2 ? 'female' : 'unknown');

        $response = [
            'id' => $user->id,
            'user_name' => $user->user_name,
            'email' => $user->email,
            'my_points' => (int) $user->my_points,
            'age' => (int) $user->age,
            'lang' => $user->lang,
            'gender' => $gender,
            'gendre_id' => $user->gendre_id,
        ];

        return $this->sendResponse($response, 'User details retrieved successfully');
    }

    public function showUsers()
    {

        $count = (int) User::count();
        $users = User::get();

        return $this->sendResponse(
            ['Num of users:' => $count, 'Users:' => $users],
            'Users retrieved successfully'
        );
    }

    public function showAges()
    {
        $countAll = (int) User::count();

        $firstQuery = User::whereBetween('age', [0, 14]);
        $countFirst = $firstQuery->count();
        $first = $firstQuery->get();

        $secondQuery = User::whereBetween('age', [15, 20]);
        $countSecond = $secondQuery->count();
        $second = $secondQuery->get();

        $thirdQuery = User::where('age', '>', 20);
        $countThird = $thirdQuery->count();
        $third = $thirdQuery->get();

        return $this->sendResponse(
            [

                'Count of all users =' => $countAll,

                'Count of users less than 14 =' => $countFirst,
                'Users less than 14' => $first,

                'Count of users between 15 and 20 =' => $countSecond,
                'Users between 15 and 20' => $second,

                'Count of users grater than 20 =' => $countThird,
                'Users greater than 20' => $third,
            ],

            'Users retrieved successfully as their ages'
        );
    }
}
