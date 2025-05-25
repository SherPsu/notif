<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>View Notification - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .notification-badge {
            position: absolute;
            top: 0;
            right: 0;
            font-size: 0.7rem;
            transform: translate(50%, -50%);
        }
        .notification-card {
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-top: 2rem;
        }
        .notification-header {
            padding: 1.5rem;
            border-bottom: 1px solid #eee;
        }
        .notification-body {
            padding: 2rem;
            min-height: 300px;
        }
        .notification-footer {
            padding: 1rem 1.5rem;
            background-color: #f8f9fa;
            border-top: 1px solid #eee;
        }
        .notification-meta {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-shield-alt me-2"></i>
                Admin Panel
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item position-relative">
                        <a class="nav-link active" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-bell me-1"></i> Notifications
                            <span class="badge bg-danger notification-badge" id="notification-badge"></span>
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link">
                                <i class="fas fa-sign-out-alt me-1"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="my-4">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
            </a>
        </div>

        <div class="notification-card">
            <div class="notification-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">{{ $notification->subject }}</h2>
                    <span class="badge {{ $notification->status === 'read' ? 'bg-success' : 'bg-warning' }} fs-6">
                        {{ $notification->status }}
                    </span>
                </div>
                <div class="notification-meta">
                    <div>
                        <strong>From:</strong> {{ $notification->user->name }} ({{ $notification->user->email }})
                    </div>
                    <div>
                        <strong>Received:</strong> {{ $notification->created_at->format('M d, Y H:i') }}
                    </div>
                </div>
            </div>
            <div class="notification-body">
                <div class="notification-content">
                    {{ $notification->message }}
                </div>
            </div>
            <div class="notification-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        @if($notification->is_emailed)
                            <span class="text-success">
                                <i class="fas fa-envelope-circle-check me-1"></i> 
                                Email notification sent
                            </span>
                        @endif
                    </div>
                    <div>
                        @if($notification->status === 'unread')
                            <button id="mark-read-btn" class="btn btn-success" data-id="{{ $notification->id }}">
                                <i class="fas fa-check me-2"></i> Mark as Read
                            </button>
                        @else
                            <span class="text-success">
                                <i class="fas fa-check-circle me-1"></i>
                                Read on {{ $notification->read_at->format('M d, Y H:i') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function() {
            // Set CSRF token for all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            // Update unread count badge
            function updateUnreadCount() {
                $.ajax({
                    url: "{{ route('admin.notifications.unread-count') }}",
                    type: "GET",
                    success: function(response) {
                        if (response.count > 0) {
                            $('#notification-badge').text(response.count).show();
                        } else {
                            $('#notification-badge').hide();
                        }
                    }
                });
            }
            
            // Mark notification as read
            $('#mark-read-btn').on('click', function() {
                const notificationId = $(this).data('id');
                const button = $(this);
                
                $.ajax({
                    url: `/admin/notifications/${notificationId}/read`,
                    type: "POST",
                    success: function() {
                        // Update UI to show read status
                        $('.badge').removeClass('bg-warning').addClass('bg-success').text('read');
                        
                        // Replace button with read timestamp
                        const now = new Date();
                        const formattedDate = now.toLocaleString('en-US', { 
                            month: 'short', 
                            day: 'numeric', 
                            year: 'numeric',
                            hour: 'numeric',
                            minute: 'numeric'
                        });
                        
                        button.replaceWith(`
                            <span class="text-success">
                                <i class="fas fa-check-circle me-1"></i>
                                Read on ${formattedDate}
                            </span>
                        `);
                        
                        // Update unread count
                        updateUnreadCount();
                    }
                });
            });
            
            // Initial load of unread count
            updateUnreadCount();
        });
    </script>
</body>
</html> 