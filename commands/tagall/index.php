<?php
if ($update['patient']) {
    return 0;
}
if ($update['message']['out'] == true) {
    $arg = explode(" ",$update['message']['message']);
    $cmd = $arg[0];
    if (strtolower($cmd) == 'tagall') {
        $pwr_chat = $MadelineProto->get_pwr_chat($update['message']['to_id'],['async' => false]);
        $reply = "";
        foreach ($pwr_chat['participants'] as $participant) {
            $reply .= "[ ](tg://user?id=".$participant['user']['id'].")";
        }
        $MadelineProto->messages->editMessage(
            [
                'peer' => $update['message']['to_id'],
                'id' => $update['message']['id'],
                'message' => $update['message']['message'].$reply,
                'parse_mode' => 'markdown' 
            ]
        );
    }
}