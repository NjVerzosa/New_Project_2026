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
    <title>My Referral Network | EarningSphere</title>
    <meta name="title" content="My Referral Dashboard | EarningSpheres">
    <meta name="description" content="Track your referral progress and earned rewards on EarningSphere. Monitor your invited users' completed tasks, claim your bonuses, and discover new earning opportunities through our referral program.">
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1950666755759348"
        crossorigin="anonymous"></script>
    <meta name="site_name" content="EarningSphere">
    <meta name="url" content="https://earningsphere.online/invites.php">
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
    <link rel="stylesheet" href="landing-assets/assets/style.css">
</head>

<body class="noselect">
    <div class="wrapper">

        <?php include 'web-sidebar.php'; ?>

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

                <!-- Referral Code -->
                <div class="card bg-dark text-white p-3 mb-2">
                    <div class="card-statistic-3">
                        <div class="card-icon card-icon-large text-light"><i class="fas fa-share-alt"></i></div>
                        <div class="mb-4">
                            <h5 class="card-title mb-0 text-light">Referral Code</h5>
                        </div>
                        <div class="row align-items-center mb-2 d-flex">
                            <div class="d-flex align-items-center">
                                <div class="col-9">
                                    <h2 class="d-flex align-items-center mb-0 text-light">
                                        <?php echo htmlspecialchars($row['my_referral']); ?>
                                    </h2>
                                </div>
                                <div class="col-8 text-right">
                                    <span class="text-light" style="cursor: pointer; font-size: 18px; font-weight: bold;">
                                        <button id="copyReferralButton" class="btn btn-primary ms-auto"
                                            onclick="copyReferralCode('<?php echo htmlspecialchars($row['my_referral'] ?? ''); ?>')">
                                            COPY
                                        </button>
                                    </span>
                                </div>
                                <script>
                                    function copyReferralCode(code) {
                                        // Check if clipboard API is available
                                        if (!navigator.clipboard) {
                                            alert('Clipboard access not supported in your browser. Please manually copy: ' + code);
                                            return;
                                        }

                                        navigator.clipboard.writeText(code).then(() => {
                                            const copyButton = document.getElementById('copyReferralButton');

                                            // Change button appearance
                                            copyButton.textContent = 'COPIED!';
                                            copyButton.classList.remove('btn-primary');
                                            copyButton.classList.add('btn-success');

                                            // Reset button after 2 seconds
                                            setTimeout(() => {
                                                copyButton.textContent = 'COPY';
                                                copyButton.classList.remove('btn-success');
                                                copyButton.classList.add('btn-primary');
                                            }, 2000);

                                        }).catch(err => {
                                            console.error('Failed to copy: ', err);
                                            alert('Failed to copy referral code. Please try again or copy manually.');
                                        });
                                    }
                                </script>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h5><?php echo htmlspecialchars($row['my_referral'], ENT_QUOTES, 'UTF-8'); ?> - Users</h5>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th class="text-center">Player ID</th>
                                            <th class="text-center">Profile</th>
                                            <th class="text-center">Joined</th>
                                            <th class="text-center">Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $stmt = $con->prepare("SELECT id, profile, registered_at, balance FROM users WHERE my_invitation_code = ?");
                                        $stmt->bind_param("s", $row['my_referral']);
                                        $stmt->execute();
                                        $result = $stmt->get_result();

                                        if ($result->num_rows > 0) {
                                            while ($data = $result->fetch_assoc()) {
                                                $profileImage = !empty($data['profile']) ? htmlspecialchars($data['profile']) : 'default.jpg';
                                                $registeredDate = htmlspecialchars($data['registered_at']);
                                                $earned_coins = number_format($data['earned_coins'], 2);
                                        ?>
                                                <tr>
                                                    <td class="text-white text-center"><?= htmlspecialchars($data['id']) ?></td>
                                                    <td class="text-center">
                                                        <img src="userProfile/<?= $profileImage ?>"
                                                            onclick="toggleZoom(this)" alt="Profile Image" style="width:30px;height:30px;cursor:pointer;border-radius: 50px;">
                                                    </td>
                                                    <td class="text-white text-center"><?= $registeredDate ?></td>
                                                    <td class="text-white text-center">₱ <?= $earned_coins ?></td>
                                                </tr>
                                        <?php
                                            }
                                        } else {
                                            echo '<tr>
                                                    <td colspan="4" class="text-center py-4">
                                                        <img src="images/record_found.jpg" alt="No records found"
                                                            class="img-fluid" style="max-width: 50px; opacity: 0.5;">
                                                    </td>
                                                </tr>';
                                        }
                                        $stmt->close();
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade text-center" id="profileImageModal" tabindex="-1" aria-labelledby="profileImageModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" style="margin: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                        <div class="modal-content" style="background: transparent; border: none; box-shadow: none; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; padding: 0; position: relative;">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                style="position: absolute; top: 10px; right: 10px; background: blue; border-radius: 50%; padding: 5px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);">
                            </button>
                            <img id="profileImageZoom" src="" alt="Profile Image" style="width: 90%; max-width: 320px; height: auto; object-fit: cover; border: 5px solid #fff; border-radius: 10px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);">
                        </div>
                    </div>
                </div>

                <script>
                    function toggleZoom(profileImage) {
                        const profileImageZoom = document.getElementById("profileImageZoom");
                        profileImageZoom.src = profileImage.src; // Set the source of the modal image to the clicked image
                        const profileImageModal = new bootstrap.Modal(document.getElementById('profileImageModal'), {
                            keyboard: false
                        });
                        profileImageModal.show(); // Show the modal
                    }
                </script>


                <div class="card bg-dark text-white p-6 mb-2">
                    <div class="card-header">
                        <h5>INVITES</h5>
                    </div>
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <span class="english">
                                Invite your friends to join EarningSphere and earn rewards together! Share your unique invitation code with others to help grow our community.
                                All your invited friends will appear here, and you'll be able to track their progress and your earnings from successful referrals.
                            </span>
                            <br>
                            <br>
                            <span class="english" style="text-align: center;">
                                Last updated on: 10 October 2024.
                            </span>
                        </div>
                    </div>
                </div>
            </main>
            <?php include 'web-footer.php'; ?>
        </div>
    </div>
</body>
<script src="game-js/pop-up.js"></script>

</html>