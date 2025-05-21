<!-- resources/views/register.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | FitTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styleslogin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

</head>
<body class="register-body">
    <div class="background-blur"></div>
    
    <div class="login-container register-container">
        <div class="logo-container">
            <div class="logo">
                <i class="fas fa-dumbbell"></i> FitTrack
            </div>
            <p class="tagline">Begin Your Fitness Journey</p>
        </div>
        
        <div class="form-container">
            <h2>Create Account</h2>
            
            <form action="{{ route('register') }}" method="POST">
                @csrf
                
                <div class="input-group">
                    <label for="username">
                        <i class="fas fa-user"></i> Username
                    </label>
                    <input type="text" name="username" id="username" placeholder="Choose a username" value="{{ old('username') }}">
                    @error('username') <small class="error-message">{{ $message }}</small> @enderror
                </div>
                
                <div class="input-group">
                    <label for="email">
                        <i class="fas fa-envelope"></i> Email
                    </label>
                    <input type="email" name="email" id="email" placeholder="Your email address" value="{{ old('email') }}">
                    @error('email') <small class="error-message">{{ $message }}</small> @enderror
                </div>
                
                <div class="input-group">
                    <label for="password">
                        <i class="fas fa-lock"></i> Password
                    </label>
                    <div class="password-input-container">
                        <input type="password" name="password" id="password" placeholder="Create a strong password">
                        <span id="togglePassword" class="toggle-password">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                    @error('password') <small class="error-message">{{ $message }}</small> @enderror
                </div>
                
                <div class="input-group">
                    <label for="phonenumber">
                        <i class="fas fa-phone"></i> Phone Number
                    </label>
                    <input type="text" name="phonenumber" id="phonenumber" placeholder="Your phone number" value="{{ old('phonenumber') }}">
                    @error('phonenumber') <small class="error-message">{{ $message }}</small> @enderror
                </div>
                
                <div class="button-container">
                    <button type="submit" class="login-btn register-btn">
                        <i class="fas fa-user-plus"></i> Create Account
                    </button>
                </div>
                
                <div class="signup-container">
                    <span>Already have an account?</span>
                    <a href="/login" class="register-text">Login</a>
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