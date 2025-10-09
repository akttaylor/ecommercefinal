<?php if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); } ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopTaylor - E-commerce Platform</title>
    <link rel="stylesheet" href="CSS/base.css">
    <link rel="stylesheet" href="CSS/navbar.css">
    <link rel="stylesheet" href="CSS/buttons.css">
    <link rel="stylesheet" href="CSS/home.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="index.php" class="logo">🛒 ShopTaylor</a>

<?php if (!empty($_SESSION['customer_id'])): ?>
    <span class="welcome-text">Welcome, <?php echo htmlspecialchars($_SESSION['customer_name'] ?? 'Customer'); ?></span>
    <a href="actions/logout.php" class="btn btn-secondary">Logout</a>
<?php else: ?>
    <a href="login/register.php" class="btn btn-primary">Register</a>
    <a href="login/login.php" class="btn btn-secondary">login</a>
<?php endif; ?>

        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <div class="welcome-section fade-in">
                <h1>Welcome to ShopTaylor</h1>
                <p>
                    Your premium e-commerce destination for sustainable and eco-friendly products. 
                    Join our community today and start your journey towards a greener future.
                </p>
                
                <div class="cta-buttons">
                    <a href="login/register.php" class="btn btn-primary">
                        Get Started
                    </a>
                    <a href="login/login.php" class="btn btn-secondary">
                        Sign In
                    </a>
                </div>

                <!-- Features Section -->
                <div class="features-grid">
                    <div class="feature-card">
                        <h3>🌱 Eco-Friendly</h3>
                        <p>Discover sustainable products that care for our planet and future generations.</p>
                    </div>

                    <div class="feature-card">
                        <h3>🛒 Easy Shopping</h3>
                        <p>Intuitive interface designed for seamless shopping experience across all devices.</p>
                    </div>

                    <div class="feature-card">
                        <h3>🚚 Fast Delivery</h3>
                        <p>Quick and reliable delivery service to bring your products right to your doorstep.</p>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="statistics">
                    <div class="stat-item">
                        <div class="stat-number">1000+</div>
                        <div class="stat-label">Products</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">500+</div>
                        <div class="stat-label">Happy Customers</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">50+</div>
                        <div class="stat-label">Categories</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">24/7</div>
                        <div class="stat-label">Support</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>© 2025 ShopTaylor. Built with MVC Architecture - Customer Registration Lab</p>
            <p>Sustainable Shopping for a Better Tomorrow 🌍</p>
            <p>Test</p>
        </div>
    </footer>

    <script>
        // Add smooth scrolling and animations
        document.addEventListener('DOMContentLoaded', function() {
            // Add hover effects to feature cards
            const featureCards = document.querySelectorAll('[style*="rgba(143, 188, 143, 0.2)"]');
            featureCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                    this.style.transition = 'all 0.3s ease';
                    this.style.boxShadow = '0 10px 25px rgba(0,0,0,0.1)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = 'none';
                });
            });
            
            // Animate statistics on scroll
            const stats = document.querySelectorAll('[style*="font-size: 2.5rem"]');
            
            const animateStats = () => {
                stats.forEach(stat => {
                    const rect = stat.getBoundingClientRect();
                    if (rect.top < window.innerHeight && rect.bottom > 0) {
                        const finalValue = parseInt(stat.textContent);
                        let currentValue = 0;
                        const increment = finalValue / 50;
                        
                        const timer = setInterval(() => {
                            currentValue += increment;
                            if (currentValue >= finalValue) {
                                stat.textContent = finalValue + (stat.textContent.includes('+') ? '+' : '');
                                clearInterval(timer);
                            } else {
                                stat.textContent = Math.floor(currentValue) + (stat.textContent.includes('+') ? '+' : '');
                            }
                        }, 30);
                    }
                });
            };
            
            // Run animation on load and scroll
            window.addEventListener('scroll', animateStats);
            animateStats();
        });
    </script>
</body>
</html>