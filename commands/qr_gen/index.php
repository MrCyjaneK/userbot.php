<?php
if ($update['patient']) {
    return 0;
}
if ($update['message']['out'] == true) {
    $arg = explode(" ",$update['message']['message'],2);
    $arg[0] = strtolower($arg[0]);
    $cmd = $arg[0];
    if ($cmd == "qrgen") {
        try {
            if (!defined('QR_MODE_NUL')) {
                include './commands/qr_gen/phpqrcode.php';
            }
        } catch (Exception $e) {

        }
        if (file_exists("tmp.png")) {
            unlink("tmp.png");
        }
        if (!isset($arg[1])) {
            $MadelineProto->messages->editMessage(
                [
                    'peer' => $update['message']['to_id'],
                    'id' => $update['message']['id'],
                    'message' => "<b>Invalid syntax</b>",
                    'no_webpage' => false,
                    'parse_mode' => 'HTML' 
                ]
            );
        } else {
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
}