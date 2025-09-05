<?php
/**
 * Logout Script
 * Destroys session and redirects to login page
 */

session_start();

// Destroy all session data
session_unset();
session_destroy();

// Redirect to login page with logout message
header('Location: login.php?logout=1');
exit();
?>
