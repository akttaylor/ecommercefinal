<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../controllers/customer_controller.php';

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
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input) {
        $input = $_POST;
    }

    if (!isset($input['email']) || empty($input['email'])) {
        echo json_encode(array(
            'status' => 'error',
            'message' => 'Email is required.'
        ));
        exit;
    }

    $controller = new CustomerController();
    $result = $controller->checkEmailAvailability($input['email']);
    echo json_encode($result);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array(
        'status' => 'error',
        'message' => 'Server error: ' . $e->getMessage()
    ));
}
?>
