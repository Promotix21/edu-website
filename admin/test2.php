<?php
echo "TEST 1: PHP is working<br>";

echo "TEST 2: Trying to include config...<br>";
try {
    require_once '../config.php';
    echo "TEST 3: Config loaded successfully!<br>";
    echo "TEST 4: PDO object exists: " . (isset($pdo) ? 'YES' : 'NO') . "<br>";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . "<br>";
    echo "Line: " . $e->getLine() . "<br>";
}
?>
