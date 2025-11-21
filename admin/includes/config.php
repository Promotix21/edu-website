<?php
/**
 * EDU Career India - Admin Configuration
 * Uses main site config for database connection
 */

// Include main site configuration (provides $pdo, isLoggedIn(), redirect(), sanitize())
require_once dirname(dirname(__DIR__)) . '/config.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Development mode
define('DEV_MODE', true);

// Admin-specific configuration
define('ADMIN_URL', SITE_URL . 'admin');

// Upload paths (only define if not already defined in main config)
if (!defined('UPLOAD_DIR')) {
    define('UPLOAD_DIR', dirname(dirname(__DIR__)) . '/uploads/');
}
if (!defined('UPLOAD_URL')) {
    define('UPLOAD_URL', '/uploads/');
}
define('MAX_UPLOAD_SIZE', 10 * 1024 * 1024); // 10MB

// Allowed image types
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/gif']);
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'webp', 'gif']);

// Admin-specific helper functions (not in main config)

function requireLogin() {
    if (!isLoggedIn()) {
        redirect(ADMIN_URL . '/login.php');
    }
}

function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function setSuccessMessage($message) {
    $_SESSION['success_message'] = $message;
}

function setErrorMessage($message) {
    $_SESSION['error_message'] = $message;
}

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

// Alias for compatibility
function escape($string) {
    return sanitize($string);
}
