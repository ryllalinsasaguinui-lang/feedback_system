<?php

session_start();

require_once "db.php";


// ========================================
// ONLY ACCEPT POST REQUESTS
// ========================================

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}


// ========================================
// GET FORM DATA
// ========================================

$name = trim($_POST["name"] ?? "");
$feedback = trim($_POST["feedback"] ?? "");


// ========================================
// GET LOGGED-IN USER IF AVAILABLE
// ========================================

$user_id = $_SESSION["user_id"] ?? null;


// ========================================
// VALIDATE NAME
// ========================================

if ($name === "") {

    $_SESSION["message"] =
        "Please enter your name.";

    $_SESSION["message_type"] = "warning";

    header("Location: index.php");
    exit;
}


// ========================================
// VALIDATE FEEDBACK
// ========================================

if ($feedback === "") {

    $_SESSION["message"] =
        "Please enter your feedback.";

    $_SESSION["message_type"] = "warning";

    header("Location: index.php");
    exit;
}


// ========================================
// MINIMUM FEEDBACK LENGTH
// ========================================

if (strlen($feedback) < 10) {

    $_SESSION["message"] =
        "Feedback must contain at least 10 characters.";

    $_SESSION["message_type"] = "warning";

    header("Location: index.php");
    exit;
}


// ========================================
// INSERT INTO DATABASE
// ========================================

$sql = "INSERT INTO feedback
        (user_id, name, message)
        VALUES (?, ?, ?)";

$stmt = $conn->prepare($sql);


// ========================================
// SERVER-SIDE ERROR HANDLING
// ========================================

if (!$stmt) {

    $_SESSION["message"] =
        "Unable to process your feedback. Please try again.";

    $_SESSION["message_type"] = "error";

    header("Location: index.php");
    exit;
}


// ========================================
// BIND VALUES
// ========================================

$stmt->bind_param(
    "iss",
    $user_id,
    $name,
    $feedback
);


// ========================================
// EXECUTE INSERT
// ========================================

if ($stmt->execute()) {

    $_SESSION["message"] =
        "Thank you, " . $name .
        "! Your feedback has been submitted successfully.";

    $_SESSION["message_type"] = "success";

} else {

    $_SESSION["message"] =
        "Something went wrong while saving your feedback.";

    $_SESSION["message_type"] = "error";
}


// ========================================
// CLOSE CONNECTION
// ========================================

$stmt->close();
$conn->close();


// ========================================
// RETURN TO MAIN PAGE
// ========================================

header("Location: index.php");
exit;

?>
