<?php
if ($update['patient']) {
    return 0;
}
if ($update['message']['out'] == true) {
    $arg = explode(" ",$update['message']['message'],2);
    $arg[0] = strtolower($arg[0]);
    $cmd = $arg[0];
    if ($cmd == $config->prefix."exec") {
        $message = "<i>Executing...</i>";
        $MadelineProto->messages->editMessage(
            [
                'peer' => $update['message']['to_id'],
                'id' => $update['message']['id'],
                'message' => $message,
                'parse_mode' => 'HTML' 
            ]
        );
        $time_start = microtime(true);
        $exec = $arg[1];
        $result = htmlspecialchars(shell_exec($exec." 2>&1 | sed -r 's/'$(echo -e "\033")'\[[0-9]{1,2}(;([0-9]{1,2})?)?[mK]//g'"));
        $time_end = microtime(true);
        $execution_time = round(($time_end - $time_start),4);
        $message = "<b>Command: </b> <code>".$exec."</code>\n".
                   "<b>Result:</b>\n".
                   "<code>$result</code>\n".
                   "<b>Path:</b> <code>".explode(PHP_EOL,shell_exec('pwd'))[0]."</code>\n".
                   "<b>User:</b> <code>".explode(PHP_EOL,shell_exec('whoami'))[0]."</code>\n".
                   "<b>Time:</b> <code>$execution_time</code> sec";
        $MadelineProto->messages->editMessage(
            [
                'peer' => $update['message']['to_id'],
                'id' => $update['message']['id'],
                'message' => $message,
                'parse_mode' => 'HTML' 
            ]
        );
    }
}
