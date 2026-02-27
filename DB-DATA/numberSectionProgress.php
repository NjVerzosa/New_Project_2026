<?php
session_start();
include __DIR__ . '/../../includes/pdo.php';

$accNumber = isset($_POST['acc_number']) ? intval($_POST['acc_number']) : 0;
$email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : '';
$score = isset($_POST['score']) ? intval($_POST['score']) : 0;

if ($accNumber <= 0 || empty($email)) {
    echo json_encode(['success' => false, 'message' => 'Invalid input data']);
    exit;
}

try {
    $con->beginTransaction();

    // Increament earned_coins (Real Money)
    $updateUserStmt = $con->prepare("UPDATE users SET earned_coins = earned_coins + 0.05 WHERE acc_number = :acc_number AND email = :email");
    $updateUserStmt->execute([
        'acc_number' => $accNumber,
        'email' => $email
    ]);

    // // Handle numberSectionProgress table using acc_number
    // $stmt = $con->prepare("SELECT id, score FROM numberSectionProgress WHERE acc_number = :acc_number AND email = :email FOR UPDATE");
    // $stmt->execute(['acc_number' => $accNumber, 'email' => $email]);
    // $progress = $stmt->fetch(PDO::FETCH_ASSOC);

    // if ($progress) {
    //     $updateStmt = $con->prepare("UPDATE numberSectionProgress SET score = score + :score WHERE id = :id");
    //     $updateStmt->execute([
    //         'score' => $score,
    //         'id' => $progress['id']
    //     ]);
    // } else {
    //     $insertStmt = $con->prepare("INSERT INTO numberSectionProgress (acc_number, email, score) VALUES (:acc_number, :email, :score)");
    //     $insertStmt->execute([
    //         'acc_number' => $accNumber,
    //         'email' => $email,
    //         'score' => $score
    //     ]);
    // }

    date_default_timezone_set('Asia/Manila');
    $date = date('D, j M Y');

    // Day-by-Day Task Progress
    $stmt = $con->prepare("SELECT id, score, date FROM encode_game WHERE acc_number = :acc_number AND email = :email AND date = :date FOR UPDATE");
    $stmt->execute([
        'acc_number' => $accNumber,
        'email' => $email,
        'date' => $date
    ]);
    $progress = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($progress) {
        $updateStmt = $con->prepare("UPDATE encode_game SET score = score + :score WHERE id = :id");
        $updateStmt->execute([
            'score' => $score,
            'id' => $progress['id']
        ]);

        if ($updateStmt->rowCount() === 0) {
            throw new Exception("No records updated in encode_game");
        }
    } else {
        $insertStmt = $con->prepare("INSERT INTO encode_game (acc_number, email, date, score) VALUES (:acc_number, :email, :date, :score)");
        $insertStmt->execute([
            'acc_number' => $accNumber,
            'email' => $email,
            'date' => $date,
            'score' => $score
        ]);
    }



    // Task Progress Purpose
    $stmt = $con->prepare("SELECT id, score FROM tasks WHERE acc_number = :acc_number AND email = :email AND date = :date FOR UPDATE");
    $stmt->execute([
        'acc_number' => $accNumber,
        'email' => $email,
        'date' => $date
    ]);
    $progress = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($progress) {
        $updateStmt = $con->prepare("UPDATE tasks SET score = score + :score WHERE id = :id");
        $updateStmt->execute([
            'score' => $score,
            'id' => $progress['id']
        ]);
    } else {
        $insertStmt = $con->prepare("INSERT INTO tasks (acc_number, email, date, score, status) VALUES (:acc_number, :email, :date, :score, 'Pending')");
        $insertStmt->execute([
            'acc_number' => $accNumber,
            'email' => $email,
            'date' => $date,
            'score' => $score
        ]);
    }

    // Update login time using acc_number (Real Time)
    $timeNow = date('g:i A');
    $updateLoginTimeStmt = $con->prepare("UPDATE users SET login_time = :login_time WHERE acc_number = :acc_number AND email = :email");
    $updateLoginTimeStmt->execute([
        'login_time' => $timeNow,
        'acc_number' => $accNumber,
        'email' => $email
    ]);

    $con->commit();
    echo json_encode(['success' => true, 'message' => 'Progress saved successfully']);
} catch (Exception $e) {
    $con->rollBack();
    echo json_encode(['success' => false, 'message' => 'Error saving progress: ' . $e->getMessage()]);
}
