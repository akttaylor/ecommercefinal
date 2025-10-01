<?php
session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION['customer_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] != 1) {
    header('Location: ../login/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - ShopTaylor</title>
    <link rel="stylesheet" href="../CSS/base.css">
    <link rel="stylesheet" href="../CSS/navbar.css">
    <link rel="stylesheet" href="../CSS/buttons.css">
    <link rel="stylesheet" href="../CSS/admin.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="../index.php" class="logo">🛒 ShopTaylor</a>
            <div class="nav-buttons">
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['customer_name']); ?> (Admin)</span>
                <a href="../actions/logout.php" class="btn btn-secondary">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="admin-container">
        <div class="admin-header">
            <h1>Admin Dashboard</h1>
            <p>Manage your e-commerce platform</p>
        </div>

        <div class="categories-section">
            <div class="section-header">
                <h2>Categories Management</h2>
                <button class="btn btn-primary" onclick="showAddCategoryModal()">
                    + Add New Category
                </button>
            </div>

            <div id="categories-container">
                <div class="loading">Loading categories...</div>
            </div>
        </div>
    </div>

    <script src="../JS/admin.js"></script>
</body>
</html>
