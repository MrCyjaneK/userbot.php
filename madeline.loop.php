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
if (!isset($update['message']['message'])) {
    $update['patient'] = true;
}
global $global;
global $_PLUGINCONFIG;
$global['count']++;
//logger($global['count'],5);

logger($update,5);
// See ./functions/config.php
//$plugins = scandir("./commands");
//unset($plugins[0]); // .
//unset($plugins[1]); // ..
foreach ($_PLUGINCONFIG as $plugin => $pluginconfig) {
    try {
        //$pluginconfig = json_decode(file_get_contents("./commands/$plugin/config.json"));
        if ($pluginconfig->active == false) {
            continue;
        }
        $type = $update['_'];
        if (!empty($pluginconfig->updates->$type)) {
            //logger("Loading plugin '$plugin',",5);
            //Disabled because it take too much resources
            include "./commands/$plugin/".$pluginconfig->entrypoint;
        }
    } catch (Exception $e) {
        logger($e,4);
    }
}