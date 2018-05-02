<?php

require_once('./../php/RecupConf.php');
require_once('./../php/Database.php');

// Get all info from conf file
$conf = new RecupConf();

$email = $_GET['email'];

// Database class (connection and requests)
$database = new Database($conf->getDbName(), $conf->getDbLogin(), $conf->getDbPasswd());

if($database->select("user", "active", "email", $email)[0] == 1){

	print '1';

}
else{

	print '0';

}

?>
