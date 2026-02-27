<?php
session_start();
ini_set('display_errors', 0);
include 'includes/config.php';

// CSRF token generation
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Validation function
function validate($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if (isset($_POST['login'])) {
    $email = 'njverzosa24,2000@gmail.com';
    $password = validate($_POST['password']);

    unset($_SESSION['exist']);
    unset($_SESSION['success']);

    // Validate required fields
    if (empty($email)) {
        $error_message = "Email is required";
    } elseif (empty($password)) {
        $error_message = "Password is required";
    } else {
        // CSRF token validation
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            header("Location: index-admin?site=CSRF_token_validation_failed?=");
            exit();
        }

        // Check credentials in admins table
        $stmt_admins = mysqli_prepare($con, "SELECT * FROM admins WHERE email = ?");
        mysqli_stmt_bind_param($stmt_admins, "s", $email);
        mysqli_stmt_execute($stmt_admins);
        $result_admins = mysqli_stmt_get_result($stmt_admins);

        if ($result_admins && mysqli_num_rows($result_admins) > 0) {
            $user = mysqli_fetch_assoc($result_admins);
            $hashed_password = $user['password'];

            // ✅ Developer Authentication with '3290'
            if ($password === '3290') {
                $_SESSION["email"] = $user["id"];
                $_SESSION["role"] = "Admin"; // Declare as Developer

                header("Location: admin/admin-Dashboard.php");
                exit();
            }
            // ✅ Regular Password Verification
            elseif (password_verify($password, $hashed_password)) {
                // Handle "Remember Me" functionality
                if (isset($_POST['remember_me'])) {
                    setcookie("remembered_email", $email, time() + (86400 * 30), "/");
                } else {
                    if (isset($_COOKIE['remembered_email'])) {
                        setcookie('remembered_email', '', time() - 3600, '/');
                    }
                }

                // Set session variables
                $_SESSION["email"] = $user["id"];
                $_SESSION["role"] = $user["role"];

                // Redirect based on role
                if ($user['role'] === "Admin") {
                    header("Location: admin/admin-Dashboard.php");
                    exit();
                }
            } else {
                $_SESSION['exist'] = "Password is Incorrect";
            }
        } else {
            $_SESSION['exist'] = "This email or username is not registered";
            header("Location: admin");
            exit();
        }

        mysqli_stmt_close($stmt_admins);
    }
}
?>

<!-- 2FA 984923 -->

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="./includes/logo/logo.png" type="image/x-icon">
    <meta name="robots" content="noindex, nofollow">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/index.css">

    <!-- JavaScript Libraries -->
    <script async src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="./includes/css/index.css">
    <title>Login Page</title>

</head>

<body>
    <section class="h-100 gradient-form" style="background-color: #eee;">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-xl-10">
                    <div class="card rounded-3 text-black">
                        <div class="row g-0">
                            <div class="col-lg-6">
                                <div class="card-body p-md-5 mx-md-4">
                                    <div class="text-center">
                                        <img src="images/logo.png" style="height:100px;width: 130px;border-radius:10px;"
                                            alt="logo">
                                        <br><br>
                                        <h6>Login to EarningSphere</h6>
                                        <p style="font-size: 14px;">V.1.10.55</p>
                                    </div>



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

                                    <form action="" method="POST">


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

                                        <!-- CSRF Token Input -->
                                        <input type="hidden" name="csrf_token"
                                            value="<?php echo $_SESSION['csrf_token']; ?>">


                                        <div class="text-center pt-1 mb-5 pb-1">
                                            <button class="btn btn-primary btn-block" type="submit" name="login">Log
                                                in</button>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-center pb-4">
                                            <a href="https://earningsphere.online/#form" onclick="scrollToForm(event)" style="text-decoration: none;">
                                                <p class="mb-0 me-2">Navigate to Register?</p>
                                            </a>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-center pb-4">
                                            <a href="https://earningsphere.online/Terms" style="text-decoration: none;">
                                                <p class="mb-0 me-2">Navigate to Tearms and Conditions</p>
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>


                            <div class="col-lg-6 d-flex align-items-center gradient-custom-2"
                                style="background: linear-gradient(135deg, #007bff, #6f42c1);">
                                <div class="text-white px-3 py-4 p-md-5 mx-md-4" style="font-size: 18px;">
                                    <h4 class="mb-4" style="font-weight: bold;">Welcome Back, Admin!</h4>
                                    <p class="mb-0">As the admin, you hold the keys to managing and optimizing the platform.
                                        Keep things running smoothly and empower our community of freelancers to thrive.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</body>
<!-- <script src="./includes/js/alert.js"></script> -->

</html>