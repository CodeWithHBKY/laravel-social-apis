<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function markNotificationComplete(Request $request, $id)
    {
        $user = $request->user();
        $notification = $user->notifications()->where("id", $id)->first();
        if ($notification) {
            // $notification->update(['read_at' => now()]);
            $notification->delete();
        }

        return response()->json([
            "message"=> "success",
        ]);
    }

    public function markAllNotificationComplete(Request $request)
    {
        $user = $request->user();
        $user->unreadNotifications->markAsRead();

        return response()->json([
            "message"=> "success",
        ]);
    }
}
