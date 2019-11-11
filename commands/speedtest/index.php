<?php
if ($update['patient']) {
    return 0;
}
if ($update['message']['out'] == true) {
    $arg = explode(" ",$update['message']['message']);
    $arg[0] = strtolower($arg[0]);
    $cmd = $arg[0];
    if ($cmd == $config->prefix."speedtest") {
        $message = "<i>Testing...</i>";
        $MadelineProto->messages->editMessage(
            [
                'peer' => $update['message']['to_id'],
                'id' => $update['message']['id'],
                'message' => $message,
                'parse_mode' => 'HTML' 
            ]
        );
        try {
            if (file_exists('testfile.zip')) {
                unlink('testfile.zip');
            }
            $filename = 'http://212.183.159.230/5MB.zip';
            if (isset($arg[1])) {
                $filename = $arg[1];
            }
            $start = microtime(true);
            copy($filename,'testfile.zip');
            $time = round((microtime(true) - $start),4);
            $kb = round(filesize('testfile.zip') / 1024);
            $speed = round($kb / $time);
            if (file_exists('testfile.zip')) {
                unlink('testfile.zip');
            }
            $message = "<b>Speedtest</b>\n".
                    "<b>File:</b> <code>$filename</code> (<b>Size:</b> <code>".round($kb/1024,2)."</code> MB)\n".
                    "<b>Time:</b> <code>$time</code> sec\n".
                    "<b>Speed:</b> <code>".round($speed/1024,4)." MB/s</code>";
        } catch (Exception $e) {
            logger($e,3);
            $message = "<b>An error occured</b>";
            if (isset($arg[1])) {
                $message .= "<i>Is link valid?</i>";
            }
        }
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