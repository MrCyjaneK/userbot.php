<?php
if ($update['patient']) {
    return 0;
}
if ($update['message']['out'] == true) {
    $arg = explode(" ",$update['message']['message'],2);
    $arg[0] = strtolower($arg[0]);
    $cmd = $arg[0];
    if ($cmd == "qrgen") {
        include './commands/qr_gen/phpqrcode.php';
        if (file_exists("tmp.png")) {
            unlink("tmp.png");
        }
        logger(QRcode::png($arg[1],"tmp.png"),5);
        $MadelineProto->messages->sendMedia([
            'peer' => $update['message']['to_id'],
            'media' => [
                '_' => 'inputMediaUploadedPhoto',
                'file' => "tmp.png"
            ],
            'message' => '<b>QRcode:</b> '.$arg[1],
            'parse_mode' => 'HTML'
        ]);
    }
}