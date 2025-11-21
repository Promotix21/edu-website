<?php
// Test if the issue is with file_put_contents
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "TEST 1: Starting<br>";

$testFile = dirname(__DIR__) . '/debug_test.log';
echo "TEST 2: Trying to write to: " . $testFile . "<br>";

try {
    $result = file_put_contents($testFile, "Test write\n");
    if ($result === false) {
        echo "TEST 3: file_put_contents returned FALSE<br>";
    } else {
        echo "TEST 3: Successfully wrote " . $result . " bytes<br>";
    }
} catch (Exception $e) {
    echo "TEST 3: Exception: " . $e->getMessage() . "<br>";
}

echo "TEST 4: Checking if file exists: " . (file_exists($testFile) ? 'YES' : 'NO') . "<br>";
?>
