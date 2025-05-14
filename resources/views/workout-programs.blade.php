<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitTrack</title>
    <link rel="stylesheet" href="{{ asset('css/stylesworkout.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;300;400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
                        <li><a href="workout-programs">Workout Programs</a></li>
                        <li><a href="load">Load</a></li>
                        <li><a href="calendar">Calendar</a></li>
                        <li><a href="customworkout">Custom</a></li>
                    </ul>
                </li>
                <li><a href="community">Community</a></li>
                <li><a href="about-us">About Us</a></li>
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

    <main>
        <section id="workout-header" class="workout-header">
            <div class="container">
                <h1 class="section-title">Workout Programs</h1>
                
                <div class="search-and-filter">
                    <div class="search-bar">
                        <form action="">
                            <input type="text" placeholder="search">
                            <button type="submit"><span class="material-icons">search</span></button>
                        </form>
                    </div>
                    
                    <div class="filter-bar">
                        <button class="filter-btn active">All</button>
                        <button class="filter-btn">Body Weight</button>
                        <button class="filter-btn">Tools Weight</button>
                    </div>
                </div>
            </div>
        </section>
      
        <section class="workout-section">
            <div class="container">
                <div class="workout-cards">
                    <!-- Card 1 -->
                    <div class="card" data-category="body-weight">
                        <img src="{{ asset('assets/imagesprograms.jpeg') }}" alt="Upper Programs">
                        <div class="card-content">
                            <h3>Upper Programs</h3>
                            <p>Target the arms, shoulders, chest, and upper back while increasing lean muscle mass, reducing body fat</p>
                            <button class="load-btn">Add to Load</button>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="card" data-category="tools-weight">
                        <img src="{{ asset('assets/imagesprograms.jpeg') }}" alt="Upper Programs">
                        <div class="card-content">
                            <h3>Dumbbell Workout</h3>
                            <p>Full body workout using dumbbells for strength training</p>
                            <button class="load-btn">Add to Load</button>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="card" data-category="body-weight">
                        <img src="{{ asset('assets/imagesprograms.jpeg') }}" alt="Upper Programs">
                        <div class="card-content">
                            <h3>Calisthenics</h3>
                            <p>Body weight exercises for functional strength</p>
                            <button class="load-btn">Add to Load</button>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterBtns = document.querySelectorAll('.filter-btn');
            const cards = document.querySelectorAll('.card');
            
            filterBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Remove active class from all buttons
                    filterBtns.forEach(b => b.classList.remove('active'));
                    // Add active class to clicked button
                    this.classList.add('active');
                    
                    const filterValue = this.textContent.toLowerCase().replace(' ', '-');
                    
                    // Filter cards
                    cards.forEach(card => {
                        if (filterValue === 'all') {
                            card.style.display = 'block';
                        } else {
                            if (card.dataset.category === filterValue) {
                                card.style.display = 'block';
                            } else {
                                card.style.display = 'none';
                            }
                        }
                    });
                });
            });
        });
        </script>
</body>
</html>