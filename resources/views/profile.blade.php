<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - FitTrack</title>
    <link rel="stylesheet" href="{{ asset('css/stylesprofil.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;300;400;600&display=swap" rel="stylesheet">
    
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

    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-avatar">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div class="profile-info">
                <h1>{{ Auth::user()->name }}</h1>
                <p>Username: {{ Auth::user()->username }}</p>
                <p>Email: {{ Auth::user()->email }}</p>
                <p>Phone: {{ Auth::user()->phone_number ?? 'Not set' }}</p>
            </div>
        </div>

       

        <div class="profile-action">
            <a href="{{ route('settings') }}" class="edit-profile-btn">Edit Profile</a>
        </div>
    </div>
</body>
</html>