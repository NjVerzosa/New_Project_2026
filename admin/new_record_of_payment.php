<?php
include './config.php';

// Helper function to alert and redirect
function alertAndRedirect($message, $redirectUrl)
{
    echo "<script>
            alert('" . addslashes($message) . "');
            window.location.href = '" . htmlspecialchars($redirectUrl, ENT_QUOTES) . "';
          </script>";
    exit;
}



function InsertPayments($con, $ref_number, $transfer_to, $transfer_from)
{
    try {
        // Validate input parameters
        if (empty($ref_number)) {
            throw new Exception("Reference number cannot be empty");
        }

        // Start transaction
        $con->begin_transaction();

        // 1. Check for duplicate reference number
        $checkStmt = $con->prepare("SELECT id FROM payments WHERE ref_number = ?");
        if (!$checkStmt) {
            throw new Exception("Prepare failed: " . $con->error);
        }
        $checkStmt->bind_param("s", $ref_number);
        $checkStmt->execute();

        if ($checkStmt->get_result()->num_rows > 0) {
            throw new Exception("Payment with this reference number already exists");
        }

        // 2. Find matching user (exact case-sensitive match)
        $userStmt = $con->prepare("SELECT acc_number, email FROM users WHERE payment_ref = ? LIMIT 1");
        if (!$userStmt) {
            throw new Exception("Prepare failed: " . $con->error);
        }
        $userStmt->bind_param("s", $ref_number);
        $userStmt->execute();
        $userResult = $userStmt->get_result();
        $userData = $userResult->fetch_assoc();


        // 3. Insert payment record
        if ($userData) {

            date_default_timezone_set('Asia/Manila');
            $today = date('D, j M Y');
            $timeNow = date('g:i A');

            // Combine date and time
            $date_time = $today . ' -:- ' . $timeNow;

            // Payment with matching user
            $insertStmt = $con->prepare("INSERT INTO payments (date_time, ref_number, transfer_to, transfer_from, status, acc_number, email) VALUES (?, ?, ?, ?,'SUCCESS', ?, ?)");

            if (!$insertStmt) {
                throw new Exception("Prepare failed: " . $con->error);
            }

            $insertStmt->bind_param("ssssis", $date_time, $ref_number, $transfer_to, $transfer_from, $userData['acc_number'], $userData['email']);

            // Update user payment status
            $updateStmt = $con->prepare("UPDATE users SET payment_status = 'PAID' WHERE email = ?");
            $updateStmt->bind_param("s", $userData['email']);

            if (!$updateStmt->execute()) {
                throw new Exception("Failed to update user status: " . $updateStmt->error);
            }
        } else {
            date_default_timezone_set('Asia/Manila');
            $today = date('D, j M Y');
            $timeNow = date('g:i A');

            // Combine date and time
            $date_time = $today . ' -:- ' . $timeNow;

            // Payment without matching user
            $insertStmt = $con->prepare("INSERT INTO payments (date_time, ref_number, transfer_to, transfer_from, status) VALUES (?, ?, ?, ?, 'PENDING')");

            if (!$insertStmt) {
                throw new Exception("Prepare failed: " . $con->error);
            }

            $insertStmt->bind_param("ssss", $date_time, $ref_number, $transfer_to, $transfer_from);
        }

        // Execute the insert
        if (!$insertStmt->execute()) {
            throw new Exception("Failed to insert payment: " . $insertStmt->error);
        }

        // Commit transaction
        $con->commit();

        alertAndRedirect("Payment recorded successfully", "admin-Paid-Players.php");
    } catch (Exception $e) {
        // Rollback on error
        if (isset($con) && $con instanceof mysqli) {
            $con->rollback();
        }
        alertAndRedirect("Error: " . $e->getMessage(), "admin-Paid-Players.php");
    }
}


// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    try {
        // Validate and sanitize input
        $ref_number = trim(filter_var($_POST['ref_number'] ?? '', FILTER_SANITIZE_STRING));
        $transfer_from = trim(filter_var($_POST['transfer_from'] ?? '', FILTER_SANITIZE_STRING));
        $transfer_to = trim(filter_var($_POST['transfer_to'] ?? '', FILTER_SANITIZE_STRING));
        $date = trim(filter_var($_POST['date'] ?? '', FILTER_SANITIZE_STRING));
        $time = trim(filter_var($_POST['time'] ?? '', FILTER_SANITIZE_STRING));

        // Uncomment and customize these validations as needed
        /*
        if (empty($ref_number)) {
            throw new Exception("Reference number is required.");
        }
        if (empty($transfer_from) || !preg_match('/^[0-9]{11}$/', $transfer_from)) {
            throw new Exception("Please enter a valid 11-digit GCash number.");
        }
        if (empty($date) || !DateTime::createFromFormat('Y-m-d', $date)) {
            throw new Exception("Please enter a valid date in YYYY-MM-DD format.");
        }
        if (empty($time) || !DateTime::createFromFormat('H:i', $time)) {
            throw new Exception("Please enter a valid time in HH:MM format (24-hour).");
        }
        */

        // If all validations pass, insert payment
        InsertPayments($con, $ref_number, $transfer_to, $transfer_from, $date, $time);
    } catch (Exception $e) {
        alertAndRedirect("Error: " . $e->getMessage(), "admin-Payments.php");
    }
}
