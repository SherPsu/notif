<?php

namespace App\Http\Controllers;

use App\Mail\NotificationMail;
use App\Models\AdminUser;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Display a form to create a new notification.
     */
    public function create(): View
    {
        return view('notifications.create');
    }

    /**
     * Store a newly created notification.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $notification = Notification::create([
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'user_id' => auth()->id(),
            'status' => 'unread',
        ]);

        // Send email to admin
        $admins = AdminUser::all();
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new NotificationMail($notification));
        }

        // Mark as emailed
        $notification->update(['is_emailed' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Notification sent successfully!',
        ]);
    }

    /**
     * Display the notifications for the authenticated user.
     */
    public function index(): View
    {
        $notifications = auth()->user()->notifications()->latest()->paginate(10);
        
        return view('notifications.index', compact('notifications'));
    }
}
