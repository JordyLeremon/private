<?php

require_once('./../php/RecupConf.php');
require_once('./../php/Database.php');

// Get all info from conf file
$conf = new RecupConf();

$value1 = $_GET['value1'];
$value2= $_GET['value2'];
$valueOffset = $_GET['valueOffset'];
$userId = $_GET['userId'];

// Database class (connection and requests)
$database = new Database($conf->getDbName(), $conf->getDbLogin(), $conf->getDbPasswd());

$request1 = ($database->sqlRequest("SELECT typeId FROM type, sensortype, sensor, usersensor WHERE type.id = sensortype.typeId AND sensortype.sensorId = sensor.id AND sensor.id = usersensor.sensorId AND usersensor.userID = ".$userId , "typeId")); 

$request2 = ($database->sqlRequest("SELECT value FROM type, sensortype, sensor, usersensor WHERE type.id = sensortype.typeId AND sensortype.sensorId = sensor.id AND sensor.id = usersensor.sensorId AND usersensor.userID = ".$userId , "value")); 

//$database->insertInto("history", "", $request1[$valueOffset], $request2[$valueOffset], $value1, $value2, "CURRENT_TIMESTAMP");
$database->sqlRequest("INSERT INTO historysensor VALUES ('', '".$request1[$valueOffset]."', '".$request2[$valueOffset]."', '".$value1."', '".$value2."', CURRENT_TIMESTAMP)", "");

?>
