<?php
include 'admin-sessions.php';

?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title></title>
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

                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <div class="row align-items-center g-2"> <!-- Added g-2 for gutter spacing -->
                                <!-- Search Section - Left Side -->
                                <div class="col-8 col-md-6 col-lg-4">
                                    <form action="" method="POST" class="d-flex">
                                        <input type="text" name="search" class="form-control form-control-sm"
                                            placeholder="Search by total"
                                            style="border-radius: 0.25rem 0 0 0.25rem;">
                                        <button type="submit" name="go" class="btn btn-secondary btn-sm"
                                            style="border-radius: 0 0.25rem 0.25rem 0;">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php
                        $result = null;
                        if (isset($_POST['go']) && !empty($_POST['search'])) {
                            $search = $_POST['search'];

                            $sql = "SELECT * FROM tasks WHERE email LIKE ? OR (score >= ? AND ? REGEXP '^[0-9]+$')";

                            $stmt = $con->prepare($sql);
                            $searchTerm = "%$search%";

                            $stmt->bind_param("ssi", $searchTerm, $search, $search);
                            $stmt->execute();
                            $result = $stmt->get_result();
                        } else {
                            $sql = "SELECT * FROM tasks ORDER BY id DESC";
                            $result = mysqli_query($con, $sql);
                        }

                        ?>

                        <div class="card border-0">
                            <div class="records table-responsive">
                                <div>
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th style="white-space: nowrap;text-align: center;">Acc. Number</th>
                                                <th style="white-space: nowrap;text-align: center;">Email</th>
                                                <th style="white-space: nowrap;text-align: center;">Date</th>
                                                <th style="white-space: nowrap;text-align: center;">Total</th>
                                                <th style="white-space: nowrap;text-align: center;">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ($result && mysqli_num_rows($result) > 0) {
                                                while ($data = mysqli_fetch_assoc($result)) {
                                                    // Remove "@gmail.com" from the email
                                                    $email = str_replace('@gmail.com', '', $data['email']);

                                                    // Convert the date to a readable format
                                                    $date = strtotime($data['date']);
                                                    $formattedDate = date("M. d, Y", $date);

                                                    if (date("Y-m-d", $date) == date("Y-m-d")) {
                                                        $formattedDate = "Today";
                                                    } elseif (date("Y-m-d", $date) == date("Y-m-d", strtotime("-1 day"))) {
                                                        $formattedDate = "Yesterday";
                                                    }
                                            ?>
                                                    <tr>
                                                        <td style="white-space: nowrap;text-align: center;"></b> <?= $data['acc_number']; ?></td>
                                                        <td style="white-space: nowrap;text-align: center;">
                                                            <?= $email; ?>
                                                        </td>
                                                        <td style="white-space: nowrap;text-align: center;"><?= $formattedDate; ?></td>
                                                        <td style="white-space: nowrap;text-align: center;"><?= $data['score']; ?></td>
                                                        <td style="white-space: nowrap;text-align: center;"><?= $data['status']; ?></td>
                                                    </tr>
                                            <?php
                                                }
                                            } else {
                                                echo "<tr><td colspan='6' style='text-align:center;'>No records found</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <a href="#" class="theme-toggle"></a>
            <footer class="footer">
            </footer>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="margin: 20px auto; max-width: 500px; width: 90%;">
            <div class="modal-content">

                <form id="updateForm" action="admin-back-end.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Account Banned</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="" class="form-label">Email</label>
                            <input type="text" class="form-control" id="email" value="<?= $data['email']; ?>" name="email" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Date Registered</label>
                            <input type="text" class="form-control" id="registeredAt" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="" class="form-label text-danger">Account Status</label>
                            <select class="form-select" id="account" name="account" required>
                                <option value="account" id="account">Select Option</option>
                                <option style="color:black;" value="1">Banned</option>
                                <option style="color:black;" value="0">Unbanned</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="delete" class="btn btn-outline-danger">
                            <i class="fas fa-trash" id="passwordIcon"></i>
                        </button>
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
            var registeredAt = button.data('registered-at');
            var features_strict = button.data('features_strict');
            var account = button.data('account');

            // Set values in the modal
            $('#userId').val(userId);
            $('#email').val(email);
            $('#registeredAt').val(registeredAt);
            $('#features_strict').val(features_strict);
            $('#account').val(account);
        });
    </script>
</body>

</html>