<?php
if ($update['patient']) {
    return 0;
}
$start_time = $update['message']['date'];
if ($update['message']['out'] == true) {
    if ($update['message']['message'] == ".ping") {
        $ping = "<i>Pong...</i>";
        logger($update['message']['to_id']);
        $MadelineProto->messages->editMessage(
            [
                'peer' => $update['message']['to_id'],
                'id' => $update['message']['id'],
                'message' => $ping,
                'parse_mode' => 'HTML' 
            ],
            [ 'async' => false ]
        );
        $end_time = microtime(true); 
        $execution_time = ($end_time - $start_time)*1000;
        logger('$start_time    : '.$start_time,5);
        logger('$end_time      : '.$end_time,5);
        logger('$execution_time: '.$execution_time,5);
        $ping = "<b>Pong!</b>\nReply took: <code>".round($execution_time,2)."</code>ms";
        $MadelineProto->messages->editMessage(
            [
                'peer' => $update['message']['to_id'],
                'id' => $update['message']['id'],
                'message' => $ping,
                'parse_mode' => 'HTML' 
            ]
        );
    }
}