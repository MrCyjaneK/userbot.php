<?php
/*
    Purpose: refresh config 
*/
function refresh() {
    global $_PLUGINCONFIG;
    $plugins = scandir("./commands");
    unset($plugins[0]); // .
    unset($plugins[1]); // ..
    foreach ($plugins as $plugin) {
        logger("Loaded config for $plugin",4);
        $conf = json_decode(file_get_contents("./commands/$plugin/config.json"));
        logger($conf,5);
        $_PLUGINCONFIG[$plugin] = $conf;
    }
}