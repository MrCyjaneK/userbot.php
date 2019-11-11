<?php
if ($update['patient']) {
    return 0;
}
if ($update['message']['out'] == true) {
    $arg = explode(" ",$update['message']['message']);
    $arg[0] = strtolower($arg[0]);
    $cmd = $arg[0];
    if ($cmd == $config->prefix."purge") {
        if (!isset($arg[1])) {
            $MadelineProto->messages->editMessage(
                [
                    'peer' => $update['message']['to_id'],
                    'id' => $update['message']['id'],
                    'message' => "<b>Invalid syntax</b>\nExcepted at least 1 parametr, 0 given",
                    'no_webpage' => false,
                    'parse_mode' => 'HTML' 
                ]
            );
        } else {
            $reply = "<b>Unknown method</b> <code>".$arg[1]."</code> <b>given.</b>";
            if ($arg[1] == "all") {
                $reply = "<i>Purge finished!</i>";
                $start = microtime(true);
                $me = $MadelineProto->get_self([ "async" => false ]);	
                $msgid = $update['message']['id'];
                $limit = 0;
                $amt = $update['message']['id'];
                if (isset($arg[2])) {
                    $amt = round($arg[2]);
                    $limit = round($update['message']['id'] - $arg[2]);
                }
                while ($limit <= $msgid) {
                    //$messages = $MadelineProto->channels->getMessages(
                    //    [
                    //        'channel' => $update['message']['to_id'], 
                    //        'id' => [$msgid] 
                    //    ],
                    //    [
                    //        "async" => false
                    //    ]
                    //)['messages'][0]['from_id'];
                    try {
                        $MadelineProto->channels->deleteMessages(
                            [
                                'channel' => $update['message']['to_id'],
                                'id' => [$msgid]
                            ],["async" => false]
                        );
                    } catch (Exception $e) {
                        logger($e,5);
                    }
                    $msgid--;
                }
                $finish = round(microtime(true) - $start,4);
                $reply .= "<b>It took me</b> <i>$finish</i> <b>seconds</b> to delete ".$amt." messages, which is <b>".round($finish/$amt*1000,4)."ms/message";
            }
            $MadelineProto->messages->sendMessage(
                [
                    'peer' => $update['message']['to_id'],
                    'message' => $reply,
                    'parse_mode' => 'HTML' 
                ],
                [ 'async' => false ]
            );
        }
    }
}