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
