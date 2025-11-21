<?php
/**
 * EDU Career India - Admin Configuration
 * Uses main site config for database connection
 */

// Include main site configuration
require_once dirname(dirname(__DIR__)) . '/config.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Development mode - set to false in production
define('DEV_MODE', true);

// Error reporting
if (DEV_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Admin-specific configuration
define('ADMIN_URL', SITE_URL . 'admin');

// Upload paths
define('UPLOAD_DIR', dirname(dirname(__DIR__)) . '/uploads/');
define('UPLOAD_URL', '/uploads/');
define('MAX_UPLOAD_SIZE', 10 * 1024 * 1024); // 10MB

// Allowed image types
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/gif']);
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'webp', 'gif']);

// $pdo is already defined in main config.php and ready to use
// No additional database connection needed

// Helper function to sanitize output
function escape($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Helper function to redirect
function redirect($url) {
    header("Location: " . $url);
    exit;
}

// Helper function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

// Helper function to require login
function requireLogin() {
    if (!isLoggedIn()) {
        redirect(ADMIN_URL . '/login.php');
    }
}

// Helper function to generate CSRF token
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Helper function to verify CSRF token
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Helper function to show success message
function setSuccessMessage($message) {
    $_SESSION['success_message'] = $message;
}

// Helper function to show error message
function setErrorMessage($message) {
    $_SESSION['error_message'] = $message;
}

// Helper function to get and clear messages
function getSuccessMessage() {
    if (isset($_SESSION['success_message'])) {
        $msg = $_SESSION['success_message'];
        unset($_SESSION['success_message']);
        return $msg;
    }
    return null;
}

function getErrorMessage() {
    if (isset($_SESSION['error_message'])) {
        $msg = $_SESSION['error_message'];
        unset($_SESSION['error_message']);
        return $msg;
    }
    return null;
}
