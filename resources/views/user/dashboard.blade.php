<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .dashboard-stats {
            margin: 2rem 0;
        }
        .stats-card {
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            padding: 1.5rem;
            transition: transform 0.3s;
        }
        .stats-card:hover {
            transform: translateY(-5px);
        }
        .stats-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        .notification-list {
            margin-top: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            padding: 1rem;
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
                        <a class="nav-link active" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('notifications.create') }}">Send Notification</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('notifications.index') }}">My Notifications</a>
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
        <h1 class="my-4">Welcome, {{ auth()->user()->name }}</h1>

        <div class="dashboard-stats">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="stats-card bg-primary text-white">
                        <div class="stats-icon">
                            <i class="fas fa-paper-plane"></i>
                        </div>
                        <h3>{{ $notifications->total() }}</h3>
                        <p class="mb-0">Total Notifications</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card bg-success text-white">
                        <div class="stats-icon">
                            <i class="fas fa-envelope-open-text"></i>
                        </div>
                        <h3>{{ $notifications->where('status', 'read')->count() }}</h3>
                        <p class="mb-0">Read Notifications</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('notifications.create') }}" class="text-decoration-none">
                        <div class="stats-card bg-info text-white">
                            <div class="stats-icon">
                                <i class="fas fa-plus-circle"></i>
                            </div>
                            <h3>New</h3>
                            <p class="mb-0">Send Notification</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div class="notification-list">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Recent Notifications</h2>
                <a href="{{ route('notifications.index') }}" class="btn btn-outline-primary">View All</a>
            </div>

            @if($notifications->count() > 0)
                <div class="list-group">
                    @foreach($notifications->take(5) as $notification)
                        <div class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">{{ $notification->subject }}</h5>
                                <small>{{ $notification->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-1">{{ Str::limit($notification->message, 100) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">Status: 
                                    <span class="badge {{ $notification->status === 'read' ? 'bg-success' : 'bg-warning' }}">
                                        {{ $notification->status }}
                                    </span>
                                </small>
                                @if($notification->is_emailed)
                                    <small class="text-success"><i class="fas fa-envelope-circle-check"></i> Emailed</small>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info">
                    You haven't sent any notifications yet. <a href="{{ route('notifications.create') }}">Create one now</a>.
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 