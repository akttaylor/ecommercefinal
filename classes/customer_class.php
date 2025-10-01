<?php
require_once __DIR__ . '/../db_connection.php';

/**
 * Customer Class - CORRECTED for your dbforlab.sql database
 */
class Customer extends DatabaseConnection {
    
    /**
     * Add new customer to database
     */
    public function addCustomer($name, $email, $password, $country, $city, $contact, $image = null, $userRole = 2) {
        try {
            // Get database connection
            $conn = $this->getConnection();
            
            // Check if email already exists
            if ($this->emailExists($email)) {
                return array(
                    'status' => 'error',
                    'message' => 'Email already exists. Please use a different email address.'
                );
            }
            
            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // CORRECTED: Using actual table name 'customer' and exact field names
            $sql = "INSERT INTO customer (customer_name, customer_email, customer_pass, customer_country, customer_city, customer_contact, customer_image, user_role) 
                    VALUES (:name, :email, :password, :country, :city, :contact, :image, :user_role)";
            
            $stmt = $conn->prepare($sql);
            
            // Bind parameters
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':country', $country);
            $stmt->bindParam(':city', $city);
            $stmt->bindParam(':contact', $contact);
            $stmt->bindParam(':image', $image);
            $stmt->bindParam(':user_role', $userRole);
            
            // Execute statement
            if ($stmt->execute()) {
                return array(
                    'status' => 'success',
                    'message' => 'Customer registered successfully!',
                    'customer_id' => $conn->lastInsertId()
                );
            } else {
                return array(
                    'status' => 'error',
                    'message' => 'Registration failed. Please try again.'
                );
            }
            
        } catch (PDOException $e) {
            return array(
                'status' => 'error',
                'message' => 'Database error: ' . $e->getMessage()
            );
        }
    }
    
    /**
     * Check if email already exists in database
     */
    public function emailExists($email) {
        try {
            $conn = $this->getConnection();
            $sql = "SELECT customer_id FROM customer WHERE customer_email = :email";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Get customer by ID
     */
    public function getCustomerById($customerId) {
        try {
            $conn = $this->getConnection();
            $sql = "SELECT * FROM customer WHERE customer_id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $customerId);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Get customer by email
     */
    public function getCustomerByEmail($email) {
        try {
            $conn = $this->getConnection();
            $sql = "SELECT * FROM customer WHERE customer_email = :email";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Update customer information
     */
    public function editCustomer($customerId, $name, $email, $country, $city, $contact, $image = null) {
        try {
            $conn = $this->getConnection();
            
            // Check if email exists for other customers
            $sql = "SELECT customer_id FROM customer WHERE customer_email = :email AND customer_id != :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':id', $customerId);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                return array(
                    'status' => 'error',
                    'message' => 'Email already exists for another customer.'
                );
            }
            
            // Update customer
            $sql = "UPDATE customer SET customer_name = :name, customer_email = :email, customer_country = :country, customer_city = :city, customer_contact = :contact";
            
            if ($image !== null) {
                $sql .= ", customer_image = :image";
            }
            
            $sql .= " WHERE customer_id = :id";
            
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':country', $country);
            $stmt->bindParam(':city', $city);
            $stmt->bindParam(':contact', $contact);
            $stmt->bindParam(':id', $customerId);
            
            if ($image !== null) {
                $stmt->bindParam(':image', $image);
            }
            
            if ($stmt->execute()) {
                return array(
                    'status' => 'success',
                    'message' => 'Customer updated successfully!'
                );
            } else {
                return array(
                    'status' => 'error',
                    'message' => 'Update failed. Please try again.'
                );
            }
            
        } catch (PDOException $e) {
            return array(
                'status' => 'error',
                'message' => 'Database error: ' . $e->getMessage()
            );
        }
    }
    
    /**
     * Delete customer
     */
    public function deleteCustomer($customerId) {
        try {
            $conn = $this->getConnection();
            $sql = "DELETE FROM customer WHERE customer_id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $customerId);
            
            if ($stmt->execute()) {
                return array(
                    'status' => 'success',
                    'message' => 'Customer deleted successfully!'
                );
            } else {
                return array(
                    'status' => 'error',
                    'message' => 'Delete failed. Please try again.'
                );
            }
            
        } catch (PDOException $e) {
            return array(
                'status' => 'error',
                'message' => 'Database error: ' . $e->getMessage()
            );
        }
    }

    /**
     * Login customer by email + password
     * Returns ['status'=>..., 'message'=>..., 'customer'=>array|null]
     */
    public function login($email, $password) {
        try {
            $conn = $this->getConnection();
            $sql = "SELECT * FROM customer WHERE customer_email = :email LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch();
            
            if (!$user) {
                return array('status'=>'error','message'=>'Invalid email or password.','customer'=>null);
            }
            $hash = $user['customer_pass'];
            $ok = false;
            if ($hash) {
                if (preg_match('/^\$2[aby]\$/', $hash) || preg_match('/^\$argon2/', $hash)) {
                    $ok = password_verify($password, $hash);
                } else {
                    // legacy plaintext fallback
                    $ok = hash_equals($hash, $password);
                }
            }
            if (!$ok) {
                return array('status'=>'error','message'=>'Invalid email or password.','customer'=>null);
            }
            // Optional: rehash if needed
            if (password_needs_rehash($hash, PASSWORD_DEFAULT)) {
                $newHash = password_hash($password, PASSWORD_DEFAULT);
                $upd = $conn->prepare("UPDATE customer SET customer_pass = :ph WHERE customer_id = :id");
                $upd->bindParam(':ph', $newHash);
                $upd->bindParam(':id', $user['customer_id']);
                $upd->execute();
                $user['customer_pass'] = $newHash;
            }
            return array('status'=>'success','message'=>'Login successful','customer'=>$user);
        } catch (PDOException $e) {
            return array('status'=>'error','message'=>'Database error: '.$e->getMessage(),'customer'=>null);
        }
    }

}
?>