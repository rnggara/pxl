<?php

function get_config(){
    $path = '../app/Config/.env';

    if (file_exists($path)){
        $env = explode("\n",file_get_contents($path, true));

        for($i = 0; $i < count($env); $i++){
            if (strpos($env[$i], "DB_CONFIG") !== false){
                $config = explode("=", $env[$i]);
            }
        }
        return end($config);
    } else {
        return -1;
    }
}
