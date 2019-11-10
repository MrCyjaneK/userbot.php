<?php
if ($update['patient']) {
    return 0;
}
if ($update['message']['out'] == true) {
    $arg = explode(" ",$update['message']['message'],2);
    $arg[0] = strtolower($arg[0]);
    $cmd = $arg[0];
    if ($cmd == ".exec") {
        $message = "<i>Executing...</i>";
        $MadelineProto->messages->editMessage(
            [
                'peer' => $update['message']['to_id'],
                'id' => $update['message']['id'],
                'message' => $message,
                'parse_mode' => 'HTML' 
            ]
        );
        $exec = $arg[1];
        $result = htmlspecialchars(shell_exec("timeout 25 ".$exec));
        $message = "<i>Command: </i> <code>".$exec."</code>\n".
                   "<b>Result:</b>\n".
                   "<code>$result\n</code>".
                   "<b>Path:</b> <code>".explode(PHP_EOL,shell_exec('pwd'))[0]."</code>\n".
                   "<b>User:</b> <code>".explode(PHP_EOL,shell_exec('whoami'))[0]."</code>";
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