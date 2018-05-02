<?php

session_start();

?>

<?php

require_once('./../php/RecupConf.php');
require_once('./../php/Database.php');

// Get all info from conf file
$conf = new RecupConf();

// Database class (connection and requests)
$database = new Database($conf->getDbName(), $conf->getDbLogin(), $conf->getDbPasswd());

$cycleId = $_GET['cycleId'];

$result = $database->select("cycle", "cycleEnd", "cycleId", substr($cycleId, 1))[0];

echo $result;

?>