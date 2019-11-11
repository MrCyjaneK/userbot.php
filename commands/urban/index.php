<?php
if ($update['patient']) {
    return 0;
}
if ($update['message']['out'] == true) {
    $arg = explode(" ",$update['message']['message'],2);
    $arg[0] = strtolower($arg[0]);
    $cmd = $arg[0];
    if ($cmd == $config->prefix.'ud') {
        $reply = "<b>Invalid syntax!</b>";
        if (isset($arg[1])) {
            $word = htmlspecialchars($arg[1]);
            $reply = "🔍<b>$word</b> on urbandictionary\n";
            $word = urlencode($word);
            $wordumean = json_decode(file_get_contents("http://api.urbandictionary.com/v0/autocomplete?term=$word"));
            if (isset($wordumean[0])) {
                $wordumean = $wordumean[0];
                $didumean = htmlspecialchars($wordumean);
                if (isset($didumean) && (strtolower($word) != strtolower($didumean))) {
                    $reply .= "❓<i>Did you mean:</i> <code>$didumean</code>?\n";
                }
            } else {
                $reply .= "<b>Unable to find suggestion</b>\n";
            }            
            $definition = json_decode(file_get_contents("http://api.urbandictionary.com/v0/define?term=".urlencode($word)))->list;
            if (!isset($definition[0])) {
                $reply .= "<code>Unable to find definition</code>";
            } else {
                $definition = $definition[0];
                $reply .= "<b>".$definition->word."</b>\n".
                          "<b>Definition:</b> <code>".$definition->definition."</code>\n".
                          "<b>Example:</b> <code>".$definition->example."</code>\n".
                          "";
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