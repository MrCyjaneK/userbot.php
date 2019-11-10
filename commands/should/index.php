<?php
if ($update['patient']) {
    return 0;
}
if ($update['message']['out'] == true) {
    $arg = explode(" ",$update['message']['message']);
    foreach ($arg as $key => $val) {
        $arg[$key] = strtolower($val);
    }
    $cmd = $arg[0];
    if ($cmd == '.should') {
        if (file_exists('yn.gif')) {
            unlink('yn.gif');
        }
        $answer = json_decode(file_get_contents("https://yesno.wtf/api"));
        copy($answer->image,"yn.gif");
        $MadelineProto->messages->sendMedia([
            'peer' => $update['message']['to_id'],
            'media' => [
                '_' => 'inputMediaUploadedDocument',
                'file' => 'yn.gif',
                'attributes' => [
                    ['_' => 'documentAttributeAnimated']
                ]
            ],
            'message' => "Personally, I think that ".$answer->answer,
        ]);
    }
}