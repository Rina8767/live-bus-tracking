<?php
// Turn off error reporting (optional)
error_reporting(0);
ini_set('display_errors', 0);

// Allow cross-origin requests (CORS)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// Read the JSON input from the request body
$data = json_decode(file_get_contents("php://input"), true);

// Check for errors in JSON data
if (json_last_error() !== JSON_ERROR_NONE) {
    // Return a JSON error response
    echo json_encode(["status" => "error", "message" => "Invalid JSON data received"]);
    exit();
}

// Database credentials
$host = "localhost";
$username = "root";  // Replace with your MySQL username
$password = "";      // Replace with your MySQL password
$database = "bus_tracking";  // Replace with your database name

// Connect to the database
$conn = new mysqli($host, $username, $password, $database);

// Check for a successful connection
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]);
    exit();
}

// Check if data is received
if ($data && isset($data['latitude']) && isset($data['longitude'])) {
    $latitude = $data['latitude'];
    $longitude = $data['longitude'];

    // Prepare the SQL query to insert data into the locations table
    $sql = "INSERT INTO locations (latitude, longitude) VALUES (?, ?)";

    // Use prepared statements to prevent SQL injection
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("dd", $latitude, $longitude);  // Bind latitude and longitude as double values
        $stmt->execute();  // Execute the query to insert the data
        $stmt->close();  // Close the prepared statement

        // Return a success message in JSON format (this is the only response now)
        echo json_encode(["status" => "success", "message" => "Location saved"]);
    } else {
        // If the query failed, return an error message in JSON format
        echo json_encode(["status" => "error", "message" => "Failed to insert data"]);
    }
} else {
    // If no data is received, return an error message in JSON format
    echo json_encode(["status" => "error", "message" => "Invalid or missing data"]);
}

// Close the database connection
$conn->close();
?>
