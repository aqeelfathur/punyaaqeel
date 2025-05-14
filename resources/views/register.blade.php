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
        <form action="submit.php" method="POST">
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" placeholder="Enter your username">
            </div>
            
            <div class="input-group">
                <label for="password">Password</label>
                <input type="text" id="password" placeholder="Enter your password">
            </div>
            
            <div class="input-group">
                <label for="email">Email</label>
                <input type="text" id="email" placeholder="Enter your email">
            </div>

            <div class="input-group">
                <label for="Phone number">Phone number</label>
                <input type="text" id="phonenumber" placeholder="Enter your phone number">
            </div>
            
            <div>
                <span>Have an account?</span>
                <a href="/login" class="register-text">Login</a>
            </div>
            <div class="button-group">
                <button type="submit" class="login-btn">Daftar</button>
                <a href="/" class="back-btn">Back</a>
            </div>
        </form>
    </div>
    </script>
            document.getElementById("loginForm").addEventListener("submit", function(event) {
            event.preventDefault();
            
            let username = document.getElementById("username").value.trim();
            let password = document.getElementById("password").value.trim();
            let userError = document.getElementById("userError");
            let passError = document.getElementById("passError");
            
            userError.textContent = "";
            passError.textContent = "";
            
            if (username === "") {
                userError.textContent = "Username cannot be empty!";
                return;
            }
            if (password === "") {
                passError.textContent = "Password cannot be empty!";
                return;
            }
            
            if (username === "admin" && password === "1234") {
                alert("Login successful!");
                window.location.href = "dashboard.html";
            } else {
                alert("Invalid username or password");
            }
        });
    </script>
</body>
</html>