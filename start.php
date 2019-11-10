<?php
include "init.php";
while (1) {
    try {
        $MadelineProto->setCallback(function ($update) use ($MadelineProto, $config) {
            include "./madeline.loop.php";
        });
        $MadelineProto->async(true);
        $MadelineProto->loop();
    } catch (Exception $e) {
        logger("CRITICAL ERROR OCCURED AT CORE",2);
        logger("Please send log file to t.me/UserbotDotPhp",2);
        logger("If possible attach:",2);
        logger("    > debug.txt",2);
        logger("    > MadelineProto.log",2);
        logger("==========================================",2);
        logger($e,0);
    }
    logger("userbot will restart in 60 seconds",2);
    sleep(60);
}