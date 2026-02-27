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
                        <h4>REQUEST APPROVAL</h4>
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
                            </div>
                        </div>


                        <div class="records table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th style="white-space: nowrap;text-align: center;">Acc. Number</th>
                                        <th style="white-space: nowrap;text-align: center;">Email</th>
                                        <th style="white-space: nowrap;text-align: center;">Date</th>
                                        <th style="white-space: nowrap;text-align: center;">Ref. #</th>
                                        <th style="white-space: nowrap;text-align: center;">Status</th>
                                        <th style="white-space: nowrap;text-align: center;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $result = null;
                                    if (isset($_POST['go']) && !empty(trim($_POST['search']))) {
                                        $search = trim($_POST['search']);
                                        $searchTerm = "%$search%";

                                        // Search payments table by ref_number only
                                        $sql = "SELECT * FROM users WHERE payment_ref LIKE ?";

                                        $stmt = $con->prepare($sql);
                                        if ($stmt) {
                                            $stmt->bind_param("s", $searchTerm);
                                            $stmt->execute();
                                            $result = $stmt->get_result();
                                        }
                                    } else {
                                        // Default query - get all payments
                                        $sql = "SELECT * FROM users ORDER BY payment_submitted_at DESC";
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
                                                    <?= htmlspecialchars($data['payment_submitted_at']); ?>
                                                </td>

                                                <td style="white-space: nowrap;text-align: center;">
                                                    <?= htmlspecialchars($data['payment_ref']); ?>
                                                </td>

                                                <td style="white-space: nowrap;text-align: center;">
                                                    <span class="badge <?= ($data['payment_status'] == 'PENDING' || $data['payment_status'] == 'NOT FOUND') ? 'bg-danger' : 'bg-primary' ?>">
                                                        <?= htmlspecialchars($data['payment_status']); ?>
                                                    </span>
                                                </td>
                                                <?php if ($data['payment_status'] == "PAID") { ?>
                                                    <td style="white-space: nowrap;text-align: center;">
                                                        <button type="button" class="btn btn-primary" disabled>
                                                            UPDATE
                                                        </button>
                                                    </td>
                                                <?php } else { ?>
                                                    <td style="white-space: nowrap;text-align: center;">
                                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                            data-bs-target="#exampleModal" data-acc_number="<?= $data['acc_number']; ?>"
                                                            data-email="<?= $data['email']; ?>"
                                                            data-registered-at="<?= $data['registered_at']; ?>"
                                                            data-ref="<?= $data['ref']; ?>">
                                                            UPDATE
                                                        </button>
                                                    </td>
                                                <?php } ?>

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
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="margin: 20px auto; max-width: 500px; width: 90%;">
            <div class="modal-content">

                <form id="updateForm" action="activation_approval.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">PAYMENT RESULT</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="" class="form-label">Email</label>
                            <input type="text" class="form-control" id="email" value="<?= $data['email']; ?>" name="email" readonly>
                        </div>


                        <div class="mb-3">
                            <label for="" class="form-label text-danger">Account Status</label>
                            <select class="form-select" id="payment_status" name="payment_status" required>
                                <option value="payment_status" id="payment_status">Select Option</option>
                                <option style="color:black;" value="Not Found">Not Found</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="update" id="updateButton" class="btn btn-primary">UPDATE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script src="assets/js/script.js"></script>
    <script>
        $('#exampleModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var userId = button.data('id');
            var email = button.data('email');
            var payment_status = button.data('payment_status');

            // Set values in the modal
            $('#userId').val(userId);
            $('#email').val(email);
            $('#payment_status').val(payment_status);
        });
    </script>

</body>

</html>