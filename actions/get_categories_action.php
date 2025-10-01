<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../controllers/category_controller.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['customer_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] != 1) {
    http_response_code(403);
    echo json_encode(array(
        'status' => 'error',
        'message' => 'Unauthorized access. Admin privileges required.'
    ));
    exit;
}

try {
    $controller = new CategoryController();
    $categories = $controller->getAllCategories();

    if ($categories !== false) {
        echo json_encode(array(
            'status' => 'success',
            'categories' => $categories
        ));
    } else {
        echo json_encode(array(
            'status' => 'error',
            'message' => 'Failed to retrieve categories.'
        ));
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array(
        'status' => 'error',
        'message' => 'Server error: ' . $e->getMessage()
    ));
}
?>
