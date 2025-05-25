<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            max-width: 450px;
            margin: 100px auto;
        }
        .login-card {
            border-radius: 10px;
            box-shadow: 0 0 25px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .login-header {
            background-color: #343a40;
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .login-body {
            padding: 2rem;
        }
        .login-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        .user-admin-toggle {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="login-card">
                <div class="login-header">
                    <div class="login-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h2>Admin Login</h2>
                    <p class="mb-0">{{ config('app.name') }}</p>
                </div>
                <div class="login-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.login') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                        </div>
                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i> Login
                            </button>
                        </div>
                    </form>
                    
                    <div class="user-admin-toggle">
                        <p>Are you a regular user? <a href="{{ route('login') }}">Login here</a></p>
                    </div>
                    
                    <div class="mt-3 text-center">
                        <p class="text-secondary">Demo Credentials:</p>
                        <p><strong>Email:</strong> admin@example.com<br><strong>Password:</strong> password</p>
                    </div>
                </div>
            </div>
            <div class="text-center mt-3">
                <a href="/" class="text-decoration-none">
                    <i class="fas fa-arrow-left me-1"></i> Back to Main Site
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>