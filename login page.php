<?php
// Database configuration
$conn = new mysqli('localhost', 'root', '', 'logininfo');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connection successful!";
}

?>
