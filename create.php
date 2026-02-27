<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    session_regenerate_id(true);
}

include 'includes/config.php';

// function generateCsrfToken()
// {
//     if (!isset($_SESSION['csrf_token'])) {
//         $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
//     }
// }

function validateInput($email)
{
    $errors = [];
    if (empty($email)) {
        $errors[] = "Please enter an email.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Must be a valid email address.";
    } elseif (!preg_match('/@gmail\.com$/', $email)) {
        $errors[] = "Must be a Gmail address.";
    }
    return $errors;
}

// function AcceptNewPlayer($con)
// {
//     $stmt = $con->prepare("SELECT lock_register FROM admins LIMIT 1");
//     $stmt->execute();
//     $result = $stmt->get_result();

//     if ($row = $result->fetch_assoc()) {
//         return $row['lock_register'] == 1;
//     }
//     return false;
// }


function isEmailAlreadyRegistered($con, $email)
{
    $stmt = $con->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    return $stmt->get_result()->num_rows > 0;
}

// function insertRiddleSectionProgress($con, $acc_number, $email)
// {
//     $stmt = $con->prepare("INSERT INTO riddleSectionProgress (acc_number, email) VALUES (?, ?)");
//     $stmt->bind_param("is", $acc_number, $email);
//     return $stmt->execute();
// }

// function insertNumberSectionProgress($con, $acc_number, $email)
// {
//     $stmt = $con->prepare("INSERT INTO numberSectionProgress (acc_number, email) VALUES (?, ?)");
//     $stmt->bind_param("is", $acc_number, $email);
//     return $stmt->execute();
// }

function insertUser($con,  $acc_number, $my_referral, $registered_at, $email, $password)
{
    $stmt = $con->prepare("INSERT INTO users (acc_number, my_referral, registered_at, email, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isssi", $acc_number, $my_referral, $registered_at, $email, $password);
    return $stmt->execute();
}

function generateUniquePassword($con)
{
    $maxAttempts = 100; // High limit to avoid infinite loops in extreme cases
    $attempt = 0;

    do {
        $password = str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_LEFT); // 8-digit format
        $stmt = $con->prepare("SELECT 1 FROM users WHERE password = ? LIMIT 1");
        $stmt->bind_param("s", $password);
        $stmt->execute();
        $exists = $stmt->get_result()->num_rows > 0;
        $attempt++;
    } while ($exists && $attempt < $maxAttempts);

    return $password; // Returns the last generated password (even if duplicate, but unlikely)
}

function generateUniqueAccountNumber($con)
{
    $maxAttempts = 100; // High limit to avoid infinite loops in extreme cases
    $attempt = 0;

    do {
        $acc_number = str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_LEFT); // 8-digit format
        $stmt = $con->prepare("SELECT 1 FROM users WHERE acc_number = ? LIMIT 1");
        $stmt->bind_param("s", $acc_number);
        $stmt->execute();
        $exists = $stmt->get_result()->num_rows > 0;
        $attempt++;
    } while ($exists && $attempt < $maxAttempts);

    return $acc_number; // Returns the last generated password (even if duplicate, but unlikely)
}

function sendVerificationEmail($email, $password)
{
    require "Mail/phpmailer/PHPMailerAutoload.php";
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls';
    $mail->Username = 'thisdomain24@gmail.com';
    $mail->Password = 'rhtq qcaj mdqp sdkv';
    $mail->setFrom('thisdomain24@gmail.com', 'EarningSphere');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = "Welcome to [Name]! Confirm Your Membership Today";
    $mail->Body = "
    <!DOCTYPE html>
    <html lang='en'>

    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Verify Your Email</title>
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
            
            body {
                font-family: 'Poppins', Arial, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f8fafc;
                color: #334155;
            }
            
            .email-container {
                max-width: 600px;
                margin: 20px auto;
                background: #ffffff;
                border-radius: 12px;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
                overflow: hidden;
                border: 1px solid #e2e8f0;
            }
            
            
            .email-header h1 {
                margin: 0;
                font-size: 28px;
                font-weight: 700;
                letter-spacing: -0.5px;
            }
            
            .email-body {
                padding: 30px;
                line-height: 1.6;
            }
            
            .email-body h2 {
                font-size: 22px;
                color: #1e40af;
                margin-top: 0;
                font-weight: 600;
            }
            
            .email-body p {
                margin: 15px 0;
                font-size: 16px;
            }
            
            .otp-container {
                background: #f1f5f9;
                border-radius: 8px;
                padding: 20px;
                text-align: center;
                margin: 25px 0;
            }
            
            .otp-code {
                font-size: 32px;
                font-weight: 700;
                letter-spacing: 3px;
                color: #1e40af;
                margin: 10px 0;
            }
            
            .action-button {
                display: inline-block;
                background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
                color: #ffffff;
                text-decoration: none;
                padding: 12px 30px;
                border-radius: 8px;
                font-size: 16px;
                font-weight: 500;
                margin: 20px 0;
                border: none;
                cursor: pointer;
                box-shadow: 0 4px 6px rgba(29, 78, 216, 0.15);
            }
            
            .qr-section {
                text-align: center;
                margin: 30px 0;
                padding: 20px;
                background: #f8fafc;
                border-radius: 8px;
                border: 1px dashed #cbd5e1;
            }
            
            .qr-code {
                max-width: 200px;
                margin: 15px auto;
                display: block;
            }
            
            .divider {
                height: 1px;
                background: linear-gradient(to right, transparent, #cbd5e1, transparent);
                margin: 25px 0;
            }
            
            .footer {
                text-align: center;
                background: #f1f5f9;
                padding: 20px;
                font-size: 14px;
                color: #64748b;
                border-top: 1px solid #e2e8f0;
            }
            
            .footer a {
                color: #2563eb;
                text-decoration: none;
                font-weight: 500;
            }
            
            .note-box {
                background: #fef2f2;
                border-left: 4px solid #dc2626;
                padding: 12px 16px;
                margin: 20px 0;
                border-radius: 0 8px 8px 0;
            }
        </style>
    </head>

    <body>
        <div class='email-container'>
            
            <div class='email-body'>
                <h2>Yowza! Login Credentials</h2>
                <p>Hello $email,</p>
                <p>Thank you for joining []. To access your account and start earning, please login your email address with the temporary password below:</p>
                
  
                <div class='otp-container'>
                    <p style='margin-bottom: 5px;'>Your temporary password:</p>
                    <div class='otp-code'>$password</div>
                </div>
                
                <p>Alternatively, you can use the link below to direct login page:</p>
                
                <center>
                    <p>http://localhost/New_Project_2026/login.php</p>
                </center>

                
                <div class='divider'></div>
                            
                <p>If you didn't request this, please ignore this email or contact support if you have questions.</p>
            </div>
            
            <div class='footer'>
                <p>&copy; 2024 EarningSphere. All rights reserved.</p>
                <p>
                    <a href='https://earningsphere.online/Terms.php#privacy-policy'>Privacy Policy</a> | 
                    <a href='https://earningsphere.online/Terms.php'>Terms of Service</a> | 
                </p>
                <p style='font-size: 12px; margin-top: 10px;'>
                    This email was sent to $email.
                </p>
            </div>
        </div>
    </body>

    </html>
    ";

    return $mail->send();
}


// generateCsrfToken();

if (isset($_POST['register'])) {
    $email = trim($_POST['email']);

    unset($_SESSION['error'], $_SESSION['email']);

    if (!empty($errors)) {
        $_SESSION['error'] = $errors;
        $_SESSION['email'] = $email;
        header("Location: index.php#message-section");
        exit();
    }

    // if (AcceptNewPlayer($con)) {
    //     $_SESSION['warning'] = "Registration are temporarily closed due to maintenance.";
    //     header("Location: index.php#message-section");
    //     exit();
    // }

    if (isEmailAlreadyRegistered($con, $email)) {
        $_SESSION['error'] = ["The email is already in use."];
        $_SESSION['email'] = $email;
        header("Location: index.php#message-section");
        exit();
    }


    // if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    //     $_SESSION['error'] = ["Invalid CSRF token."];
    //     header("Location: index.php#message-section");
    //     exit();
    // }


    $password = generateUniquePassword($con);
    $acc_number = generateUniqueAccountNumber($con);
    $my_referral = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 10);
    date_default_timezone_set('Asia/Manila');
    $registered_at = date('D, j M Y h:i A', time());
    $con->begin_transaction();

    try {
        $insertSuccess = insertUser($con, $acc_number, $my_referral, $registered_at, $email, $password);

        if ($insertSuccess) {

            $player_id = $acc_number;

            // // Insert into wordSectionProgress
            // if (!insertRiddleSectionProgress($con, $player_id, $email)) {
            //     throw new Exception("Failed to insert word section progress.");
            // }

            // // Insert into numberSectionProgress
            // if (!insertNumberSectionProgress($con, $player_id, $email)) {
            //     throw new Exception("Failed to insert number section progress.");
            // }

            if (sendVerificationEmail($email, $password)) {
                $con->commit();
                header("Location: message.html");
                exit();
            } else {
                throw new Exception("User registration failed. Please try again.");
            }
        } else {
            throw new Exception("User registration failed. Please try again.");
        }
    } catch (Exception $e) {
        $con->rollback();
        $_SESSION['error'] = [$e->getMessage()];
        header("Location: index.php#message-section");
        exit();
    }
}
