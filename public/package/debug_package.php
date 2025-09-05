<?php
// Debug script for package routing
require_once '../../includes/db.php';

// Get package name from URL parameter
$package_name = $_GET['package'] ?? '';
echo "Requested package name: " . htmlspecialchars($package_name) . "<br>";

// Convert URL-friendly name to database name
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

try {
    // Check if packages table exists
    $tables = fetchAll("SHOW TABLES LIKE 'packages'");
    echo "Found packages table: " . (!empty($tables) ? "Yes" : "No") . "<br>";

    if (!empty($tables)) {
        // Get all active packages
        $all_packages = fetchAll("SELECT * FROM packages WHERE status = 'active'");
        echo "Active packages found: " . count($all_packages) . "<br>";
        echo "Active packages:<br>";
        foreach ($all_packages as $pkg) {
            echo "- " . htmlspecialchars($pkg['name']) . " (status: " . $pkg['status'] . ")<br>";
        }

        // Try to find the specific package
        if (!empty($db_package_name)) {
            $package = fetchOne("SELECT * FROM packages WHERE name = ? AND status = 'active'", [$db_package_name]);
            echo "<br>Looking for package: " . htmlspecialchars($db_package_name) . "<br>";
            echo "Package found: " . ($package ? "Yes" : "No") . "<br>";
        }
    }
} catch (Exception $e) {
    echo "Error: " . htmlspecialchars($e->getMessage());
}
