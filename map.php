<?php
// Database connection
$servername = "localhost";
$username = "root"; // Update if needed
$password = ""; // Update if needed
$dbname = "bus_tracking";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed"]));
}

// Query to fetch the latest coordinates
$sql = "SELECT latitude, longitude FROM locations ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode([
        "status" => "success",
        "latitude" => $row['latitude'],
        "longitude" => $row['longitude']
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "No location data found"]);
}

$conn->close();
?>
