<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../controllers/customer_controller.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
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
    if (!$input) { $input = $_POST; }

    $email = isset($input['email']) ? trim($input['email']) : '';
    $password = isset($input['password']) ? $input['password'] : '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status'=>'error','message'=>'Please enter a valid email address.']);
        exit;
    }
    if ($password === '') {
        echo json_encode(['status'=>'error','message'=>'Please enter your password.']);
        exit;
    }

    // Use the required controller function per spec
    $result = login_customer_ctr($email, $password);
    if ($result['status'] === 'success') {
        // Determine redirect based on user role
        $redirect = '../index.php';
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 1) {
            $redirect = '../admin/dashboard.php';
        }

        echo json_encode([
            'status' => 'success',
            'message' => 'Login successful. Redirecting...',
            'redirect' => $redirect
        ]);
    } else {
        echo json_encode($result);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array(
        'status' => 'error',
        'message' => 'Server error: ' . $e->getMessage()
    ));
}
?>
