<?php
header('Content-Type: application/json');

// Start session and check CSRF token
session_start();
if (!isset($_SERVER['HTTP_X_CSRF_TOKEN']) || $_SERVER['HTTP_X_CSRF_TOKEN'] !== $_SESSION['csrf_token']) {
    http_response_code(403);
    die(json_encode(['error' => 'Invalid CSRF token']));
}

// Get the input
$data = json_decode(file_get_contents('php://input'), true);
$deviceId = $data['device_id'] ?? null;

if (!$deviceId) {
    http_response_code(400);
    die(json_encode(['error' => 'Device ID required']));
}

// Check against database (example using PDO)
try {
    $stmt = $con->prepare("SELECT device_id FROM users WHERE acc_number = :acc_number");
    $stmt->bindParam(':acc_number', $_SESSION['acc_number'], PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'match' => ($user && $user['device_id'] === $deviceId)
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}
