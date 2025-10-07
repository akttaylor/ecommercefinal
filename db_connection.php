<?php
class DatabaseConnection {
    private $host = "localhost";
    private $db_name = "db";
    private $username = "root";
    private $password = "";
    protected $conn;

    public function __construct() {
        // Try to load project settings if available
        $cred = __DIR__ . '/settings/db_cred.php';
        if (file_exists($cred)) {
            require_once $cred;
            if (defined('SERVER')) $this->host = SERVER;
            if (defined('DATABASE')) $this->db_name = DATABASE;
            if (defined('USERNAME')) $this->username = USERNAME;
            if (defined('PASSWD')) $this->password = PASSWD;
        }
    }

    /**
     * Get PDO database connection
     */
    public function getConnection() {
        $this->conn = null;
        try {
            $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', $this->host, $this->db_name);
            $this->conn = new PDO($dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $exception) {
            // Keep same behavior as before: echo error (you may want to change this in production)
            echo 'Connection error: ' . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>