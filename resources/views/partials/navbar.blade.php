<!-- resources/views/partials/navbar.blade.php -->

<header>
    <nav>
        <div class="logo">FIT TRACK</div>
        <ul class="nav-links">
            <li><a href="/" class="{{ request()->is('/') ? 'active' : '' }}">Home</a></li>
            <li class="dropdown">
                <a href="#">Programs &#9662;</a>
                <ul class="dropdown-menu">
                    <li><a href="/workout-programs">Workout Programs</a></li>
                    <li><a href="/load">Load</a></li>
                    <li><a href="/calendar">Calendar</a></li>
                </ul>
            </li>
            <li><a href="/community" class="{{ request()->is('community') ? 'active' : '' }}">Community</a></li>
            <li><a href="/about-us" class="{{ request()->is('about-us') ? 'active' : '' }}">About Us</a></li>
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