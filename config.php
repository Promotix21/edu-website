<?php
/**
 * EDU Career India - Main Configuration File
 * Works for both Docker and Shared Hosting automatically
 */

// ============================================
// DATABASE CONFIGURATION - Auto Environment Detection
// ============================================

// Auto-detect Docker vs Shared Hosting
$isDocker = (gethostname() === 'web' || file_exists('/.dockerenv'));

// Docker Configuration
if ($isDocker) {
    define('DB_HOST', 'db');
    define('DB_NAME', 'educareer_db');
    define('DB_USER', 'educareer_user');
    define('DB_PASS', 'educareer_pass_2025');
}
// Shared Hosting Configuration
else {
    // Your actual shared hosting credentials
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'u588596519_eduwebx');
    define('DB_USER', 'u588596519_eduwebx');
    define('DB_PASS', '2~kPwXg^$U');
}

// ============================================
// SITE CONFIGURATION
// ============================================
define('SITE_URL', 'https://webxexpert.space/');
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

// Error reporting - FULL DEBUG MODE
error_reporting(E_ALL);
ini_set('display_errors', 1);  // SHOW ERRORS ON SCREEN
ini_set('display_startup_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', BASE_PATH . '/php_errors.log');

// Debug log
file_put_contents(BASE_PATH . '/debug.log',
    "\n=== Config loaded: " . date('Y-m-d H:i:s') . " ===\n" .
    "Is Docker: " . ($isDocker ? 'YES' : 'NO') . "\n" .
    "DB_HOST: " . DB_HOST . "\n" .
    "DB_NAME: " . DB_NAME . "\n" .
    "DB_USER: " . DB_USER . "\n",
    FILE_APPEND
);

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
    // Log error
    error_log("Database connection failed: " . $e->getMessage());
    file_put_contents(BASE_PATH . '/debug.log',
        "DB CONNECTION ERROR: " . $e->getMessage() . "\n",
        FILE_APPEND
    );

    // SHOW FULL ERROR FOR DEBUGGING
    die("<h1>Database Connection Error</h1><pre>" .
        "Error: " . $e->getMessage() . "\n" .
        "Host: " . DB_HOST . "\n" .
        "Database: " . DB_NAME . "\n" .
        "User: " . DB_USER . "\n" .
        "</pre>");
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
