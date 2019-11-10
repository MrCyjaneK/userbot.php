<?php
foreach (glob("functions/*.php") as $filename) {
    include $filename;
}
$config = json_decode(file_get_contents('config.json'));
if ($config->logging->deleteLogOnStart) {
    unlink("MadelineProto.log");
    unlink("debug.txt");
    logger("Deleted log files",4);
}
logger("Loading config file");
logger($config,5);
if (!file_exists('madeline.php')) {
    logger("madeline.php is not downloaded, downloading");
    copy('https://phar.madelineproto.xyz/madeline.php', 'madeline.php');
    logger("Downloaded madeline.php");
}
logger('Starting MadelineProto');
include 'madeline.php';
logger('Logging in');
$settings['logger']['logger'] = \danog\MadelineProto\Logger::FILE_LOGGER;
$MadelineProto = new \danog\MadelineProto\API('userbot.php.session');
$MadelineProto->settings = $settings;
$MadelineProto->async(true);
$MadelineProto->start();
$global = [
    "count" => 0
];