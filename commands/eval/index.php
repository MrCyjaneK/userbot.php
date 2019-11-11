<?php
if ($update['patient']) {
    return 0;
}
if ($update['message']['out'] == true) {
    $arg = explode(" ",$update['message']['message'],2);
    $arg[0] = strtolower($arg[0]);
    $cmd = $arg[0];
    if ($cmd == $config->prefix."eval") {
        $message = "<i>Evaluating...</i>";
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
        $file = "./commands/eval/tmp/".rand(0,99999).".php";
        file_put_contents($file,$exec);
        $result = "";
        ob_start();
        try {
            include $file;
        } catch (Exception $e) {
            $result .= substr(print_r($e,1),0,2048);
        }
        $result = ob_get_contents();
        ob_end_clean();
        unlink($file);
        $time_end = microtime(true);
        $execution_time = round(($time_end - $time_start),4);
        $message = "<b>Code: </b> <code>".$exec."</code>\n".
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