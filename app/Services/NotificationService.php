<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Google\Client as GoogleClient;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Notification;
class NotificationService
{

    public function index()
    {
        return auth()->user()->notifications;
    }

    public function sendFcmNotification(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string',
            'body' => 'required|string',
        ]);

        $userId = $request->user_id;
        $title = $request->title;
        $body = $request->body;

        // Retrieve devices from the database
        $devices = \App\Models\UserDevice::where('user_id', $userId)->get();

        if ($devices->isEmpty()) {
            return response()->json(['message' => 'User does not have any device tokens'], 400);
        }

        $title = $request->title;
        $body = $request->body;

        $credentialsFilePath = Storage::path('app/firebase/boffee-7fa4c-firebase-adminsdk-xp4k4-5bf998dd8d.json');
        $client = new GoogleClient();
        $client->setAuthConfig($credentialsFilePath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->refreshTokenWithAssertion();
        $token = $client->getAccessToken();

        foreach ($devices as $device) {
            $data = [
                "message" => [
                    "token" => $device->fcm_token,
                    "notification" => [
                        "title" => $title,
                        "body" => $body,
                    ],
                ]
            ];

            // Send notification using cURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/v1/projects/boffee-7fa4c/messages:send");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_exec($ch);
            curl_close($ch);
        }

        // Store notification in the database
        Notification::create([
            'user_id' => $user->id,
            'title' => $title,
            'body' => $body,
        ]);

        return response()->json(['message' => 'Notifications have been sent']);
    }



    public function destroy($id): bool
    {
        $notification = auth()->user()->notifications()->findOrFail($id);

        if(isset($notification)) {
            $notification->delete();
            return true;
        }else return false;
    }

}
