<?php
// Database connection settings
$host = "localhost"; // Replace with your host name
$dbname = "bus"; // Replace with your database name
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password

try {
    // Connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $message = htmlspecialchars($_POST['message']);

        // Prepare SQL statement
        $stmt = $pdo->prepare("INSERT INTO contact_form (name, email, message) VALUES (:name, :email, :message)");

        // Bind parameters and execute the query
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':message', $message);
        $stmt->execute();

        // Redirect to a thank-you page or display a success message
        echo "<h1>Thank you! Your message has been submitted successfully We will get back to you ASAP😉.</h1>";
    }
} catch (PDOException $e) {
    // Handle errors
    echo "Error: " . $e->getMessage();
}
?>
