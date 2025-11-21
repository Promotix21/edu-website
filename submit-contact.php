<?php
/**
 * EDU Career India - Contact Form Submission Handler
 */

// Include main configuration
require_once __DIR__ . '/config.php';

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/contact.php');
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
    redirect('/contact.php?error=' . urlencode($errorMsg));
}

// Insert into database
try {
    $stmt = $pdo->prepare("
        INSERT INTO contact_submissions (name, email, phone, course, city, message, submitted_at)
        VALUES (?, ?, ?, ?, ?, ?, NOW())
    ");

    $stmt->execute([$name, $email, $phone, $course, $city, $message]);

    // Redirect with success message
    redirect('/contact.php?success=1');

} catch (PDOException $e) {
    // Log error for debugging
    error_log("Contact form submission error: " . $e->getMessage());

    // Redirect with error
    redirect('/contact.php?error=submission_failed');
}
