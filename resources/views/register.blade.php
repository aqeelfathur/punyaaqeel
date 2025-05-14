<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styleslogin.css') }}">
    
</head>
<body>
    <div class="background-blur"></div>
    <div class="login-container">
        <h3>Register to FitTrack</h3>
        <form action="{{ route('register') }}" method="POST">
        @csrf

        <div class="input-group">
            <label for="username">Username</label>
            <input type="text" name="username" value="{{ old('username') }}">
            @error('username') <small style="color:red;">{{ $message }}</small> @enderror
        </div>

        <div class="input-group">
            <label for="password">Password</label>
            <input type="password" name="password">
            @error('password') <small style="color:red;">{{ $message }}</small> @enderror
        </div>

        <div class="input-group">
            <label for="email">Email</label>
            <input type="email" name="email" value="{{ old('email') }}">
            @error('email') <small style="color:red;">{{ $message }}</small> @enderror
        </div>

        <div class="input-group">
            <label for="phonenumber">Phone number</label>
            <input type="text" name="phonenumber" value="{{ old('phonenumber') }}">
            @error('phonenumber') <small style="color:red;">{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="login-btn">Daftar</button>
    </form>


    </div>
    
</body>
</html>