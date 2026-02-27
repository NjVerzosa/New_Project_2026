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
                                <?php if ($row['my_invitation_code'] == NULL) { ?>
                                    <a href="#" data-toggle="modal" data-target="#insertCodeModal" class="dropdown-item text-black">
                                        <i class="fa-solid fa-clipboard pe-2"></i>Invitation Code
                                    </a>
                                <?php } ?>
                                <a href="logout.php" rel="nofollow" class="dropdown-item text-black">
                                    <i class="fa-solid fa-right-from-bracket pe-2"></i>Logout
                                </a>

                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
            <!-- Invitation Code -->
            <div class="modal fade" id="insertCodeModal" tabindex="-1" role="dialog" aria-labelledby="insertCodeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 350px; height: auto; margin: auto;">
                    <div class="modal-content" style="height: auto; margin: auto;">
                        <div class="modal-body" style="padding: 30px;">
                            <form action="includes/referral.php" method="POST" enctype="multipart/form-data">
                                <div class="form-group">
                                    <input type="hidden" class="form-control text-center border-0" name="email" value="<?php echo htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <input type="text" name="referral_code" placeholder="Enter code" class="form-control">
                                </div>
                                <br><br>
                                <div class="text-center">
                                    <button type="submit" id="submit-invitationCode" class="btn btn-primary" name="get_points">SUBMIT</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <main class="content px-3 py-5" style="font-size: 14px;">
                <div class="card bg-dark text-white p-3 mb-2">
                    <div class="row align-items-center">
                        <div class="col-2 text-center">
                            <div class="rounded-circle overflow-hidden" style="width: 80px; height: 80px; background-color: #e9ecef; display: flex; justify-content: center; align-items: center;">
                                <img src="../userProfile/<?php echo htmlspecialchars($row['profile'], ENT_QUOTES, 'UTF-8'); ?>"
                                    style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%; border: 2px solid white; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);"
                                    alt="User Profile Picture" id="upload-profile" data-toggle="modal" data-target="#updateImageModal">
                            </div>
                        </div>
                        <div class="col-4 d-flex flex-column justify-content-center ms-3 mt-6">
                            <small data-toggle="modal" id="update-username" data-target="#updateUsernameModal" class="mb-6 text-nowrap" style="color:blue;font-size:15px;margin-left: 40px;"><?php echo htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8'); ?></small>
                            <small class="mb-6 text-nowrap" style="font-size:15px;margin-left: 40px;"><?php echo htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8'); ?></small>
                        </div>
                    </div>

                    <div class="modal fade" id="updateImageModal" tabindex="-1" role="dialog" aria-labelledby="updateImageModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 350px; height: auto; margin: auto;">
                            <div class="modal-content" style="height: auto; margin: auto;">
                                <div class="modal-body" style="padding: 30px;">
                                    <form action="./includes/backend.php" method="POST" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <input type="hidden" class="form-control text-center border-0" id="email" name="email" value="<?php echo htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8'); ?>">
                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                                            <div id="file-upload-area" style="border: 2px dashed #007bff; border-radius: 10px; padding: 30px; text-align: center; color: #6c757d; transition: background-color 0.3s ease; cursor: pointer;">
                                                <input type="file" id="profileImage" class="form-control" name="image" accept="image/*" required autofocus>
                                            </div>
                                        </div>
                                        <br><br>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary" name="upload">Upload</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="border-light">

                    <br>
                    <div class="card-body">
                        <form id="form" action="updating-data.php" method="POST" enctype="multipart/form-data">
                            <div class="accordion-body bg-dark text-white">
                                <div class="mb-3">
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <input type="hidden" name="email" value="<?php echo htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <input type="hidden" name="acc_number" value="<?php echo htmlspecialchars($row['acc_number'], ENT_QUOTES, 'UTF-8'); ?>">

                                </div>
                                <div class="mb-4">
                                    <!-- Email Section -->
                                    <div class="card border-primary mb-3">
                                        <div class="card-header bg-dark">
                                            <h6 class="mb-0"><i class="fas fa-envelope me-2"></i>Registered Email Address</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted small mb-3">
                                                This is your primary contact email used for account verification, security alerts,
                                                and transaction notifications. For security reasons, email changes require
                                                OTP verification.
                                            </p>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-at"></i></span>
                                                <input type="text" class="form-control bg-dark"
                                                    value="<?php echo htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8'); ?>"
                                                    placeholder="<?php echo htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8'); ?>"
                                                    disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Account Number Section -->
                                    <div class="card border-primary mb-3">
                                        <div class="card-header bg-dark">
                                            <h6 class="mb-0"><i class="fas fa-id-card me-2"></i>Account Identification</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted small mb-3">
                                                Your unique account number is permanently assigned during registration.
                                                This identifier is used for all transactions and support requests.
                                                Keep this number confidential as it helps verify your identity.
                                            </p>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                                <input type="text" class="form-control bg-dark font-monospace"
                                                    name="acc_number"
                                                    value="<?php echo htmlspecialchars($row['acc_number'], ENT_QUOTES, 'UTF-8'); ?>"
                                                    placeholder="<?php echo htmlspecialchars($row['acc_number'], ENT_QUOTES, 'UTF-8'); ?>"
                                                    disabled>
                                            </div>
                                            <div class="d-flex justify-content-end mt-2">
                                                <small class="text-muted">Immutable identifier</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <!-- Username Section -->
                                    <div class="card border-primary mb-3">
                                        <div class="card-header bg-dark">
                                            <h6 class="mb-0"><i class="fas fa-user me-2"></i>Your Username</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted small mb-3">
                                                Your username is your gaming identity that will be visible to other players.
                                                Choose something memorable but unique (3-16 characters, letters only).
                                                This name will represent you in leaderboards, multiplayer matches, and community forums.
                                                Note: Usernames cannot be changed.
                                            </p>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                <input type="text" class="form-control bg-dark"
                                                    value="<?php echo htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8'); ?>"
                                                    placeholder="<?php echo htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8'); ?>"
                                                    disabled>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Registered Device Section -->
                                    <div class="card border-primary mb-3">
                                        <div class="card-header bg-dark">
                                            <h6 class="mb-0"><i class="fas fa-mobile-alt me-2"></i>Your Registered Device</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted small mb-3">
                                                To protect your account and ensure fair play, we register your device securely.
                                                This prevents multiple accounts per device and unauthorized access.
                                                <br><br>
                                                Your device ID is encrypted for security.
                                                If you switch devices, simply verify with your email and account number using the <a href="../forgot-password">Change Credentials</a>.
                                            </p>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-fingerprint"></i></span>
                                                <input type="text" class="form-control bg-dark"
                                                    value="<?php echo htmlspecialchars($row['device_id'], ENT_QUOTES, 'UTF-8'); ?>"
                                                    placeholder="<?php echo htmlspecialchars($row['device_id'], ENT_QUOTES, 'UTF-8'); ?>"
                                                    disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <br>
                </div>

                <div class="card bg-dark text-white">
                    <div class="card-header">
                        <h5>WITHDRAWAL</h5>
                    </div>
                    <br>
                    <div class="card-body">
                        <form action="./includes/withdraw.php" method="POST">


                            <div class="accordion-body bg-dark text-white">
                                <div class="mb-3">
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <input type="hidden" name="email" value="<?php echo htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <input type="hidden" name="acc_number" value="<?php echo htmlspecialchars($row['acc_number'], ENT_QUOTES, 'UTF-8'); ?>">
                                </div>

                                <!-- Display general error message if exists -->
                                <?php if (isset($_SESSION['error_message'])): ?>
                                    <div class="alert alert-danger">
                                        <?php echo $_SESSION['error_message'];
                                        unset($_SESSION['error_message']); ?>
                                    </div>
                                <?php endif; ?>

                                <!-- Display success message if exists -->
                                <?php if (isset($_SESSION['success_message'])): ?>
                                    <div class="alert alert-success">
                                        <?php echo $_SESSION['success_message'];
                                        unset($_SESSION['success_message']); ?>
                                    </div>
                                <?php endif; ?>

                                <!-- GCash Number Section -->
                                <div class="mb-3">
                                    <div class="card border-primary mb-3">
                                        <div class="card-header bg-dark">
                                            <h6 class="mb-0"><i class="fas fa-mobile-alt me-2"></i>GCash Number</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted small mb-3">
                                                Enter your registered GCash mobile number where the payment will be sent.
                                                Must be a valid 11-digit Philippine mobile number starting with 09 (e.g., 09171234567).
                                            </p>
                                            <div class="input-group">
                                                <input type="text" class="form-control <?php echo isset($_SESSION['validation_errors']['gcash_number']) ? 'is-invalid' : ''; ?>"
                                                    name="gcash_number"
                                                    value="<?php echo isset($_SESSION['form_data']['gcash_number']) ? htmlspecialchars($_SESSION['form_data']['gcash_number'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                                                    placeholder="09171234567">
                                            </div>
                                            <?php if (isset($_SESSION['validation_errors']['gcash_number'])): ?>
                                                <div class="text-danger small mt-2">
                                                    <?php echo $_SESSION['validation_errors']['gcash_number']; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Receiver Name Section -->
                                <div class="mb-3">
                                    <div class="card border-primary mb-3">
                                        <div class="card-header bg-dark">
                                            <h6 class="mb-0"><i class="fas fa-user me-2"></i>Receiver Name</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted small mb-3">
                                                Enter the exact name as it appears in the GCash account you're sending to.
                                                This helps ensure the payment reaches the correct recipient.
                                            </p>
                                            <div class="input-group">
                                                <input type="text" class="form-control <?php echo isset($_SESSION['validation_errors']['receiver_name']) ? 'is-invalid' : ''; ?>"
                                                    name="receiver_name"
                                                    value="<?php echo isset($_SESSION['form_data']['receiver_name']) ? htmlspecialchars($_SESSION['form_data']['receiver_name'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                                                    placeholder="Juan Dela Cruz">
                                            </div>
                                            <?php if (isset($_SESSION['validation_errors']['receiver_name'])): ?>
                                                <div class="text-danger small mt-2">
                                                    <?php echo $_SESSION['validation_errors']['receiver_name']; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Amount Selection Section -->
                                <div class="mb-3">
                                    <div class="card border-primary mb-3">
                                        <div class="card-header bg-dark">
                                            <h6 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i>Amount Selection</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted small mb-3">
                                                Select the amount you want to cash out. Minimum amount is ₱20.
                                            </p>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Select Amount</label>
                                                    <select class="form-select <?php echo isset($_SESSION['validation_errors']['amount']) ? 'is-invalid' : ''; ?>"
                                                        name="amount">
                                                        <option value="" selected disabled>Choose amount</option>
                                                        <option value="20" <?php echo (isset($_SESSION['form_data']['amount']) && $_SESSION['form_data']['amount'] == 20) ? 'selected' : ''; ?>>₱20 GCASH</option>
                                                        <option value="50" <?php echo (isset($_SESSION['form_data']['amount']) && $_SESSION['form_data']['amount'] == 50) ? 'selected' : ''; ?>>₱50 GCASH</option>
                                                        <option value="100" <?php echo (isset($_SESSION['form_data']['amount']) && $_SESSION['form_data']['amount'] == 100) ? 'selected' : ''; ?>>₱100 GCASH</option>
                                                        <option value="300" <?php echo (isset($_SESSION['form_data']['amount']) && $_SESSION['form_data']['amount'] == 300) ? 'selected' : ''; ?>>₱300 GCASH</option>
                                                    </select>
                                                    <?php if (isset($_SESSION['validation_errors']['amount'])): ?>
                                                        <div class="text-danger small mt-2">
                                                            <?php echo $_SESSION['validation_errors']['amount']; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3 text-center">
                                    <button type="submit" name="withdraw" class="btn btn-primary btn-lg w-50">
                                        SUBMIT REQUEST
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
                    </div>
                </div>



                <div class="row justify-content-center">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="text-white">PAYOUT HISTORY</h5>
                            </div>
                            <div class="table-responsive" id="gcashForm">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th style="white-space: nowrap; text-align:center;">Date Requested</th>
                                            <th style="white-space: nowrap; text-align:center;">Amount</th>
                                            <th style="white-space: nowrap; text-align:center;">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Make sure $row['player_id'] exists and is valid
                                        $accNumber = $row['acc_number'];

                                        // Use prepared statement to prevent SQL injection
                                        $sql = "SELECT amount, gcash_number, date_requested, status FROM withdrawals WHERE acc_number = ? ORDER BY date_requested DESC";
                                        $stmt = mysqli_prepare($con, $sql);

                                        if ($stmt) {
                                            mysqli_stmt_bind_param($stmt, "i", $accNumber);
                                            mysqli_stmt_execute($stmt);
                                            $result = mysqli_stmt_get_result($stmt);

                                            if ($result && mysqli_num_rows($result) > 0) {
                                                while ($data = mysqli_fetch_assoc($result)) {
                                                    // Sanitize all output
                                                    $amount = htmlspecialchars($data['amount'], ENT_QUOTES, 'UTF-8');
                                                    $date = htmlspecialchars($data['date_requested'], ENT_QUOTES, 'UTF-8');
                                                    $status = htmlspecialchars($data['status'], ENT_QUOTES, 'UTF-8');

                                                    // Format the date if needed
                                                    $formatted_date = date('M j, Y', strtotime($date));

                                                    // Determine status color
                                                    $status_class = '';
                                                    if ($status === 'Success') {
                                                        $status_class = 'text-success';
                                                    } elseif ($status === 'Failed') {
                                                        $status_class = 'text-danger';
                                                    }
                                        ?>
                                                    <tr>
                                                        <td style="white-space: nowrap; text-align:center;" class="text-white "><?= $formatted_date ?></td>
                                                        <td style="white-space: nowrap; text-align:center;" class="text-white ">₱<?= number_format($amount, 2) ?></td>
                                                        <td style="white-space: nowrap; text-align:center;" class=" <?= $status_class ?>"><?= $status ?></td>
                                                    </tr>
                                        <?php
                                                }
                                            } else {
                                                echo '<tr><td colspan="4" class="text-white text-center">No payout records found</td></tr>';
                                            }

                                            mysqli_stmt_close($stmt);
                                        }

                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </main>

            <?php include 'footer.php'; ?>

        </div>
    </div>
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "WebPage",
            "name": "Account | EarningSphere",
            "description": "Manage your profile, withdraw your balance, track VIP status, and update username on your profile page.",
            "url": "https://earningsphere.online/account.php",
            "mainEntityOfPage": "https://earningsphere.online/account.php",
            "potentialAction": [{
                    "@type": "Action",
                    "name": "Update Profile",
                    "target": "https://earningsphere.online/account.php#updateProfileModal",
                    "actionStatus": "ActiveActionStatus",
                    "description": "User can update their profile and invitation code."
                },
                {
                    "@type": "Action",
                    "name": "Upload Profile Picture",
                    "target": "https://earningsphere.online/account.php#upload-profile",
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

</html>