<?php
include 'admin-sessions.php';
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>EP | Account Manager</title>
    <?php include 'parts/frameworks.html'; ?>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div class="wrapper">
        <?php include 'parts/sidebar.php'; ?>
        <div class="main">
            <nav class="navbar navbar-expand px-3 border-bottom">
                <button class="btn" id="sidebar-toggle" type="button">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="navbar-collapse navbar">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a href="#" data-bs-toggle="dropdown" class="nav-icon pe-md-0">
                                <img src="assets/image/logo.png" class="avatar img-fluid rounded" alt="">
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="logout.php" class="dropdown-item">Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
            <main class="content px-3 py-2">
                <div class="container-fluid">

                    <div class="card border-0">
                        <div class="card-header bg-primary text-white text-center">
                            <div class="row align-items-center">
                                <div class="col-md-6 col-lg-4 mb-1 mb-md-0 d-flex justify-content-start">
                                    <button type="button" onclick="refreshPage()" class="btn btn-secondary">
                                        Refresh
                                    </button>
                                </div>
                            </div>
                        </div>
                        <script>
                            function refreshPage() {
                                location.reload();
                            }
                        </script>

                        <div class="records table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">ID</th>
                                        <th style="text-align: center;">Player</th>
                                        <th style="text-align: center;">Earned</th>
                                        <th style="text-align: center;">Balance</th>
                                        <th style="text-align: center;">Total Income</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT * FROM users";
                                    $result = mysqli_query($con, $sql);
                                    if ($result && mysqli_num_rows($result) > 0) {
                                        while ($data = mysqli_fetch_assoc($result)) {
                                            // Remove "@gmail.com" from the email
                                            $email = str_replace('@gmail.com', '', $data['email']);

                                    ?>
                                            <tr>
                                                <td style="white-space: nowrap;text-align: center;">
                                                    <?= $data['id']; ?>
                                                </td>
                                                <td style="white-space: nowrap; text-align:center;"><?= $email; ?></td>

                                                <td style="white-space: nowrap;text-align: center;">
                                                    <?= $data['earnedPoints']; ?>
                                                </td>

                                                <td style="white-space: nowrap;text-align: center;">
                                                    <?= $data['balance']; ?>
                                                </td>

                                                <td style="white-space: nowrap;text-align: center;">
                                                    <?= $data['total_income']; ?>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan='10'>No records found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>

            <a href="#" class="theme-toggle"></a>
            <footer class="footer">
            </footer>
        </div>
    </div>

    <!-- Bootstrap and jQuery scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>


    <script src="assets/js/script.js"></script>
</body>

</html>