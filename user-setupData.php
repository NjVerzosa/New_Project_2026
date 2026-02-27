<?php
session_start();
include 'user-sessions.php';  // If database.php is one level up in config folder
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="dark" style="font-size:14px">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="index, follow">
    <title>Setting | EarningSphere</title>
    <meta name="title" content="Account | EarningSphere">
    <meta name="description"
        content="Manage your profile, withdraw your balance, track VIP status, and update username on your profile page.">
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

<body class="">
    <div class="wrapper">


        <div class="main">
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




                <div class="card bg-dark text-white">
                    <div class="card-header">
                        <h5>ACCOUNT SETUP</h5>
                    </div>
                    <br>
                    <div class="card-body">
                        <form id="form" action="user-updateData.php" method="POST" enctype="multipart/form-data">
                            <div class="accordion-body bg-dark text-white">
                                <div class="mb-3">
                                    <!-- <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>"> -->
                                    <input type="text" name="email"
                                        value="<?php echo htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <input type="text" name="acc_number"
                                        value="<?php echo htmlspecialchars($row['acc_number'], ENT_QUOTES, 'UTF-8'); ?>">

                                </div>
                                <div class="mb-4">
                                    <!-- Email Section -->
                                    <div class="card border-primary mb-3">
                                        <div class="card-header bg-dark">
                                            <h6 class="mb-0"><i class="fas fa-envelope me-2"></i>Registered Email
                                                Address</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted small mb-3">
                                                This is your primary contact email used for account verification,
                                                security alerts,
                                                and transaction notifications. For security reasons, email changes
                                                require
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
                                            <h6 class="mb-0"><i class="fas fa-id-card me-2"></i>Account Identification
                                            </h6>
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

                                <div class="mb-3">
                                    <!-- Username Section -->
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="usernameCheckbox">
                                        <label class="form-check-label" for="usernameCheckbox">
                                            <label>Create Your Unique Identity</label>
                                            <small class="d-block text-muted mt-1">
                                                Your username is your gaming identity that will be visible to other
                                                players.
                                                Choose something memorable but unique (3-16 characters, letters/numbers
                                                only).
                                                This name will represent you in leaderboards, multiplayer matches, and
                                                community forums.
                                                Note: Usernames cannot be changed later.
                                            </small>
                                        </label>
                                    </div>
                                    <div id="usernameInputGroup" style="display: none;">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="username" id="username"
                                                value="<?php echo htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8'); ?>"
                                                placeholder="<?php echo htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8'); ?>">
                                            <button class="btn btn-outline-secondary" type="button"
                                                id="checkUsernameBtn">
                                                Check Availability
                                            </button>
                                        </div>
                                        <div id="usernameError" class="invalid-feedback" style="display: none;"></div>
                                        <div id="usernameSuccess" class="valid-feedback" style="display: none;">Username
                                            looks good!</div>
                                        <div id="usernameChecking" class="text-info small mt-1" style="display: none;">
                                            <i class="fas fa-spinner fa-spin"></i> Checking username availability...
                                        </div>
                                    </div>
                                </div>


                                <div class="mb-3">
                                    <!-- Device ID Section -->
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="deviceIdCheckbox">
                                        <label class="form-check-label" for="deviceIdCheckbox">
                                            <span>Secure Device Registration</span>
                                            <small class="d-block text-muted mt-1">
                                                For fair gameplay and account security, we require device registration.
                                                This ensures one account per device and prevents unauthorized access.
                                                Your device ID is encrypted and stored securely. If you change devices,
                                                you'll need to verify via email. Click GENERATE to create a unique
                                                identifier
                                                for your current device.
                                            </small>
                                        </label>
                                    </div>

                                    <div id="deviceIdInputGroup" style="display: none;">
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control bg-dark text-white"
                                                id="device_display"
                                                value="<?php echo htmlspecialchars($row['device_id'], ENT_QUOTES, 'UTF-8'); ?>"
                                                placeholder="<?php echo htmlspecialchars($row['device_id'], ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                        <input type="hidden" name="device_id" id="device_id" value="">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <!-- Password Section -->
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="passwordCheckbox">
                                        <label class="form-check-label" for="passwordCheckbox">
                                            <label>Account Protection</label>
                                            <small class="d-block text-muted mt-1">
                                                Create a strong password to safeguard your account and progress.
                                                We recommend a minimum 12-character mix of uppercase, lowercase,
                                                numbers and symbols. Avoid common phrases or personal information.
                                                Your password encrypts all game data and purchase history.
                                                Consider using a password manager for best security practices.
                                            </small>
                                        </label>
                                    </div>
                                    <div id="passwordInputGroup" style="display: none;">
                                        <div class="input-group">
                                            <input type="password" class="form-control" name="password" id="password"
                                                placeholder="Enter new password" required>
                                            <span class="input-group-text toggle-password bg-dark"
                                                style="cursor: pointer;">
                                                <i class="fa fa-eye"></i>
                                            </span>
                                        </div>
                                        <small id="passwordHelp" class="form-text text-muted">Use 8+ characters with mix
                                            of uppercase, lowercase, numbers & symbols</small>
                                        <div class="password-strength mt-2">
                                            <div class="progress" style="height: 5px;">
                                                <div id="password-strength-bar" class="progress-bar" role="progressbar"
                                                    style="width: 0%"></div>
                                            </div>
                                            <div id="passwordError" class="invalid-feedback" style="display: none;">
                                            </div>
                                            <div id="passwordSuccess" class="valid-feedback" style="display: none;">
                                                Strong password!</div>
                                            <ul id="password-requirements" class="list-unstyled mt-2"
                                                style="font-size: 0.8rem;">
                                                <li id="req-length" class="text-muted"><i class="far fa-circle"></i>
                                                    Minimum 8 characters</li>
                                                <li id="req-uppercase" class="text-muted"><i class="far fa-circle"></i>
                                                    At least 1 uppercase letter</li>
                                                <li id="req-lowercase" class="text-muted"><i class="far fa-circle"></i>
                                                    At least 1 lowercase letter</li>
                                                <li id="req-number" class="text-muted"><i class="far fa-circle"></i> At
                                                    least 1 number</li>
                                                <li id="req-special" class="text-muted"><i class="far fa-circle"></i> At
                                                    least 1 special character</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>


                                <div class="mB-3 text-center">
                                    <button type="submit" name="setup" class="btn btn-primary btn-sm w-50"
                                        id="submitBtn">
                                        SUBMIT
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <br>
                </div>

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
                <?php 
                // include 'embeded.php'; 
                ?>
            </main>
            <?php 
            // include 'footer.php';
             ?>
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
<script src="landing-assets/js/validate.js"></script>

</html>