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
                        <div class="card-header bg-primary text-white text-center">
                            <div class="row align-items-center">
                                <div class="col-md-6 col-lg-4 mb-1 mb-md-0 d-flex justify-content-start">
                                    <form action="" method="POST" class="d-flex align-items-center">
                                        <input type="text" name="search" class="form-control me-2"
                                            placeholder="Search by (any data)"
                                            style="border-radius: 0.5rem 0 0 0.5rem; border: 1px solid #007bff;" required>
                                        <button type="submit" name="go" class="btn btn-secondary">
                                            Search
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <?php
                        $result = null;
                        if (isset($_POST['go']) && !empty($_POST['search'])) {
                            $search = mysqli_real_escape_string($con, $_POST['search']);
                            $sql = "SELECT * FROM users WHERE my_invitation_code LIKE '%$search%' OR email LIKE '%$search%' OR username LIKE '%$search%' ";
                            $result = mysqli_query($con, $sql);
                        } else {
                            $sql = "SELECT * FROM users ORDER BY registered_at DESC";
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
                                                <th style="white-space: nowrap;text-align: center;">Referral</th>
                                                <th style="white-space: nowrap;text-align: center;">Balance</th>
                                                <th style="white-space: nowrap;text-align: center;">Bad Request</th>
                                                <th style="white-space: nowrap;text-align: center;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ($result->num_rows > 0) {
                                                while ($data = $result->fetch_assoc()) {
                                                    // Check if the account is 1
                                                    $emailStyle = $data['account'] == 1 ? 'background-color: red; color: white; border-radius: 5px; padding: 5px; box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.1);' : '';
                                                    // Remove "@gmail.com" from the email
                                                    $email = str_replace('@gmail.com', '', $data['email']);
                                            ?>
                                                    <tr>
                                                        <td style="white-space: nowrap;text-align: center;">
                                                            <?= htmlspecialchars($data['acc_number'], ENT_QUOTES, 'UTF-8'); ?>
                                                        </td>
                                                        <td style="white-space: nowrap;text-align: center; <?= $emailStyle; ?>">
                                                            <?= $email; ?>
                                                        </td>
                                                        <td style="white-space: nowrap;text-align: center;">
                                                            <?= htmlspecialchars($data['my_referral'], ENT_QUOTES, 'UTF-8'); ?>
                                                        </td>
                                                        <td style="white-space: nowrap;text-align: center;">
                                                            <?= htmlspecialchars($data['earned_coins'], ENT_QUOTES, 'UTF-8'); ?>
                                                        </td>
                                                        <td class="text-center" style="padding: 5px;margin-top:5px; color: red;">
                                                            <?php
                                                            switch ($data['unrecognized_device']) {
                                                                case 1:
                                                                    echo 'Cleared Cache';
                                                                    break;
                                                                case 2:
                                                                    echo 'Access Denied';
                                                                    break;
                                                                case 3:
                                                                    echo 'Access Denied';
                                                                    break;
                                                                case 4:
                                                                    echo "Needs Attention";
                                                                    break;
                                                                case 5:
                                                                    echo 'Needs Attention';
                                                                    break;
                                                                case 0:
                                                                    echo 'No Problem';
                                                                    break;
                                                                default:
                                                                    if ($data['unrecognized_device'] > 5) {
                                                                        echo 'Exceed Limit';
                                                                    }
                                                                    break;
                                                            }
                                                            ?>
                                                        </td>



                                                        <td style="white-space: nowrap;text-align: center;">
                                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                                data-bs-target="#exampleModal"
                                                                data-id="<?= $data['id']; ?>"
                                                                data-email="<?= $data['email']; ?>"
                                                                data-acc_number="<?= $data['acc_number']; ?>"
                                                                data-registered_at="<?= $data['registered_at']; ?>"
                                                                data-account="<?= $data['account']; ?>"
                                                                data-unrecognized_device="<?= $data['unrecognized_device']; ?>">
                                                                UP
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <tr class="user-details-row" id="user-details-<?= htmlspecialchars($data['referral_code'], ENT_QUOTES, 'UTF-8'); ?>" style="display: none;">
                                                        <td colspan="4" id="user-list-<?= htmlspecialchars($data['referral_code'], ENT_QUOTES, 'UTF-8'); ?>"></td>
                                                    </tr>
                                            <?php
                                                }
                                            } else {
                                                echo "<tr><td colspan='7'>No records found</td></tr>";
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
                            <label for="" class="form-label">Acc. Number</label>
                            <input type="text" class="form-control" id="acc_number" name="acc_number" value="<?= $data['acc_number']; ?>" name="email" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Email</label>
                            <input type="text" class="form-control" id="email" value="<?= $data['email']; ?>" name="email" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Date Registered</label>
                            <input type="text" class="form-control" id="registered_at" readonly>
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
            var acc_number = button.data('acc_number');
            var email = button.data('email');
            var registered_at = button.data('registered_at');
            var unrecognized_device = button.data('unrecognized_device');
            var account = button.data('account');

            // Set values in the modal
            $('#userId').val(userId);
            $('#acc_number').val(acc_number);
            $('#email').val(email);
            $('#registered_at').val(registered_at);
            $('#unrecognized_device').val(unrecognized_device);
            $('#account').val(account);
        });
    </script>
</body>

</html>