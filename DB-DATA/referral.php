<?php
session_start();
include __DIR__ . '/../../includes/pdo.php';

if (isset($_POST['get_points'])) {
    $email = trim($_POST['email']);
    $referral_code = trim($_POST['referral_code']);
    $csrf_token = trim($_POST['csrf_token']);

    // Clear previous session messages
    unset($_SESSION['errors'], $_SESSION['success_message'], $_SESSION['error_message'], $_SESSION['csrf_token_error']);

    // Validate CSRF token
    if (!isset($_SESSION['csrf_token']) || $csrf_token !== $_SESSION['csrf_token']) {
        $_SESSION['error_message'] = "Invalid CSRF token. Please try again.";
        header("Location: ../account");
        exit();
    }

    try {
        // Get the user's own referral code to prevent self-referral
        $stmt = $con->prepare("SELECT my_referral FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            throw new Exception("User not found.");
        }

        $user_referral_code = $user['my_referral'];

        // Check if the referral code is the same as the user's own referral code
        if ($referral_code === $user_referral_code) {
            throw new Exception("You cannot use your own referral code.");
        }

        // Check if the referral code exists and get the owner's ID
        $stmt = $con->prepare("SELECT id FROM users WHERE my_referral = ?");
        $stmt->execute([$referral_code]);
        $owner = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$owner) {
            throw new Exception("This referral code does not exist.");
        }

        $code_id = $owner['id'];

        // Start transaction
        $con->beginTransaction();

        // Update inviter points
        if (!codeOwnerUpdate($con, $code_id)) {
            throw new Exception("Failed to update inviter points.");
        }

        // Update user balance and set inviter relationship
        if (!codeUserUpdate($con, $email, $referral_code)) {
            throw new Exception("Failed to update user balance.");
        }

        // Commit transaction
        $con->commit();
        $_SESSION['success_message'] = "Points added successfully!";
    } catch (Exception $e) {
        // Rollback transaction on error
        $con->rollBack();
        $_SESSION['error_message'] = $e->getMessage();
    }

    // Redirect back to the account page
    header("Location: ../account");
    exit();
}

/**
 * Function to update inviter points
 */
function codeOwnerUpdate($con, $code_id)
{
    try {
        $stmt = $con->prepare("UPDATE users SET my_referral_earnings = my_referral_earnings + 300 WHERE id = ?");
        $stmt->execute([$code_id]);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Function to update user balance and set inviter relationship
 */
function codeUserUpdate($con, $email, $referral_code)
{
    try {
        $stmt = $con->prepare("UPDATE users SET my_invitation_code = ?, my_referral_earnings = my_referral_earnings + 300 WHERE email = ?");
        $stmt->execute([$referral_code, $email]);
        return true;
    } catch (Exception $e) {
        return false;
    }
}
