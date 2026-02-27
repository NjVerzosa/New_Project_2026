<?php
include 'admin-sessions.php';
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EP | Payments</title>
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
                    <div class="mb-3">
                        <h4>PAID PLAYERS</h4>
                    </div>
                    <div class="card border-0">
                        <div class="card-header bg-primary text-white">
                            <div class="row align-items-center g-2"> <!-- Added g-2 for gutter spacing -->
                                <!-- Search Section - Left Side -->
                                <div class="col-8 col-md-6 col-lg-4">
                                    <form action="" method="POST" class="d-flex">
                                        <input type="text" name="search" class="form-control form-control-sm"
                                            placeholder="Search by reference number"
                                            style="border-radius: 0.25rem 0 0 0.25rem;">
                                        <button type="submit" name="go" class="btn btn-secondary btn-sm"
                                            style="border-radius: 0 0.25rem 0.25rem 0;">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </form>
                                </div>
                                <!-- Add Button - Right Side -->
                                <div class="col-4 col-md-6 col-lg-4 ms-auto text-end">
                                    <button type="button" class="btn btn-success btn-sm"
                                        data-bs-toggle="modal" data-bs-target="#addPaymentModal">
                                        ADD
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Add Payment Modal -->
                        <div class="modal fade" id="addPaymentModal" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="addPaymentModalLabel">NEW RECORD</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="new_record_of_payment.php" method="POST" enctype="multipart/form-data">
                                        <div class="modal-body">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="ref_number" class="form-label">Reference Number</label>
                                                    <input type="text" class="form-control" id="" name="ref_number" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="gcash_name" class="form-label">Transfer From</label>
                                                    <input type="text" class="form-control" id="" name="transfer_from" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="gcash_name" class="form-label">Transfer To</label>
                                                    <input type="text" class="form-control" id="" name="transfer_to" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" name="submit" class="btn btn-primary">Save Payment</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>


                        <div class="records table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th style="white-space: nowrap;text-align: center;">Acc. Number</th>
                                        <th style="white-space: nowrap;text-align: center;">Email</th>
                                        <th style="white-space: nowrap;text-align: center;">Date & Time</th>
                                        <th style="white-space: nowrap;text-align: center;">Ref. #</th>
                                        <th style="white-space: nowrap;text-align: center;">Tranfer From</th>
                                        <th style="white-space: nowrap;text-align: center;">Tranfer To</th>
                                        <th style="white-space: nowrap;text-align: center;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $result = null;
                                    if (isset($_POST['go']) && !empty(trim($_POST['search']))) {
                                        $search = trim($_POST['search']);
                                        $searchTerm = "%$search%";

                                        // Search payments table by ref_number only
                                        $sql = "SELECT * FROM payments WHERE ref_number LIKE ?";

                                        $stmt = $con->prepare($sql);
                                        if ($stmt) {
                                            $stmt->bind_param("s", $searchTerm);
                                            $stmt->execute();
                                            $result = $stmt->get_result();
                                        } else {
                                            echo "<tr><td colspan='9' class='text-center'>Error preparing search query</td></tr>";
                                        }
                                    } else {
                                        // Default query - get all payments
                                        $sql = "SELECT * FROM payments ORDER BY date_time DESC";
                                        $result = mysqli_query($con, $sql);
                                    }

                                    if ($result && mysqli_num_rows($result) > 0) {
                                        while ($data = mysqli_fetch_assoc($result)) {
                                            // Remove "@gmail.com" from the email
                                            $email = str_replace('@gmail.com', '', $data['email']);
                                    ?>
                                            <tr>
                                                <td style="white-space: nowrap;text-align: center;">
                                                    <?= htmlspecialchars($data['acc_number']); ?>
                                                </td>
                                                <td style="white-space: nowrap; text-align:center;"><?= htmlspecialchars($email); ?></td>

                                                <td style="white-space: nowrap;text-align: center;">
                                                    <?= htmlspecialchars($data['date_time']); ?>
                                                </td>
                                                <td style="white-space: nowrap;text-align: center;">
                                                    <?= htmlspecialchars($data['ref_number']); ?>
                                                </td>
                                                <td style="white-space: nowrap;text-align: center;">
                                                    <?= htmlspecialchars($data['transfer_from']); ?>
                                                </td>
                                                <td style="white-space: nowrap;text-align: center;">
                                                    <?= htmlspecialchars($data['transfer_to']); ?>
                                                </td>

                                                <td style="white-space: nowrap;text-align: center;">
                                                    <span class="badge <?= $data['status'] == 'Pending' ? 'bg-danger' : 'bg-primary' ?>">
                                                        <?= htmlspecialchars($data['status']); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan='9' class='text-center'>No records found</td></tr>";
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