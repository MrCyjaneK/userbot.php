<?php
/*
    Purpose: log stuff.
*/
function logger (
    $thing = "Empty",    // Thing that is being logged
    $level = 3 // Logging level
    ) {
        global $config;
        $loglevels = [
            "Critical", // 0 - Something that shouldn't happen
            "Error",    // 1 - Something that user should see
            "Info",     // 2 - Something that user can see
            "Debug",    // 3 - Something that nerd should see   [DEFAULT]
            "Verbose",  // 4 - Something that not important
            "Crazy"     // 5 - Everything else
        ];
        $thing = print_r($thing,1);
        file_put_contents("./debug.txt", PHP_EOL."[".date("M,d,Y h:i:s A")."][".$loglevels[$level]."] ".$thing, FILE_APPEND | LOCK_EX);
        if ($level <= $config->logging->loglevel)
        echo PHP_EOL."[".$loglevels[$level]."] ".$thing;
}