<?php
if ($update['patient']) {
    return 0;
}
if ($update['message']['out'] == true) {
    if (strtolower($update['message']['message']) == ".about") {
        $message = "😎<b>userbot.php</b> - flexible PHP userbot\n".
                   "🏷️<b>Version:</b> ".file_get_contents('version.txt')."\n\n".
                   "Created by <a href=\"http://mrcyjanek.net\">Cyjan</a>\n".
                   'You can get a free copy on <a href="https://github.com/MrCyjaneK/userbot.php">Microsoft Github</a>'."\n".
                   "Questions? <a href=\"http://t.me/UserbotDotPHP\">@UserbotDotPHP</a>";
        $MadelineProto->messages->editMessage(
            [
                'peer' => $update['message']['to_id'],
                'id' => $update['message']['id'],
                'message' => $message,
                'no_webpage' => true,
                'parse_mode' => 'HTML' 
            ]
        );
    }
}