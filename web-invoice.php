<?php
include 'user-sessions.php';
?>
<link rel="stylesheet" href="assets/invoice.css">

<?php
// Retrieve OTP from query parameters or session
$ref_number = isset($_GET['payment_ref']) ? htmlspecialchars($_GET['payment_ref']) : '';

// Fetch transaction data (same as before)
$transactionData = null;
if (isset($row['payment_ref'])) {
    $sql = "SELECT * FROM payments WHERE ref_number = ?";
    $stmt = mysqli_prepare($con, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $row['payment_ref']);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $transactionData = mysqli_fetch_assoc($result);
            // Sanitize all output data
            $ref = htmlspecialchars($transactionData['ref_number'] ?? '', ENT_QUOTES, 'UTF-8');
            $amount = htmlspecialchars($transactionData['amount'] ?? '0', ENT_QUOTES, 'UTF-8');
            $date = htmlspecialchars($transactionData['date'] ?? '', ENT_QUOTES, 'UTF-8');
            $time = htmlspecialchars($transactionData['time'] ?? '', ENT_QUOTES, 'UTF-8');
            $transfer_from = htmlspecialchars($transactionData['transfer_from'] ?? '', ENT_QUOTES, 'UTF-8');
            $tranfer_to = htmlspecialchars($transactionData['transfer_to'] ?? '', ENT_QUOTES, 'UTF-8');
            $player_id = htmlspecialchars($transactionData['player_id'] ?? '', ENT_QUOTES, 'UTF-8');
            $status = isset($transactionData) ? 'Completed' : 'Pending';
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<table class="body-wrap">
    <tbody>
        <tr>
            <td></td>
            <td class="container" width="600">
                <div class="content">
                    <table class="main" width="100%" cellpadding="0" cellspacing="0">
                        <tbody>
                            <tr>
                                <td class="content-wrap aligncenter" style="background-color: #f7fdfa; padding: 20px;">
                                    <table width="100%" cellpadding="0" cellspacing="0">
                                        <tbody>
                                            <tr>
                                                <td class="content-block text-center">
                                                    <img src="images/logo.png" alt="GCash" style="height: 40px; margin-bottom: 15px;">
                                                    <h2 style="color: #007944; margin-bottom: 5px;">Payment Receipt</h2>
                                                    <?php if ($transactionData): ?>
                                                        <p style="color: #5a947a; margin-top: 0;">Transaction <?= $status ?></p>
                                                    <?php else: ?>
                                                        <p style="color: #d58512; margin-top: 0;">Payment Pending</p>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="content-block">
                                                    <table class="invoice">
                                                        <tbody>
                                                            <tr>
                                                                <td style="padding-bottom: 15px;">
                                                                    <?php if ($transactionData): ?>
                                                                        <strong style="color: #007944;">Transaction ID:</strong> <?= $ref ?><br>
                                                                        <strong style="color: #007944;">Approved Time:</strong> <?= isset($date) ? date('M j, Y h:i A', strtotime("$date $time")) : 'N/A' ?><br>
                                                                    <?php else: ?>
                                                                        <p style="color: #d58512;">No transaction found. Please complete your payment.</p>
                                                                    <?php endif; ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <table class="invoice-items" cellpadding="0" cellspacing="0" width="100%">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td style="border-bottom: 1px solid #e8f5ee; padding: 8px 0;">Payment Method</td>
                                                                                <td class="alignright" style="border-bottom: 1px solid #e8f5ee; padding: 8px 0; color: #2c6e4b;">GCash</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="border-bottom: 1px solid #e8f5ee; padding: 8px 0;">GCash Number</td>
                                                                                <td class="alignright" style="border-bottom: 1px solid #e8f5ee; padding: 8px 0; color: #2c6e4b;"><?= $transfer_from ?? 'N/A' ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="border-bottom: 1px solid #e8f5ee; padding: 8px 0;">Transfer To</td>
                                                                                <td class="alignright" style="border-bottom: 1px solid #e8f5ee; padding: 8px 0; color: #2c6e4b;"><?= $tranfer_to ?? 'N/A' ?></td>
                                                                            </tr>
                                                                            <?php if ($transactionData): ?>
                                                                                <tr>
                                                                                    <td style="border-bottom: 1px solid #e8f5ee; padding: 8px 0;">Status</td>
                                                                                    <td class="alignright" style="border-bottom: 1px solid #e8f5ee; padding: 8px 0; color: #00a859;"><?= $status ?></td>
                                                                                </tr>
                                                                            <?php endif; ?>
                                                                            <tr class="total">
                                                                                <td class="alignright" width="80%" style="padding-top: 15px; font-weight: bold; color: #007944;">Total Amount</td>
                                                                                <td class="alignright" style="padding-top: 15px; font-weight: bold; color: #007944;">₱110</td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="content-block" style="text-align: center; color: #5a947a; padding-top: 20px;">
                                                    <i class="fas fa-lock" style="color: #00a859;"></i> Secured by EarningSphere
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="content-block" style="text-align: center; color: #a3c4b5; font-size: 12px; padding-top: 10px;">
                                                    Transaction generated on <?= isset($date) ? date('M j, Y h:i A', strtotime("$date $time")) : 'N/A' ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="footer">
                        <table width="100%">
                            <tbody>
                                <tr>
                                    <td class="aligncenter content-block" style="color: #5a947a; font-size: 12px; padding-top: 20px;">Questions? Email <a href="mailto:support@earningsphere.online" style="color: #007944;">support@earningsphere.online</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </td>
            <td></td>
        </tr>
    </tbody>
</table>