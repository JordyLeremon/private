<?php

require_once('./../php/RecupConf.php');
require_once('./../php/Database.php');

// Get all info from conf file
$conf = new RecupConf();

$name = $_GET['name'];
$timerId = $_GET['timerId'];
$userId = $_GET['userId'];

// Database class (connection and requests)
$database = new Database($conf->getDbName(), $conf->getDbLogin(), $conf->getDbPasswd());

$database->setValue("usertimer", "timerNamePerso", $name, "timerId", $timerId, "userId", $userId);

?>
