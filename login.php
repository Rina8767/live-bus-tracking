<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1); // Enable error reporting for debugging

// Include the database connection
require_once 'db.php'; // Make sure 'db.php' contains the correct database credentials

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get form data
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    $password = isset($_POST['password']) ? trim($_POST['password']) : null;

    // Validate inputs
    if (empty($email) || empty($password)) {
        echo "Please fill in all fields.";
        exit();
    }

    // Prepare SQL to check if user exists
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);

    // Execute the query
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        
        // Check if user exists
        if ($result->num_rows > 0) {
            // Fetch user data
            $user = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Password matches, log the user in
                $_SESSION['user_id'] = $user['id']; // Store user ID in session
                $_SESSION['email'] = $user['email']; // Store email in session
                
                // Redirect to dashboard after successful login
                header("Location: dashboard.html");
                exit();
            } else {
                echo "Invalid password.";
            }
        } else {
            echo "No user found with this email.";
        }
    } else {
        echo "Database error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
