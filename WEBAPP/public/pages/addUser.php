<?php

require_once('./../php/RecupConf.php');
require_once('./../php/Database.php');

// Get all info from conf file
$conf = new RecupConf();

$email = $_GET['email'];
$passwd = $_GET['passwd'];


// Database class (connection and requests)
$database = new Database($conf->getDbName(), $conf->getDbLogin(), $conf->getDbPasswd());
$pass=hash("sha256", $passwd);
$database->sqlRequestInsert("INSERT INTO user(email,mdp,active) VALUES('".$email."','".$pass."',0)");

//$database->insertInto("user", "", $email, hash("sha256", $passwd), "0");
?>
