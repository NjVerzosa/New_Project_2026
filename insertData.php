<?php
session_start();
include 'includes/pdo.php'; // Make sure this path is correct

$accNumber = isset($_POST['acc_number']) ? intval($_POST['acc_number']) : 0;
$email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : '';
$score = isset($_POST['score']) ? intval($_POST['score']) : 0;

if ($accNumber <= 0 || empty($email) || $score <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid input data']);
    exit;
}

try {
    $con->beginTransaction();

    date_default_timezone_set('Asia/Manila');
    $date = date('D, j M Y');

    // Calculate balance increment based on score: each point = 0.00100
    // Score 1 = 0.00100, Score 2 = 0.00200, Score 3 = 0.00300, etc.
    $balanceIncrement = $score * 0.00123;
    
    // Update balance in users table
    $updateUserStmt = $con->prepare("UPDATE users SET balance = balance + :increment WHERE acc_number = :acc_number AND email = :email");
    $updateUserStmt->execute([
        'increment' => $balanceIncrement,
        'acc_number' => $accNumber,
        'email' => $email
    ]);

    // Check if user was found
    if ($updateUserStmt->rowCount() === 0) {
        $con->rollBack();
        echo json_encode(['success' => false, 'message' => 'User not found']);
        exit;
    }

    // For data_entry count: ALWAYS 1 regardless of score
    $countValue = 1;
    
    // Check if record exists for this user and date
    $checkStmt = $con->prepare("SELECT id, count FROM data_entry WHERE acc_number = :acc_number AND date = :date");
    $checkStmt->execute([
        'acc_number' => $accNumber,
        'date' => $date
    ]);
    $existingRecord = $checkStmt->fetch(PDO::FETCH_ASSOC);

    if ($existingRecord) {
        // Update existing record - count becomes current count + 1
        $updateStmt = $con->prepare("UPDATE data_entry SET count = count + 1 WHERE acc_number = :acc_number AND date = :date");
        $updateStmt->execute([
            'acc_number' => $accNumber,
            'date' => $date
        ]);
    } else {
        // Insert new record with count = 1
        $insertStmt = $con->prepare("INSERT INTO data_entry (acc_number, email, date, count) VALUES (:acc_number, :email, :date, :count)");
        $insertStmt->execute([
            'acc_number' => $accNumber,
            'email' => $email,
            'date' => $date,
            'count' => $countValue
        ]);
    }

    $con->commit();
    echo json_encode(['success' => true, 'message' => 'Progress saved successfully']);
} catch (Exception $e) {
    $con->rollBack();
    echo json_encode(['success' => false, 'message' => 'Error saving progress: ' . $e->getMessage()]);
}
?>