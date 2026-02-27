<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    session_regenerate_id(true);
}
header('Content-Type: application/json');

include __DIR__ . 'includes/config.php';

// Get username from POST data
$inputUsername = isset($_POST['username']) ? trim($_POST['username']) : '';

if (empty($inputUsername)) {
    echo json_encode(['error' => 'Username is required']);
    exit;
}

try {
    // Check username availability
    $stmt = $con->prepare("SELECT username FROM users WHERE username = ?");
    $stmt->bind_param("s", $inputUsername);
    $stmt->execute();
    $stmt->store_result();

    $response = [
        'exists' => $stmt->num_rows > 0,
        'username' => $inputUsername
    ];

    echo json_encode($response);
} catch (Exception $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
} finally {
    // Close connections if they exist
    if (isset($stmt)) $stmt->close();
    if (isset($con)) $con->close();
}
