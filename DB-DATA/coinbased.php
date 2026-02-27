<?php
session_start();
include __DIR__ . './DB_DATA/pdo.php';

$accNumber = isset($_GET['acc_number']) ? intval($_GET['acc_number']) : 0;
$email = isset($_GET['email']) ? filter_var($_GET['email'], FILTER_SANITIZE_EMAIL) : '';

if ($accNumber <= 0 || empty($email)) {
    echo json_encode(['success' => false, 'message' => 'Invalid input data']);
    exit;
}

try {
    // Fetch earnedPoints from users table using acc_number
    $stmt = $con->prepare("SELECT earned_coins FROM users WHERE acc_number = :acc_number AND email = :email");
    $stmt->execute([
        'acc_number' => $accNumber,
        'email' => $email
    ]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'User not found']);
        exit;
    }

    $response = [
        'success' => true,
        'earned_coins' => '₱' . number_format((float)$user['earned_coins'], 2)
    ];

    echo json_encode($response);
} catch (Exception $e) {
    error_log("Error fetching user progress: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching user progress'
    ]);
}
