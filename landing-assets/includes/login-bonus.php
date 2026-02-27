<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    session_regenerate_id(true);
}
include __DIR__ . '/../../includes/pdo.php';

// CSRF token generation (if not set)
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Generate a new token
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo json_encode(['success' => false, 'message' => 'CSRF token validation failed']);
        exit;
    }

    // Input sanitization
    $accNumber = isset($_POST['acc_number']) ? intval($_POST['acc_number']) : 0;
    $userEmail = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : '';

    // Ensure required inputs are present
    if ($accNumber <= 0 || empty($userEmail)) {
        echo json_encode(['success' => false, 'message' => 'Invalid user details']);
        exit;
    }

    try {
        $con->beginTransaction();

        // Step 1: Fetch the current daily_login_earnings
        $fetchQuery = "SELECT daily_login_earnings FROM users WHERE acc_number = :acc_number AND email = :email";
        $fetchStmt = $con->prepare($fetchQuery);
        $fetchStmt->bindParam(':acc_number', $accNumber, PDO::PARAM_INT);
        $fetchStmt->bindParam(':email', $userEmail, PDO::PARAM_STR);
        $fetchStmt->execute();

        $userData = $fetchStmt->fetch(PDO::FETCH_ASSOC);

        if (!$userData) {
            throw new Exception('User not found. Please verify the user details.');
        }

        $dailyLoginEarnings = (int) $userData['daily_login_earnings'];

        // Step 2: Add daily_login_earnings to earnedPoints
        $updateQuery = "UPDATE users SET earned_coins = earned_coins + :daily_login_earnings WHERE acc_number = :acc_number AND email = :email";
        $updateStmt = $con->prepare($updateQuery);
        $updateStmt->bindParam(':daily_login_earnings', $dailyLoginEarnings, PDO::PARAM_INT);
        $updateStmt->bindParam(':acc_number', $accNumber, PDO::PARAM_INT);
        $updateStmt->bindParam(':email', $userEmail, PDO::PARAM_STR);
        $updateStmt->execute();

        if ($updateStmt->rowCount() === 0) {
            throw new Exception('No records were updated. Please verify the user details.');
        }

        // Step 3: Reset daily_login_earnings to 0
        $resetQuery = "UPDATE users SET daily_login_earnings = 0 WHERE acc_number = :acc_number AND email = :email";
        $resetStmt = $con->prepare($resetQuery);
        $resetStmt->bindParam(':acc_number', $accNumber, PDO::PARAM_INT);
        $resetStmt->bindParam(':email', $userEmail, PDO::PARAM_STR);
        $resetStmt->execute();

        $con->commit();
        echo json_encode(['success' => true, 'message' => 'Points added to earnedPoints.']);
    } catch (Exception $e) {
        // Rollback transaction if error occurs
        if ($con->inTransaction()) {
            $con->rollBack();
        }

        // Error response
        echo json_encode(['success' => false, 'message' => 'Error processing your request: ' . $e->getMessage()]);
    } finally {
        // Close the connection
        $con = null;
    }
}
