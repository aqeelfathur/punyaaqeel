<!-- resources/views/login.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | FitTrack</title>
    <link rel="stylesheet" href="{{ asset('css/styleslogin.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="background-blur"></div>
    
    <div class="login-container">
        <div class="logo-container">
            <div class="logo">
                <i class="fas fa-dumbbell"></i> FitTrack
            </div>
            <p class="tagline">Your Fitness Journey Starts Here</p>
        </div>
        
        <div class="form-container">
            <h2>Welcome Back!</h2>

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
                    <label for="username">
                        <i class="fas fa-user"></i> Username
                    </label>
                    <input type="text" id="username" name="username" placeholder="Enter your username" value="{{ old('username') }}">
                </div>
                
                <div class="input-group">
                    <label for="password">
                        <i class="fas fa-lock"></i> Password
                    </label>
                    <div class="password-input-container">
                        <input type="password" id="password" name="password" placeholder="Enter your password">
                        <span id="togglePassword" class="toggle-password">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                </div>
                
                <div class="forgot-password">
                    <a href="#" class="forgot-text">Forgot Password?</a>
                </div>
                
                <div class="button-container">
                    <button type="submit" class="login-btn">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </button>
                </div>
                
                <div class="signup-container">
                    <span>Don't have an account?</span>
                    <a href="/register" class="register-text">Sign Up</a>
                </div>
                
                <div class="back-container">
                    <a href="/" class="back-btn">
                        <i class="fas fa-arrow-left"></i> Back to Home
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    </script>
</body>
</html>