<?php

require_once('./../php/RecupConf.php');
require_once('./../php/Database.php');

// Get all info from conf file
$conf = new RecupConf();

$email = $_GET['email'];
$passwd = $_GET['passwd'];

// Database class (connection and requests)
$database = new Database($conf->getDbName(), $conf->getDbLogin(), $conf->getDbPasswd());

if($database->select("user", "mdp", "email", $email)[0] == hash("sha256", $passwd)){

	echo '1';

}
else{

	echo '0';

}

?>
