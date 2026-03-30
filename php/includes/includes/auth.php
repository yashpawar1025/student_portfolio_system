<?php
require_once 'config.php'; // Include database config

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']); 
}

// Redirect to login if not authenticated
function redirectIfNotLoggedIn() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

// Fetch user data from database
function getUser($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $id); // 'i' = integer type
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc(); // Get user data as array
}
?>