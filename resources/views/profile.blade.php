{{-- resources/views/profile.blade.php --}}

@extends('layouts.main')

@section('title', 'Profile - FitTrack')

@section('additional_css')
<style>
    /* Profile Page Styles - Compatible with main layout */
    .profile-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 120px 20px 40px; /* Top padding increased for fixed navbar */
        min-height: calc(100vh - 160px); /* Adjusted for navbar and footer */
    }

    /* Profile Header Section */
    .profile-header {
        background: rgba(20, 20, 20, 0.9);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 50px 40px;
        text-align: center;
        margin-bottom: 40px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
        border: 1px solid #FFD700;
        position: relative;
        overflow: hidden;
    }

    .profile-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #FFD700, #ffa500, #FFD700);
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    .profile-avatar {
        position: relative;
        display: inline-block;
        margin-bottom: 30px;
    }

    .avatar-img {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #FFD700;
        box-shadow: 0 15px 35px rgba(255, 69, 0, 0.3);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .avatar-img:hover {
        transform: scale(1.05);
        box-shadow: 0 20px 45px rgba(255, 69, 0, 0.4);
    }

    .profile-name {
        font-size: 3rem;
        font-weight: 900;
        color: #ffffff;
        margin-bottom: 15px;
        text-transform: uppercase;
        letter-spacing: 2px;
        background: linear-gradient(45deg, #FFD700);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-shadow: 0 0 30px rgba(255, 69, 0, 0.3);
    }

    .profile-status {
        color: #cccccc;
        font-size: 1.2rem;
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .status-indicator {
        width: 12px;
        height: 12px;
        background: #48bb78;
        border-radius: 50%;
        animation: blink 1.5s infinite;
    }

    @keyframes blink {
        0%, 50% { opacity: 1; }
        51%, 100% { opacity: 0.3; }
    }

    .profile-stats {
        display: flex;
        justify-content: center;
        gap: 60px;
        margin-top: 30px;
    }

    .stat-item {
        text-align: center;
        padding: 20px;
        background: rgba(255, 69, 0, 0.1);
        border-radius: 15px;
        border: 1px solid #FFD700;
        min-width: 120px;
    }

    .stat-number {
        font-size: 2.2rem;
        font-weight: 900;
        color: #FFD700;
        display: block;
        margin-bottom: 5px;
    }

    .stat-label {
        color: #cccccc;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
    }

    /* Info Cards Grid */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 30px;
        margin-bottom: 40px;
    }

    .info-card {
        background: rgba(20, 20, 20, 0.9);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 35px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
        border: 1px solid #FFD700;
        transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .info-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, transparent, #FFD700, transparent);
        transform: translateX(-100%);
        transition: transform 0.3s ease;
    }

    .info-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 50px rgba(255, 69, 0, 0.2);
        border-color: rgba(255, 69, 0, 0.4);
    }

    .info-card:hover::before {
        transform: translateX(100%);
    }

    .card-header {
        display: flex;
        align-items: center;
        margin-bottom: 25px;
    }

    .card-icon {
        width: 60px;
        height: 60px;
        border-radius: 15px;
        background: linear-gradient(135deg, #FFD700, #ffa500);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.4rem;
        margin-right: 20px;
        box-shadow: 0 10px 20px rgba(255, 69, 0, 0.3);
    }

    .card-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #ffffff;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-label {
        color: #cccccc;
        font-weight: 600;
        font-size: 1rem;
    }

    .info-value {
        color: #ffffff;
        font-weight: 700;
        font-size: 1rem;
    }

    .badge {
        background: linear-gradient(135deg, #FFD700, #ffa500);
        color: white;
        padding: 8px 16px;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 5px 15px rgba(255, 69, 0, 0.3);
    }

    .verified-icon {
        color: #48bb78;
        margin-right: 8px;
        font-size: 1.1rem;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        justify-content: center;
        gap: 25px;
        margin-top: 40px;
        margin-bottom: 60px; /* Extra margin for footer spacing */
    }

    .profile-btn {
        padding: 16px 40px;
        border-radius: 50px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        font-size: 1.1rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        position: relative;
        overflow: hidden;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .profile-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s ease;
    }

    .profile-btn:hover::before {
        left: 100%;
    }

    .profile-btn-primary {
        background: linear-gradient(135deg, #FFD700, #ffa500);
        color: white;
        box-shadow: 0 10px 25px rgba(255, 69, 0, 0.4);
    }

    .profile-btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(255, 69, 0, 0.5);
    }

    .profile-btn-secondary {
        background: transparent;
        color: #FFD700;
        border: 2px solid #FFD700;
    }

    .profile-btn-secondary:hover {
        background: #FFD700;
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(255, 69, 0, 0.3);
    }

    /* Fitness themed additions */
    .fitness-quote {
        text-align: center;
        font-style: italic;
        color: #ffa500;
        font-size: 1.1rem;
        margin-top: 20px;
        font-weight: 600;
    }

    /* Override untuk mencegah konflik dengan main layout */
    .profile-container h1,
    .profile-container h2,
    .profile-container h3 {
        font-family: 'Poppins', sans-serif !important;
    }

    .profile-container a {
        font-family: 'Poppins', sans-serif !important;
    }

    /* Pastikan profile container tidak terganggu oleh body styling */
    .profile-container {
        position: relative;
        z-index: 1;
    }

    /* Responsive Design */
    @media (max-width: 992px) {
        .profile-container {
            padding: 100px 20px 40px; /* Reduced top padding for tablets */
        }
        
        .profile-stats {
            gap: 40px;
        }
        
        .info-grid {
            grid-template-columns: 1fr;
            gap: 25px;
        }
    }

    @media (max-width: 768px) {
        .profile-container {
            padding: 90px 15px 30px; /* Further reduced for mobile */
        }

        .profile-header {
            padding: 30px 20px;
        }

        .profile-name {
            font-size: 2.2rem;
        }

        .profile-stats {
            gap: 30px;
            flex-wrap: wrap;
        }

        .action-buttons {
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }

        .profile-btn {
            width: 100%;
            max-width: 300px;
            justify-content: center;
        }
        
        .info-card {
            padding: 25px;
        }
    }

    @media (max-width: 576px) {
        .profile-container {
            padding: 80px 10px 20px; /* Minimal padding for small phones */
        }
        
        .profile-stats {
            gap: 20px;
        }

        .stat-item {
            min-width: 100px;
            padding: 15px;
        }

        .profile-name {
            font-size: 1.8rem;
        }
        
        .card-icon {
            width: 50px;
            height: 50px;
            font-size: 1.2rem;
        }
        
        .card-title {
            font-size: 1.3rem;
        }
    }

    /* Smooth Entrance Animation */
    .fade-in {
        animation: fadeInUp 0.8s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endsection

@section('content')
<div class="profile-container">
    <!-- Profile Header -->
    <div class="profile-header fade-in">
        <div class="profile-avatar">
            <img src="{{ $user->profile_image ? Storage::url($user->profile_image) : asset('assets/default-avatar.png') }}" 
                 alt="Profile Picture" class="avatar-img">
        </div>
        
        <h1 class="profile-name">{{ $user->username ?? 'Fitness Warrior' }}</h1>
        
    </div>

    <!-- Info Cards Grid -->
    <div class="info-grid">
        <!-- Personal Information Card -->
        <div class="info-card fade-in">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fas fa-user"></i>
                </div>
                <h3 class="card-title">Personal Info</h3>
            </div>
            <div class="card-content">
                <div class="info-item">
                    <span class="info-label">Full Name</span>
                    <span class="info-value">{{ $user->username ?? 'Fitness Enthusiast' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email</span>
                    <span class="info-value">{{ $user->email ?? 'warrior@fittrack.com' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Phone</span>
                    <span class="info-value">{{ $user->phone_number ?? 'Not provided' }}</span>
                </div>
            </div>
        </div>

        <!-- Account Details Card -->
        <div class="info-card fade-in">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fas fa-dumbbell"></i>
                </div>
                <h3 class="card-title">Fitness Journey</h3>
            </div>
            <div class="card-content">
                <div class="info-item">
                    <span class="info-label">Member Since</span>
                    <span class="info-value">{{ $user->created_at ? $user->created_at->format('M Y') : 'Jan 2024' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Last Active</span>
                    <span class="info-value">{{ $user->updated_at ? $user->updated_at->format('M d, Y') : 'Today' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Status</span>
                    <span class="badge">Active Warrior</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Verification</span>
                    <span class="info-value">
                        <i class="fas fa-check-circle verified-icon"></i>
                        Verified Member
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="action-buttons">
        <a href="{{ route('settings') }}" class="profile-btn profile-btn-primary">
            <i class="fas fa-edit"></i>
            Edit Profile
        </a>
        <a href="/workout-programs" class="profile-btn profile-btn-secondary">
            <i class="fas fa-fire"></i>
            View Workouts
        </a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add entrance animations with staggered delay
    const cards = document.querySelectorAll('.info-card');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${0.3 + (index * 0.2)}s`;
    });

    
});
</script>
@endsection