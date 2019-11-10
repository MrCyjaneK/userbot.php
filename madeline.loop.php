<?php
$update['patient'] = false;
if (isset($update['message']['date'])) {
    //logger("Update time: ".$update['message']['date'],5);
    //logger("Server time: ".time());
    //logger("Difference : ".round(time()-$update['message']['date']));
    if (round(time()-$update['message']['date']) >= 30) {
        logger("Ignored very old message",5);
        return;
    }
} else {
    $update['patient'] = true;
}

global $global;
$global['count']++;
logger($global['count'],5);
logger($update,5);

$plugins = scandir("./commands");
unset($plugins[0]); // .
unset($plugins[1]); // ..
foreach ($plugins as $plugin) {
    $pluginconfig = json_decode(file_get_contents("./commands/$plugin/config.json"));
    if ($pluginconfig->active == false) {
        continue;
    }
    $type = $update['_'];
    if (!empty($pluginconfig->updates->$type)) {
        logger("Loading plugin '$plugin',",5);
        include "./commands/$plugin/".$pluginconfig->entrypoint;
    }
}