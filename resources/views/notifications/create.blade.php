<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Notification - {{ config('app.name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .notification-form {
            max-width: 600px;
            margin: 2rem auto;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .form-header {
            margin-bottom: 1.5rem;
            text-align: center;
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
                        <a class="nav-link active" href="{{ route('notifications.create') }}">Send Notification</a>
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
        <div class="notification-form">
            <div class="form-header">
                <h2>Send Notification to Admin</h2>
                <p class="text-muted">Fill out the form below to send a notification to the admin</p>
            </div>

            <div id="notification-alert" class="alert" style="display: none;"></div>

            <form id="notification-form">
                <div class="mb-3">
                    <label for="subject" class="form-label">Subject</label>
                    <input type="text" class="form-control" id="subject" name="subject" required>
                    <div class="invalid-feedback" id="subject-error"></div>
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">Message</label>
                    <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                    <div class="invalid-feedback" id="message-error"></div>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary" id="submit-btn">
                        <span id="spinner" class="spinner-border spinner-border-sm me-2" style="display: none;"></span>
                        Send Notification
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#notification-form').on('submit', function(e) {
                e.preventDefault();
                
                // Reset errors
                $('.is-invalid').removeClass('is-invalid');
                
                // Show loading state
                $('#spinner').show();
                $('#submit-btn').prop('disabled', true);
                
                $.ajax({
                    url: "{{ route('notifications.store') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        subject: $('#subject').val(),
                        message: $('#message').val()
                    },
                    success: function(response) {
                        // Show success message
                        $('#notification-alert')
                            .removeClass('alert-danger')
                            .addClass('alert-success')
                            .text(response.message)
                            .show();
                        
                        // Reset form
                        $('#notification-form')[0].reset();
                    },
                    error: function(xhr) {
                        // Show error message
                        $('#notification-alert')
                            .removeClass('alert-success')
                            .addClass('alert-danger')
                            .text('Error sending notification. Please try again.')
                            .show();
                        
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            // Show field errors
                            const errors = xhr.responseJSON.errors;
                            
                            if (errors.subject) {
                                $('#subject').addClass('is-invalid');
                                $('#subject-error').text(errors.subject[0]);
                            }
                            
                            if (errors.message) {
                                $('#message').addClass('is-invalid');
                                $('#message-error').text(errors.message[0]);
                            }
                        }
                    },
                    complete: function() {
                        // Hide loading state
                        $('#spinner').hide();
                        $('#submit-btn').prop('disabled', false);
                    }
                });
            });
        });
    </script>
</body>
</html> 