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

// Common function to filter user input
function filter($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}

// Validate CSRF token
function validateCsrfToken($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// ============== Upload Profile Logic ==============
function handleFileUpload($con)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
        // Validate CSRF token first
        if (!isset($_POST['csrf_token']) || !validateCsrfToken($_POST['csrf_token'])) {
            $_SESSION['error_message'] = 'Invalid CSRF Token. Please try again.';
            header("Location: ../account");
            exit();
        }

        // Check if file was uploaded without errors
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error_message'] = 'No file uploaded or upload error occurred.';
            header("Location: ../account");
            exit();
        }

        try {
            $email = filter($_POST['email']);
            $file = $_FILES['image'];

            // Validate file
            $validImageExtensions = ['jpg', 'jpeg', 'png'];
            $maxFileSize = 5 * 1024 * 1024; // 5 MB
            $imageExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            // Check extension and size
            if (!in_array($imageExtension, $validImageExtensions)) {
                $_SESSION['error_message'] = 'Invalid file type. Allowed types: jpg, jpeg, png.';
                header("Location: ../account");
                exit();
            }

            if ($file['size'] > $maxFileSize) {
                $_SESSION['error_message'] = 'File size exceeds 5MB limit.';
                header("Location: ../account");
                exit();
            }

            // Generate unique filename
            $newImageName = uniqid() . '.' . $imageExtension;
            $targetPath1 = __DIR__ . '/../../userProfile/' . $newImageName;
            $targetPath2 = __DIR__ . '/../../admin/userProfile/' . $newImageName;

            // Move uploaded file
            if (!move_uploaded_file($file['tmp_name'], $targetPath1)) {
                throw new Exception('Failed to move uploaded file.');
            }

            // Copy to second location
            if (!copy($targetPath1, $targetPath2)) {
                // If copy fails, delete the original uploaded file
                unlink($targetPath1);
                throw new Exception('Failed to copy file to secondary location.');
            }

            // Update database
            $stmt = $con->prepare("UPDATE users SET profile = ? WHERE email = ?");
            if (!$stmt->execute([$newImageName, $email])) {
                // If DB update fails, delete both files
                unlink($targetPath1);
                unlink($targetPath2);
                throw new Exception('Failed to update database.');
            }

            $_SESSION['success_message'] = 'Profile picture updated successfully!';
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Error: ' . $e->getMessage();
        }

        header("Location: ../account");
        exit();
    }
}

handleFileUpload($con);

// Close connection if needed
$con = null;
