<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['user_email'])) {
    // Destroy the session data
    session_destroy();
}

// Redirect to the home page
header("Location: home.php");
exit();
?>
