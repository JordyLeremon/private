<?php

require_once('./../php/RecupConf.php');
require_once('./../php/Database.php');

// Get all info from conf file
$conf = new RecupConf();

$value = $_GET['value'];
$timerId = $_GET['timerId'];

// Database class (connection and requests)
$database = new Database($conf->getDbName(), $conf->getDbLogin(), $conf->getDbPasswd());

$database->setValue("timertype", "value", $value, "timerId", $timerId);

?>