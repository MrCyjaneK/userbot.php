<?php
if ($update['patient']) {
    return 0;
}
if ($update['message']['out'] == true) {
    $arg = explode(" ",$update['message']['message'],2);
    $arg[0] = strtolower($arg[0]);
    $cmd = $arg[0];
    if ($cmd == $config->prefix."upload") {
        if (!isset($arg[1])) {
            $MadelineProto->messages->editMessage(
                [
                    'peer' => $update['message']['to_id'],
                    'id' => $update['message']['id'],
                    'message' => "<b>Idk what to do, Invalix syntax</b>",
                    'no_webpage' => false,
                    'parse_mode' => 'HTML' 
                ]
            );
        } else {
            $url = $arg[1];
            $tmp = explode("/",$url);
            $filename = $tmp[count($tmp)-1];
            $MadelineProto->messages->editMessage(
                [
                    'peer' => $update['message']['to_id'],
                    'id' => $update['message']['id'],
                    'message' => "<b>Downloading...</b>",
                    'no_webpage' => false,
                    'parse_mode' => 'HTML' 
                ]
            );
            copy($url,"commands/upload/tmp/".$filename);
            $MadelineProto->messages->editMessage(
                [
                    'peer' => $update['message']['to_id'],
                    'id' => $update['message']['id'],
                    'message' => "<b>Uploading...</b>",
                    'no_webpage' => false,
                    'parse_mode' => 'HTML' 
                ]
            );
            $MadelineProto->messages->sendMedia([
                'peer' => $update['message']['to_id'],
                'media' => [
                    '_' => 'inputMediaUploadedDocument',
                    'file' => "commands/upload/tmp/".$filename
                ],
                'message' => "Filename: <code>$filename</code>\nSource: <code>$url</code>",
                'parse_mode' => 'HTML'
            ], ["async" => false]);
            unlink("commands/upload/tmp/".$filename);
        }
    }
}