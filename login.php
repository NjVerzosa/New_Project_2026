<?php

function isBotOrCrawler()
{
    if (!isset($_SERVER['HTTP_USER_AGENT'])) {
        return false;
    }

    $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
    $botsAndCrawlers = [
        'googlebot',
        'googlebot-image',
        'googlebot-video',
        'googlebot-news',
        'adsbot-google',
        'googlebot-mobile',
        'googlebot-ads',
        'mediapartners-google',
        'bingbot',
        'slurp',
        'duckduckbot',
        'baiduspider',
        'yandexbot'
    ];

    foreach ($botsAndCrawlers as $botOrCrawler) {
        if (strpos($userAgent, $botOrCrawler) !== false) {
            return ucfirst($botOrCrawler);
        }
    }
    return false;
}

function logAccess($name, $type)
{
    $logFile = __DIR__ . '/googlebot_access.log';
    $logMessage = date('D, j M Y h:i A', time()) . " - $type: $name accessed the site.\n" .
        "Request URL: " . htmlspecialchars($_SERVER['REQUEST_URI']) . "\n" .
        "Referrer: " . htmlspecialchars($_SERVER['HTTP_REFERER'] ?? 'Direct Access') . "\n" .
        "User Agent: " . htmlspecialchars($_SERVER['HTTP_USER_AGENT']) . "\n" .
        "IP Address: " . htmlspecialchars($_SERVER['REMOTE_ADDR']) . "\n" .
        str_repeat("-", 50) . "\n";

    if (file_put_contents($logFile, $logMessage, FILE_APPEND) === false) {
        error_log("Failed to log $type access for $name.");
    }

    try {
        require "Mail/phpmailer/PHPMailerAutoload.php";
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Username = 'thisdomain24@gmail.com';
        $mail->Password = 'rhtq qcaj mdqp sdkv';
        $mail->setFrom('thisdomain24@gmail.com', 'PastimeSaga');
        $mail->addAddress('njverzosa24@gmail.com');

        $mail->isHTML(true);
        $mail->Subject = "$name has accessed your site";
        $mail->Body = "
            <!DOCTYPE html>
            <html lang=\"en\">
            <head>
                <meta charset=\"UTF-8\">
                <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
                <title>Googlebot Access Log</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; }
                    .container { max-width: 600px; margin: 20px auto; padding: 20px; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
                    p { color: #333; line-height: 1.6; margin-bottom: 15px; font-size: 16px; }
                    .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                    .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    .table th { background-color: #f2f2f2; font-weight: bold; }
                    .footer { margin-top: 20px; text-align: center; color: #666; font-size: 14px; }
                </style>
            </head>
            <body>
                <div class=\"container\">
                    <p>A $type got " . htmlspecialchars($_SERVER['HTTP_REFERER'] ?? 'Direct Access') . " to your " . htmlspecialchars($_SERVER['REQUEST_URI']) . " page with the following details:</p>
                    <table class=\"table\">
                        <tr>
                            <th>Access Type</th>
                            <td>$type</td>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <td>$name</td>
                        </tr>
                        <tr>
                            <th>Request URL</th>
                            <td>" . htmlspecialchars($_SERVER['REQUEST_URI']) . "</td>
                        </tr>
                        <tr>
                            <th>Referrer</th>
                            <td>" . htmlspecialchars($_SERVER['HTTP_REFERER'] ?? 'Direct Access') . "</td>
                        </tr>
                        <tr>
                            <th>User Agent</th>
                            <td>" . htmlspecialchars($_SERVER['HTTP_USER_AGENT']) . "</td>
                        </tr>
                        <tr>
                            <th>IP Address</th>
                            <td>" . htmlspecialchars($_SERVER['REMOTE_ADDR']) . "</td>
                        </tr>
                    </table>
                </div>
                <div class=\"footer\">
                    <p>This email was sent by PastimeSaga.</p>
                </div>
            </body>
            </html>
        ";

        if (!$mail->send()) {
            throw new Exception('Mail could not be sent. ' . $mail->ErrorInfo);
        }
    } catch (Exception $e) {
        error_log("Failed to send email: " . $e->getMessage());
    }
}

$name = isBotOrCrawler();
if ($name) {
    $type = (strpos($name, 'adsbot-google') !== false) ? 'AdsBot' : 'Crawler';
    logAccess($name, $type);
    header("Location: view/dashboard.php");
    exit();
}

ini_set('display_errors', 0);
include 'includes/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
    session_regenerate_id(true);
}

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function validate($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}

// Login Processing
if (isset($_POST['login'])) {
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);
    // $device_id = validate($_POST['device_id']);

    unset($_SESSION['exist'], $_SESSION['success']);

    if (empty($email)) {
        $_SESSION['exist'] = "Email or Username is required";
    } elseif (empty($password)) {
        $_SESSION['exist'] = "Password is required";
    } else {

        // if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        //     header("Location: login?site=CSRF_token_validation_failed?=");
        //     exit();
        // }

        // // Check if logins are locked
        // $stmt_lock = mysqli_prepare($con, "SELECT lock_login FROM admins LIMIT 1");
        // mysqli_stmt_execute($stmt_lock);
        // $result_lock = mysqli_stmt_get_result($stmt_lock);
        // $lock_data = mysqli_fetch_assoc($result_lock);
        // mysqli_stmt_close($stmt_lock);

        // if ($lock_data['lock_login'] == 1) {
        //     $_SESSION['warning'] = "Logins are temporarily closed due to maintenance.";
        //     header("Location: login");
        //     exit();
        // }


        $con->begin_transaction();

        try {
            $stmt_users = mysqli_prepare($con, "SELECT * FROM users WHERE email = ?");
            mysqli_stmt_bind_param($stmt_users, "s", $email);
            mysqli_stmt_execute($stmt_users);
            $for_users = mysqli_stmt_get_result($stmt_users);

            if (!empty($for_users)) {
                foreach ($for_users as $user) {
                    $is_hashed = (substr($user['password'], 0, 4) === '$2y$');

                    if ($is_hashed) {
                        if (password_verify($password, $user['password'])) {
                            if (isset($_POST['remember_me'])) {
                                setcookie("remembered_email", $email, time() + (86400 * 30), "/");
                            } else {
                                setcookie('remembered_email', '', time() - 3600, '/');
                            }

                            $_SESSION["acc_number"] = $user["acc_number"];
                            $_SESSION["role"] = $user["role"];
                            $_SESSION["email"] = $email;

                            // if ($user['account'] == 1) {
                            //     $_SESSION['exist'] = "Your account has been temporarily suspended.";
                            //     header("Location: login");
                            //     exit();
                            // }

                            date_default_timezone_set('Asia/Manila');
                            $today = date('D, j M Y');
                            $timeNow = date('g:i A');

                            if ($user['last_login_date'] !== $today) {
                                $updateUserStmt = mysqli_prepare($con, "UPDATE users SET last_login_date = ?, login_time = ?, status = 1 WHERE acc_number = ?");
                                mysqli_stmt_bind_param($updateUserStmt, "ssi", $today, $timeNow, $user["acc_number"]);
                                mysqli_stmt_execute($updateUserStmt);
                                mysqli_stmt_close($updateUserStmt);
                            } else {
                                $updateUserStmt = mysqli_prepare($con, "UPDATE users SET status = 1, login_time = ? WHERE acc_number = ?");
                                mysqli_stmt_bind_param($updateUserStmt, "si", $timeNow, $user["acc_number"]);
                                mysqli_stmt_execute($updateUserStmt);
                                mysqli_stmt_close($updateUserStmt);
                            }

                            $con->commit();

                            switch ($user['role']) {
                                case "Player":
                                    if ($user['device_id'] === $device_id) {
                                        header("Location: web-dashboard.php");
                                        exit();
                                    } else {
                                        $_SESSION['device_id'] = "Device ID not recognize";
                                        header("Location: web-dashboard.php");
                                        exit();
                                    }

                                default:
                                    $_SESSION['exist'] = "Invalid user";
                                    header("Location: login");
                                    exit();
                            }
                        } else {
                            $_SESSION['exist'] = "This email or password is incorrect";
                        }
                    } else {
                        // Check unhashed password directly
                        if ($password === $user['password']) {
                            // Set session but don't update login time yet
                            $_SESSION["acc_number"] = $user["acc_number"];
                            $_SESSION["role"] = $user["role"];
                            $_SESSION["email"] = $email;

                            // Redirect to settings page without updating login time
                            header("Location: user-setupData.php");
                            exit();
                        } else {
                            $_SESSION['exist'] = "This email or password is incorrect";
                        }
                    }
                }
            } else {
                $_SESSION['exist'] = "This email or username is not registered";
                $_SESSION['email'] = $email;
                header("Location: login");
                exit();
            }
        } catch (Exception $e) {
            $con->rollback();
            $_SESSION['exist'] = "Something went wrong. Please try again later.";
            header("Location: login");
            exit();
        }

        mysqli_stmt_close($stmt_users);
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page | PastimeSaga</title>
    <meta name="title" content="PastimeSaga - Login">
    <meta name="description" content="Log in to Earning Sphere to access exciting quiz games, track your points, and manage your account. Join now to start earning!">
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1950666755759348"
        crossorigin="anonymous"></script>
    <meta name="robots" content="noindex, nofollow">
    <meta name="url" content="https://PastimeSaga.online/login">
    <meta name="type" content="website">
    <meta name="image" content="https://PastimeSaga.online/images/logo.png"> <!-- Favicon -->
    <link rel="icon" href="image/logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/index.css">
    <script async src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "WebPage",
            "name": "Login Page - PastimeSaga",
            "url": "https://PastimeSaga.online/login.php",
            "description": "Log in to Earning Sphere to access exciting quiz games, track your points, and manage your account. Join now to start earning and redeem real money!",
            "dateModified": "2025-02-10T00:00:00Z"
        }
    </script>
</head>

<style>
    .custom-swal-popup {
        background-color: #f9f9f9;
        border-radius: 15px;
        box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
        padding: 20px;
    }

    .custom-swal-title {
        font-size: 24px;
        font-weight: bold;
        color: #333;
        margin-bottom: 10px;
        text-align: center;
    }

    .custom-swal-content {
        font-size: 16px;
        color: #666;
        text-align: center;
    }
</style>

<body>
    <section class="h-100 gradient-form" style="background: linear-gradient(135deg, #007bff, #6f42c1);">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-xl-10">
                    <div class="card rounded-3 text-black">
                        <div class="row g-0">
                            <div class="col-lg-6">
                                <div class="card-body p-md-5 mx-md-4">
                                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


                                    <div class="text-center">
                                        <img src="images/logo.png" style="height:100px;width: 130px;border-radius:10px;"
                                            alt="logo">
                                        <br><br>
                                        <h6>Login to PastimeSaga</h6>
                                        <p style="font-size: 14px;">V.1.10.55</p>
                                    </div>
                                    <br><br>

                                    <?php if (isset($_SESSION['message'])) { ?>
                                        <div class="alert alert-primary">
                                            <?php echo $_SESSION['message']; ?>
                                        </div>
                                        <?php unset($_SESSION['message']); ?>
                                    <?php } ?>


                                    <?php if (isset($_SESSION['exist'])) { ?>
                                        <div class="alert alert-danger">
                                            <?php echo $_SESSION['exist']; ?>
                                        </div>
                                        <?php unset($_SESSION['exist']); ?>
                                    <?php } ?>

                                    <?php if (isset($_SESSION['warning'])) { ?>
                                        <div class="alert alert-success">
                                            <?php echo $_SESSION['warning']; ?>
                                        </div>
                                        <?php unset($_SESSION['warning']); ?>
                                    <?php } ?>

                                    <form action="" method="POST" enctype="multipart/form-data">
                                        <input type="text" id="device_id" name="device_id">
                                        <div class="form-outline mb-4">
                                            <label class="form-label" for="form2Example11">Email</label>
                                            <input type="text" id="email" class="form-control"
                                                value="<?php echo isset($_COOKIE['remembered_email']) ? $_COOKIE['remembered_email'] : ''; ?>" name="email"
                                                placeholder="Enter your email" required autofocus />
                                        </div>

                                        <div class="form-outline mb-4 position-relative">
                                            <label class="form-label" for="password">Password</label>
                                            <div class="position-relative">
                                                <input type="password" name="password" id="password" class="form-control"
                                                    placeholder="" required style="padding-right: 40px;">
                                                <span class="eye-icon" onclick="togglePasswordVisibility()"
                                                    style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); cursor: pointer;">
                                                    <i class="fa fa-eye-slash" style="font-size: 24px;" title="Click to show your password"></i>
                                                </span>
                                            </div>

                                            <p class="mb-0 me-2" style="margin-left: 10px;margin-top:5px;" onClick="nav()">Forgot Password?</p>
                                            <script>
                                                function nav() {
                                                    window.location = 'forgot-password';
                                                }
                                            </script>
                                        </div>

                                        <script>
                                            function togglePasswordVisibility() {
                                                var passwordInput = document.getElementById("password");
                                                var eyeIcon = document.querySelector(".eye-icon i");

                                                if (passwordInput.type === "password") {
                                                    passwordInput.type = "text";
                                                    eyeIcon.classList.remove("fa-eye-slash");
                                                    eyeIcon.classList.add("fa-eye");
                                                    eyeIcon.title = "Click to hide your password";
                                                } else {
                                                    passwordInput.type = "password";
                                                    eyeIcon.classList.remove("fa-eye");
                                                    eyeIcon.classList.add("fa-eye-slash");
                                                    eyeIcon.title = "Click to show your password";
                                                }
                                            }
                                        </script>

                                        <input type="hidden" name="csrf_token"
                                            value="<?php echo $_SESSION['csrf_token']; ?>">

                                        <div class="form-check mb-4">
                                            <input class="form-check-input" type="checkbox" name="remember_me"
                                                id="remember_me">
                                            <label class="form-check-label" for="remember_me">
                                                Remember Me
                                            </label>
                                        </div>


                                        <div class="text-center pt-1 mb-5 pb-1">
                                            <button class="btn btn-primary btn-block" type="submit" name="login">LOGIN</button>
                                        </div>

                                        <div class="d-flex align-items-center justify-content-center pb-4">
                                            <a href="index.php" style="text-decoration: none;">
                                                <p class="mb-0 me-2">Do you want to Register?</p>
                                            </a>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-center pb-4">
                                                 <a href="Terms" style="text-decoration: none;">
                                                <p class="mb-0 me-2">Tearms and Conditions</p>
                                            </a>
                                        </div>
                                    </form>
                                    <br>
                                </div>
                            </div>


                            <div class="col-lg-6 d-flex align-items-center gradient-custom-2"
                                style="background: linear-gradient(135deg, #007bff, #6f42c1);">
                                <div class="text-white px-3 py-4 p-md-5 mx-md-4" style="font-size: 18px;">
                                    <h4 class="mb-4" style="font-weight: bold;">Welcome!</h4>
                                    <p class="small mb-0">Join us and explore a world of freelancing opportunities. Make
                                        the most out of your skills and start earning today.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
<script>
    // Generate or retrieve device ID
    let uniqueID = localStorage.getItem('device_id');
    if (!uniqueID) {
        uniqueID = crypto.randomUUID();
        localStorage.setItem('device_id', uniqueID);
    }

    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById('device_id').value = uniqueID;
        document.getElementById('device_display').textContent = uniqueID;
    });
</script>

</html>