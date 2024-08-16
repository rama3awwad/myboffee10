<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserDevice;

class DeviceController extends Controller
{
    // Update or add a new device token
 /*   public function updateDeviceToken(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'fcm_token' => 'required|string',
        ]);

        $user = User::find($request->user_id);

        // Check if the FCM token already exists
        $existingDevice = $user->devices()->where('fcm_token', $request->fcm_token)->first();

        if ($existingDevice) {

        } else {
            $user->devices()->create([
                'fcm_token' => $request->fcm_token,
            ]);
        }

        return response()->json(['message' => 'Device token updated successfully']);
    }*/

    // Send notifications to all devices of a user
    public function sendNotification(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string',
            'message' => 'required|string',
        ]);

        $user = User::find($request->user_id);

        foreach ($user->devices as $device) {
            $this->sendFcmNotification($device->fcm_token, $request->title, $request->message);
        }

        return response()->json(['message' => 'Notifications sent successfully']);
    }

    protected function sendFcmNotification($fcmToken, $title, $message)
    {
        $serviceAccountPath = storage_path('app/firebase/boffee-7fa4c-firebase-adminsdk-xp4k4-5bf998dd8d.json');
        $factory = (new \Kreait\Firebase\Factory)->withServiceAccount($serviceAccountPath);
        $messaging = $factory->createMessaging();

        $notification = [
            'title' => $title,
            'body' => $message,
        ];

        $cloudMessage = \Kreait\Firebase\Messaging\CloudMessage::withTarget('token', $fcmToken)
            ->withNotification($notification);

        try {
            $messaging->send($cloudMessage);
        } catch (\Kreait\Firebase\Exception\MessagingException $e) {
            \Log::error($e->getMessage());
        } catch (\Kreait\Firebase\Exception\FirebaseException $e) {
            \Log::error($e->getMessage());
        }
    }


}
