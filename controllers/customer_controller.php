<?php
require_once __DIR__ . '/../classes/customer_class.php';

/**
 * Customer Controller - CORRECTED for your dbforlab.sql database
 */
class CustomerController {
    private $customer;
    
    public function __construct() {
        $this->customer = new Customer();
    }
    
    /**
     * Register new customer
     */
    public function registerCustomer($kwargs) {
        // Validate required fields
        $requiredFields = ['name', 'email', 'password', 'country', 'city', 'contact'];
        
        foreach ($requiredFields as $field) {
            if (!isset($kwargs[$field]) || empty(trim($kwargs[$field]))) {
                return array(
                    'status' => 'error',
                    'message' => ucfirst($field) . ' is required.'
                );
            }
        }
        
        // Sanitize inputs
        $name = trim($kwargs['name']);
        $email = trim(strtolower($kwargs['email']));
        $password = $kwargs['password'];
        $country = trim($kwargs['country']);
        $city = trim($kwargs['city']);
        $contact = trim($kwargs['contact']);
        $image = isset($kwargs['image']) ? trim($kwargs['image']) : null;
        $userRole = isset($kwargs['user_role']) ? intval($kwargs['user_role']) : 2;
        
        // CORRECTED: Validation based on your actual database field lengths
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return array(
                'status' => 'error',
                'message' => 'Please enter a valid email address.'
            );
        }
        
        // Email length validation (your DB: varchar(50))
        if (strlen($email) > 50) {
            return array(
                'status' => 'error',
                'message' => 'Email address is too long (maximum 50 characters).'
            );
        }
        
        if (strlen($password) < 6) {
            return array(
                'status' => 'error',
                'message' => 'Password must be at least 6 characters long.'
            );
        }
        
        // Name length validation (your DB: varchar(100))
        if (strlen($name) < 2 || strlen($name) > 100) {
            return array(
                'status' => 'error',
                'message' => 'Name must be between 2 and 100 characters.'
            );
        }
        
        // Country length validation (your DB: varchar(30))
        if (strlen($country) > 30) {
            return array(
                'status' => 'error',
                'message' => 'Country name is too long (maximum 30 characters).'
            );
        }
        
        // City length validation (your DB: varchar(30))
        if (strlen($city) > 30) {
            return array(
                'status' => 'error',
                'message' => 'City name is too long (maximum 30 characters).'
            );
        }
        
        // Contact length validation (your DB: varchar(15))
        if (strlen($contact) > 15) {
            return array(
                'status' => 'error',
                'message' => 'Contact number is too long (maximum 15 characters).'
            );
        }
        
        if (strlen($contact) < 10) {
            return array(
                'status' => 'error',
                'message' => 'Contact number is too short (minimum 10 characters).'
            );
        }
        
        // Validate phone number format
        if (!preg_match('/^[\+]?[0-9\-\(\)\s]+$/', $contact)) {
            return array(
                'status' => 'error',
                'message' => 'Please enter a valid contact number.'
            );
        }
        
        // Call customer model to add customer
        return $this->customer->addCustomer($name, $email, $password, $country, $city, $contact, $image, $userRole);
    }
    
    /**
     * Check if email is available
     */
    public function checkEmailAvailability($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return array(
                'status' => 'error',
                'message' => 'Invalid email format.'
            );
        }
        
        // Email length validation (your DB: varchar(50))
        if (strlen($email) > 50) {
            return array(
                'status' => 'error',
                'message' => 'Email address is too long (maximum 50 characters).'
            );
        }
        
        if ($this->customer->emailExists($email)) {
            return array(
                'status' => 'error',
                'message' => 'Email already exists.'
            );
        } else {
            return array(
                'status' => 'success',
                'message' => 'Email is available.'
            );
        }
    }
    
    /**
     * Get customer by ID
     */
    public function getCustomer($customerId) {
        return $this->customer->getCustomerById($customerId);
    }
    
    /**
     * Update customer
     */
    public function updateCustomer($kwargs) {
        // Validate required fields
        $requiredFields = ['customer_id', 'name', 'email', 'country', 'city', 'contact'];
        
        foreach ($requiredFields as $field) {
            if (!isset($kwargs[$field]) || empty(trim($kwargs[$field]))) {
                return array(
                    'status' => 'error',
                    'message' => ucfirst($field) . ' is required.'
                );
            }
        }
        
        $customerId = intval($kwargs['customer_id']);
        $name = trim($kwargs['name']);
        $email = trim(strtolower($kwargs['email']));
        $country = trim($kwargs['country']);
        $city = trim($kwargs['city']);
        $contact = trim($kwargs['contact']);
        $image = isset($kwargs['image']) ? trim($kwargs['image']) : null;
        
        // Additional validation with correct field lengths
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return array(
                'status' => 'error',
                'message' => 'Please enter a valid email address.'
            );
        }
        
        if (strlen($email) > 50) {
            return array(
                'status' => 'error',
                'message' => 'Email address is too long (maximum 50 characters).'
            );
        }
        
        if (strlen($name) < 2 || strlen($name) > 100) {
            return array(
                'status' => 'error',
                'message' => 'Name must be between 2 and 100 characters.'
            );
        }
        
        if (strlen($country) > 30) {
            return array(
                'status' => 'error',
                'message' => 'Country name is too long (maximum 30 characters).'
            );
        }
        
        if (strlen($city) > 30) {
            return array(
                'status' => 'error',
                'message' => 'City name is too long (maximum 30 characters).'
            );
        }
        
        if (strlen($contact) < 10 || strlen($contact) > 15) {
            return array(
                'status' => 'error',
                'message' => 'Contact number must be between 10 and 15 characters.'
            );
        }
        
        // Call customer model to update customer
        return $this->customer->editCustomer($customerId, $name, $email, $country, $city, $contact, $image);
    }
    
    /**
     * Delete customer
     */
    public function removeCustomer($customerId) {
        if (empty($customerId) || !is_numeric($customerId)) {
            return array(
                'status' => 'error',
                'message' => 'Invalid customer ID.'
            );
        }
        
        return $this->customer->deleteCustomer(intval($customerId));
    }
}

/**
 * Standalone controller function required by Activity 2
 * Accepts email & password, uses model->login, sets session on success.
 */
function login_customer_ctr($email, $password) {
    // Normalize & validate
    $email = trim($email);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return array('status'=>'error','message'=>'Invalid email address.');
    }
    if ($password === '') {
        return array('status'=>'error','message'=>'Password is required.');
    }
    try {
        $model = new Customer(); // uses DatabaseConnection internally
        $res = $model->login($email, $password);
        if ($res['status'] === 'success') {
            if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
            }
            $cust = $res['customer'];
            $_SESSION['customer_id'] = $cust['customer_id'];
            $_SESSION['customer_name'] = $cust['customer_name'];
            $_SESSION['customer_email'] = $cust['customer_email'];
            $_SESSION['user_role'] = $cust['user_role'];
        }
        return $res;
    } catch (Exception $e) {
        return array('status'=>'error','message'=>'Server error: '.$e->getMessage());
    }
}

?>