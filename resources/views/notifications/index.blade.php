<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Notifications - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .notification-list {
            margin-top: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            padding: 1rem;
        }
        .notification-item {
            border-left: 5px solid #ccc;
            transition: all 0.3s;
        }
        .notification-item:hover {
            background-color: #f8f9fa;
            transform: translateX(5px);
        }
        .notification-read {
            border-left-color: #28a745;
        }
        .notification-unread {
            border-left-color: #ffc107;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">{{ config('app.name') }}</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('notifications.create') }}">Send Notification</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('notifications.index') }}">My Notifications</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="d-flex justify-content-between align-items-center my-4">
            <h1>My Notifications</h1>
            <a href="{{ route('notifications.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i> New Notification
            </a>
        </div>

        <div class="notification-list">
            @if($notifications->count() > 0)
                <div class="list-group">
                    @foreach($notifications as $notification)
                        <div class="list-group-item list-group-item-action notification-item {{ $notification->status === 'read' ? 'notification-read' : 'notification-unread' }}">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">{{ $notification->subject }}</h5>
                                <small>{{ $notification->created_at->format('M d, Y H:i') }}</small>
                            </div>
                            <p class="mb-1">{{ Str::limit($notification->message, 150) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">Status: 
                                    <span class="badge {{ $notification->status === 'read' ? 'bg-success' : 'bg-warning' }}">
                                        {{ $notification->status }}
                                    </span>
                                </small>
                                @if($notification->is_emailed)
                                    <small class="text-success">
                                        <i class="fas fa-envelope-circle-check me-1"></i> 
                                        Emailed to Admin
                                    </small>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="d-flex justify-content-center mt-4">
                    {{ $notifications->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> You haven't sent any notifications yet.
                    <a href="{{ route('notifications.create') }}" class="alert-link">Create your first notification</a>
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 