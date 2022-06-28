<?php

$path = app_path().'Config/.env';

$env = explode("\n",file_get_contents($path, true));

for($i = 0; $i < count($env); $i++){
    if (strpos($env[$i], "DB_CONFIG") !== false){
        $config = explode("=", $env[$i]);
    }
}

if (end($config) == 0){
    URL::route('install');
} else {
    URL::route('login');
}

function get_config(){
    end($config);
}

?>
