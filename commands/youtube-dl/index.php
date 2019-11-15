<?php
if ($update['patient']) {
    return 0;
}
if ($update['message']['out'] == true) {
    $arg = explode(" ",$update['message']['message'],2);
    $arg[0] = strtolower($arg[0]);
    $cmd = $arg[0];
    if ($cmd == $config->prefix."youtube-dl") {
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
            $url = str_replace("`","",$url); //
            $url = str_replace("$","",$url); //  Because I know that some of are going
            $url = str_replace("(","",$url); // to download $(poweroff)
            $url = str_replace(")","",$url); //
            $url = str_replace("'","",$url); //
            $url = str_replace('"',"",$url); //
            $tmp = explode("/",$url);
            $filename = $tmp[count($tmp)-1];
            $MadelineProto->messages->editMessage(
                [
                    'peer' => $update['message']['to_id'],
                    'id' => $update['message']['id'],
                    'message' => "<b>Downloading...</b>\n<b>File name:</b> <i>Loading...</i>",
                    'no_webpage' => false,
                    'parse_mode' => 'HTML' 
                ],
                ["async" => false]
            );
            

            $name = substr(preg_replace('/[^0-9 A-Za-z]/', '', explode("\n",shell_exec('youtube-dl "'.$url.'" --get-title'))[0]),0,64);
            $name = str_replace(" ", "_", $name);
            $MadelineProto->messages->editMessage(
                [
                    'peer' => $update['message']['to_id'],
                    'id' => $update['message']['id'],
                    'message' => "<b>Downloading...</b>\n<b>File name:</b> <code>$name</code>",
                    'no_webpage' => false,
                    'parse_mode' => 'HTML' 
                ],
                ["async" => false]
            );
            shell_exec('youtube-dl "'.$url.'" -o "./commands/youtube-dl/tmp/toconv'.$name.'.%(ext)s"');
            shell_exec('ffmpeg -i ./commands/youtube-dl/tmp/toconv* "./commands/youtube-dl/tmp/'.$name.'.mp4"');
            shell_exec('rm ./commands/youtube-dl/tmp/toconv*');
            // Fetch file name to upload;
            $filename = explode("\n",shell_exec("echo ./commands/youtube-dl/tmp/*"))[0];
            //logger($filename);
            //copy($url,"commands/youtube-dl/tmp/".$filename);
            $MadelineProto->messages->editMessage(
                [
                    'peer' => $update['message']['to_id'],
                    'id' => $update['message']['id'],
                    'message' => "<b>Uploading...</b>",
                    'no_webpage' => false,
                    'parse_mode' => 'HTML' 
                ],
                ["async" => false]
            );
            $MadelineProto->messages->sendMedia([
                'peer' => $update['message']['to_id'],
                'media' => [
                    '_' => 'inputMediaUploadedDocument',
                    'file' => $filename,
                    'attributes' => [
                        [
                            '_' => 'documentAttributeVideo',
                            'supports_streaming' => true
                        ]
                    ]
                ],
                'message' => "Filename: <code>$name</code>\nSource: <code>$url</code>",
                'parse_mode' => 'HTML'
            ], ["async" => false]);
            shell_exec('rm commands/youtube-dl/tmp/*');
        }
    }
}