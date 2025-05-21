<!-- resources/views/home.blade.php - Fixed Version -->

@extends('layouts.main')

@section('title', 'FitTrack - Your Fitness Journey')

@section('additional_css')
<link rel="stylesheet" href="{{ asset('css/styleshome.css') }}">
@endsection

@section('content')
    <section id="home" class="section hero">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="animated-title">Fit Your Muscles<br> with <span class="highlight">FitTrack</span></h1>
            <p class="hero-tagline">Track your progress. Customize your workouts. Join the community.</p>
            @guest
                <div class="hero-cta-container">
                    <a href="/register"><button class="hero-cta primary">Get Started</button></a>
                    <a href="/workout-programs"><button class="hero-cta secondary">Explore Programs</button></a>
                </div>
            @else
                <div class="hero-cta-container">
                    <a href="/load"><button class="hero-cta primary">Your Workouts</button></a>
                    <a href="/community"><button class="hero-cta secondary">Join Community</button></a>
                </div>
            @endguest
            <div class="stats-container">
                <div class="stat-item">
                    <span class="stat-number">{{ $programCount-1 }}+</span>
                    <span class="stat-label">Workout Programs</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">{{ $userCount-1 }}+</span>
                    <span class="stat-label">Users</span>
                </div>
            </div>

        </div>
    </section>

    <div class="content feature-section">
        <ul class="section-heading">
            <li>Why Choose Us?</li>
        </ul>
        
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-img-container">
                    <img src="{{ asset('assets/content1.webp') }}" alt="bicep curl">
                    <div class="feature-overlay">
                        <a href="/workout-programs" class="feature-link">Explore Programs</a>
                    </div>
                </div>
                <div class="feature-text">
                    <h2>LOT OF PROGRAMS</h2>
                    <p>
                        Not sure where to start? FitTrack offers a variety of workout programs designed by experts, catering to all fitness levels—from beginners to professionals. Find the perfect plan to build muscle, lose weight, or boost endurance.
                    </p>
                </div>
            </div>
            
            <div class="feature-card">
                <div class="feature-img-container">
                    <img src="{{ asset('assets/content1.webp') }}" alt="tracking progress">
                    <div class="feature-overlay">
                        <a href="/calendar" class="feature-link">View Calendar</a>
                    </div>
                </div>
                <div class="feature-text">
                    <h2>TRACK YOUR PROGRESS</h2>
                    <p>
                        Keep track of every rep, set, and strength gain with our progress tracker. Visualize your improvement through easy-to-read charts and stay motivated to reach your fitness goals.
                    </p>
                </div>
            </div>
            
            <div class="feature-card">
                <div class="feature-img-container">
                    <img src="{{ asset('assets/content1.webp') }}" alt="community">
                    <div class="feature-overlay">
                        <a href="/community" class="feature-link">Join Community</a>
                    </div>
                </div>
                <div class="feature-text">
                    <h2>SHARE WITH COMMUNITY</h2>
                    <p>
                        Join a supportive fitness community! Share your experiences, exchange tips, and even create your own workout plans for others to follow. Together, we grow stronger!
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="content bottom-cta-section">
        <div class="bottom-cta-container">
            <div class="bottom-cta-content">
                <h2>Ready to Transform Your Fitness Journey?</h2>
                <p>Join thousands of satisfied users who've achieved their fitness goals with FitTrack. Our personalized programs, progress tracking, and supportive community make reaching your goals easier than ever.</p>
                <div class="bottom-cta-buttons">
                    @guest
                        <a href="/register" class="bottom-cta-button primary">Get Started Now</a>
                        <a href="/workout-programs" class="bottom-cta-button secondary">Explore Programs</a>
                    @else
                        <a href="/profile" class="bottom-cta-button primary">View Your Profile</a>
                        <a href="/customworkout" class="bottom-cta-button secondary">Create Custom Workout</a>
                    @endguest
                </div>
            </div>
            <div class="bottom-cta-image">
                <img src="{{ asset('assets/fitness-goal.webp') }}" alt="Fitness Goal">
            </div>
        </div>
    </div>
@endsection