<?php
// Database connection parameters
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

// Get form data from JSON request
$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'];
$password = $data['password']; // Note: You should hash the password for security
$email = $data['email'];

// Perform insert query
$sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";

if ($conn->query($sql) === TRUE) {
    // Insertion successful
    $response = array('success' => true);
} else {
    // Insertion failed
    $response = array('success' => false);
}

// Close connection
$conn->close();

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
