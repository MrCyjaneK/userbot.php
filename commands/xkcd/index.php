<?php
if ($update['patient']) {
    return 0;
}
if ($update['message']['out'] == true) {
    $arg = explode(" ",$update['message']['message'],2);
    $arg[0] = strtolower($arg[0]);
    $cmd = $arg[0];
    if ($cmd == $config->prefix."xkcd") {
        if (file_exists("tmp.png")) {
            unlink("tmp.png");
        }
        if (!isset($arg[1])) {
            $MadelineProto->messages->editMessage(
                [
                    'peer' => $update['message']['to_id'],
                    'id' => $update['message']['id'],
                    'message' => "<b>Invalix syntax</b>",
                    'parse_mode' => 'HTML' 
                ]
            );
        } else {
            $id = explode(' ',file_get_contents("https://relevantxkcd.appspot.com/process?action=xkcd&query=".$arg[1]))[2];
            $id = preg_replace('/[^0-9]+/', '', $id);
            $info = json_decode(file_get_contents("https://xkcd.com/$id/info.0.json"));
            $image = $info->img;
            $title = $info->title;
            $message = "<a href=\"$image\">🏷️</a><a href=\"https://xkcd.com/$id/\">$title</a>";
            $MadelineProto->messages->editMessage(
                [
                    'peer' => $update['message']['to_id'],
                    'id' => $update['message']['id'],
                    'message' => $message,
                    'no_webpage' => false,
                    'parse_mode' => 'HTML' 
                ]
            );
        }
    }
}