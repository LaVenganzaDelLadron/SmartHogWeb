<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class NotificationController extends Controller
{

    public function sendNotification(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|string|in:info,warning,error',
        ]);

        // Here you would typically send the notification using a service or package
        // For demonstration, we'll just return a success response

        return response()->json([
            'ok' => true,
            'message' => 'Notification sent successfully.',
            'data' => $validated,
        ]);
    }

}