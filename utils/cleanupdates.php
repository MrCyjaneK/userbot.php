<?php
include 'madeline.php';
$MadelineProto = new \danog\MadelineProto\API('../userbot.php.session');
$MadelineProto->async(true);
$MadelineProto->start();
$MadelineProto->setNoop();
$MadelineProto->loop();