<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Redirect root to login or dashboard
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    
    if (Auth::guard('admin')->check()) {
        return redirect()->route('admin.dashboard');
    }
    
    return redirect()->route('login');
});

// User Authentication Routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Authentication routes (already provided by Laravel)
Route::middleware('auth')->group(function () {
    // User routes
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/notifications', [UserController::class, 'notifications'])->name('notifications');
    
    // Notification routes
    Route::get('/notifications/create', [NotificationController::class, 'create'])->name('notifications.create');
    Route::post('/notifications', [NotificationController::class, 'store'])->name('notifications.store');
    Route::get('/my-notifications', [NotificationController::class, 'index'])->name('notifications.index');
});

// Admin routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Admin authentication
    Route::get('/login', function () {
        return view('admin.auth.login');
    })->name('login');
    
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    
    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // Admin notification routes
        Route::get('/notifications/{notification}', [AdminController::class, 'showNotification'])->name('notifications.show');
        Route::post('/notifications/{notification}/read', [AdminController::class, 'markAsRead'])->name('notifications.mark-read');
        Route::get('/notifications/unread-count', [AdminController::class, 'getUnreadCount'])->name('notifications.unread-count');
    });
});
