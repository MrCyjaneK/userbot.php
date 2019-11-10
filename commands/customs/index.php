<?php
if ($update['patient']) {
    return 0;
}
if ($update['message']['out'] == true) {
    $arg = explode(" ",$update['message']['message'],4);
    $arg[0] = strtolower($arg[0]);
    $cmd = $arg[0];
    if ($cmd == ".custom") {
        $reply = "<b>Invalid syntax!</b>\n".
                 '<code>'.json_decode(file_get_contents("./commands/$plugin/config.json"))->syntax.'<code>';
        if (isset($arg[1])) {
            $arg[1] = strtolower($arg[1]);
            if ($arg[1] == "list") {
                $commands = scandir("./commands/customs/data");
                unset($commands[0]); // .
                unset($commands[1]); // ..
                $reply = "<b>Following custom commands are available</b>\n\n";
                foreach ($commands as $cmd) {
                    $reply .= "<code>$cmd</code>";
                }
            }
            if (isset($arg[2])) {
                $arg[2] = strtolower($arg[2]);
                if ($arg[1] == 'add' && isset($arg[3])) {
                    file_put_contents('./commands/customs/data/'.$arg[2],$arg[3]);
                    $reply = "<b>Custom command</b> <code>".$arg[2]."</code> <b>saved!</b>";
                }
                if ($arg[1] == 'remove') {
                    if (!file_exists('./commands/customs/data/'.$arg[2])) {
                        $reply = "<b>Custom command doesn't exist</b>";
                    } else {
                        unlink('./commands/customs/data/'.$arg[2]);
                        $reply = "<b>Removed</b>";
                    }
                }
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
    if (substr($update['message']['message'],0,1) == "#") {
        $commands = scandir("./commands/customs/data");
        unset($commands[0]); // .
        unset($commands[1]); // ..
        if (in_array(substr($update['message']['message'],1),$commands)) {
            $MadelineProto->messages->sendMessage(
                [
                    'peer' => $update['message']['to_id'], 
                    'message' => file_get_contents("./commands/customs/data/".substr($update['message']['message'],1)),
                    'parse_mode' => "HTML"
                ]);
        } else {
            $MadelineProto->messages->editMessage(
                [
                    'peer' => $update['message']['to_id'],
                    'id' => $update['message']['id'],
                    'message' => "<b>Command doesn't exist</b>",
                    'parse_mode' => 'HTML' 
                ]
            );
        }
    }
}