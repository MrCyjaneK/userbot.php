<?php
if ($update['patient']) {
    return 0;
}
if ($update['message']['out'] == true) {
    if ($update['message']['message'] == "Hello, World!") {
        $MadelineProto->messages->sendMessage(['peer' => $update['message']['to_id'], 'message' => " > Hello, World!"]);
    }
}