<!-- resources/views/about-us.blade.php -->

@extends('layouts.main')

@section('title', 'About Us - FitTrack')

@section('additional_css')
<link rel="stylesheet" href="{{ asset('css/stylesaboutus.css') }}">
<style>
    /* About Us Page Specific Styles */

    /* Hero Section */
    .hero-text {
        font-size: 1.2rem;
        max-width: 800px;
        margin: 20px auto;
    }

    /* Team Section */
    .team-wrap {
        flex-direction: column;
        height: auto;
        gap: 30px;
    }

    .team-container {
        display: flex;
        justify-content: center;
        width: 100%;
        flex-wrap: wrap;
        gap: 60px;
    }

    .team-member {
        text-align: center;
        max-width: 250px;
    }

    .team-photo {
        width: 200px;
        height: 200px;
        object-fit: cover;
        border-radius: 50%;
    }

    .team-role {
        font-size: 1rem;
    }

    /* Contact Section */
    .contact-section {
        padding-bottom: 60px;
    }

    .contact-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
        text-align: center;
    }

    .contact-intro {
        font-size: 1.2rem;
        margin-bottom: 30px;
    }

    .contact-info {
        display: flex;
        justify-content: center;
        gap: 40px;
        flex-wrap: wrap;
    }

    .contact-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .contact-button-wrapper {
        display: inline-block;
        margin-top: 40px;
    }
</style>
@endsection

@section('content')
    <section id="home" class="section hero">
        <h1>About FitTrack</h1>
        <p class="hero-text">Your ultimate fitness companion on the journey to a stronger, healthier you.</p>
    </section>

    <div class="content">
        <ul>
            Our Story
        </ul>
        <div class="content-wrap">
            <img src="{{ asset('assets/our team.png') }}" alt="Our team">
            <div class="content-text">
                <h2>WHO WE ARE</h2>
                <p>
                    We are the creators of FitTrack, a digital fitness platform dedicated to helping individuals plan, perform, and track their workout routines effectively. 
                </p>
            </div>
        </div>
    </div>
    
    <div class="content">
        <div class="content-wrap">
            <div class="content-text">
                <h2>OUR MISSION</h2>
                <p>
                    We created this website so that users can track their progress, share their journey with the community, and ask for as well as exchange advice
                </p>
            </div>
            <img src="{{ asset('assets/pull up.webp') }}" alt="Our mission">
        </div>
    </div>

    <div class="content">
        <div class="content-wrap">
            <img src="{{ asset('assets/push up.jpg') }}" alt="Our values">
            <div class="content-text">
                <h2>OUR VALUES</h2>
                <p>
                    We value health, accessibility, personalization, and community spirit as the foundation to support users in achieving their fitness goals.
                </p>
            </div>
        </div>
    </div>

    <div class="content">
        <ul>
            Meet Our Team
        </ul>
        <div class="content-wrap team-wrap">
            <div class="team-container">
                <div class="team-member">
                    <img src="{{ asset('assets/about us aqeel.JPG') }}" alt="Team member" class="team-photo">
                    <h3>Ananda Aqeel Fathur Rahman</h3>
                    <p class="team-role">Co-Founder FitTrack</p>
                </div>
                <div class="team-member">
                    <img src="{{ asset('assets/about us russel.jpg') }}" alt="Team member" class="team-photo">
                    <h3>Russel Ishak Dalton Tampubolon</h3>
                    <p class="team-role">Co-Founder FitTrack</p>
                </div>
            </div>
        </div>
    </div>

    <div class="content contact-section">
        <ul>
            Get In Touch
        </ul>
        <div class="contact-container">
            <p class="contact-intro">
                Have questions or suggestions? We'd love to hear from you!
            </p>
            <div class="contact-info">
                <div class="contact-item">
                    <h3>Email</h3>
                    <p>support@fittrack.com</p>
                </div>
                <div class="contact-item">
                    <h3>Social Media</h3>
                    <p>@FitTrackOfficial</p>
                </div>
                <div class="contact-item">
                    <h3>Address</h3>
                    <p>Unair</p>
                </div>
            </div>
        </div>
    </div>
@endsection