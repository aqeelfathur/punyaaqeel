    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Settings - FitTrack</title>
        <link rel="stylesheet" href="{{ asset('css/stylesprofil.css') }}">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;300;400;600&display=swap" rel="stylesheet">
        <style>
            /* Tambahan CSS untuk dropdown user */
            .user-dropdown {
                position: relative;
                display: inline-block;
                margin-right: 20px;
            }
            
            .user-button {
                font-family: 'Poppins', sans-serif;
                padding: 10px 20px;
                border: none;
                background: #ffffff;
                color: #0a0a0a;
                font-weight: 400;
                border-radius: 1000px;
                cursor: pointer;
                box-shadow: 0px 0px 80px rgba(255, 255, 255, 0.5);
                transition: box-shadow 0.3s ease-in-out;
                display: flex;
                align-items: center;
            }
            
            .user-button:hover {
                box-shadow: 40px 0px 100px #ffd900a7, -40px 0px 100px #f7eaa5a7;
            }
            
            .user-button:after {
                content: " ▼";
                font-size: 10px;
                margin-left: 5px;
            }
            
            .user-dropdown-menu {
                position: absolute;
                top: 100%;
                right: 0;
                background: #181717;
                display: none;
                width: 200px;
                padding: 0;
                border-radius: 40px;
                z-index: 10;
                list-style: none;
                margin-top: 10px;
                box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            }
            
            .user-dropdown:hover .user-dropdown-menu {
                display: block;
            }
            
            .user-dropdown-menu a {
                padding: 15px 20px;
                text-decoration: none;
                color: white;
                display: block;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                transition: background 0.3s;
            }
            
            .user-dropdown-menu a:hover {
                background: #333;
            }
            
            .user-dropdown-menu a:first-child {
                border-top-left-radius: 40px;
                border-top-right-radius: 40px;
            }
            
            .user-dropdown-menu a:last-child {
                border-bottom: none;
                border-bottom-left-radius: 40px;
                border-bottom-right-radius: 40px;
            }

            /* CSS for Settings Page */
            .settings-container {
                margin-top: 150px;
                max-width: 800px;
                margin-left: auto;
                margin-right: auto;
                padding: 30px;
                background: rgba(20, 20, 20, 0.8);
                border-radius: 20px;
                box-shadow: 0 0 30px rgba(255, 215, 0, 0.2);
            }

            .settings-header {
                text-align: center;
                margin-bottom: 40px;
            }

            .settings-header h1 {
                font-size: 2.5rem;
                color: #FFD700;
                margin-bottom: 10px;
            }

            .settings-header p {
                font-size: 1.2rem;
                color: #ccc;
            }

            .settings-form {
                margin-top: 20px;
            }

            .form-group {
                margin-bottom: 25px;
            }

            .form-group label {
                display: block;
                font-size: 1.1rem;
                color: #FFD700;
                margin-bottom: 8px;
            }

            .form-group input {
                width: 100%;
                padding: 12px 15px;
                border: none;
                border-radius: 1000px;
                background: rgba(40, 40, 40, 0.7);
                color: white;
                font-family: 'Poppins', sans-serif;
                font-size: 1rem;
            }

            .form-group input:focus {
                outline: none;
                box-shadow: 0 0 0 2px rgba(255, 215, 0, 0.5);
            }

            .settings-actions {
                display: flex;
                justify-content: space-between;
                margin-top: 40px;
            }

            .cancel-btn {
                font-family: 'Poppins', sans-serif;
                padding: 12px 30px;
                background: transparent;
                color: white;
                font-weight: 400;
                border: 1px solid rgba(255, 255, 255, 0.3);
                border-radius: 1000px;
                cursor: pointer;
                transition: all 0.3s ease-in-out;
                font-size: 1rem;
            }

            .cancel-btn:hover {
                background: rgba(255, 255, 255, 0.1);
            }

            .save-btn {
                font-family: 'Poppins', sans-serif;
                padding: 12px 30px;
                background: #FFD700;
                color: black;
                font-weight: 600;
                border: none;
                border-radius: 1000px;
                cursor: pointer;
                box-shadow: 0px 0px 20px rgba(255, 215, 0, 0.5);
                transition: box-shadow 0.3s ease-in-out;
                font-size: 1rem;
            }

            .save-btn:hover {
                box-shadow: 40px 0px 60px #ffd900a7, -40px 0px 60px #f7eaa5a7;
            }

            .alert {
                padding: 15px;
                margin-bottom: 20px;
                border-radius: 10px;
                font-weight: 400;
            }

            .alert-success {
                background: rgba(40, 167, 69, 0.2);
                border: 1px solid rgba(40, 167, 69, 0.5);
                color: #2ecc71;
            }

            .alert-danger {
                background: rgba(220, 53, 69, 0.2);
                border: 1px solid rgba(220, 53, 69, 0.5);
                color: #e74c3c;
            }
        </style>
    </head>
    <body>
        <header>
            <nav>
                <div class="logo">FIT TRACK</div>
                <ul class="nav-links">
                    <li><a href="/">Home</a></li>
                    <li class="dropdown">
                        <a href="#">Programs &#9662;</a>
                        <ul class="dropdown-menu">
                            <li><a href="/workout-programs">Workout Programs</a></li>
                            <li><a href="/load">Load</a></li>
                            <li><a href="/calendar">Calendar</a></li>
                            <li><a href="/customworkout">Custom</a></li>
                        </ul>
                    </li>
                    <li><a href="/community">Community</a></li>
                    <li><a href="/about-us">About Us</a></li>
                </ul>
                
                @auth
                    <div class="user-dropdown">
                        <button class="user-button">{{ Auth::user()->username }}</button>
                        <div class="user-dropdown-menu">
                            <a href="/profile">Profile</a>
                            <a href="/settings">Settings</a>
                            <a href="{{ route('logout') }}" 
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                @else
                    <a href="/login"><button class="sign-in" aria-label="Sign in">Sign in</button></a>
                @endauth
            </nav>
        </header>

        <div class="settings-container">
            <div class="settings-header">
                <h1>Account Settings</h1>
                <p>Update your personal information</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form class="settings-form" action="{{ route('settings.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="{{ Auth::user()->username }}" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ Auth::user()->email }}" required>
                </div>
                
                <div class="form-group">
                    <label for="phone_number">Phone Number</label>
                    <input type="tel" id="phone_number" name="phone_number" value="{{ Auth::user()->phone_number }}">
                </div>
                
                <div class="form-group">
                    <label for="current_password">Current Password (required to save changes)</label>
                    <input type="password" id="current_password" name="current_password" placeholder="Enter your current password" required>
                </div>
                
                <div class="form-group">
                    <label for="password">New Password (leave blank to keep current)</label>
                    <input type="password" id="password" name="password" placeholder="Enter new password">
                </div>
                
                <div class="form-group">
                    <label for="password_confirmation">Confirm New Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm new password">
                </div>
                
                <div class="settings-actions">
                    <a href="{{ route('profile') }}"><button type="button" class="cancel-btn">Cancel</button></a>
                    <button type="submit" class="save-btn">Save Changes</button>
                </div>
            </form>
        </div>
    </body>
    </html>