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
    <link rel="icon" href="images/logo.png" type="image/x-icon">
    <title>Number Encode | EarningSphere</title>
    <meta name="title" content="Number Encode | EarningSphere">
    <meta name="description" content="Test your speed in encoding numbers and solving tasks to earn rewards.">
    <meta name="keywords"
        content="number typing game, speed challenge, encoding task, online earning game, quick typing test, skill-based rewards, EarningSphere">
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1950666755759348"
        crossorigin="anonymous"></script>
    <meta name="site_name" content="EarningSphere">
    <meta name="image" content="https://earningsphere.online/images/logo.png">
    <meta name="url" content="https://earningsphere.online/encoding.php">
    <script src="https://cdn.jsdelivr.net/npm/blockadblock@1.0.0/blockadblock.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="landing-assets/assets/style.css">
</head>

<body class="">
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

            <main class="content px-3 py-4">

                <div class="row justify-content-center">
                    <div class="card text-white p-3 mb-2"
                        style="width: 100%; background: linear-gradient(135deg, rgba(101,78,163,0.8) 0%, rgba(41,10,89,0.9) 100%); border: 1px solid rgba(255,255,255,0.15); box-shadow: 0 4px 20px rgba(101,78,163,0.3);">
                        <div class="card-statistic-3">
                            <div class="card-icon card-icon-large text-light"><i class="fas fa-coins"></i></div>
                            <div class="mb-4">
                                <h5 class="card-title mb-0 text-light">Earned Coins</h5>
                            </div>
                            <div class="d-flex align-items-center">
                                <!-- Display current coins -->
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
                    <div class="card bg-dark text-white">
                        <div class="card-header">
                            <h5>ENCODING NUMBER TASK</h5>
                        </div>
                        <div class="card shadow-sm border-0">
                            <div class="card-body">
                                <div class="bg-dark-light rounded">
                                    <p class="mb-2">
                                    <h4 class="font-weight-bold"> <i class="fas fa-check-circle text-success mr-2"></i>
                                        How It Works:</h4>
                                    </p>
                                    <ul class="list-unstyled">
                                        <li class="mb-3 d-flex align-items-start">
                                            <span class="me-2 text-primary">●</span>
                                            <span class="english">Enter the numbers displayed in the box. Your earned
                                                coins will automatically increase when you type the correct matching
                                                numbers.</span>
                                        </li>
                                        <li class="mb-3 d-flex align-items-start">
                                            <span class="me-2 text-primary">●</span>
                                            <span class="english">Unlimitted encoding</span>
                                        </li>

                                        <li class="mb-3 d-flex align-items-start">
                                            <span class="me-2 text-primary">●</span>
                                            <span class="english">Focus on this task to complete the progress in your
                                                dashboard.</span>
                                        </li>

                                    </ul>
                                </div>
                                <br>
                                <span class="english">
                                    Transform digits into earnings through our Number Encoding system. Each successful
                                    encoding session
                                    adds to your reward balance, with accuracy and speed determining your payout. This
                                    dashboard displays
                                    your encoding history, success rate, and accumulated rewards. Enhance your encoding
                                    efficiency to
                                    optimize your earning potential with our progressive compensation structure.
                                </span>
                                <br>
                                <br>
                                <span class="english" style="text-align: center;">
                                    Last updated on: 10 October 2024.
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="container-fluid">
                        <div class="row justify-content-center align-items-center">
                            <div class="col-14 col-md-12 col-lg-6">
                                <div class="card shadow-lg border-0 rounded-4">
                                    <div class="card-header text-center">
                                        <h5>ENCODING NUMBER TASK</h5>
                                    </div>
                                    <div class="card-body py-5 d-flex flex-column align-items-center bg-dark rounded-2">
                                        <div class="text-center mb-4 position-relative">
                                            <img id="profileImage"
                                                src="userProfile/<?php echo htmlspecialchars($row['profile'], ENT_QUOTES, 'UTF-8'); ?>"
                                                class="rounded-circle shadow"
                                                style="width: 100px; height: 100px; object-fit: cover; border: 4px solid #28a745; cursor: pointer;"
                                                alt="User Profile Picture">
                                        </div>

                                        <div id="number-display"
                                            class="text-center p-3 rounded shadow-sm bg-dark border fw-bold text-primary text-white"
                                            style="font-family: 'Courier New', Courier, monospace; font-size: 23px; text-shadow: 1px 1px 2px rgba(0,0,0,0.2);">
                                            Generating....
                                        </div>

                                        <input type="text" id="user-input" placeholder="Type your answer here"
                                            class="form-control text-center mt-3" style="text-transform: uppercase; ">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row justify-content-center">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>ENCODE NUMBER HISTORY</h5>
                            </div>
                            <div class="table-responsive">
                                <table class="table"
                                    style="border-collapse: separate; border-spacing: 0; border: none;">
                                    <thead>
                                        <tr>
                                            <th
                                                style="white-space: nowrap; text-align: center; border: none !important;">
                                                Date</th>
                                            <th
                                                style="white-space: nowrap; text-align: center; border: none !important;">
                                                Correct</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT * FROM encode WHERE email = ? AND acc_number = ? ORDER BY id DESC";
                                        $stmt = $con->prepare($sql);

                                        if ($stmt) {
                                            $stmt->bind_param("si", $row['email'], $row['acc_number']);
                                            $stmt->execute();
                                            $result = $stmt->get_result();

                                            $today = date('Y-m-d');
                                            $yesterday = date('Y-m-d', strtotime('-1 day'));

                                            if ($result && $result->num_rows > 0) {
                                                while ($data = $result->fetch_assoc()) {
                                                    $entryDate = isset($data['date']) ? date('Y-m-d', strtotime($data['date'])) : '';

                                                    if ($entryDate == $today) {
                                                        $displayDate = "Today";
                                                    } elseif ($entryDate == $yesterday) {
                                                        $displayDate = "Yesterday";
                                                    } else {
                                                        $displayDate = !empty($entryDate) ? date('D, m-Y', strtotime($data['date'])) : 'N/A';
                                                    }
                                        ?>
                                        <tr style="border: none !important;">
                                            <td
                                                style="white-space: nowrap; text-align: center; border: none !important;">
                                                <?= htmlspecialchars($displayDate, ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td
                                                style="white-space: nowrap; text-align: center; border: none !important;">
                                                <?= htmlspecialchars($data['score'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?>
                                            </td>
                                        </tr>
                                        <?php
                                                }
                                            } else {
                                                ?>
                                        <tr style="border: none !important;">
                                            <td colspan="4"
                                                style="text-align: center; padding: 20px; border: none !important;">
                                                <img src="landing-assets/images/record_found.jpg" alt=""
                                                    style="width: 50px; height: 50px; border-radius: 10px; opacity: 0.5;">
                                            </td>
                                        </tr>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
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
        "@type": "Guessing",
        "name": "Number Encode | EarningSphere",
        "description": "Test your speed in encoding numbers and solving tasks to earn rewards.",
        "url": "https://earningsphere.online/encoding.php",
        "gameMode": "SinglePlayer",
        "playMode": "Online",
        "gameCategory": "EducationalGame",
        "educationalUse": "SkillDevelopment",
        "educationalLevel": "Beginner",
        "isAccessibleForFree": true,
        "mainEntityOfPage": {
            "@type": "WebPage",
            "@id": "https://earningsphere.online/encoding.php"
        },
        "potentialAction": [{
                "@type": "PlayAction",
                "name": "Start encoding",
                "target": "https://earningsphere.online/encoding.php",
                "actionStatus": "PotentialActionStatus",
                "description": "Begin the quiz and complete tasks to earn rewards."
            },
            {
                "@type": "Action",
                "name": "Enter Number",
                "target": "#user-input",
                "actionStatus": "ActiveActionStatus",
                "description": "The field where users input their answers."
            }
        ]
    }
    </script>
</body>
<script>
let userId = <?= json_encode($row['id'] ?? null) ?>;
let acc_number = <?= json_encode($row['acc_number'] ?? null) ?>;
let userEmail = <?= json_encode($row['email'] ?? null) ?>;
let csrfToken = <?= json_encode($_SESSION['csrf_token'] ?? ''); ?>;
</script>
<script src="game-js/claims.js"></script>
<script src="game-js/encoding.js"></script>
<script src="game-js/pop-up.js"></script>

</html>