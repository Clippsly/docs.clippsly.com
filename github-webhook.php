<?php
// GitHub webhook handler script
$secret = '809bea947f08121de541a0eba8d0252f'; // Replace with the generated secret token

// Verify the webhook signature
$signature = $_SERVER['HTTP_X_HUB_SIGNATURE'] ?? '';
$payload = file_get_contents('php://input');
if (!verifySignature($secret, $signature, $payload)) {
    http_response_code(403);
    echo "Forbidden";
    exit();
}

// Decode the payload
$data = json_decode($payload, true);

// Handle different event types
switch ($_SERVER['HTTP_X_GITHUB_EVENT']) {
    case 'push':
        handlePushEvent();
        break;
    // Add more cases for other event types if needed
    default:
        echo "Unhandled event type: " . $_SERVER['HTTP_X_GITHUB_EVENT'];
}

// Function to verify the GitHub webhook signature
function verifySignature($secret, $signature, $payload) {
    $hash = 'sha1=' . hash_hmac('sha1', $payload, $secret);
    return hash_equals($hash, $signature);
}

// Function to handle push event
function handlePushEvent() {
    // Execute git pull command to fetch changes from the remote repository
    $output = shell_exec("git pull origin master 2>&1");
    echo $output; // Output the result of the git pull command
}
?>