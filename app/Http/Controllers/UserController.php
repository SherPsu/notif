<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display the user dashboard.
     */
    public function dashboard(): View
    {
        $notifications = auth()->user()->notifications()->latest()->paginate(10);
        
        return view('user.dashboard', compact('notifications'));
    }

    /**
     * Display the notification history.
     */
    public function notifications(): View
    {
        $notifications = auth()->user()->notifications()->latest()->paginate(10);
        
        return view('user.notifications', compact('notifications'));
    }
}
