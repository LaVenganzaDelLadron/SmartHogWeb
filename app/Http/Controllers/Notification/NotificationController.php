<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(): View
    {
        return view('notifications.index', [
            'notifications' => collect(),
            'newNotificationsCount' => 0,
        ]);
    }

    public function list(): JsonResponse
    {
        return response()->json([
            'ok' => true,
            'notifications' => [],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $notification = [
            'id' => 'NTF-'.strtoupper(substr(sha1((string) now()), 0, 8)),
            'title' => (string) $request->input('title', 'Notification'),
            'status' => (string) $request->input('status', 'new'),
            'type' => (string) $request->input('type', 'system'),
            'description' => (string) $request->input('description', ''),
            'recorded_date' => (string) $request->input('recorded_date', now()->toDateTimeString()),
        ];

        return response()->json([
            'ok' => true,
            'message' => 'Frontend-only notification accepted.',
            'notification' => $notification,
        ]);
    }

    public function markAsRead(string $notification): JsonResponse
    {
        return response()->json([
            'ok' => true,
            'message' => 'Frontend-only notification marked as read.',
            'notification' => [
                'id' => $notification,
                'status' => 'read',
            ],
        ]);
    }
}
