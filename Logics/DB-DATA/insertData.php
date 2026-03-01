<?php
session_start();
include __DIR__ . 'DB_Connection/pdo.php';

$accNumber = isset($_POST['acc_number']) ? intval($_POST['acc_number']) : 0;
$email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : '';
$score = isset($_POST['score']) ? floatval($_POST['score']) : 0; // Changed to floatval to accept 0.25

if ($accNumber <= 0 || empty($email) || $score <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid input data']);
    exit;
}

try {
    $con->beginTransaction();

    // Update balance in users table
    $updateUserStmt = $con->prepare("UPDATE users SET balance = balance + :score WHERE acc_number = :acc_number AND email = :email");
    $updateUserStmt->execute([
        'score' => $score,
        'acc_number' => $accNumber,
        'email' => $email
    ]);

    // Save progress in data_entry table with current date
    date_default_timezone_set('Asia/Manila');
    $date = date('Y-m-d'); // Use proper date format for database

    // Check if record exists for today
    $stmt = $con->prepare("SELECT id, count FROM data_entry WHERE acc_number = :acc_number AND email = :email AND date = :date FOR UPDATE");
    $stmt->execute([
        'acc_number' => $accNumber,
        'email' => $email,
        'date' => $date
    ]);
    $progress = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($progress) {
        // Update existing record - increment count by 1
        $updateStmt = $con->prepare("UPDATE data_entry SET count = count + 1 WHERE id = :id");
        $updateStmt->execute([
            'id' => $progress['id']
        ]);

        if ($updateStmt->rowCount() === 0) {
            throw new Exception("No records updated in data_entry");
        }
    } else {
        // Insert new record
        $insertStmt = $con->prepare("INSERT INTO data_entry (acc_number, email, date, count) VALUES (:acc_number, :email, :date, 1)");
        $insertStmt->execute([
            'acc_number' => $accNumber,
            'email' => $email,
            'date' => $date
        ]);
    }

    $con->commit();
    echo json_encode(['success' => true, 'message' => 'Progress saved successfully']);
} catch (Exception $e) {
    $con->rollBack();
    echo json_encode(['success' => false, 'message' => 'Error saving progress: ' . $e->getMessage()]);
}
?>