<?php

require_once('./../php/RecupConf.php');
require_once('./../php/Database.php');

// Get all info from conf file
$conf = new RecupConf();

$name = $_GET['name'];
$sensorId = $_GET['sensorId'];
$userId = $_GET['userId'];
$assoc = $_GET['association'];
$associated = $_GET['associated'];
$min = $_GET['min'];
$max = $_GET['max'];
$alertInf = $_GET['alertInf'];
$alertSup = $_GET['alertSup'];
$typeId = $_GET['typeId'];
$infMin = $_GET['infMin'];
$supMin = $_GET['supMin'];
$infMax = $_GET['infMax'];
$supMax = $_GET['supMax'];

// Database class (connection and requests)
$database = new Database($conf->getDbName(), $conf->getDbLogin(), $conf->getDbPasswd());


$lastValue = $database->sqlRequest("SELECT value FROM historysensor WHERE sensorId =".$sensorId." ORDER BY date DESC","value")[0];

if ($associated == 0 && $assoc != 0)
{
	//$database->insertInto("sensortimer",$sensorId,$assoc, $infMin, $supMin, $infMax, $supMax);
	$database->sqlRequestInsert("INSERT INTO sensortimer VALUES('".$sensorId."','".$assoc."','".$infMin."','".$supMin."','".$infMax."','".$supMax."','".$min."','".$max."','".$userId."')");
}
if ($associated > 0 && $assoc != 0)
{
	$database->setValue("sensortimer", "timerId", $assoc,"sensorId",$sensorId);
	$database->setValue("sensortimer", "infMin", $infMin, "timerId",$assoc,"sensorId",$sensorId);
	$database->setValue("sensortimer", "supMin", $supMin, "timerId",$assoc,"sensorId",$sensorId);
	$database->setValue("sensortimer", "infMax", $infMax, "timerId",$assoc,"sensorId",$sensorId);
	$database->setValue("sensortimer", "supMax", $supMax, "timerId",$assoc,"sensorId",$sensorId);
	$database->setValue("sensortimer", "max", $max, "timerId",$assoc,"sensorId",$sensorId);
	$database->setValue("sensortimer", "min", $min, "timerId",$assoc,"sensorId",$sensorId);
}
if ($associated > 0 && $assoc == 0)
{
	$database->delete("sensortimer","sensorId",$sensorId);
}

$database->setValue("usersensor", "sensorNamePerso", utf8_encode($name), "sensorId", $sensorId, "userId", $userId, "typeId", $typeId);
//$database->sqlRequestInsert("INSERT INTO historysensor (`typeId`, `value`, `sensorId`) VALUES('".$typeId."','".$lastValue."','".$sensorId."')");
$database->sqlRequestInsert("update sensor set min='".$min."', max='".$max."' where id='".$sensorId."'");

$sensorAlert = $database->select("sensortype","value","sensorId",$sensorId,"typeId",$typeId);
if ($sensorAlert != "")
	$database->sqlRequestInsert("UPDATE sensortype SET infMin = '".$alertInf."', supMax = '".$alertSup."' WHERE sensorId =".$sensorId." AND typeId =".$typeId.";");
else
	$database->sqlRequestInsert("INSERT INTO sensortype VALUES('".$sensorId."','".$typeId."','".$alertInf."','".$alertSup."');");

//Post to IBM timer configuration after uploading it in database

$deviceId = $_GET['deviceId'];

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,"selfedenapp.eu-gb.mybluemix.net/OrdreSensorChange");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "deviceId=".$deviceId);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec ($ch);

curl_close ($ch);

?>
