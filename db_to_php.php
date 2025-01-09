<?php
// Database connection
function connectDB() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "shop_db";
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Session management
function startSecureSession() {
    session_start();
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
}

// Admin authentication
function requireAdmin() {
    if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
        header("Location: SSSSJC.php");
        exit();
    }
}

// User authentication
function requireLogin() {
    if (!isset($_SESSION['username'])) {
        header("Location: SSSSJC.php");
        exit();
    }
}

// Database operations
function executeQuery($conn, $sql) {
    $result = $conn->query($sql);
    return $result;
}

// Sanitize input
function sanitizeInput($conn, $input) {
    return mysqli_real_escape_string($conn, $input);
}
