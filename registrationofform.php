<?php
// Include the database connection file
require_once 'db.php';  // Ensure that the db.php file has correct database connection details

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get form data
    $name = isset($_POST['name']) ? trim($_POST['name']) : null;
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    $password = isset($_POST['password']) ? trim($_POST['password']) : null;
    $confirm_password = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : null;

    // Validate inputs
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        echo "<div class='message error'>Oops! Looks like some fields are missing. Please fill them out!</div>";
    } elseif ($password !== $confirm_password) {
        echo "<div class='message error'>Whoops! Your passwords don't match. Try again!</div>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<div class='message error'>Hmm, that doesn't look like a valid email address. Try again!</div>";
    } else {
        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Prepare SQL to insert the user into the database
        $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name, $email, $hashed_password);

        // Execute the query
        if ($stmt->execute()) {
            echo "<div class='message success'>Boom! You're all set. Welcome aboard! Redirecting to the login page...</div>";
            header("refresh:2;url=login page.html"); // Redirect to login page after 2 seconds
            exit(); // Ensure no further code is executed after redirection
        } else {
            echo "<div class='message error'>Bummer! Something went wrong while saving your data. Please try again!</div>";
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    }
}
?>
