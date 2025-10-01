<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - E-commerce Platform</title>
    <link rel="stylesheet" href="../CSS/base.css">
    <link rel="stylesheet" href="../CSS/navbar.css">
    <link rel="stylesheet" href="../CSS/buttons.css">
    <link rel="stylesheet" href="../CSS/forms.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="../index.php" class="logo">🛒 ShopTaylor</a>
            <div class="nav-buttons">
                <a href="register.php" class="btn btn-secondary">Register</a>
                <a href="../index.php" class="btn btn-primary">Home</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <div class="form-container fade-in">
                <h2 class="form-title">Welcome Back</h2>
                
                <!-- Success/Error Messages -->
                <div id="success-message" class="success-message"></div>
                <div id="error-message" class="error-message"></div>
                
                <!-- Login Form (No functionality as per requirements) -->
                <form id="login-form" novalidate>
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" required placeholder="Enter your email address">
                        <div class="field-error" id="email-error"></div>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required placeholder="Enter your password">
                        <div class="field-error" id="password-error"></div>
                    </div>

                    <div class="form-group remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Remember me</label>
                    </div>

                    <button type="submit" class="btn-submit" id="submit-btn">
                        Sign In
                    </button>
                </form>

                <div class="form-links">
                    <p><a href="#">Forgot your password?</a></p>
                    <p>Don't have an account? <a href="register.php">Create one here</a></p>
                </div>

                <!-- Note for demonstration -->
                <div class="info-box">
                    <p>You can now sign in with the account you registered.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="../JS/login.js"></script>
</body>
</html>