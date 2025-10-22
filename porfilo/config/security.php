<?php
/**
 * Security Configuration
 * Portfolio Website - Jayaram
 */

// Security settings
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_TIME', 900); // 15 minutes
define('SESSION_TIMEOUT', 3600); // 1 hour

// CSRF Protection
class CSRFProtection {
    public static function generateToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    public static function validateToken($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}

// Rate Limiting
class RateLimiter {
    private static $attempts = [];
    
    public static function isRateLimited($ip, $action = 'login') {
        $key = $ip . '_' . $action;
        $now = time();
        
        if (!isset(self::$attempts[$key])) {
            self::$attempts[$key] = [];
        }
        
        // Remove old attempts
        self::$attempts[$key] = array_filter(self::$attempts[$key], function($timestamp) use ($now) {
            return ($now - $timestamp) < LOGIN_LOCKOUT_TIME;
        });
        
        return count(self::$attempts[$key]) >= MAX_LOGIN_ATTEMPTS;
    }
    
    public static function recordAttempt($ip, $action = 'login') {
        $key = $ip . '_' . $action;
        if (!isset(self::$attempts[$key])) {
            self::$attempts[$key] = [];
        }
        self::$attempts[$key][] = time();
    }
}

// Input Sanitization
class InputSanitizer {
    public static function sanitizeString($input) {
        return htmlspecialchars(trim(strip_tags($input)), ENT_QUOTES, 'UTF-8');
    }
    
    public static function sanitizeEmail($email) {
        return filter_var(trim($email), FILTER_SANITIZE_EMAIL);
    }
    
    public static function sanitizeUrl($url) {
        return filter_var(trim($url), FILTER_SANITIZE_URL);
    }
    
    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    public static function validateUrl($url) {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }
}

// SQL Injection Prevention
class SQLProtection {
    public static function escapeString($string) {
        return addslashes($string);
    }
    
    public static function validateInput($input, $type = 'string') {
        switch ($type) {
            case 'email':
                return self::validateEmail($input);
            case 'url':
                return self::validateUrl($input);
            case 'int':
                return is_numeric($input);
            case 'string':
            default:
                return is_string($input) && strlen($input) > 0;
        }
    }
    
    private static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    private static function validateUrl($url) {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }
}

// XSS Protection
class XSSProtection {
    public static function cleanOutput($data) {
        if (is_array($data)) {
            return array_map([self::class, 'cleanOutput'], $data);
        }
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
    
    public static function cleanInput($data) {
        if (is_array($data)) {
            return array_map([self::class, 'cleanInput'], $data);
        }
        return strip_tags(trim($data));
    }
}

// File Upload Security
class FileUploadSecurity {
    private static $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'];
    private static $maxFileSize = 5 * 1024 * 1024; // 5MB
    
    public static function validateFile($file) {
        $errors = [];
        
        // Check if file was uploaded
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            $errors[] = 'No file uploaded';
            return $errors;
        }
        
        // Check file size
        if ($file['size'] > self::$maxFileSize) {
            $errors[] = 'File too large. Maximum size is 5MB.';
        }
        
        // Check file type
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($fileExtension, self::$allowedTypes)) {
            $errors[] = 'Invalid file type. Allowed types: ' . implode(', ', self::$allowedTypes);
        }
        
        // Check MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        $allowedMimes = [
            'image/jpeg', 'image/png', 'image/gif',
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];
        
        if (!in_array($mimeType, $allowedMimes)) {
            $errors[] = 'Invalid file type detected';
        }
        
        return $errors;
    }
}

// Session Security
class SessionSecurity {
    public static function startSecureSession() {
        // Set secure session parameters
        ini_set('session.cookie_httponly', 1);
        ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
        ini_set('session.use_strict_mode', 1);
        
        session_start();
        
        // Regenerate session ID periodically
        if (!isset($_SESSION['last_regeneration'])) {
            $_SESSION['last_regeneration'] = time();
        } elseif (time() - $_SESSION['last_regeneration'] > 300) { // 5 minutes
            session_regenerate_id(true);
            $_SESSION['last_regeneration'] = time();
        }
    }
    
    public static function checkSessionTimeout() {
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT)) {
            session_unset();
            session_destroy();
            return false;
        }
        $_SESSION['last_activity'] = time();
        return true;
    }
}

// Logging
class SecurityLogger {
    public static function logSecurityEvent($event, $details = '') {
        $logFile = '../logs/security.log';
        $logDir = dirname($logFile);
        
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        $timestamp = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
        
        $logEntry = "[$timestamp] IP: $ip | Event: $event | Details: $details | User-Agent: $userAgent\n";
        
        file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
    
    public static function logLoginAttempt($username, $success) {
        $event = $success ? 'LOGIN_SUCCESS' : 'LOGIN_FAILED';
        $details = "Username: $username";
        self::logSecurityEvent($event, $details);
    }
    
    public static function logSuspiciousActivity($activity, $details = '') {
        self::logSecurityEvent('SUSPICIOUS_ACTIVITY', "$activity - $details");
    }
}
?>
