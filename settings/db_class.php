<?php
require_once __DIR__ . '/db_cred.php';

class DB {
    private $conn;

    public function __construct() {
        $host = defined('SERVER') ? SERVER : 'localhost';
        $user = defined('USERNAME') ? USERNAME : 'root';
        $pass = defined('PASSWD') ? PASSWD : '';
        $db   = defined('DATABASE') ? DATABASE : 'db';

        $this->conn = new mysqli($host, $user, $pass, $db);
        if ($this->conn->connect_error) {
            die('DB connection failed: ' . $this->conn->connect_error);
        }
        $this->conn->set_charset('utf8mb4');
    }

    public function getConn() {
        return $this->conn;
    }

    // Helper for SELECT queries: returns array of rows
    public function read($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return false;
        if ($params) {
            $types = '';
            foreach ($params as $p) { $types .= is_int($p) ? 'i' : 's'; }
            // bind_param requires references
            $refs = [];
            foreach ($params as $k => $v) { $refs[$k] = &$params[$k]; }
            array_unshift($refs, $types);
            call_user_func_array([$stmt, 'bind_param'], $refs);
        }
        if (!$stmt->execute()) { $stmt->close(); return false; }
        $res = $stmt->get_result();
        $rows = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();
        return $rows;
    }

    // Helper for INSERT/UPDATE/DELETE: returns [true, insert_id|affected_rows]
    public function write($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return [false, $this->conn->error];
        if ($params) {
            $types = '';
            foreach ($params as $p) { $types .= is_int($p) ? 'i' : 's'; }
            $refs = [];
            foreach ($params as $k => $v) { $refs[$k] = &$params[$k]; }
            array_unshift($refs, $types);
            call_user_func_array([$stmt, 'bind_param'], $refs);
        }
        if (!$stmt->execute()) { $err = $stmt->error; $stmt->close(); return [false, $err]; }
        $id = $this->conn->insert_id;
        $affected = $stmt->affected_rows;
        $stmt->close();
        return [true, $id ? $id : $affected];
    }

}

?>
