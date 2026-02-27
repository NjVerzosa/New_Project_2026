<?php
header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
    session_regenerate_id(true);
}
include __DIR__ . '/../../includes/pdo.php';

try {
    // Validate request method
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    // Validate required fields
    $required = ['acc_number', 'email', 'task_id', 'csrf_token'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("Missing required field: $field");
        }
    }

    // CSRF token validation
    if (!isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        throw new Exception('CSRF token validation failed');
    }

    // Sanitize inputs
    $acc_number = filter_var($_POST['acc_number'], FILTER_SANITIZE_STRING);
    $userEmail = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $taskId = filter_var($_POST['task_id'], FILTER_SANITIZE_NUMBER_INT);

    // Validate email
    if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email format');
    }

    // Begin transaction
    $con->beginTransaction();

    // Check if the task exists and belongs to the user
    $checkQuery = "SELECT id FROM tasks WHERE id = :task_id AND email = :email AND status != 'CLAIMED'";
    $stmt = $con->prepare($checkQuery);
    $stmt->bindParam(':task_id', $taskId, PDO::PARAM_INT);
    $stmt->bindParam(':email', $userEmail, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() === 0) {
        throw new Exception('Task not found or already claimed');
    }

    // Update task status
    $updateTaskQuery = "UPDATE tasks SET status = 'CLAIMED' WHERE id = :task_id";
    $stmt = $con->prepare($updateTaskQuery);
    $stmt->bindParam(':task_id', $taskId, PDO::PARAM_INT);
    $stmt->execute();

    // Update user balance
    $updateUserQuery = "UPDATE users SET earned_coins = earned_coins + 300.00 WHERE acc_number = :acc_number";
    $stmt = $con->prepare($updateUserQuery);
    $stmt->bindParam(':acc_number', $acc_number, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() === 0) {
        throw new Exception('User account not found');
    }

    // Commit transaction
    $con->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Task claimed successfully! 300 coins added to your Account.'
    ]);
} catch (Exception $e) {
    // Roll back transaction if active
    if (isset($con) && $con->inTransaction()) {
        $con->rollBack();
    }

    // Log the error (in a real application, you would log to a file)
    error_log("Claim Task Error: " . $e->getMessage());

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
