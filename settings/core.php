<?php

// Project session helpers adapted for `final` project keys
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/** Is anyone logged in? */
function is_logged_in(): bool {
    return isset($_SESSION['customer_id']) && !empty($_SESSION['customer_id']);
}

/** Is the logged-in user an admin? (role 1 assumed as admin) */
function is_admin(): bool {
    if (!is_logged_in()) return false;
    return ((int)($_SESSION['user_role'] ?? 0)) === 1;
}

/** Gatekeepers (use inside admin pages / protected pages) */
function require_login(): void {
    if (!is_logged_in()) {
        header('Location: ../login/login.php'); exit;
    }
}

function require_admin(): void {
    if (!is_admin()) {
        header('Location: ../login/login.php'); exit;
    }
}

?>
