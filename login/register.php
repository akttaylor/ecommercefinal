<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - E-commerce Platform</title>
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
                <a href="login.php" class="btn btn-secondary">Login</a>
                <a href="../index.php" class="btn btn-primary">Home</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <div class="form-container fade-in">
                <h2 class="form-title">Create Account</h2>
                
                <!-- Success/Error Messages -->
                <div id="success-message" class="success-message"></div>
                <div id="error-message" class="error-message"></div>
                
                <!-- Registration Form -->
                <form id="registration-form" novalidate>
                    <div class="form-group">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" id="name" name="name" class="form-control" required maxlength="100" placeholder="Enter your full name">
                        <div class="field-error" id="name-error"></div>
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="email-input-wrapper">
                            <input type="email" id="email" name="email" class="form-control" required maxlength="50" placeholder="Enter your email address">
                            <div class="email-check" id="email-check"></div>
                        </div>
                        <div class="field-error" id="email-error"></div>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required minlength="6" placeholder="Enter your password">
                        <div class="field-error" id="password-error"></div>
                    </div>

                    <div class="form-group">
                        <label for="confirm-password" class="form-label">Confirm Password</label>
                        <input type="password" id="confirm-password" name="confirm-password" class="form-control" required placeholder="Confirm your password">
                        <div class="field-error" id="confirm-password-error"></div>
                    </div>

                    <div class="form-group">
                        <label for="country" class="form-label">Country</label>
                        <input type="text" id="country" name="country" class="form-control" required maxlength="30" placeholder="Enter your country">
                        <div class="field-error" id="country-error"></div>
                    </div>

                    <div class="form-group">
                        <label for="city" class="form-label">City</label>
                        <input type="text" id="city" name="city" class="form-control" required maxlength="30" placeholder="Enter your city">
                        <div class="field-error" id="city-error"></div>
                    </div>

                    <div class="form-group">
                        <label for="contact" class="form-label">Contact Number</label>
                        <input type="tel" id="contact" name="contact" class="form-control" required maxlength="15" placeholder="Enter your contact number">
                        <div class="field-error" id="contact-error"></div>
                    </div>

                    <button type="submit" class="btn-submit" id="submit-btn">
                        Create Account
                    </button>
                </form>

                <div class="form-links">
                    <p>Already have an account? <a href="login.php">Login here</a></p>
                </div>
            </div>
        </div>
    </div>

    <script src="../JS/register.js"></script>
</body>
</html>