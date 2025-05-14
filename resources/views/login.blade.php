<!-- resources/views/login.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/styleslogin.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="background-blur"></div>
    <div class="login-container">
        <h2>Login to FitTrack</h2>

        <!-- Menampilkan error jika ada -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="loginForm" method="POST" action="{{ url('/login') }}">
            @csrf <!-- Pastikan untuk menambahkan token CSRF untuk keamanan -->
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" value="{{ old('username') }}">
            </div>
            
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password">
            </div>
            
            <div>
                <span>Don't have an account?</span>
                <a href="/register" class="register-text">Sign Up</a>
            </div>

            <div class="button-group">
                <button type="submit" class="login-btn">Login</button>
                <a href="/" class="back-btn">Back</a>
            </div>
        </form>
    </div>
</body>
</html>
