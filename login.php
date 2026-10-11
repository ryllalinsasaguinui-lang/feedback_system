<?php

session_start();
require_once "db.php";

// Only accept POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

// Get form data
$username = trim($_POST["username"] ?? "");
$password = $_POST["password"] ?? "";

// Check empty fields
if ($username === "" || $password === "") {

    $_SESSION["message"] = "Please enter both username and password.";
    $_SESSION["message_type"] = "warning";

    header("Location: index.php");
    exit;
}

// Find user
$sql = "SELECT id, username, password
        FROM users
        WHERE username = ?";

$stmt = $conn->prepare($sql);

// Check database error
if (!$stmt) {

    $_SESSION["message"] =
        "Something went wrong while processing your login.";

    $_SESSION["message_type"] = "error";

    header("Location: index.php");
    exit;
}

// Bind username
$stmt->bind_param("s", $username);

// Execute query
$stmt->execute();

$result = $stmt->get_result();

// Check if username exists
if ($result->num_rows === 1) {

    $user = $result->fetch_assoc();

    /*
     * If your database stores SHA-256 passwords,
     * use this:
     */
    if (hash("sha256", $password) === $user["password"]) {

        session_regenerate_id(true);

        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];

        $_SESSION["message"] =
            "Login successful! Welcome, " .
            $user["username"] . ".";

        $_SESSION["message_type"] = "success";

    } else {

        $_SESSION["message"] =
            "Login failed. Invalid username or password.";

        $_SESSION["message_type"] = "error";
    }

} else {

    $_SESSION["message"] =
        "Login failed. Invalid username or password.";

    $_SESSION["message_type"] = "error";
}

// Close
$stmt->close();
$conn->close();

// Return to main page
header("Location: index.php");
exit;

?>
