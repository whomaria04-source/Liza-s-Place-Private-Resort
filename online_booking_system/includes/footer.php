    <footer class="footer">
        <style>
            .footer {
                background: linear-gradient(135deg, var(--primary-color) 0%, #1e3f5a 100%);
                color: white;
                padding: 3rem 0 1rem 0;
                margin-top: 3rem;
            }

            .footer-content {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 2rem;
                margin-bottom: 2rem;
            }

            .footer-section h3,
            .footer-section h4 {
                margin-bottom: 1rem;
                color: var(--secondary-color);
            }

            .footer-section ul {
                list-style: none;
            }

            .footer-section ul li {
                margin-bottom: 0.5rem;
            }

            .footer-section a {
                color: #ecf0f1;
                text-decoration: none;
                transition: all 0.3s ease;
            }

            .footer-section a:hover {
                color: var(--secondary-color);
                padding-left: 0.5rem;
            }

            .social-links {
                display: flex;
                gap: 1rem;
            }

            .social-links a {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 40px;
                height: 40px;
                background-color: rgba(255, 255, 255, 0.2);
                border-radius: 50%;
                font-size: 1.2rem;
                transition: all 0.3s ease;
            }

            .social-links a:hover {
                background-color: var(--secondary-color);
                transform: translateY(-5px);
            }

            .footer-bottom {
                text-align: center;
                padding-top: 2rem;
                border-top: 1px solid rgba(255, 255, 255, 0.2);
                color: #bdc3c7;
            }

            @media (max-width: 768px) {
                .footer-content {
                    grid-template-columns: 1fr;
                }
            }
        </style>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>🏖️ Liza's Place Private Resort</h3>
                    <p>Your ultimate destination for perfect getaways and unforgettable memories.</p>
                </div>
                
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="<?php echo isset($base_url) ? $base_url : '../'; ?>index.php"><i class="fas fa-home"></i> Home</a></li>
                        <li><a href="<?php echo isset($base_url) ? $base_url : '../'; ?>pages/rooms.php"><i class="fas fa-door-open"></i> Browse Rooms</a></li>
                        <li><a href="<?php echo isset($base_url) ? $base_url : '../'; ?>pages/gallery.php"><i class="fas fa-images"></i> Gallery</a></li>
                        <li><a href="<?php echo isset($base_url) ? $base_url : '../'; ?>pages/contact.php"><i class="fas fa-envelope"></i> Contact</a></li>
                        <?php if (isset($_SESSION['user_id']) && ($_SESSION['user_role'] ?? null) != 'admin'): ?>
                            <li><a href="<?php echo isset($base_url) ? $base_url : '../'; ?>pages/booking-history.php"><i class="fas fa-calendar-check"></i> My Bookings</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Contact Info</h4>
                    <p><i class="fas fa-map-marker-alt"></i> 804 D. Ilaya St. Bagong Pook, Lipa City, Batangas</p>
                    <p><i class="fas fa-phone"></i> <a href="tel:+639943854824" style="color: #ecf0f1;">0994 385 4824</a></p>
                    <p><i class="fas fa-envelope"></i> <a href="mailto:Liza@resort.com" style="color: #ecf0f1;">Liza@resort.com</a></p>
                </div>
                
                <div class="footer-section">
                    <h4>Follow Us</h4>
                    <div class="social-links">
                        <a href="https://www.facebook.com/lizasplaceresort" target="_blank" title="Follow us on Facebook"><i class="fab fa-facebook"></i></a>
                        <a href="https://www.twitter.com/lizasplaceresort" target="_blank" title="Follow us on Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.instagram.com/lizasplaceresort" target="_blank" title="Follow us on Instagram"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2026 Liza's Place Private Resort. All rights reserved. | <a href="<?php echo isset($base_url) ? $base_url : '../'; ?>auth/login.php" style="color: #bdc3c7; text-decoration: none;">Admin Login</a></p>
            </div>
        </div>
    </footer>
    
    <script src="<?php echo $base_url ?? './'; ?>assets/js/script.js"></script>
</body>
</html>