<?php
require_once 'vendor/autoload.php';

function verifyGoogleToken($idToken)
{
    $client = new Google_Client(['client_id' => 'YOUR_GOOGLE_CLIENT_ID']); // Replace with your Google Client ID
    try {
        // Verify the token
        $payload = $client->verifyIdToken($idToken);

        if ($payload) {
            // Token is valid; extract user information
            $userId = $payload['sub']; // Google user ID
            $email = $payload['email'];
            $name = $payload['name'];
            $picture = $payload['picture'];

            // Return user information
            return [
                'status' => 'success',
                'userId' => $userId,
                'email' => $email,
                'name' => $name,
                'picture' => $picture,
            ];
        } else {
            // Invalid token
            return ['status' => 'error', 'message' => 'Invalid ID token'];
        }
    } catch (Exception $e) {
        // Handle token verification errors
        return ['status' => 'error', 'message' => $e->getMessage()];
    }
}

// Example usage
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idToken = $_POST['idToken'];

    if ($idToken) {
        $result = verifyGoogleToken($idToken);
        echo json_encode($result);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No token provided']);
    }
}
?>
