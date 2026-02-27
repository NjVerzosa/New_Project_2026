<?php
session_start();
ini_set('display_errors', 0);

include 'includes/config.php';


// Add CSRF token for backend security
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (isset($_POST['recover'])) {
    // Sanitize and validate user input (email)
    $email = htmlspecialchars(mysqli_real_escape_string($con, trim($_POST['email'])));
    $acc_number = htmlspecialchars(mysqli_real_escape_string($con, trim($_POST['acc_number'])));

    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        // Redirect with an error message for CSRF token mismatch
        header("Location: forgot-password?error=CSRF Tokens Field");
        exit();
    }

    // Prepare SQL query to check if the email exists
    $stmt = mysqli_prepare($con, "SELECT id FROM users WHERE email = ? AND acc_number = ?");
    mysqli_stmt_bind_param($stmt, "si", $email, $acc_number);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Check if the email exists in the users table
    if (mysqli_num_rows($result) == 0) {
        // Email does not exist
        $_SESSION['error_message'] = "Email or Account Number not Found";
        $_SESSION['email'] = $email;
        header("Location: forgot-password");
        exit();
    } else {
        // Email exists in at least one of the tables
        $password = rand(100000, 999999);

        // Update code status for the email in the users table
        $stmt_update_users = mysqli_prepare($con, "UPDATE users SET password = ?, device_id = NULL, status = 0 WHERE email = ? AND acc_number = ?");
        mysqli_stmt_bind_param($stmt_update_users, "ssi", $password, $email, $acc_number);
        mysqli_stmt_execute($stmt_update_users);

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
        $mail->Subject = "Reset some credentials";
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
                        <p>http://localhost/Freelancing/login.php</p>
                    </center>

                    
                    <div class='divider'></div>
                                
                    <p>If you didn't request this, please contact support if you have questions.</p>
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


        // Check if the email was sent successfully
        if ($mail->send()) {
            header("Location: view.html");
            exit();
        } else {
            $_SESSION['error_message'] = "Error sending email. Please try again later.";
            header("Location: forgot-password");
            exit();
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" href="images/logo.png" type="image/x-icon">


    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Recovery</title>

    <style>
        body {
            background-color: #f6f8fa;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            width: 80%;
            max-width: 400px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h4 {
            color: #0366d6;
        }

        .form-outline {
            margin-bottom: 20px;
        }

        .btn-primary {
            background-color: #28a745;
            border: none;
        }

        .btn-primary:hover {
            background-color: #218838;
        }

        .alert {
            margin-top: 20px;
        }

        @media (max-width: 768px) {
            .container {
                margin-top: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="text-center">
            <h4 class="mt-1 mb-4">[]</h4>
        </div>

        <?php if (isset($_SESSION['error_message'])) { ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['error_message']; ?>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php } ?>
        <?php if (isset($_SESSION['success_message'])) { ?>
            <div class="alert alert-primary">
                <?php echo $_SESSION['success_message']; ?>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php } ?>

        <form action="" method="POST">

            <div class="form-outline">
                <label class="form-label">Email <span style="color: red;">*</span></label>
                <input type="email" name="email" class="form-control"
                    required />
            </div>
            <div class="form-outline">
                <label class="form-label">Account Number <span style="color: red;">*</span></label>
                <input type="text" name="acc_number" class="form-control"
                    required />
            </div>
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <div class="text-center">
                <button class="btn btn-primary btn-block mb-3" type="submit" name="recover">Submit</button>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-pzjw8Y+JLqL5Lq7jRjrjqGuzlRnMSsVBBO9LQ8EBYQ/tzHoqQavBRBXeBpZa2ixt"
        crossorigin="anonymous"></script>
</body>

</html>