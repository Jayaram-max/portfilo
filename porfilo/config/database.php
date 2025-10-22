<?php
/**
 * Database Configuration and Connection
 * Portfolio Website - Jayaram
 */

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'jayaram_portfolio');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

class Database {
    private $host = DB_HOST;
    private $db_name = DB_NAME;
    private $username = DB_USER;
    private $password = DB_PASS;
    private $charset = DB_CHARSET;
    private $conn;

    /**
     * Get database connection
     */
    public function getConnection() {
        $this->conn = null;

        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=" . $this->charset;
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $exception) {
            error_log("Connection error: " . $exception->getMessage());
            throw new Exception("Database connection failed");
        }

        return $this->conn;
    }

    /**
     * Close database connection
     */
    public function closeConnection() {
        $this->conn = null;
    }

    /**
     * Test database connection
     */
    public function testConnection() {
        try {
            $conn = $this->getConnection();
            if ($conn) {
                return true;
            }
        } catch (Exception $e) {
            return false;
        }
        return false;
    }
}

/**
 * Utility functions for database operations
 */
class DatabaseUtils {
    
    /**
     * Sanitize input data
     */
    public static function sanitize($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    /**
     * Validate email
     */
    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Get client IP address
     */
    public static function getClientIP() {
        $ipkeys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR');
        foreach ($ipkeys as $keyword) {
            if (array_key_exists($keyword, $_SERVER) && !empty($_SERVER[$keyword])) {
                return $_SERVER[$keyword];
            }
        }
        return $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    }

    /**
     * Log visitor information
     */
    public static function logVisitor($page = 'home') {
        try {
            $db = new Database();
            $conn = $db->getConnection();
            
            $ip = self::getClientIP();
            $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
            
            $stmt = $conn->prepare("INSERT INTO visitors (ip_address, user_agent, page_visited) VALUES (?, ?, ?)");
            $stmt->execute([$ip, $user_agent, $page]);
            
        } catch (Exception $e) {
            error_log("Visitor logging error: " . $e->getMessage());
        }
    }

    /**
     * Get site settings
     */
    public static function getSiteSettings() {
        try {
            $db = new Database();
            $conn = $db->getConnection();
            
            $stmt = $conn->prepare("SELECT setting_key, setting_value FROM site_settings");
            $stmt->execute();
            
            $settings = [];
            while ($row = $stmt->fetch()) {
                $settings[$row['setting_key']] = $row['setting_value'];
            }
            
            return $settings;
        } catch (Exception $e) {
            error_log("Settings fetch error: " . $e->getMessage());
            return [];
        }
    }
}

// Initialize database connection
try {
    $database = new Database();
    $db_connection = $database->getConnection();
} catch (Exception $e) {
    error_log("Database initialization failed: " . $e->getMessage());
    $db_connection = null;
}
?>
