<?php
require_once __DIR__ . '/../settings/db_class.php';

class Customer {
    private $db;

    public function __construct() {
        $this->db = new DB();
    }

    /**
     * Add new customer to database
     */
    public function addCustomer($name, $email, $password, $country, $city, $contact, $image = null, $userRole = 2) {
        // Check if email already exists
        if ($this->emailExists($email)) {
            return ['status' => 'error', 'message' => 'Email already exists. Please use a different email address.'];
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO customer (customer_name, customer_email, customer_pass, customer_country, customer_city, customer_contact, customer_image, user_role) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $params = [$name, $email, $hashedPassword, $country, $city, $contact, $image, $userRole];

        list($ok, $idOrAffected) = $this->db->write($sql, $params);
        if ($ok) {
            return ['status' => 'success', 'message' => 'Customer registered successfully!', 'customer_id' => $idOrAffected];
        }
        return ['status' => 'error', 'message' => 'Registration failed.'];
    }

    /**
     * Check if email already exists in database
     */
    public function emailExists($email) {
        $sql = "SELECT customer_id FROM customer WHERE customer_email = ?";
        $rows = $this->db->read($sql, [$email]);
        return !empty($rows);
    }
    
    /**
     * Get customer by ID
     */
    public function getCustomerById($customerId) {
        $sql = "SELECT * FROM customer WHERE customer_id = ? LIMIT 1";
        $rows = $this->db->read($sql, [$customerId]);
        return $rows[0] ?? false;
    }
    
    /**
     * Get customer by email
     */
    public function getCustomerByEmail($email) {
        $sql = "SELECT * FROM customer WHERE customer_email = ? LIMIT 1";
        $rows = $this->db->read($sql, [$email]);
        return $rows[0] ?? false;
    }
    
    /**
     * Update customer information
     */
    public function editCustomer($customerId, $name, $email, $country, $city, $contact, $image = null) {
        // Check if email exists for other customers
        $sql = "SELECT customer_id FROM customer WHERE customer_email = ? AND customer_id != ?";
        $rows = $this->db->read($sql, [$email, $customerId]);
        if (!empty($rows)) {
            return ['status' => 'error', 'message' => 'Email already exists for another customer.'];
        }

        $params = [$name, $email, $country, $city, $contact];
        $sql = "UPDATE customer SET customer_name = ?, customer_email = ?, customer_country = ?, customer_city = ?, customer_contact = ?";
        if ($image !== null) {
            $sql .= ", customer_image = ?";
            $params[] = $image;
        }
        $sql .= " WHERE customer_id = ?";
        $params[] = $customerId;

        list($ok, $info) = $this->db->write($sql, $params);
        if ($ok) return ['status' => 'success', 'message' => 'Customer updated successfully!'];
        return ['status' => 'error', 'message' => 'Update failed. Please try again.'];
    }
    
    /**
     * Delete customer
     */
    public function deleteCustomer($customerId) {
        $sql = "DELETE FROM customer WHERE customer_id = ?";
        list($ok, $info) = $this->db->write($sql, [$customerId]);
        if ($ok) return ['status' => 'success', 'message' => 'Customer deleted successfully!'];
        return ['status' => 'error', 'message' => 'Delete failed. Please try again.'];
    }

    /**
     * Login customer by email + password
     * Returns ['status'=>..., 'message'=>..., 'customer'=>array|null]
     */
    public function login($email, $password) {
        $user = $this->getCustomerByEmail($email);
        if (!$user) return ['status'=>'error','message'=>'Invalid email or password.','customer'=>null];
        $hash = $user['customer_pass'] ?? null;
        $ok = false;
        if ($hash) {
            if (preg_match('/^\$2[aby]\$/', $hash) || preg_match('/^\$argon2/', $hash)) {
                $ok = password_verify($password, $hash);
            } else {
                $ok = hash_equals($hash, $password);
            }
        }
        if (!$ok) return ['status'=>'error','message'=>'Invalid email or password.','customer'=>null];

        if (password_needs_rehash($hash, PASSWORD_DEFAULT)) {
            $newHash = password_hash($password, PASSWORD_DEFAULT);
            $this->db->write("UPDATE customer SET customer_pass = ? WHERE customer_id = ?", [$newHash, $user['customer_id']]);
            $user['customer_pass'] = $newHash;
        }
        return ['status'=>'success','message'=>'Login successful','customer'=>$user];
    }

}
?>