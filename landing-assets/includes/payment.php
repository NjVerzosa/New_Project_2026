<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    session_regenerate_id(true);
}
include __DIR__ . '/../../includes/pdo.php';

// Initialize CSRF token if not set
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Basic sanitization
function filter($data)
{
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Validation functions
function validateGCashNumber($number)
{
    return preg_match('/^09\d{9}$/', $number);
}

function validateReferenceNumber($ref)
{
    return preg_match('/^[a-zA-Z0-9]{8,}$/', $ref);
}


// Handle form submission
function handleReviewRequest($con)
{
    if (!isset($_POST['process_payment'])) {
        return;
    }

    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || !hash_equals($_POST['csrf_token'], $_SESSION['csrf_token'])) {
        $_SESSION['error_message'] = "Security token mismatch";
        header("Location: ../activation");
        exit();
    }

    try {
        // Get filtered input
        $acc_number = filter($_POST['acc_number'] ?? '');
        $email = filter($_POST['email'] ?? '');
        $ref_number = filter($_POST['ref_number'] ?? '');
        $gcash_number = filter($_POST['gcash_number'] ?? '');

        // Validation
        $errors = [];

        if (!validateGCashNumber($gcash_number)) {
            $errors['gcash_number'] = 'GCash number must be 11 digits starting with 09';
        }

        if (!validateReferenceNumber($ref_number)) {
            $errors['ref_number'] = 'Reference number must be at least 8 alphanumeric characters';
        }


        // If there are validation errors, store them in session and redirect back
        if (!empty($errors)) {
            $_SESSION['validation_errors'] = $errors;
            $_SESSION['form_data'] = ['gcash_number' => $gcash_number, 'ref_number' => $ref_number];
            header("Location: ../activation");
            exit();
        }

        date_default_timezone_set('Asia/Manila');
        $today = date('D, j M Y');
        $timeNow = date('g:i A');

        // Combine date and time
        $date_time = $today . ' -:- ' . $timeNow;

        // Start transaction
        $con->beginTransaction();

        // Update user record
        $stmt = $con->prepare("UPDATE users SET payment_ref = ?, payment_submitted_at = ?, payment_status = 'PENDING' WHERE acc_number = ?");
        $stmt->execute([$ref_number, $date_time, $acc_number]);

        // Check if payment exists
        $checkPayment = $con->prepare("SELECT status FROM payments WHERE ref_number = ?");
        $checkPayment->execute([$ref_number]);
        $paymentData = $checkPayment->fetch();

        if ($paymentData) {
            if ($paymentData['status'] === 'PENDING') {
                // Update existing payment
                $stmt = $con->prepare("UPDATE payments SET acc_number = ?, email = ?, status = 'SUCCESS' WHERE ref_number = ?");
                $stmt->execute([$acc_number, $email, $ref_number]);

                // Update user status
                $stmt = $con->prepare("UPDATE users SET payment_status = 'PAID' WHERE payment_ref = ?");
                $stmt->execute([$ref_number]);
            }
        }

        $con->commit();
        header("Location: ../activation");
        exit();
    } catch (Exception $e) {
        $con->rollBack();
        $_SESSION['error_message'] = "Processing error: " . $e->getMessage();
        header("Location: ../activation");
        exit();
    }
}

// Main execution
handleReviewRequest($con);
