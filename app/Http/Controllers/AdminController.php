<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard with notifications.
     */
    public function dashboard(): View
    {
        $notifications = Notification::with('user')
            ->latest()
            ->paginate(10);
        
        return view('admin.dashboard', compact('notifications'));
    }

    /**
     * Display a specific notification.
     */
    public function showNotification(Notification $notification): View
    {
        // Mark as read if unread
        if ($notification->status === 'unread') {
            $notification->markAsRead();
        }
        
        return view('admin.notifications.show', compact('notification'));
    }

    /**
     * Mark a notification as read via AJAX.
     */
    public function markAsRead(Notification $notification): JsonResponse
    {
        $notification->markAsRead();
        
        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read.',
        ]);
    }

    /**
     * Get unread notifications count for navbar badge.
     */
    public function getUnreadCount(): JsonResponse
    {
        $count = Notification::where('status', 'unread')->count();
        
        return response()->json([
            'count' => $count,
        ]);
    }
}
