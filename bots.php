<?php

function isBotOrCrawler()
{
    if (!isset($_SERVER['HTTP_USER_AGENT'])) {
        return false;
    }

    $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
    $botsAndCrawlers = [
        'googlebot',
        'googlebot-image',
        'googlebot-video',
        'googlebot-news',
        'adsbot-google',
        'googlebot-mobile',
        'googlebot-ads',
        'mediapartners-google',
        'bingbot',
        'slurp',
        'duckduckbot',
        'baiduspider',
        'yandexbot'
    ];

    foreach ($botsAndCrawlers as $botOrCrawler) {
        if (strpos($userAgent, $botOrCrawler) !== false) {
            return ucfirst($botOrCrawler);
        }
    }
    return false;
}

function logAccess($name, $type)
{
    $logFile = 'googlebot_access.log';
    $logMessage = date('D, j M Y h:i A', time()) . " - $type: $name accessed the site.\n" .
        "Request URL: " . htmlspecialchars($_SERVER['REQUEST_URI']) . "\n" .
        "Referrer: " . htmlspecialchars($_SERVER['HTTP_REFERER'] ?? 'Direct Access') . "\n" .
        "User Agent: " . htmlspecialchars($_SERVER['HTTP_USER_AGENT']) . "\n" .
        "IP Address: " . htmlspecialchars($_SERVER['REMOTE_ADDR']) . "\n" .
        str_repeat("-", 50) . "\n";

    if (file_put_contents($logFile, $logMessage, FILE_APPEND) === false) {
        error_log("Failed to log $type access.");
    }

    try {
        require "Mail/phpmailer/PHPMailerAutoload.php";
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Username = 'thisdomain24@gmail.com';
        $mail->Password = 'rhtq qcaj mdqp sdkv';
        $mail->setFrom('thisdomain24@gmail.com', 'EarningSphere');
        $mail->addAddress('njverzosa24@gmail.com');

        $mail->isHTML(true);
        $mail->Subject = "$name has accessed your site";
        $mail->Body = "
            <!DOCTYPE html>
            <html lang=\"en\">
            <head>
                <meta charset=\"UTF-8\">
                <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
                <title>Googlebot Access Log</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; }
                    .container { max-width: 600px; margin: 20px auto; padding: 20px; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
                    p { color: #333; line-height: 1.6; margin-bottom: 15px; font-size: 16px; }
                    .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                    .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    .table th { background-color: #f2f2f2; font-weight: bold; }
                    .footer { margin-top: 20px; text-align: center; color: #666; font-size: 14px; }
                </style>
            </head>
            <body>
                <div class=\"container\">
                    <p>A $type got " . htmlspecialchars($_SERVER['HTTP_REFERER'] ?? 'Direct Access') . " to your " . htmlspecialchars($_SERVER['REQUEST_URI']) . " page with the following details:</p>
                    <table class=\"table\">
                        <tr>
                            <th>Access Type</th>
                            <td>$type</td>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <td>$name</td>
                        </tr>
                        <tr>
                            <th>Request URL</th>
                            <td>" . htmlspecialchars($_SERVER['REQUEST_URI']) . "</td>
                        </tr>
                        <tr>
                            <th>Referrer</th>
                            <td>" . htmlspecialchars($_SERVER['HTTP_REFERER'] ?? 'Direct Access') . "</td>
                        </tr>
                        <tr>
                            <th>User Agent</th>
                            <td>" . htmlspecialchars($_SERVER['HTTP_USER_AGENT']) . "</td>
                        </tr>
                        <tr>
                            <th>IP Address</th>
                            <td>" . htmlspecialchars($_SERVER['REMOTE_ADDR']) . "</td>
                        </tr>
                    </table>
                </div>
                <div class=\"footer\">
                    <p>This email was sent by EarningSphere.</p>
                </div>
            </body>
            </html>
        ";

        if (!$mail->send()) {
            throw new Exception('Mail could not be sent. ' . $mail->ErrorInfo);
        }
    } catch (Exception $e) {
        error_log("Failed to send email: " . $e->getMessage());
    }
}


$name = isBotOrCrawler();
if ($name) {
    $type = (strpos($name, 'adsbot-google') !== false) ? 'AdsBot' : 'Crawler';
}
