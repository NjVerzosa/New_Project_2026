<?php
include 'user-sessions.php';
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="dark" style="font-size:14px">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="index, follow">
    <title>Account | EarningSphere</title>
    <meta name="title" content="Account | EarningSphere">
    <meta name="description" content="Manage your profile, withdraw your balance, track VIP status, and update username on your profile page.">
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1950666755759348"
        crossorigin="anonymous"></script>
    <meta name="site_name" content="EarningSphere">
    <meta name="url" content="https://earningsphere.online/account.php">
    <meta name="type" content="website">
    <meta name="image" content="https://earningsphere.online/images/logo.png">
    <link rel="icon" href="images/logo.png" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/blockadblock@1.0.0/blockadblock.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/style.css">
</head>

<body class="noselect">
    <div class="wrapper">

        <?php include 'sidebar.php'; ?>

        <div class="main">
            <?php
            if (isset($_SESSION['success_message'])) {
                echo "<script> 
                        Swal.fire({
                            icon: 'info',
                            text: '" . htmlspecialchars($_SESSION['success_message'], ENT_QUOTES, 'UTF-8') . "',
                            showConfirmButton: true,
                            confirmButtonText: 'OK',
                            width: '280px',
                            height: '240px'
                        });
                    </script>";
                unset($_SESSION['success_message']);
            }


            if (isset($_SESSION['error_message'])) {
                echo "<script>
                                Swal.fire({
                                    icon: 'info',
                                    text: '" . htmlspecialchars($_SESSION['error_message'], ENT_QUOTES, 'UTF-8') . "',
                                    showConfirmButton: true,
                                    confirmButtonText: 'OK',
                                    width: '280px',
                                    height: '240px'
                                });
                            </script>";
                unset($_SESSION['error_message']);
            }
            ?>

            <nav class="navbar navbar-expand px-3 border-bottom" style="background: #2a2438;">
                <button class="btn border-0 bg-transparent p-2" id="sidebar-toggle" type="button" style="width: 45px; height: 45px;">
                    <i class="fa-solid fa-bars text-white fs-5"></i>
                </button>


                <div class="navbar-collapse navbar">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a href="#" data-bs-toggle="dropdown" class="nav-icon pe-md-0">
                                <img src="../userProfile/<?php echo htmlspecialchars($row['profile'], ENT_QUOTES, 'UTF-8'); ?>"
                                    style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover; max-width: 100%; max-height: 100%;"
                                    class="avatar img-fluid rounded"
                                    alt="User Profile Picture">
                            </a>
                            <div class="dropdown-menu dropdown-menu-end bg-white">
                                <a href="logout.php" rel="nofollow" class="dropdown-item text-black">
                                    <i class="fa-solid fa-right-from-bracket pe-2"></i>Logout
                                </a>
                                <?php if ($payment_status === 'Paid') { ?>

                                <?php } else { ?>
                                    <a href="#" rel="nofollow" class="dropdown-item text-black" data-toggle="modal" data-target="#ActivationModal">
                                        <i class="fa-solid fa-qrcode pe-2"></i>QR Code
                                    </a>
                                <?php } ?>

                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="content px-3 py-5" style="font-size: 14px;">

                <!-- <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1950666755759348"
                    crossorigin="anonymous"></script>
                 <ins class="adsbygoogle"
                    style="display:block"
                    data-ad-client="ca-pub-1950666755759348"
                    data-ad-slot="6592135564"
                    data-ad-format="auto"
                    data-full-width-responsive="true"></ins>
                <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                </script> -->



                <!-- Activation Modal -->
                <div class="modal fade" id="ActivationModal" tabindex="-1" role="dialog" aria-labelledby="ActivationModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="ActivationModalLabel">Account Activation</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body text-center p-4">
                                <div class="qr-code-container mb-4">
                                    <img src="images/qr.jpg" alt="Account Activation QR Code" class="img-fluid rounded border" style="max-width: 100%; height: auto;">
                                </div>
                                <p class="text-muted">Scan this QR code to complete your account activation</p>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                $payment_match = false;
                $ref = '';
                $payment_status = $row['payment_status'] ?? null;

                // Only check if payment_ref exists in the user row
                if (!empty($row['payment_ref'])) {
                    // Query to find matching payment record
                    $sql = "SELECT ref_number FROM payments WHERE ref_number = ? LIMIT 1";
                    $stmt = mysqli_prepare($con, $sql);

                    if ($stmt) {
                        mysqli_stmt_bind_param($stmt, "s", $row['payment_ref']);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);

                        if ($result && mysqli_num_rows($result) > 0) {
                            $transactionData = mysqli_fetch_assoc($result);
                            $ref = htmlspecialchars($transactionData['ref_number'] ?? '', ENT_QUOTES, 'UTF-8');
                            $payment_match = ($row['payment_ref'] === $ref);
                        }
                        mysqli_stmt_close($stmt);
                    }
                }
                ?>

                <div class="card bg-dark text-white">
                    <div class="card-header">
                        <h5>PREMIUM PROCESSS</h5>
                    </div>
                    <br>
                    <div class="card-body">
                        <?php if (empty($row['payment_ref']) || $payment_status === 'Not Found'): ?>

                            <?php if ($payment_status === 'Not Found'): ?>
                                <!-- Case 1a: Payment was declined - show form with error message -->

                                <div style="background-color: #FFF8E6; border-radius: 4px; padding: 12px 16px; margin-bottom: 16px;">
                                    <div style="display: flex; align-items: flex-start; gap: 8px;">
                                        <div style="flex-grow: 1;">
                                            <p style="margin-bottom: 0; color: rgb(45, 46, 48); font-size: 14px; line-height: 20px;">
                                                We couldn't find a matching transaction for your payment details. Please check your reference number and try again.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                            <?php endif; ?>

                            <!-- Case 1: No payment reference exists or was declined - show clean form -->
                            <form id="form" action="./includes/payment.php" method="POST" enctype="multipart/form-data">
                                <div class="accordion-body bg-dark text-white">
                                    <div class="mb-3">
                                        <input type="hidden" name="email" value="<?php echo htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8'); ?>">
                                        <input type="hidden" name="acc_number" value="<?php echo htmlspecialchars($row['acc_number'], ENT_QUOTES, 'UTF-8'); ?>">
                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>

                                    <!-- Display general error message if exists -->
                                    <?php if (isset($_SESSION['error_message'])): ?>
                                        <div class="alert alert-danger">
                                            <?php echo $_SESSION['error_message'];
                                            unset($_SESSION['error_message']); ?>
                                        </div>
                                    <?php endif; ?>

                                    <!-- GCash Number Field -->
                                    <div class="mb-3">
                                        <div class="card border-primary mb-3">
                                            <div class="card-header bg-dark">
                                                <h6 class="mb-0">GCash Number</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="input-group">
                                                    <input type="text" class="form-control <?php echo isset($_SESSION['validation_errors']['gcash_number']) ? 'is-invalid' : ''; ?>"
                                                        name="gcash_number"
                                                        value="<?php echo isset($_SESSION['form_data']['gcash_number']) ? htmlspecialchars($_SESSION['form_data']['gcash_number'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                                                        placeholder="Enter the GCash number you used in payment">
                                                </div>
                                                <?php if (isset($_SESSION['validation_errors']['gcash_number'])): ?>
                                                    <div class="error-message text-danger mt-2">
                                                        <?php echo $_SESSION['validation_errors']['gcash_number']; ?>
                                                    </div>
                                                    <?php unset($_SESSION['validation_errors']['gcash_number']); ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Transaction Reference Number Field -->
                                    <div class="mb-3">
                                        <div class="card border-primary mb-3">
                                            <div class="card-header bg-dark">
                                                <h6 class="mb-0">Transaction Reference Number</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="input-group">
                                                    <input type="text" class="form-control <?php echo isset($_SESSION['validation_errors']['ref_number']) ? 'is-invalid' : ''; ?>"
                                                        name="ref_number"
                                                        value="<?php echo isset($_SESSION['form_data']['ref_number']) ? htmlspecialchars($_SESSION['form_data']['ref_number'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                                                        placeholder="Please enter the reference number of transaction">
                                                </div>
                                                <?php if (isset($_SESSION['validation_errors']['ref_number'])): ?>
                                                    <div class="error-message text-danger mt-2">
                                                        <?php echo $_SESSION['validation_errors']['ref_number']; ?>
                                                    </div>
                                                    <?php unset($_SESSION['validation_errors']['ref_number']); ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="mt-4 text-center">
                                        <button type="submit" name="process_payment" class="btn btn-primary btn-sm w-50" id="submitBtn">
                                            <?php echo $payment_status === 'Declined' ? 'REQUEST REVIEW' : 'REQUEST REVIEW'; ?>
                                        </button>
                                    </div>
                                </div>

                                <?php
                                // Clear the form data and validation errors after displaying them
                                if (isset($_SESSION['form_data'])) {
                                    unset($_SESSION['form_data']);
                                }
                                if (isset($_SESSION['validation_errors'])) {
                                    unset($_SESSION['validation_errors']);
                                }
                                ?>
                            </form>

                        <?php elseif (!empty($row['payment_ref']) && empty($ref)): ?>

                            <!-- Case 2: Payment reference exists but ref is empty - show processing message -->
                            <div class="card border-0 shadow-sm" style="max-width: 500px; margin: 0 auto;">
                                <div class="card-body text-center p-4">
                                    <div class="mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="#FFA500" class="mx-auto">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z" />
                                        </svg>
                                    </div>

                                    <h3 class="h4 mb-3" style="color:rgb(39, 182, 238); font-weight: 500;">Request Under Review</h3>

                                    <p class="mb-4" style="color:rgb(158, 166, 175); line-height: 1.6;">
                                        Your payment reference <span style="color:rgb(39, 182, 238); font-weight: 500;"><?php echo htmlspecialchars($row['payment_ref'], ENT_QUOTES, 'UTF-8'); ?></span>
                                        is being verified.<br>
                                        Please allow <span style="color:rgb(39, 182, 238); font-weight: 500;">24 to 48 business hours</span>
                                        for processing.
                                    </p>

                                    <div class="mt-4 pt-3 border-top text-muted small">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock me-1" viewBox="0 0 16 16">
                                            <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z" />
                                            <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z" />
                                        </svg>
                                        Processing Time: 24-48 business hours
                                    </div>
                                </div>
                            </div>

                        <?php elseif (empty($ref)): ?>

                            <!-- Case when reference is empty - show processing message -->
                            <div class="card border-0 shadow-sm" style="max-width: 500px; margin: 0 auto;">
                                <div class="card-body text-center p-4">
                                    <div class="mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="#FFA500" class="mx-auto">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z" />
                                        </svg>
                                    </div>

                                    <h3 class="h4 mb-3" style="color:rgb(39, 182, 238); font-weight: 500;">Request Under Review</h3>

                                    <p class="mb-4" style="color:rgb(158, 166, 175); line-height: 1.6;">
                                        Your payment request is being processed.<br>
                                        Please allow <span style="color:rgb(39, 182, 238); font-weight: 500;">24 to 48 business hours</span>
                                        for verification.
                                    </p>

                                    <div class="mt-4 pt-3 border-top text-muted small">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock me-1" viewBox="0 0 16 16">
                                            <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z" />
                                            <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z" />
                                        </svg>
                                        Processing Time: 24-48 business hours
                                    </div>
                                </div>
                            </div>

                        <?php else: ?>

                            <!-- Case 3: Payment reference exists and matches - show success message -->
                            <div class="card border-0 shadow-sm" style="max-width: 500px; margin: 0 auto;">
                                <div class="card-body text-center p-4">
                                    <div class="mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="#34A853" class="mx-auto">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                                        </svg>
                                    </div>

                                    <h3 class="h4 mb-3" style="color:rgb(39, 182, 238); font-weight: 500;">Payment Verified Successfully</h3>

                                    <p class="mb-4" style="color:rgb(158, 166, 175); line-height: 1.6;">
                                        Your payment reference <span style="color:rgb(39, 182, 238); font-weight: 500;"><?php echo htmlspecialchars($row['payment_ref'], ENT_QUOTES, 'UTF-8'); ?></span>
                                        has been found and verified.
                                    </p>

                                    <div class="d-flex justify-content-center gap-3">
                                        <a href="invoice.php?receipt=<?php echo htmlspecialchars($row['payment_ref'], ENT_QUOTES); ?>"
                                            rel="nofollow"
                                            class="btn text-white"
                                            style="background-color:rgb(26, 109, 243); min-width: 120px;">
                                            View Receipt
                                        </a>
                                    </div>

                                    <div class="mt-4 pt-3 border-top text-muted small">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-shield-check me-1" viewBox="0 0 16 16">
                                            <path d="M5.338 1.59a61.44 61.44 0 0 0-2.837.856.481.481 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.725 10.725 0 0 0 2.287 2.233c.346.244.652.42.893.533.12.057.218.095.293.118a.55.55 0 0 0 .101.025.615.615 0 0 0 .1-.025c.076-.023.174-.061.294-.118.24-.113.547-.29.893-.533a10.726 10.726 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.775 11.775 0 0 1-2.517 2.453 7.159 7.159 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7.158 7.158 0 0 1-1.048-.625 11.777 11.777 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 62.456 62.456 0 0 1 5.072.56z" />
                                            <path d="M10.854 5.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0z" />
                                        </svg>
                                        Secured by EarningSphere
                                    </div>
                                </div>
                            </div>

                        <?php endif; ?>
                    </div>
                </div>

                <?php include 'web-embeded.php'; ?>


            </main>

            <?php include 'web-footer.php'; ?>

        </div>
    </div>
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "WebPage",
            "name": "Account Activation| EarningSphere",
            "description": "Manage your profile, withdraw your balance, track VIP status, and update username on your profile page.",
            "url": "https://earningsphere.online/activation.php",
            "mainEntityOfPage": "https://earningsphere.online/activation.php",
            "potentialAction": [{
                    "@type": "Action",
                    "name": "Update Profile",
                    "target": "https://earningsphere.online/activation.php#updateProfileModal",
                    "actionStatus": "ActiveActionStatus",
                    "description": "User can update their profile and invitation code."
                },
                {
                    "@type": "Action",
                    "name": "Upload Profile Picture",
                    "target": "https://earningsphere.online/activation.php#upload-profile",
                    "actionStatus": "ActiveActionStatus",
                    "description": "User can upload a new profile picture."
                },
                {
                    "@type": "Action",
                    "name": "Update Username",
                    "target": "https://earningsphere.online/account.php#update-username",
                    "actionStatus": "ActiveActionStatus",
                    "description": "User can update their username."
                },
                {
                    "@type": "Action",
                    "name": "Submit Invitation Code",
                    "target": "https://earningsphere.online/account.php#submit-invitationCode",
                    "actionStatus": "ActiveActionStatus",
                    "description": "User can submit their invitation code to invite others."
                },
                {
                    "@type": "Action",
                    "name": "View VIP Status",
                    "target": "https://earningsphere.online/account.php#vipDescription",
                    "actionStatus": "ActiveActionStatus",
                    "description": "User can view their VIP status and related details."
                },
                {
                    "@type": "Action",
                    "name": "Withdraw Amount",
                    "target": "https://earningsphere.online/account.php#amount",
                    "actionStatus": "ActiveActionStatus",
                    "description": "User can enter and withdraw an amount from their balance."
                }
            ]
        }
    </script>
</body>
<script src="js/pop-up.js"></script>
<!-- <script src="js/payment_validation.js"></script> -->

</html>