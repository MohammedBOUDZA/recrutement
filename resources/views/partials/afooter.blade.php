<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h4>About Us</h4>
                <p>We are a leading recruitment platform connecting emploi seekers with employers. Find your dream emploi or the perfect candidate today!</p>
            </div>

            <div class="footer-section">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="/home">Home</a></li>
                    <li><a href="/create">post job</a></li>
                    <li><a href="{{ route('profile') }}">Profile</a></li>
                    <li><a href="/employer/dashboard">Dashboard</a></li>
                    <li><a href="/contact">Contact Us</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h4>Contact Us</h4>
                <ul>
                    <li><i class="fas fa-map-marker-alt"></i> 1 st chefchaouni , Fes, Morocco</li>
                    <li><i class="fas fa-phone"></i> +212 64 41 34 89 5</li>
                    <li><i class="fas fa-envelope"></i> recrutement@gmail.com</li>
                </ul>
            </div>

            <div class="footer-section">
                <h4>Follow Us</h4>
                <div class="social-links">
                    <a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" target="_blank"><i class="fab fa-twitter"></i></a>
                    <a href="#" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" target="_blank"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} Recruitment Website. All rights reserved.</p>
        </div>
    </div>
</footer>