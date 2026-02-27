<?php
include 'admin-sessions.php';

?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title>EP | Player Redeem</title>
    <?php include 'parts/frameworks.html'; ?>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div class="wrapper">
        <?php include 'parts/sidebar.php'; ?>
        <!-- Main content -->
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
                        <div class="card-header">
                            <div class="records table-responsive">
                                <div>
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th scope="col" style="white-space: nowrap;text-align: center;"> Acc. Number </th>
                                                <th scope="col" style="white-space: nowrap;text-align: center;"> Receiver </th>
                                                <th scope="col" style="white-space: nowrap;text-align: center;"> Amount </th>
                                                <th scope="col" style="white-space: nowrap;text-align: center;"> Status </th>
                                                <th scope="col" style="white-space: nowrap;text-align: center;"> Action </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // SQL query to fetch all users ordered by 'date_requested' (most recent first)
                                            $sql = "SELECT * FROM withdrawals ORDER BY date_requested DESC";
                                            $result = mysqli_query($con, $sql);

                                            // Check if the query returned any results
                                            if ($result && mysqli_num_rows($result) > 0) {
                                                // Loop through and display all users ordered by 'date_requested'
                                                while ($data = mysqli_fetch_assoc($result)) {
                                                    // Determine if the button should be enabled or disabled
                                                    $isButtonDisabled = in_array($data['status'], ['FAILED', 'APPROVED', NUll]) ? 'disabled' : '';

                                            ?>
                                                    <tr>
                                                        <td style="white-space: nowrap;text-align: center;">
                                                            <?= $data['acc_number']; ?>
                                                        </td>

                                                        <td style="white-space: nowrap;text-align: center;">
                                                            <?= $data['receiver_name']; ?>
                                                        </td>
                                                        <td style="white-space: nowrap;text-align: center;">
                                                            <?= $data['amount']; ?>
                                                        </td>
                                                        <td style="white-space: nowrap;text-align: center;">
                                                            <?= $data['status']; ?>
                                                        </td>

                                                        <td style="white-space: nowrap;text-align: center;">
                                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                                data-bs-target="#exampleModal"
                                                                data-id="<?= $data['id']; ?>"
                                                                data-acc_number="<?= $data['acc_number']; ?>"
                                                                data-amount="<?= $data['amount']; ?>"
                                                                data-gcash_number="<?= $data['gcash_number']; ?>"
                                                                data-receiver_name="<?= $data['receiver_name']; ?>"
                                                                data-status="<?= $data['status']; ?>"
                                                                data-date_requested="<?= $data['date_requested']; ?>"
                                                                <?= $isButtonDisabled; ?>>
                                                                UPDATE
                                                            </button>
                                                        </td>
                                                    </tr>
                                            <?php
                                                }
                                            } else {
                                                // If no users found, display a message
                                                echo "<tr><td colspan='6' style='text-align: center;'>No users found</td></tr>";
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
                <!-- Ads Code -->
            </footer>
        </div>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="margin: 20px auto; max-width: 500px; width: 90%;">
            <div class="modal-content" style="border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                <form id="updateForm" action="admin-back-end.php" method="POST" onsubmit="return validateForm()">
                    <div class="modal-header" style="background-color: #007bff; color: white; border-top-left-radius: 0.5rem; border-top-right-radius: 0.5rem;">
                        <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">Cash-Out Updater</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="padding: 20px;">
                        <div class="mb-3">
                            <label for="email" class="form-label text-primary" style="font-weight: bold;">DB. ID</label>
                            <input type="text" class="form-control" id="id" name="id" readonly style="background-color: #f8f9fa;">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label text-primary" style="font-weight: bold;">Acc. Number</label>
                            <input type="text" class="form-control" id="acc_number" name="acc_number" readonly style="background-color: #f8f9fa;">
                        </div>
                        <div class="mb-3">
                            <label for="number" class="form-label text-primary" style="font-weight: bold;">Gcash Number</label>
                            <input type="text" class="form-control" id="gcash_number" name="gcash_number" readonly style="background-color: #f8f9fa;">
                        </div>
                        <div class="mb-3">
                            <label for="number" class="form-label text-primary" style="font-weight: bold;">Receiver Name</label>
                            <input type="text" class="form-control" id="receiver_name" name="receiver_name" readonly style="background-color: #f8f9fa;">
                        </div>
                        <div class="mb-3">
                            <label for="number" class="form-label text-primary" style="font-weight: bold;">Amount</label>
                            <input type="text" class="form-control" id="amount" name="amount" readonly style="background-color: #f8f9fa;">
                        </div>
                        <div class="mb-3">
                            <label for="request_status" class="form-label text-danger" style="font-weight: bold;">Action</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="">Select Option</option>
                                <option value="APPROVED">APPROVED</option>
                                <option value="FAILED">FAILED</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer" style="padding: 10px;">
                        <button type="submit" name="send" class="btn btn-primary" style="width: 100%; padding: 10px; font-size: 16px; border-radius: 5px;">Send</button>
                    </div>
                </form>
                <script>
                    // Show/hide reference number field based on status selection
                    document.getElementById('request_status').addEventListener('change', function() {
                        const refContainer = document.getElementById('refNumberContainer');
                        if (this.value === 'Approved') {
                            refContainer.style.display = 'block';
                            document.getElementById('cashoutRef').setAttribute('required', 'required');
                        } else {
                            refContainer.style.display = 'none';
                            document.getElementById('cashoutRef').removeAttribute('required');
                        }
                    });

                    // Form validation
                    function validateForm() {
                        const status = document.getElementById('request_status').value;
                        const refNumber = document.getElementById('cashoutRef').value;

                        if (status === 'Approved' && !/^\d{13}$/.test(refNumber)) {
                            alert('Please enter a valid 13-digit reference number for approved requests.');
                            return false;
                        }
                        return true;
                    }
                </script>
            </div>
        </div>
    </div>

    <!-- Bootstrap and jQuery scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Custom JavaScript -->
    <script src="assets/js/script.js"></script>
    <script>
        $('#exampleModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var acc_number = button.data('acc_number');
            var amount = button.data('amount');
            var gcash_number = button.data('gcash_number');
            var receiver_name = button.data('receiver_name');
            var status = button.data('status');
            var date_requested = button.data('date_requested');

            // Set values in the modal
            $('#acc_number').val(acc_number);
            $('#id').val(id);
            $('#amount').val(amount);
            $('#gcash_number').val(gcash_number);
            $('#receiver_name').val(receiver_name);
            $('#status').val(status);
            $('#date_requested').val(date_requested);
        });
    </script>
</body>

</html>