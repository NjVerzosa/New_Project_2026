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
                                    <form action="" method="POST" class="d-flex align-items-center">
                                        <input type="text" id="searchInput" name="search" class="form-control me-2"
                                            placeholder="Search by reference number"
                                            style="border-radius: 0.5rem 0 0 0.5rem; border: 1px solid #007bff;"
                                            oninput="updatePlaceholder()" required
                                            pattern="^\d{13}$" title="Please enter a 13-digit reference number.">
                                        <button type="submit" name="go" class="btn btn-secondary">
                                            Search
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <script>
                            function updatePlaceholder() {
                                const input = document.getElementById('searchInput');
                                const basePlaceholder = "Search by reference number";
                                const userInput = input.value.trim();

                                // Check if the user input is not empty
                                if (userInput) {
                                    input.placeholder = `${userInput}@gmail.com`;
                                } else {
                                    input.placeholder = basePlaceholder; // Reset to original placeholder
                                }
                            }
                        </script>


                        <div class="records table-responsive">
                            <div>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th style="white-space: nowrap;text-align: center;"> Registered Date </th>
                                            <th style="white-space: nowrap;text-align: center;"> Email </th>
                                            <th style="white-space: nowrap;text-align: center;"> Last Login </th>
                                            <th style="white-space: nowrap;text-align: center;"> Features </th>
                                            <th style="white-space: nowrap;text-align: center;"> Reference </th>
                                            <th style="white-space: nowrap;text-align: center;"> Account </th>
                                            <th style="white-space: nowrap;text-align: center;"> Action </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $result = null;
                                        if (isset($_POST['go']) && !empty($_POST['search'])) {
                                            $search = mysqli_real_escape_string($con, $_POST['search']);
                                            $sql = "SELECT * FROM users WHERE email LIKE '%$search%' OR ref LIKE '%$search%'";
                                            $result = mysqli_query($con, $sql);
                                        } else {
                                            $sql = "SELECT * FROM users ORDER BY registered_at DESC";
                                            $result = mysqli_query($con, $sql);
                                        }
                                        if ($result && mysqli_num_rows($result) > 0) {
                                            while ($data = mysqli_fetch_assoc($result)) {
                                        ?>
                                                <tr>
                                                    <td style="white-space: nowrap;text-align: center;">
                                                        <?= $data['registered_at']; ?>
                                                    </td>
                                                    <td style="white-space: nowrap;text-align: center;">
                                                        <?= $data['email']; ?>
                                                    </td>
                                                    <td style="white-space: nowrap;text-align: center;">
                                                        <?= $data['last_login_date']; ?>
                                                    </td>


                                                    <!-- Verified column -->
                                                    <td style="white-space: nowrap;text-align: center;">
                                                        <?php if ($data['features'] == "1") { ?>
                                                            <h5> ✔️ </h5>
                                                        <?php } elseif ($data['features'] == "0") { ?>
                                                            <h5> ❌ </h5>
                                                        <?php } ?>
                                                    </td>
                                                    <td style="white-space: nowrap;text-align: center;">
                                                        <?= $data['ref']; ?>
                                                    </td>
                                                    <td style="white-space: nowrap;text-align: center;">
                                                        <?= $data['account']; ?>
                                                    </td>
                                                    <td style="white-space: nowrap;text-align: center;">
                                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                            data-bs-target="#exampleModal" data-id="<?= $data['id']; ?>"
                                                            data-email="<?= $data['email']; ?>"
                                                            data-registered-at="<?= $data['registered_at']; ?>"
                                                            data-ref="<?= $data['ref']; ?>"
                                                            data-account="<?= $data['account']; ?>"
                                                            data-purchase_at="<?= $data['purchase_at']; ?>">
                                                            UPDATE
                                                        </button>
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
                        <h5 class="modal-title" id="exampleModalLabel">Account Updater</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="" class="form-label">Email</label>
                            <input type="text" class="form-control" id="email" value="<?= $data['email']; ?>"
                                name="email" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Date Registered</label>
                            <input type="text" class="form-control" id="registeredAt" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Date Purchase</label>
                            <input type="text" class="form-control" id="purchase_at" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="" class="form-label text-primary">Reference No.</label>
                            <input type="text" class="form-control" id="ref" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="" class="form-label text-danger">Account Status</label>
                            <select class="form-select" id="account" name="account" required>
                                <option value="">Select Option</option>
                                <option style="color:black;" value="" title="Column is empty">NULL</option>
                                <option style="color:black;" value="Paid" title="Premium Account">Paid</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="update" class="btn btn-primary">UPDATE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap and jQuery scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>


    <script src="assets/js/script.js"></script>
    <script>
        $('#exampleModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var userId = button.data('id');
            var email = button.data('email');
            var role = button.data('role');
            var registeredAt = button.data('registered-at');
            var purchase_at = button.data('purchase_at');
            var ref = button.data('ref');

            // Set values in the modal
            $('#userId').val(userId);
            $('#email').val(email);
            $('#role').val(role);
            $('#registeredAt').val(registeredAt);
            $('#purchase_at').val(purchase_at);
            $('#ref').val(ref);
        });
    </script>
</body>

</html>