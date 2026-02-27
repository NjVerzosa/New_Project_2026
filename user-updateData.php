<?php
include __DIR__ . '/includes/config.php';
include 'user-sessions.php';

// function isBotOrCrawler()
// {
//     if (!isset($_SERVER['HTTP_USER_AGENT'])) {
//         return false;
//     }

//     $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
//     $botsAndCrawlers = [
//         'googlebot',
//         'googlebot-image',
//         'googlebot-video',
//         'googlebot-news',
//         'adsbot-google',
//         'googlebot-mobile',
//         'googlebot-ads',
//         'mediapartners-google',
//         'bingbot',
//         'slurp',
//         'duckduckbot',
//         'baiduspider',
//         'yandexbot'
//     ];

//     foreach ($botsAndCrawlers as $botOrCrawler) {
//         if (strpos($userAgent, $botOrCrawler) !== false) {
//             return ucfirst($botOrCrawler);
//         }
//     }
//     return false;
// }

// $name = isBotOrCrawler();
// if ($name) {
//     $_SESSION['googlebot_access'] = true;

//     if (!isset($_SESSION['redirected'])) {
//         $_SESSION['redirected'] = true;
//         header("Location: web-dashboard.php");
//         exit();
//     }
// }

// function generateCsrfToken()
// {
//     if (!isset($_SESSION['csrf_token'])) {
//         $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
//     }
// }










function updateUser($con, $device_id, $username, $password, $acc_number)
{
    // Hash the password
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $con->prepare("UPDATE users SET username = ?, password = ?, device_id = ? WHERE acc_number = ?");
    $stmt->bind_param("sssi", $username, $password_hashed, $device_id, $acc_number);
    return $stmt->execute();
}

// function verifyCsrfToken($token)
// {
//     return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
// }

// generateCsrfToken();

if (isset($_POST['setup'])) {
    
    // // Verify CSRF token first
    // if (!verifyCsrfToken($_POST['csrf_token'])) {
    //     $_SESSION['error'] = "Invalid CSRF token";
    //     header("Location: user-setupData.php");
    //     exit();
    // }

    $password = trim($_POST['password']);
    $username = trim($_POST['username']);
    $device_id = trim($_POST['device_id']);
    $acc_number = trim($_POST['acc_number']);

    // // Basic validation
    // if (empty($username) || empty($password) || empty($device_id) || empty($otp)) {
    //     $_SESSION['error'] = "All fields are required";
    //     header("Location: setting.php");
    //     exit();
    // }

    $con->begin_transaction();

    try {
        $updateSuccess = updateUser($con, $device_id, $username, $password, $acc_number);

        if ($updateSuccess) {
            $con->commit();
            $_SESSION['message'] = "Account setup completed successfully!";
            header("Location: login.php"); // Redirect to login after successful setup
            exit();
        } else {
            throw new Exception("Account setup failed. Please try again.");
        }
    } catch (Exception $e) {
        $con->rollback();
        $_SESSION['error'] = $e->getMessage();
        header("Location: user-setupData.php");
        exit();
    }
}
