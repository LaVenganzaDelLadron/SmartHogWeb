<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(): View
    {
        $notifications = Notification::query()
            ->orderByDesc('recorded_date')
            ->get();

        $newNotificationsCount = (int) Notification::query()
            ->where('status', 'new')
            ->count();

        return view('notifications.index', [
            'notifications' => $notifications,
            'newNotificationsCount' => $newNotificationsCount,
        ]);
    }

    public function list(): JsonResponse
    {
        $notifications = Notification::query()
            ->orderByDesc('recorded_date')
            ->get();

        return response()->json([
            'ok' => true,
            'notifications' => $notifications,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'nullable|string|in:new,read',
            'type' => 'required|string|in:system,feeding,pig_health,admin',
            'description' => 'required|string',
            'recorded_date' => 'nullable|date',
        ]);

        $notification = Notification::query()->create([
            'title' => $validated['title'],
            'status' => $validated['status'] ?? 'new',
            'type' => $validated['type'],
            'description' => $validated['description'],
            'recorded_date' => $validated['recorded_date'] ?? now(),
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Notification received successfully.',
            'notification' => $notification,
        ]);
    }

    public function markAsRead(Notification $notification): JsonResponse
    {
        if ($notification->status !== 'read') {
            $notification->status = 'read';
            $notification->save();
        }

        return response()->json([
            'ok' => true,
            'message' => 'Notification marked as read.',
            'notification' => $notification,
        ]);
    }
}
