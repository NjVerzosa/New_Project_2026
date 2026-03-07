<?php
session_start();
include 'includes/pdo.php'; // Make sure this path is correct

$accNumber = isset($_POST['acc_number']) ? intval($_POST['acc_number']) : 0;
$email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : '';
$score = isset($_POST['score']) ? floatval($_POST['score']) : 0;

if ($accNumber <= 0 || empty($email) || $score <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid input data']);
    exit;
}

try {
    $con->beginTransaction();

    date_default_timezone_set('Asia/Manila');
    $date = date('D, j M Y');

    // Update balance in users table
    $updateUserStmt = $con->prepare("UPDATE users SET balance = balance + :score WHERE acc_number = :acc_number AND email = :email");
    $updateUserStmt->execute([
        'score' => $score,
        'acc_number' => $accNumber,
        'email' => $email
    ]);

    // Check if user was found
    if ($updateUserStmt->rowCount() === 0) {
        $con->rollBack();
        echo json_encode(['success' => false, 'message' => 'User not found']);
        exit;
    }

    // Check if record exists for this user and date
    $checkStmt = $con->prepare("SELECT id, count FROM data_entry WHERE acc_number = :acc_number AND date = :date");
    $checkStmt->execute([
        'acc_number' => $accNumber,
        'date' => $date
    ]);
    $existingRecord = $checkStmt->fetch(PDO::FETCH_ASSOC);

    if ($existingRecord) {
        // Update existing record
        $updateStmt = $con->prepare("UPDATE data_entry SET count = count + :count WHERE acc_number = :acc_number AND date = :date");
        $updateStmt->execute([
            'count' => $score,
            'acc_number' => $accNumber,
            'date' => $date
        ]);
    } else {
        // Insert new record
        $insertStmt = $con->prepare("INSERT INTO data_entry (acc_number, email, date, count) VALUES (:acc_number, :email, :date, :count)");
        $insertStmt->execute([
            'acc_number' => $accNumber,
            'email' => $email,
            'date' => $date,
            'count' => $score
        ]);
    }

    $con->commit();
    echo json_encode(['success' => true, 'message' => 'Progress saved successfully']);
} catch (Exception $e) {
    $con->rollBack();
    echo json_encode(['success' => false, 'message' => 'Error saving progress: ' . $e->getMessage()]);
}
?>
