<?php
if ($update['patient']) {
    return 0;
}
if ($update['message']['out'] == true) {
    $arg = explode(" ",$update['message']['message']);
    $cmd = $arg[0];
    foreach ($arg as $key => $val) {
        $arg[$key] = strtolower($val);
    }
    if ($cmd == 'cmds') {
        $reply = "<b>Invaid syntax</b>\nUse: <code>cmds help</code>";
        if (isset($arg[1])) {
            if ($arg[1] == 'help') {
                $reply = "<b>Supported cmds:</b>\n".
                "<code>    -</code> <b>cmds help</b>\n".
                "<code>    -</code> <b>cmds list [active(true/false default: both)]</b>\n".
                "<code>    -</code> <b>cmds setactive plugin_name active(true/false)";
            }
            if ($arg[1] == 'list') {
                $active = true;
                $inactive = true;
                if (isset($arg[2])) {
                    if ($arg[2] == 'true') {
                        $active = true;
                        $inactive = false;
                    }
                    if ($arg[2] == 'false') {
                        $active = false;
                        $inactive = true;
                    }
                }
                $plugins = scandir("./commands");
                unset($plugins[0]); // .
                unset($plugins[1]); // ..
                $reply = "<b>List of all commands</b>\n";
                foreach ($plugins as $plugin) {
                    $pluginconfig = json_decode(file_get_contents("./commands/$plugin/config.json"));
                    if ($active) {
                        if ($pluginconfig->active) {
                            $reply .= "☑️ <i>$plugin</i>\n";
                        }
                    }
                    if ($inactive) {
                        if (!$pluginconfig->active) {
                            $reply .= "❎ <i>$plugin</i>\n";
                        }
                    }
                }
            }
            if ($arg[1] == 'setactive') {
                $reply = "<b>Invalid syntax</b>\nUse: cmds setactive plugin_name active(true/false)";
                if (isset($arg[2]) && isset($arg[3])) {
                    $plugin_name = $arg[2];
                    $active = $arg[3];
                    $plugins = scandir("./commands");
                    unset($plugins[0]); // .
                    unset($plugins[1]); // ..
                    if (in_array($plugin_name,$plugins)) {
                        $pluginconfig = json_decode(file_get_contents('./commands/'.$plugin_name.'/config.json'));
                        if ($active == 'true') {
                            $pluginconfig->active = true;
                        } else {
                            $pluginconfig->active = false;
                        }
                        file_put_contents('./commands/'.$plugin_name.'/config.json',json_encode($pluginconfig,JSON_PRETTY_PRINT));
                        $reply = "Plugin: $plugin_name is now ";
                        if ($pluginconfig->active) {
                            $reply .= "enabled";
                        } else {
                            $reply .= "disabled";
                        }
                    } else {
                        $reply = "Plugin not found";
                    }
                }
            }
        }
        $MadelineProto->messages->editMessage(
            [
                'peer' => $update['message']['to_id'],
                'id' => $update['message']['id'],
                'message' => $reply,
                'parse_mode' => 'HTML' 
            ]
        );
    }
}