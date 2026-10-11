<?php

session_start();

// Remove all session variables
session_unset();

// Destroy the current session
session_destroy();

// Start a new session for the logout message
session_start();

$_SESSION["message"] =
    "You have been successfully logged out.";

$_SESSION["message_type"] = "success";

// Return to main page
header("Location: index.php");
exit;

?>
