<!-- resources/views/partials/footer.blade.php -->

<footer class="footer">
    <div class="footer-container">
        <div class="footer-top">
            <div class="footer-logo">
                <div class="logo">FIT TRACK</div>
                <p>Your Ultimate Fitness Companion</p>
            </div>
            
            <div class="footer-links">
                <div class="footer-column">
                    <h3>Navigation</h3>
                    <ul>
                        <li><a href="/">Home</a></li>
                        <li><a href="/community">Community</a></li>
                        <li><a href="/about-us">About Us</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h3>Programs</h3>
                    <ul>
                        <li><a href="/workout-programs">Workout Programs</a></li>
                        <li><a href="/load">Load Programs</a></li>
                        <li><a href="/calendar">Calendar</a></li>
                        <li><a href="/customworkout">Custom Workout</a></li>
                    </ul>
                </div>
                
                
                
                <div class="footer-column">
                    <h3>Connect</h3>
                    <div class="social-links">
                        <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                    </div>
                    <div class="contact-info">
                        <p><i class="fas fa-envelope"></i> fittrack@gmail.com</p>
                        <p><i class="fas fa-phone"></i> +62 812 3239 3979</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} FitTrack. All rights reserved.</p>
        </div>
    </div>
</footer>