<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Supply Chain Risk Intelligence</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #F5F5F0; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .register-card { background: #FFFFFF; border-radius: 20px; box-shadow: 0 8px 32px rgba(30,45,76,0.12); padding: 40px; width: 100%; max-width: 420px; }
        .form-control { background-color: #F0F0EB; border-color: #E8E8E3; color: #1E2D4C; border-radius: 10px; padding: 12px; }
        .form-control:focus { background-color: #F0F0EB; border-color: #ACBDAA; color: #1E2D4C; box-shadow: none; }
        .btn-register { background-color: #4A4A4A; color: #FFFFFF; border-radius: 10px; padding: 12px; font-weight: bold; width: 100%; }
        .btn-register:hover { background-color: #606060; color: #FFFFFF; }
        .brand-icon { color: #ACBDAA; font-size: 2.5rem; }
        label { color: #858585; font-size: 0.9rem; }
        a { color: #ACBDAA; }
        a:hover { color: #4A4A4A; }
        h4 { color: #4A4A4A; }
        p { color: #858585; }
    </style>
</head>
<body>
    <div class="register-card">
        <div class="text-center mb-4">
            <i class="bi bi-globe2 brand-icon"></i>
            <h4 class="mt-2">Supply Chain Risk</h4>
            <p class="mb-0" style="font-size: 0.9rem;">Intelligence Platform</p>
        </div>

        @if($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="mb-3">
                <label>Full Name</label>
                <input type="text" name="name" class="form-control mt-1"
                    value="{{ old('name') }}" placeholder="Enter your name" required>
            </div>
            <div class="mb-3">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control mt-1"
                    value="{{ old('email') }}" placeholder="Enter your email" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control mt-1"
                    placeholder="Min. 8 characters" required>
            </div>
            <div class="mb-4">
                <label>Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control mt-1"
                    placeholder="Repeat your password" required>
            </div>
            <button type="submit" class="btn btn-register">
                <i class="bi bi-person-plus me-2"></i> Register
            </button>
        </form>

        <div class="text-center mt-3">
            <small>Already have an account? <a href="{{ route('login') }}">Login here</a></small>
        </div>
    </div>
</body>
</html>