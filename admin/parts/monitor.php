<?php
include 'user-sessions.php';
?>

<style>
    .container {
        margin-top: 20px;
    }

    .card {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s, box-shadow 0.3s;
        /* Rounded corners */
    }

    .card-header {
        font-weight: bold;
        background-color: #007bff;
        /* Bootstrap primary color */
        color: white;
        padding: 15px;
        /* More padding */
        border-radius: 10px 10px 0 0;
        /* Round top corners */
    }

    .table th,
    .table td {
        vertical-align: middle;
        /* Center the content in table cells */
        padding: 12px;
        /* Increased padding for better spacing */
    }

    .table th {
        background-color: #f8f9fa;
        /* Light grey for headers */
        color: #343a40;
        /* Dark text for contrast */
    }

    .text-primary {
        color: #007bff !important;
        /* Ensures primary color consistency */
    }

    /* Add a footer with buttons (optional) */
    .card-footer {
        text-align: right;
        /* Align footer contents to the right */
        padding: 15px;
        background-color: #f1f1f1;
        /* Light grey for footer */
        border-radius: 0 0 10px 10px;
        /* Round bottom corners */
    }

    /* Add a button style */
    .btn-custom {
        background-color: #28a745;
        /* Bootstrap success color */
        color: white;
        border: none;
        border-radius: 5px;
        /* Rounded button */
        padding: 10px 20px;
        /* Padding for the button */
        transition: background-color 0.3s;
        /* Transition for hover effect */
        cursor: pointer;
        /* Pointer cursor on hover */
    }

    .btn-custom:hover {
        background-color: #218838;
        /* Darker green on hover */
    }
</style>

<!-- Single Card with Table -->
<div class="container-fluid"> <!-- Full width container -->
    <div class="row">
        <div class="col-12"> <!-- Full width column -->
            <div class="card border-primary mb-3">
                <div class="card-header text-center">
                    <h4 class="m-0">Player Statistics</h4>
                </div>
                <div class="card-body text-primary">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">Statistic</th>
                                <th class="text-center">Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Registered Players</td>
                                <td class="text-center">
                                    <?php
                                    $query = "SELECT * FROM users";
                                    $query_run = mysqli_query($con, $query);
                                    $data = mysqli_num_rows($query_run);
                                    echo $data;
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Active Sessions</td>
                                <td class="text-center">
                                    <?php

                                    date_default_timezone_set('Asia/Manila');
                                    $timeNow = date('g:i A');


                                    // Query to count active sessions
                                    $query = "SELECT COUNT(*) as active_count FROM users WHERE status = 1 AND login_time >= '$timeNow'";

                                    $query_run = mysqli_query($con, $query);
                                    $row = mysqli_fetch_assoc($query_run);
                                    $activePlayers = $row['active_count'];

                                    echo $activePlayers;
                                    ?>
                                </td>
                            </tr>


                            <style>
                                .green-background {
                                    background-color: green;
                                }

                                .pulse-animation {
                                    animation: pulse 2s infinite;
                                }

                                @keyframes pulse {
                                    0% {
                                        background-color: green;
                                    }

                                    50% {
                                        background-color: #28a745;
                                        /* Slightly lighter green */
                                    }

                                    100% {
                                        background-color: green;
                                    }
                                }
                            </style>
                            <tr>
                                <td>Cost Payouts / month</td>
                                <td class="text-center">
                                    <?php
                                    $query = "SELECT SUM(amount) AS total_amount FROM withdrawals";
                                    $query_run = mysqli_query($con, $query);

                                    if ($query_run) {
                                        $row = mysqli_fetch_assoc($query_run);
                                        $totalAmount = $row['total_amount'] ? $row['total_amount'] : 0; // Handle NULL by defaulting to 0
                                        echo $totalAmount;
                                    } else {
                                        echo "0"; // Fallback if query fails
                                    }
                                    ?>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-6 d-flex justify-content-end justify-content-md-end">
                            <?php if ($date['lock_register'] == 1) { ?>
                                <form action="lock_0.php" method="POST" class="d-inline">
                                    <button type="submit" name="make_it_0" class="btn btn-success">Open R</button>
                                </form>
                            <?php } elseif ($date['lock_register'] == 0) { ?>
                                <form action="lock_1.php" method="POST" class="d-inline">
                                    <button type="submit" name="make_it_1" class="btn btn-danger">Close R</button>
                                </form>
                            <?php } ?>
                        </div>

                        <div class="col-6 d-flex justify-content-start justify-content-md-start">
                            <?php if ($date['lock_login'] == 1) { ?>
                                <form action="login_0.php" method="POST" class="d-inline">
                                    <button type="submit" name="make_it_0" class="btn btn-success">Open L</button>
                                </form>
                            <?php } elseif ($date['lock_login'] == 0) { ?>
                                <form action="login_1.php" method="POST" class="d-inline">
                                    <button type="submit" name="make_it_1" class="btn btn-danger">Close L</button>
                                </form>
                            <?php } ?>
                        </div>
                    </div>
                    <br>
                    <button class="btn btn-custom" onclick="refreshPage()">Refresh Data</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function refreshPage() {
        location.reload(); // Reloads the current page
    }
</script>