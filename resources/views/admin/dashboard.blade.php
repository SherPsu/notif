<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - {{ config('app.name') }}</title>
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
        .notification-table {
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.1);
        }
        .unread-row {
            font-weight: bold;
            background-color: rgba(255, 193, 7, 0.1);
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
                        <a class="nav-link active" href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item position-relative">
                        <a class="nav-link" href="#" id="notifications-link">
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
        <h1 class="my-4">Admin Dashboard</h1>

        <div class="dashboard-stats">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="stats-card bg-primary text-white">
                        <div class="stats-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <h3>{{ $notifications->total() }}</h3>
                        <p class="mb-0">Total Notifications</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card bg-warning text-dark">
                        <div class="stats-icon">
                            <i class="fas fa-envelope-open"></i>
                        </div>
                        <h3>{{ $notifications->where('status', 'unread')->count() }}</h3>
                        <p class="mb-0">Unread Notifications</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card bg-success text-white">
                        <div class="stats-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3>{{ \App\Models\User::count() }}</h3>
                        <p class="mb-0">Registered Users</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card notification-table">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-bell me-2"></i>Recent Notifications
                </h5>
            </div>
            <div class="card-body p-0">
                @if($notifications->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Subject</th>
                                    <th>From</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($notifications as $notification)
                                    <tr class="{{ $notification->status === 'unread' ? 'unread-row' : '' }}">
                                        <td>{{ $notification->id }}</td>
                                        <td>{{ $notification->subject }}</td>
                                        <td>{{ $notification->user->name }}</td>
                                        <td>{{ $notification->created_at->format('M d, Y H:i') }}</td>
                                        <td>
                                            <span class="badge {{ $notification->status === 'read' ? 'bg-success' : 'bg-warning' }}">
                                                {{ $notification->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.notifications.show', $notification) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($notification->status === 'unread')
                                                <button class="btn btn-sm btn-success mark-read-btn" data-id="{{ $notification->id }}">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center p-3">
                        {{ $notifications->links() }}
                    </div>
                @else
                    <div class="alert alert-info m-3">
                        No notifications found.
                    </div>
                @endif
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
            $('.mark-read-btn').on('click', function() {
                const notificationId = $(this).data('id');
                const button = $(this);
                
                $.ajax({
                    url: `/admin/notifications/${notificationId}/read`,
                    type: "POST",
                    success: function() {
                        // Update UI
                        button.closest('tr').removeClass('unread-row');
                        button.closest('tr').find('.badge').removeClass('bg-warning').addClass('bg-success').text('read');
                        button.remove();
                        
                        // Update unread count
                        updateUnreadCount();
                    }
                });
            });
            
            // Initial load of unread count
            updateUnreadCount();
            
            // Poll for new notifications every 30 seconds
            setInterval(updateUnreadCount, 30000);
        });
    </script>
</body>
</html> 