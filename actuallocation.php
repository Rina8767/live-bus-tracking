<?php
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

// Get the most recent location data (assuming you have a timestamp column or an auto-increment ID)
$sql = "SELECT latitude, longitude FROM locations ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch the data
    $row = $result->fetch_assoc();
    $latitude = $row['latitude'];
    $longitude = $row['longitude'];

    // Use Google Geocoding API to convert lat/long to an address
    $apiKey = "YOUR_GOOGLE_MAPS_API_KEY";  // Replace with your Google Maps API key
    $geocodeUrl = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$latitude,$longitude&key=$apiKey";

    // Make the API request
    $response = file_get_contents($geocodeUrl);
    $json = json_decode($response, true);

    // Check if the address is available
    if (isset($json['results'][0])) {
        $address = $json['results'][0]['formatted_address'];
    } else {
        $address = "Address not found";
    }

    // Return the location data along with the address
    echo json_encode([
        "status" => "success",
        "latitude" => $latitude,
        "longitude" => $longitude,
        "address" => $address
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "No location data found"]);
}

// Close the database connection
$conn->close();
?>
