<?php
include './config.php';

// Helper function to alert and redirect with proper escaping
function alertAndRedirect($message, $redirectUrl)
{
    echo "<script>
            alert('" . addslashes($message) . "');
            window.location.href = '" . htmlspecialchars($redirectUrl, ENT_QUOTES) . "';
          </script>";
    exit;
}

// Function to process approved requests
function processApprovedRequest($con, $player_id, $acc_number)
{
    try {

        // Get user data
        $stmt = $con->prepare("SELECT id FROM withdrawals WHERE id = ?");
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $con->error);
        }
        $stmt->bind_param("i", $player_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result || !$get = $result->fetch_assoc()) {
            throw new Exception("User not found or error occurred while retrieving data.");
        }

        // Start transaction
        $con->begin_transaction();

        try {
            // Update users table
            $updateQuery = "UPDATE withdrawals SET status = 'APPROVED' WHERE id = ?";
            $stmt = $con->prepare($updateQuery);
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $con->error);
            }
            $stmt->bind_param("i", $player_id);
            if (!$stmt->execute()) {
                throw new Exception("Update failed: " . $stmt->error);
            }

            $con->commit();
            alertAndRedirect("Request for $acc_number has been APPROVED.", "admin-Mailer.php");
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    } catch (Exception $e) {
        alertAndRedirect("Error: " . $e->getMessage(), "admin-Mailer.php");
    }
}

// Function to process declined requests
function processDeclinedRequest($con, $player_id, $acc_number)
{
    try {

        // Get user data
        $stmt = $con->prepare("SELECT id FROM withdrawals WHERE id = ?");
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $con->error);
        }
        $stmt->bind_param("i", $player_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result || !$get = $result->fetch_assoc()) {
            throw new Exception("User not found or error occurred while retrieving data.");
        }

        // Start transaction
        $con->begin_transaction();

        try {
            // Update users table
            $updateQuery = "UPDATE withdrawals SET status = 'FAILED' WHERE id = ?";
            $stmt = $con->prepare($updateQuery);
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $con->error);
            }
            $stmt->bind_param("i", $player_id);
            if (!$stmt->execute()) {
                throw new Exception("Update failed: " . $stmt->error);
            }

            $con->commit();
            alertAndRedirect("Request for $acc_number has been FAILED.", "admin-Mailer.php");
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    } catch (Exception $e) {
        alertAndRedirect("Error: " . $e->getMessage(), "admin-Mailer.php");
    }
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send'])) {
    try {
        // Validate inputs
        $player_id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
        $acc_number = filter_var($_POST['acc_number'], FILTER_VALIDATE_INT);
        if ($player_id === false) {
            throw new Exception("Invalid player ID.");
        }


        $status = filter_var($_POST['status'], FILTER_SANITIZE_STRING);
        if (!in_array($status, ['APPROVED', 'FAILED'])) {
            throw new Exception("Invalid request status.");
        }

        // Process request based on status
        if ($status === "APPROVED") {
            processApprovedRequest($con, $player_id, $acc_number);
        } else {
            processDeclinedRequest($con, $player_id, $acc_number);
        }
    } catch (Exception $e) {
        alertAndRedirect("Error: " . $e->getMessage(), "admin-Mailer.php");
    }
}



// Function to handle unbanned status (account = 0)
function handleUnbannedStatus($con, $acc_number)
{
    $query = "UPDATE users SET account = 0, unrecognized_device = 0 WHERE acc_number = ?";
    $stmt = mysqli_prepare($con, $query);

    if (!$stmt) {
        throw new Exception("Database error: " . mysqli_error($con));
    }

    mysqli_stmt_bind_param($stmt, 'i', $acc_number);
    return mysqli_stmt_execute($stmt);
}

// Function to handle banned status (account = 1)
function handleBannedStatus($con, $acc_number)
{
    $query = "UPDATE users SET account = 1, unrecognized_device = 0 WHERE acc_number = ?";
    $stmt = mysqli_prepare($con, $query);

    if (!$stmt) {
        throw new Exception("Database error: " . mysqli_error($con));
    }

    mysqli_stmt_bind_param($stmt, 'i', $acc_number);
    return mysqli_stmt_execute($stmt);
}

// Function to process form submission
function processFormSubmission($con)
{
    if (!isset($_POST['update'])) {
        return;
    }

    // Validate and sanitize input
    if (empty($_POST['acc_number']) || !is_numeric($_POST['acc_number'])) {
        $_SESSION['error'] = "Invalid account number";
        redirectToManagement();
    }

    $acc_number = intval($_POST['acc_number']);
    $accountStatus = isset($_POST['account']) ? intval($_POST['account']) : null;

    try {
        if ($accountStatus === 0) {
            if (handleUnbannedStatus($con, $acc_number)) {
                $_SESSION['success'] = "Account $acc_number has been unbanned";
            } else {
                throw new Exception("Failed to unban account");
            }
        } elseif ($accountStatus === 1) {
            if (handleBannedStatus($con, $acc_number)) {
                $_SESSION['success'] = "Account $acc_number has been banned";
            } else {
                throw new Exception("Failed to ban account");
            }
        } else {
            $_SESSION['error'] = "Invalid account status selected";
        }
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }

    redirectToManagement();
}


// Helper function for redirect
function redirectToManagement()
{
    header("Location: admin-Management.php");
    exit();
}

// Main execution
session_start();
try {
    processFormSubmission($con);
} catch (Exception $e) {
    $_SESSION['error'] = "System error: " . $e->getMessage();
    redirectToManagement();
} finally {
    if (isset($con)) {
        $con->close();
    }
}



// // Delete device_id
// // Function to handle NULL status
// function handleDevice_id($con, $email)
// {
//     $query = "UPDATE users SET attempted_status = 0, device_id = NULL WHERE email = ?";
//     $stmt = mysqli_prepare($con, $query);
//     mysqli_stmt_bind_param($stmt, 's', $email);

//     return mysqli_stmt_execute($stmt);
// }

// // Function to process form submission
// function device_deletion($con)
// {
//     if (isset($_POST['delete'])) {
//         // Sanitize input
//         $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

//         if (handleDevice_id($con, $email)) {
//             header("Location: admin-Management.php");
//             exit;
//         } else {
//             header("Location: admin-Management.php");
//             exit;
//         }
//     }
// }

// // Call the function to process form submission
// device_deletion($con);
