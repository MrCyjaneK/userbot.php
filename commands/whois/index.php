<?php
if ($update['patient']) {
    return 0;
}
if ($update['message']['out'] == true) {
    $arg = explode(" ",$update['message']['message']);
    $cmd = strtolower($arg[0]);
    if ($cmd == $config->prefix.'whois') {
        $reply = "<b>Invaid syntax</b>\nUse: <code>.whois InputPeer(username, user_id)</code>";
        if (isset($arg[1])) {
            try {
                $info = $MadelineProto->get_full_info($arg[1],['async' => false]);
                $name = "";
                logger($info,5);
                if (empty($info["full"]["user"]["last_name"])) {
                    $lastname = "Not available.";
                } else {
                    $lastname = $info["full"]["user"]["last_name"];
                }
                if(!empty($info["full"]["about"])){
                    $about = $info["full"]["about"];
                } else {
                    $about = "Not available";
                }
                if($info["full"]["user"]["bot"] == true){
                    $bott = "✅";
                    $status = "Bot";
                } else {
                    if(empty($info["full"]["user"]["status"]["was_online"])) {
                        if($info["full"]["user"]["status"]["_"] == "userStatusRecently") {
                            $status = "Recently saw";
                        } elseif ($info["full"]["user"]["status"]["_"] == "userStatusOnline") { 
                            $status = "Online.";}
                        } else {
                            $status = ''.date('d.m.Y H:i:s',$info["full"]["user"]["status"]["was_online"]).'';
                        }
                    $bott = "❌";
                }
                if(!empty($info["full"]["user"]["username"])){
                    $usernam = '@'.$info["full"]["user"]["username"].'';
                } else {
                    $usernam = "Not available";
                }
                $reply = "<b>ID:</b> <code>".$info["full"]["user"]["id"]."</code>\n".
                "First Name: <code>".$info["full"]["user"]["first_name"]."</code>\n".
                "Last Name: <code>".$lastname."</code>\n".
                "Username: ".$usernam."\n".
                "Status: <code>".$status."</code>\n".
                "Bio: <code>".$about."</code>\n".
                "Bot: ".$bott."";
                // TODO: add profile photo
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