<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Supply Chain Risk Intelligence</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #F5F5F0; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .login-card { background: #FFFFFF; border-radius: 20px; box-shadow: 0 8px 32px rgba(30,45,76,0.12); padding: 40px; width: 100%; max-width: 420px; }
        .login-title { color: #4A4A4A; font-weight: bold; }
        .login-subtitle { color: #858585; font-size: 0.9rem; }
        .form-control { background-color: #F0F0EB; border-color: #E8E8E3; color: #1E2D4C; border-radius: 10px; padding: 12px; }
        .form-control:focus { background-color: #F0F0EB; border-color: #ACBDAA; color: #1E2D4C; box-shadow: none; }
        .btn-login { background-color: #4A4A4A; color: #FFFFFF; border-radius: 10px; padding: 12px; font-weight: bold; width: 100%; }
        .btn-login:hover { background-color: #606060; color: #FFFFFF; }
        .brand-icon { color: #ACBDAA; font-size: 2.5rem; }
        label { color: #858585; font-size: 0.9rem; }
        a { color: #ACBDAA; }
        a:hover { color: #4A4A4A; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="text-center mb-4">
            <i class="bi bi-globe2 brand-icon"></i>
            <h4 class="login-title mt-2">Supply Chain Risk</h4>
            <p class="login-subtitle">Intelligence Platform</p>
        </div>

        @if($errors->any())
        <div class="alert alert-danger" role="alert">
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control mt-1" 
                    value="{{ old('email') }}" placeholder="Enter your email" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control mt-1" 
                    placeholder="Enter your password" required>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                <label class="form-check-label" for="remember" style="color: #858585;">Remember Me</label>
            </div>
            <button type="submit" class="btn btn-login">
                <i class="bi bi-box-arrow-in-right me-2"></i> Login
            </button>
        </form>

        <div class="text-center mt-3">
            <small>Don't have an account? <a href="{{ route('register') }}">Register here</a></small>
        </div>
    </div>
</body>
</html>