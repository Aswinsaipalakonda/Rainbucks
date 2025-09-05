<?php
/**
 * Database Connection File
 * Connects to MySQL database with error handling
 */

// Include configuration
require_once __DIR__ . '/config.php';

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8mb4", $username, $password);
    
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Set default fetch mode to associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Optional: Uncomment the line below for debugging
    // echo "Database connection successful!";
    
} catch(PDOException $e) {
    // Log error and display user-friendly message
    error_log("Database Connection Error: " . $e->getMessage());
    die("Database connection failed. Please try again later.");
}

/**
 * Function to get database connection
 * @return PDO Database connection object
 */
function getConnection() {
    global $pdo;
    return $pdo;
}

/**
 * Function to close database connection
 */
function closeConnection() {
    global $pdo;
    $pdo = null;
}

/**
 * Function to execute prepared statements safely
 * @param string $sql SQL query with placeholders
 * @param array $params Parameters for the query
 * @return PDOStatement
 */
function executeQuery($sql, $params = []) {
    global $pdo;
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    } catch(PDOException $e) {
        error_log("Query Error: " . $e->getMessage());
        throw new Exception("Database query failed");
    }
}

/**
 * Function to fetch single row
 * @param string $sql SQL query
 * @param array $params Parameters for the query
 * @return array|false
 */
function fetchOne($sql, $params = []) {
    $stmt = executeQuery($sql, $params);
    return $stmt->fetch();
}

/**
 * Function to fetch multiple rows
 * @param string $sql SQL query
 * @param array $params Parameters for the query
 * @return array
 */
function fetchAll($sql, $params = []) {
    $stmt = executeQuery($sql, $params);
    return $stmt->fetchAll();
}

/**
 * Function to get last inserted ID
 * @return string
 */
function getLastInsertId() {
    global $pdo;
    return $pdo->lastInsertId();
}
?>
