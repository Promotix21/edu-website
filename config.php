<?php
/**
 * EDU Career India - Main Configuration File
 *
 * IMPORTANT: For shared hosting deployment, update the values below
 */

// ============================================
// DATABASE CONFIGURATION
// ============================================
// For shared hosting, change these values:
// - DB_HOST: Usually 'localhost' on shared hosting
// - DB_NAME: Your database name (e.g., 'username_educareer')
// - DB_USER: Your database username
// - DB_PASS: Your database password

define('DB_HOST', 'db');              // Change to 'localhost' for shared hosting
define('DB_NAME', 'educareer_db');    // Change to your actual database name
define('DB_USER', 'educareer_user');  // Change to your database username
define('DB_PASS', 'educareer_pass_2025'); // Change to your database password

// ============================================
// SITE CONFIGURATION
// ============================================
define('SITE_URL', 'https://www.educareerindia.com'); // Your actual domain
define('SITE_NAME', 'EDU Career India');
define('SITE_EMAIL', 'info@educareerindia.com');

// ============================================
// PATH CONFIGURATION
// ============================================
// Base path (root directory of your website)
define('BASE_PATH', dirname(__FILE__));

// Admin path
define('ADMIN_PATH', BASE_PATH . '/admin');

// Uploads directory (must be writable)
define('UPLOAD_DIR', BASE_PATH . '/uploads');
define('UPLOAD_URL', '/uploads');

// ============================================
// SECURITY CONFIGURATION
// ============================================
// Session configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Set to 1 if using HTTPS

// Error reporting (disable in production)
// For shared hosting, set to 0 after testing
error_reporting(E_ALL);
ini_set('display_errors', 0); // Change to 0 for production
ini_set('log_errors', 1);
ini_set('error_log', BASE_PATH . '/error.log');

// ============================================
// DATABASE CONNECTION
// ============================================
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    // Log error but don't expose details to users
    error_log("Database connection failed: " . $e->getMessage());

    // Show user-friendly error
    die("Sorry, we're experiencing technical difficulties. Please try again later.");
}

// ============================================
// HELPER FUNCTIONS
// ============================================

/**
 * Get page content from database
 */
function getContent($page, $section, $key, $default = '') {
    global $pdo;
    if (!$pdo) return $default;

    try {
        $stmt = $pdo->prepare("SELECT content_value FROM page_content WHERE page_name = ? AND section_name = ? AND content_key = ? AND is_active = 1");
        $stmt->execute([$page, $section, $key]);
        $result = $stmt->fetch();
        return $result ? $result['content_value'] : $default;
    } catch (Exception $e) {
        error_log("getContent error: " . $e->getMessage());
        return $default;
    }
}

/**
 * Get site statistics
 */
function getStat($key, $default = 0) {
    global $pdo;
    if (!$pdo) return $default;

    try {
        $stmt = $pdo->prepare("SELECT stat_value FROM site_statistics WHERE stat_key = ?");
        $stmt->execute([$key]);
        $result = $stmt->fetch();
        return $result ? $result['stat_value'] : $default;
    } catch (Exception $e) {
        error_log("getStat error: " . $e->getMessage());
        return $default;
    }
}

/**
 * Sanitize user input
 */
function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Check if user is logged in (for admin pages)
 */
function isLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

/**
 * Redirect to URL
 */
function redirect($url) {
    header("Location: " . $url);
    exit;
}
