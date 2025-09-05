<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Starting test...<br>";

// Include database connection
try {
    require_once '../../includes/db.php';
    echo "Database connection file included successfully<br>";
} catch (Exception $e) {
    die("Failed to include db.php: " . $e->getMessage());
}

// Test database connection
try {
    $test = fetchOne("SELECT 1");
    echo "Database connection successful<br>";
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Get package name from URL
$package_name = $_GET['package'] ?? '';
echo "Requested package: " . htmlspecialchars($package_name) . "<br>";

// Check package mapping
$package_map = [
    'starter' => 'Starter Package',
    'professional' => 'Professional Package',
    'advanced' => 'Advanced Package',
    'expert' => 'Expert Package',
    'ultimate' => 'Ultimate Package',
    'super-ultimate' => 'Super Ultimate Package'
];

$db_package_name = $package_map[$package_name] ?? '';
echo "Mapped package name: " . htmlspecialchars($db_package_name) . "<br>";

// Try to fetch the package
try {
    $package = fetchOne("SELECT * FROM packages WHERE name = ? AND status = 'active'", [$db_package_name]);
    if ($package) {
        echo "Package found in database:<br>";
        echo "<pre>";
        print_r($package);
        echo "</pre>";
    } else {
        echo "Package not found in database<br>";
    }
} catch (Exception $e) {
    echo "Error fetching package: " . $e->getMessage() . "<br>";
}

// List all active packages
try {
    $all_packages = fetchAll("SELECT name, status FROM packages WHERE status = 'active'");
    echo "<br>All active packages:<br>";
    foreach ($all_packages as $pkg) {
        echo "- " . htmlspecialchars($pkg['name']) . " (status: " . $pkg['status'] . ")<br>";
    }
} catch (Exception $e) {
    echo "Error fetching all packages: " . $e->getMessage() . "<br>";
}
