<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "STEP 1: Loading config...<br>";
require_once __DIR__ . '/includes/config.php';
echo "✓ Config loaded<br>";

echo "STEP 2: Loading functions...<br>";
require_once __DIR__ . '/includes/functions.php';
echo "✓ Functions loaded<br>";

echo "STEP 3: Checking database tables...<br>";
try {
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "✓ Database has " . count($tables) . " tables:<br>";
    foreach ($tables as $table) {
        echo "&nbsp;&nbsp;- $table<br>";
    }
} catch (PDOException $e) {
    echo "✗ ERROR: " . $e->getMessage() . "<br>";
}

echo "<br>STEP 4: Testing contact_submissions query...<br>";
try {
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM contact_submissions");
    $result = $stmt->fetch();
    echo "✓ contact_submissions table exists, has " . $result['total'] . " rows<br>";
} catch (PDOException $e) {
    echo "✗ ERROR: " . $e->getMessage() . "<br>";
}

echo "<br>STEP 5: Testing testimonials query...<br>";
try {
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM testimonials");
    $result = $stmt->fetch();
    echo "✓ testimonials table exists, has " . $result['total'] . " rows<br>";
} catch (PDOException $e) {
    echo "✗ ERROR: " . $e->getMessage() . "<br>";
}

echo "<br>STEP 6: Testing helper functions...<br>";
try {
    $loggedIn = isLoggedIn();
    echo "✓ isLoggedIn() works, returned: " . ($loggedIn ? 'true' : 'false') . "<br>";
} catch (Exception $e) {
    echo "✗ ERROR: " . $e->getMessage() . "<br>";
}

echo "<br>STEP 7: Testing admin_users table...<br>";
try {
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM admin_users");
    $result = $stmt->fetch();
    echo "✓ admin_users table exists, has " . $result['total'] . " rows<br>";
} catch (PDOException $e) {
    echo "✗ ERROR: " . $e->getMessage() . "<br>";
}

echo "<br>ALL TESTS COMPLETED!<br>";
?>
