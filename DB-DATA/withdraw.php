<?php
// Main execution function
function main()
{
    $con = initializeSession();
    handleRequest($con);
}

// Initialize session and CSRF token, and return database connection
function initializeSession()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
        session_regenerate_id(true);
    }

    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    // Include database connection and return it
    include __DIR__ . '/../../includes/pdo.php';
    return $con;
}

// Handle incoming requests
function handleRequest($con)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['withdraw'])) {
        handleWithdrawalRequest($con);
    }
}

// Sanitize and validate input data
function filterInput($data)
{
    return htmlspecialchars(stripslashes(trim($data)), ENT_QUOTES, 'UTF-8');
}

// Validate withdrawal form data
function validateWithdrawalForm(&$validation_errors)
{
    $gcash_number = filterInput($_POST['gcash_number'] ?? '');
    $receiver_name = filterInput($_POST['receiver_name'] ?? '');
    $amount = isset($_POST['amount']) ? (int)$_POST['amount'] : 0;

    // Validate GCash number
    if (empty($gcash_number)) {
        $validation_errors['gcash_number'] = "GCash number is required";
    } elseif (!preg_match('/^09\d{9}$/', $gcash_number)) {
        $validation_errors['gcash_number'] = "Invalid GCash number. Must be 11 digits starting with 09";
    }

    // Validate Receiver Name
    if (empty($receiver_name)) {
        $validation_errors['receiver_name'] = "Receiver name is required";
    } elseif (!preg_match('/^[a-zA-Z\s]+$/', $receiver_name)) {
        $validation_errors['receiver_name'] = "Invalid name. Only letters and spaces allowed";
    }

    // Validate Amount
    if ($amount < 20) {
        $validation_errors['amount'] = "Minimum withdrawal amount is ₱20";
    }

    return [
        'gcash_number' => $gcash_number,
        'receiver_name' => $receiver_name,
        'amount' => $amount
    ];
}

// Process withdrawal transaction
function processWithdrawal($con, $acc_number, $receiver_name, $gcash_number, $amount)
{
    $con->beginTransaction();

    try {
        // Lock and check user balance
        $stmt = $con->prepare("SELECT earned_coins FROM users WHERE acc_number = ? FOR UPDATE");
        $stmt->execute([$acc_number]);
        $user = $stmt->fetch();

        if (!$user) {
            throw new Exception('Account not found.');
        }

        if ($user['earned_coins'] < $amount) {
            throw new Exception('Insufficient balance for withdrawal.');
        }

        // Update balance
        $new_balance = $user['earned_coins'] - $amount;
        $stmt = $con->prepare("UPDATE users SET earned_coins = ? WHERE acc_number = ?");
        $stmt->execute([$new_balance, $acc_number]);

        // Create withdrawal record
        date_default_timezone_set('Asia/Manila');
        $date_requested = date('D, j M Y h:i A', time());

        $stmt = $con->prepare("INSERT INTO withdrawals (acc_number, receiver_name, gcash_number, amount, status, date_requested) VALUES (?, ?, ?, ?, 'IN PROCESS', ?)");
        $stmt->execute([$acc_number, $receiver_name, $gcash_number, $amount, $date_requested]);

        $con->commit();
        return true;
    } catch (Exception $e) {
        $con->rollBack();
        throw $e;
    }
}

// Handle withdrawal request
function handleWithdrawalRequest($con)
{
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        setSessionError("Invalid CSRF token");
        redirectToAccount();
    }

    $validation_errors = [];
    $form_data = validateWithdrawalForm($validation_errors);

    if (!empty($validation_errors)) {
        $_SESSION['validation_errors'] = $validation_errors;
        $_SESSION['form_data'] = $form_data;
        redirectToAccount();
    }

    try {
        $acc_number = filterInput($_POST['acc_number']);
        $success = processWithdrawal(
            $con,
            $acc_number,
            $form_data['receiver_name'],
            $form_data['gcash_number'],
            $form_data['amount']
        );

        if ($success) {
            setSessionSuccess("Withdrawal request submitted successfully!");
            redirectToAccount();
        }
    } catch (Exception $e) {
        setSessionError($e->getMessage());
        redirectToAccount();
    }
}

// Helper functions
function setSessionError($message)
{
    $_SESSION['error_message'] = $message;
}

function setSessionSuccess($message)
{
    $_SESSION['success_message'] = $message;
}

function redirectToAccount()
{
    header("Location: ../account.php#gcashForm");
    exit();
}

// Execute main function
try {
    main();
} catch (Exception $e) {
    setSessionError($e->getMessage());
    redirectToAccount();
}
