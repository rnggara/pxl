<?php
include('adodb5/adodb.inc.php');

$path = app_path().'/Config/.env';

$env = explode("\n",file_get_contents($path, true));

for($i = 0; $i < count($env); $i++){
    if (strpos($env[$i], "DB_USERNAME") !== false){
        $username = explode("=", $env[$i]);
    } elseif (strpos($env[$i], "DB_PASSWORD") !== false){
        $password = explode("=", $env[$i]);
    } elseif (strpos($env[$i], "DB_DATABASE") !== false){
        $dbname = explode("=", $env[$i]);
    }
}

$server = "localhost";
$user   = end($username);
$password = end($password);
$database = end($dbname);
$driver = "mysqli";

$db = adoNewConnection($driver); # eg. 'mysqli' or 'oci8'

$db->connect($server, $user, $password, $database);
//$rs = $db->execute('select * from some_small_table');
//print "<pre>";
//print_r($rs->getRows());
//print "</pre>";
