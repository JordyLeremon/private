<?php
//
// A very simple PHP example that sends a HTTP POST to a remote site
//

require_once('./../php/RecupConf.php');
require_once('./../php/Database.php');

// Get all info from conf file
$conf = new RecupConf();

$deviceId = $_GET['deviceId'];

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,"selfedenapp.eu-gb.mybluemix.net/OrdreSensorChange");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,
            "deviceId=".$deviceId);
// in real life you should use something like:
// curl_setopt($ch, CURLOPT_POSTFIELDS, 
//          http_build_query(array('postvar1' => 'value1')));

// receive server response ...
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec ($ch);

curl_close ($ch);

// further processing ....
//if ($server_output == "OK") { ... } else { ... }

?>
