<?php
// Database connection parameters
$servername = "localhost";
$username = "root"; // Default username for XAMPP
$password = ""; // Default password for XAMPP
$dbname = "info";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get username and password from the form
$username = $_POST['username'];
$password = $_POST['password'];

// SQL query to check if the username and password match
$sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Username and password match
    session_start();
    $_SESSION['username'] = $username;
    echo "Login successful";
} else {
    // Username and password do not match
    echo "Invalid username or password";
}

$conn->close();
?>
