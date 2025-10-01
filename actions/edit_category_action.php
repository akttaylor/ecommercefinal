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

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(array(
        'status' => 'error',
        'message' => 'Method not allowed. Only POST requests are accepted.'
    ));
    exit;
}

try {
    // Accept JSON or form-encoded
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) {
        $input = $_POST;
    }

    $categoryId = isset($input['category_id']) ? $input['category_id'] : '';
    $categoryName = isset($input['category_name']) ? trim($input['category_name']) : '';

    if (empty($categoryId)) {
        echo json_encode(array(
            'status' => 'error',
            'message' => 'Category ID is required.'
        ));
        exit;
    }

    if (empty($categoryName)) {
        echo json_encode(array(
            'status' => 'error',
            'message' => 'Category name is required.'
        ));
        exit;
    }

    $controller = new CategoryController();
    $result = $controller->updateCategory($categoryId, $categoryName);

    echo json_encode($result);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array(
        'status' => 'error',
        'message' => 'Server error: ' . $e->getMessage()
    ));
}
?>
