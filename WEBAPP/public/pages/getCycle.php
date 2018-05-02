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

$select = $_GET['select'];
$user_id = $_GET['userId'];
$timer_id = $_GET['timerId'];

if($select == 1){
	$accountCycle = $database->select("usertimer", "cycle1", "userId", $user_id, "timerId", $timer_id)[0];
}
else if($select == 2){
	$accountCycle = $database->select("usertimer", "cycle2", "userId", $user_id, "timerId", $timer_id)[0];
}
else{
	$accountCycle = $database->select("usertimer", "cycle3", "userId", $user_id, "timerId", $timer_id)[0];
}

$result = $accountCycle;

echo $result;

?>