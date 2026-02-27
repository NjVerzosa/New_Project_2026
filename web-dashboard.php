<?php
include 'user-sessions.php';
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark" style="font-size:14px">

<head>
    <meta charset=" UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="index, follow">
    <title>Dashboard | EarningSphere</title>
    <meta name="title" content="Dashboard | EarningSphere">
    <meta name="description"
        content="Your central hub for tracking completed tasks, claiming rewards, and discovering new earning opportunities on EarningSphere. Monitor your progress and maximize your earnings.">
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1950666755759348"
        crossorigin="anonymous"></script>

    <link rel="icon" href="images/logo.png" type="image/x-icon">
    <meta name="image" content="https://earningsphere.online/images/logo.png">
    <meta name="url" content="https://earningsphere.online/home.php">

    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebPage",
        "name": "Dashboard | EarningSphere",
        "description": "Your central hub for tracking completed tasks, claiming rewards, and discovering new earning opportunities on EarningSphere. Monitor your progress and maximize your earnings.",
        "url": "https://earningsphere.online/home.php",
        "mainEntityOfPage": "https://earningsphere.online/home.php",
        "potentialAction": [{
                "@type": "Action",
                "name": "Convert Points",
                "target": "#convertPoints",
                "actionStatus": "ActiveActionStatus",
                "description": "User needs to click to convert their points to balance."
            },
            {
                "@type": "Action",
                "name": "Claim Balance",
                "target": "#claimBalance",
                "actionStatus": "ActiveActionStatus",
                "description": "User needs to click to claim their balance."
            },
            {
                "@type": "Action",
                "name": "Referred Reward",
                "target": "#referredReward",
                "actionStatus": "ActiveActionStatus",
                "description": "User needs to click to claim rewards for referred users."
            },
            {
                "@type": "Action",
                "name": "Copy Referral",
                "target": "#copyReferral",
                "actionStatus": "ActiveActionStatus",
                "description": "User needs to click to copy their referral link."
            },
            {
                "@type": "Action",
                "name": "Daily Login",
                "target": "#dailyLogin",
                "actionStatus": "ActiveActionStatus",
                "description": "User needs to click to claim their daily login reward."
            }
        ]
    }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/blockadblock@1.0.0/blockadblock.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="landing-assets/assets/style.css">
</head>

<body class="noselect">
    <div class="wrapper">

        <?php include 'web-sidebar.php'; ?>

        <div class="main">
            <nav class="navbar navbar-expand px-3 border-bottom" style="background: #2a2438;">
                <button class="btn border-0 bg-transparent p-2" id="sidebar-toggle" type="button"
                    style="width: 45px; height: 45px;">
                    <i class="fa-solid fa-bars text-white fs-5"></i>
                </button>

                <div class="navbar-collapse navbar">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a href="#" data-bs-toggle="dropdown" class="nav-icon pe-md-0">
                                <img src="userProfile/<?php echo htmlspecialchars($row['profile'], ENT_QUOTES, 'UTF-8'); ?>"
                                    style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover; max-width: 100%; max-height: 100%;"
                                    class="avatar img-fluid rounded" alt="User Profile Picture">
                            </a>
                            <div class="dropdown-menu dropdown-menu-end bg-white">
                                <a href="logout.php" rel="nofollow" class="dropdown-item text-black">
                                    <i class="fa-solid fa-right-from-bracket pe-2"></i>Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
            <main class="content px-3 py-5" style="font-size: 14px;">
                <?php if (isset($_SESSION['device_id'])) { ?>
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        html: '<div style="font-size: 14px; color: white;"><?php echo htmlspecialchars($_SESSION['device_id'], ENT_QUOTES, 'UTF-8'); ?></div>',
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        width: '250px',
                        padding: '10px',
                        background: 'green',
                        customClass: {
                            container: 'toast-top-container',
                            popup: 'toast-top-popup'
                        },
                        showClass: {
                            popup: 'animate__animated animate__fadeInDown'
                        },
                        hideClass: {
                            popup: 'animate__animated animate__fadeOutUp'
                        }
                    });
                });
                </script>
                <?php unset($_SESSION['device_id']); ?>
                <?php } ?>

                <!-- Required for animations -->
                <link rel="stylesheet"
                    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
                <!-- SweetAlert2 -->
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


                <?php if (isset($_SESSION['success_message'])) {
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
                } ?>
                <div class="container">
                    <div class="row">
                        <!-- Collected Balance Card -->
                        <div class="card text-white p-3 mb-2"
                            style="background: linear-gradient(135deg, rgba(101,78,163,0.8) 0%, rgba(41,10,89,0.9) 100%); border: 1px solid rgba(255,255,255,0.15); box-shadow: 0 4px 20px rgba(101,78,163,0.3);">
                            <div class="card-statistic-3">
                                <div class="card-icon card-icon-large text-light"><i class="fas fa-wallet"></i></div>
                                <div class="mb-4">
                                    <h5 class="card-title mb-0 text-light">Account Balance</h5>
                                </div>

                                <div class="d-flex align-items-center">
                                    <!-- Content on the left (if any) -->
                                    <div class="col-9">
                                        <h2 class="d-flex align-items-center mb-0 text-light" id="coins">
                                            ₱<?php echo number_format($row['balance'], 2); ?>
                                        </h2>
                                    </div>
                                </div>

                                <!-- Progress bar (max 50.00) -->
                                <?php
                                $maxBalance = 50.00; // Updated max balance
                                $progressPercentage = ($row['balance'] / $maxBalance) * 100;
                                $progressPercentage = min($progressPercentage, 100); // Cap at 100%
                                ?>
                                <div class="progress mt-1" data-height="8" style="height: 8px;">
                                    <div class="progress-bar l-bg-green" role="progressbar"
                                        aria-valuenow="<?php echo $progressPercentage; ?>" aria-valuemin="0"
                                        aria-valuemax="100" style="width: <?php echo $progressPercentage; ?>%;">
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Collected Bonus Card -->
                        <div class="card text-white p-3 mb-2"
                            style="background: linear-gradient(135deg, rgba(101,78,163,0.8) 0%, rgba(41,10,89,0.9) 100%); border: 1px solid rgba(255,255,255,0.15); box-shadow: 0 4px 20px rgba(101,78,163,0.3);">
                            <div class="card-statistic-3">
                                <div class="card-icon card-icon-large text-light"><i class="fas fa-gift"></i></div>
                                <div class="mb-4">
                                    <h5 class="card-title mb-0 text-light">Collected Daily Login</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="d-flex align-items-center">
                                        <div class="col-9">
                                            <h2 class="d-flex align-items-center mb-0 text-light">
                                                ₱<?php echo number_format($row['daily_login_earnings'], 2); ?>
                                            </h2>
                                        </div>
                                        <?php if ($row['daily_login_earnings'] >= 1.00) { ?>
                                        <div class="col-8 text-right">
                                            <span class="text-light" id='dailyLogin'>
                                                <button id="dailyLoginButton" class="btn btn-primary ms-auto"
                                                    onclick="claimBonus()">
                                                    CLAIM
                                                </button>
                                            </span>
                                        </div>
                                        <?php }

                                        $maxBalance = 1.00; // Maximum balance for full progress
                                        $progressPercentage = ($row['daily_login_earnings'] / $maxBalance) * 100; // Calculate percentage
                                        $progressPercentage = min($progressPercentage, 100);

                                        ?>
                                    </div>
                                </div>
                                <div class="progress mt-1" data-height="8" style="height: 8px;">
                                    <div class="progress-bar <?php echo $progressClass; ?>" role="progressbar"
                                        aria-valuenow="<?php echo $progressPercentage; ?>" aria-valuemin="0"
                                        aria-valuemax="100" style="width: <?php echo $progressPercentage; ?>%;">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>





                <div class="card bg-dark text-white p-3 mb-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="text-white text-center">Tasks Progress</h4>
                    </div>
                    <div class="mt-3">
                        <?php
                        // Check if $row exists and has required fields
                        if (!isset($row) || !isset($row['email']) || !isset($row['acc_number'])) {
                            echo "<p class='text-danger'>Error: User data not available</p>";
                        } else {
                            $email = $row['email'];
                            $acc_number = $row['acc_number'];

                            // Validate connection
                            if (!isset($con) || !($con instanceof mysqli)) {
                                echo "<p class='text-danger'>Database connection error</p>";
                            } else {
                                try {
                                    $sql = "SELECT id, acc_number, score, date FROM tasks WHERE email = ? AND acc_number = ? AND status = 'Pending' ORDER BY date DESC";
                                    $stmt = $con->prepare($sql);

                                    if (!$stmt) {
                                        throw new Exception("Error preparing query: " . $con->error);
                                    }

                                    $stmt->bind_param("si", $email, $acc_number);

                                    if (!$stmt->execute()) {
                                        throw new Exception("Error executing query: " . $stmt->error);
                                    }

                                    $result = $stmt->get_result();
                                    $maxScore = 300.00;
                                    $pesosValue = 300.00;
                                    $today = date('D, j M Y');
                                    $yesterday = date('D, j M Y', strtotime('-1 day'));

                                    if ($result && $result->num_rows > 0) {
                                        while ($tasksData = $result->fetch_assoc()) {
                                            // Validate and format date
                                            $entryDate = '';
                                            if (isset($tasksData['date']) && !empty($tasksData['date'])) {
                                                try {
                                                    $dateObj = new DateTime($tasksData['date']);
                                                    $entryDate = $dateObj->format('D, j M Y');
                                                } catch (Exception $e) {
                                                    $entryDate = 'Invalid date';
                                                }
                                            }

                                            $displayDate = $entryDate;
                                            if ($entryDate == $today) {
                                                $displayDate = "Today";
                                            } elseif ($entryDate == $yesterday) {
                                                $displayDate = "Yesterday";
                                            } elseif (empty($entryDate)) {
                                                $displayDate = 'N/A';
                                            }

                                            // Validate score data
                                            $tasksScore = isset($tasksData['score']) ? (int)$tasksData['score'] : 0;
                                            $pesosEarned = round(($tasksScore / $maxScore) * $pesosValue);
                                            $progressPercentage = min(round(($tasksScore / $maxScore) * 100), 100); // Ensure it doesn't exceed 100%
                                            $taskId = isset($tasksData['id']) ? (int)$tasksData['id'] : 0;
                        ?>
                        <div class="d-flex justify-content-between align-items-center">
                            <span><?= htmlspecialchars($displayDate, ENT_QUOTES, 'UTF-8'); ?></span>
                            <?php if ($tasksScore >= $maxScore): ?>
                            <button id="dailyRewards_<?= $taskId ?>" onclick="claimTasks(<?= $taskId ?>)"
                                class="btn btn-primary btn-sm" style="border-radius: 5px; border: 1px solid #007bff;">
                                CLAIM
                            </button>
                            <?php else: ?>
                            <button disabled class="btn btn-secondary btn-sm"
                                style="border-radius: 5px; border: 1px solid #6c757d;">
                                CLAIM
                            </button>
                            <?php endif; ?>
                        </div>

                        <div class="progress mt-1" style="height: 15px;">
                            <div class="progress-bar bg-success" role="progressbar"
                                style="width: <?= $progressPercentage; ?>%;" aria-valuenow="<?= $tasksScore; ?>"
                                aria-valuemin="0" aria-valuemax="<?= $maxScore; ?>">
                                <?php if ($tasksScore < $maxScore): ?>
                                ₱<?= htmlspecialchars($pesosEarned, ENT_QUOTES, 'UTF-8'); ?>
                                <?php else: ?>
                                ₱<?= htmlspecialchars($pesosValue, ENT_QUOTES, 'UTF-8'); ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <hr class="border-light">
                        <?php
                                        }
                                    } else {
                                        ?>
                        <p class="text-center py-4">
                            <img src="landing-assets/images/record_found.jpg" alt="No records found" class="img-fluid"
                                style="max-width: 50px; opacity: 0.5;">
                        </p>
                        <?php
                                    }

                                    $stmt->close();
                                } catch (Exception $e) {
                                    echo "<p class='text-danger'>Error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
                <?php
                //  include 'embeded.php'; ?>
            </main>
            <?php
            //  include 'footer.php'; ?>
        </div>
    </div>
</body>
<script>
const userId = <?= json_encode($row['id'] ?? null) ?>;
const acc_number = <?= json_encode($row['acc_number'] ?? null) ?>;
const userEmail = <?= json_encode($row['email'] ?? null) ?>;
const csrfToken = <?= json_encode($_SESSION['csrf_token'] ?? ''); ?>;

const dailyLogin = <?= json_encode($row['daily_login']) ?>;
const userMy_referral_earnings = "<?php echo addslashes($row['my_referral_earnings']); ?>";
</script>
<script src="game-js/claim-tasks.js"></script>
<script src="game-js/claims.js"></script>
<script src="game-js/pop-up.js"></script>

</html>