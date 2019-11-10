<?php
if ($update['patient']) {
    return 0;
}
if ($update['message']['out'] == true) {
    $arg = explode(" ",$update['message']['message']);
    $cmd = strtolower($arg[0]);
    if ($cmd == 'whois') {
        $reply = "<b>Invaid syntax</b>\nUse: <code>whois InputPeer(username, user_id)</code>";
        if (isset($arg[1])) {
            try {
                $user = $MadelineProto->get_info($arg[1],['async' => false])["User"];
                $reply = "<i>".$user['first_name']." ".$user['last_name']." profile</i>\n";
                $reply .= "<b>ID:</b> <code>".$user['id']."</code>\n";
                if (!empty($user['username'])) {
                    $reply .= "<b>Username:</b> <code>@".$user['username']."</code>\n";
                }
                if (!empty($user['scam'])) {
                    $reply = "\nTHIS USER IS KNOWN AS SCAMMER\n";
                }
                // TODO: Send profile photo.
            } catch (Exception $e) {
                $reply = "Error";
                logger($e,5);
            }
        }
        $MadelineProto->messages->editMessage(
            [
                'peer' => $update['message']['to_id'],
                'id' => $update['message']['id'],
                'message' => $reply,
                'parse_mode' => 'HTML' 
            ]
        );
    }
}