<?php
if (!file_exists('madeline.php')) {
    copy('https://phar.madelineproto.xyz/madeline.php', 'madeline.php');
}
include 'madeline.php';
$MadelineProto = new \danog\MadelineProto\API('../userbot.php.session');
$MadelineProto->start();
echo PHP_EOL."Logged in, to start type: php index.php";