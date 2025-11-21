<?php
/**
 * Admin User Creation Script
 * Run this file once to create a new admin user
 * Access: https://webxexpert.space/admin/create_admin.php
 * DELETE THIS FILE after creating the admin user!
 */

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include database config
require_once dirname(__DIR__) . '/config.php';

// NEW ADMIN CREDENTIALS - Change these as needed
$new_username = 'admin';
$new_password = 'admin123'; // Plain text password - will be hashed
$new_email = 'admin@educareerindia.com';

echo "<h2>Admin User Creation Script</h2>";
echo "<hr>";

try {
    // Hash the password using PHP's password_hash
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    echo "<p><strong>Step 1:</strong> Password hashed successfully</p>";
    echo "<p>Plain password: <code>" . htmlspecialchars($new_password) . "</code></p>";
    echo "<p>Hashed password: <code>" . htmlspecialchars($hashed_password) . "</code></p>";
    echo "<hr>";

    // Check if user already exists
    $check_stmt = $pdo->prepare("SELECT id FROM admin_users WHERE username = ?");
    $check_stmt->execute([$new_username]);
    $existing_user = $check_stmt->fetch();

    if ($existing_user) {
        echo "<p><strong>Step 2:</strong> User already exists, updating password...</p>";

        // Update existing user
        $update_stmt = $pdo->prepare("UPDATE admin_users SET password = ?, email = ?, updated_at = NOW() WHERE username = ?");
        $update_stmt->execute([$hashed_password, $new_email, $new_username]);

        echo "<p style='color: green;'><strong>✓ SUCCESS!</strong> Admin user password updated.</p>";
    } else {
        echo "<p><strong>Step 2:</strong> Creating new admin user...</p>";

        // Insert new user
        $insert_stmt = $pdo->prepare("INSERT INTO admin_users (username, password, email, created_at) VALUES (?, ?, ?, NOW())");
        $insert_stmt->execute([$new_username, $hashed_password, $new_email]);

        echo "<p style='color: green;'><strong>✓ SUCCESS!</strong> New admin user created.</p>";
    }

    echo "<hr>";
    echo "<h3>Login Credentials:</h3>";
    echo "<p><strong>URL:</strong> <a href='" . ADMIN_URL . "/login.php'>" . ADMIN_URL . "/login.php</a></p>";
    echo "<p><strong>Username:</strong> <code>" . htmlspecialchars($new_username) . "</code></p>";
    echo "<p><strong>Password:</strong> <code>" . htmlspecialchars($new_password) . "</code></p>";
    echo "<hr>";
    echo "<p style='color: red;'><strong>IMPORTANT:</strong> DELETE THIS FILE (create_admin.php) after successful login for security!</p>";

    // Verify the password works
    echo "<hr>";
    echo "<h3>Verification:</h3>";
    $verify_stmt = $pdo->prepare("SELECT id, username, password FROM admin_users WHERE username = ?");
    $verify_stmt->execute([$new_username]);
    $user = $verify_stmt->fetch();

    if ($user && password_verify($new_password, $user['password'])) {
        echo "<p style='color: green;'><strong>✓ VERIFIED!</strong> Password verification successful. You can now login.</p>";
    } else {
        echo "<p style='color: red;'><strong>✗ ERROR:</strong> Password verification failed. Please try again.</p>";
    }

} catch (PDOException $e) {
    echo "<p style='color: red;'><strong>Database Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
