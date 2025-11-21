<?php
/**
 * EDU Career India - Contact Form Submission Handler
 */

// Database configuration
define('DB_HOST', 'db');
define('DB_NAME', 'educareer_db');
define('DB_USER', 'educareer_user');
define('DB_PASS', 'educareer_pass_2025');

// Connect to database
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    // Redirect back with error
    header('Location: /contact.php?error=database');
    exit;
}

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /contact.php');
    exit;
}

// Sanitize and validate input
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$course = trim($_POST['course'] ?? '');
$city = trim($_POST['city'] ?? '');
$message = trim($_POST['message'] ?? '');

// Validation
$errors = [];

if (empty($name) || strlen($name) < 2) {
    $errors[] = 'Please enter a valid name';
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address';
}

if (empty($phone) || !preg_match('/^[0-9+\s-]{10,15}$/', $phone)) {
    $errors[] = 'Please enter a valid phone number';
}

if (empty($course)) {
    $errors[] = 'Please select a course';
}

// If validation fails, redirect back with errors
if (!empty($errors)) {
    $errorMsg = implode(', ', $errors);
    header('Location: /contact.php?error=' . urlencode($errorMsg));
    exit;
}

// Insert into database
try {
    $stmt = $pdo->prepare("
        INSERT INTO contact_submissions (name, email, phone, course, city, message, submitted_at)
        VALUES (?, ?, ?, ?, ?, ?, NOW())
    ");

    $stmt->execute([$name, $email, $phone, $course, $city, $message]);

    // Redirect with success message
    header('Location: /contact.php?success=1');
    exit;

} catch (PDOException $e) {
    // Redirect with error
    header('Location: /contact.php?error=submission_failed');
    exit;
}
