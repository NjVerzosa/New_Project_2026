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

// Function to convert 24-hour time to 12-hour format
function convertTo12Hour($time24)
{
    $time = DateTime::createFromFormat('H:i', $time24);
    return $time ? $time->format('g:i A') : $time24;
}

// Function to format date as "Mar 22, 2025"
function formatDate($date)
{
    $dateObj = DateTime::createFromFormat('Y-m-d', $date);
    return $dateObj ? $dateObj->format('M j, Y') : $date;
}


if (isset($_POST['update'])) {
    $email = $_POST['email'];
    $payment_status = $_POST['payment_status'];

    // Get current timestamp for payment_submitted_at
    date_default_timezone_set('Asia/Manila');
    $date = date('D, j M Y');

    $query = "UPDATE users SET payment_ref = NULL, payment_status = ?, payment_submitted_at = ? WHERE email = ?";
    if ($stmt = $con->prepare($query)) {
        // Bind parameters and execute the query
        $stmt->bind_param("sss", $payment_status, $date, $email);
        if ($stmt->execute()) {
            alertAndRedirect("Payment status updated successfully for $email.", "admin-Paid-Players.php");
        } else {
            alertAndRedirect("Error updating password. Please try again.", "admin-Paid-Players.php");
        }
        $stmt->close();
    } else {
        alertAndRedirect("Failed to prepare the query. Please try again.", "admin-Paid-Players.php");
    }
}
