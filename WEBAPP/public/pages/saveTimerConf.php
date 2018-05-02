<?php

require_once('./../php/RecupConf.php');
require_once('./../php/Database.php');

// Get all info from conf file
$conf = new RecupConf();

$name = $_GET['name'];
$timerId = $_GET['timerId'];
$userId = $_GET['userId'];
$cycleNumber = $_GET['cycleNumber'];

// Database class (connection and requests)
$database = new Database($conf->getDbName(), $conf->getDbLogin(), $conf->getDbPasswd());
print $cycleNumber;

if($cycleNumber == 0){

	$database->setValue("usertimer", "timerNamePerso", utf8_encode($name), "timerId", $timerId, "userId", $userId);
	$database->setValue("usertimer", "cycle1", "0", "timerId", $timerId, "userId", $userId);
	$database->setValue("usertimer", "cycle2", "0", "timerId", $timerId, "userId", $userId);
	$database->setValue("usertimer", "cycle3", "0", "timerId", $timerId, "userId", $userId);

}
else if($cycleNumber == 1){

	$iOn1 = $_GET['iOn1'];
	$iOff1 = $_GET['iOff1'];
	$iFreq1 = $_GET['iFreq1'];

	$database->setValue("usertimer", "timerNamePerso", utf8_encode($name), "timerId", $timerId, "userId", $userId);

	if($database->select("usertimer", "cycle1", "timerId", $timerId, "userId", $userId)[0] == "0"){
		$database->sqlRequestInsert("INSERT INTO cycle(cycleStart,cycleEnd,freq,userId,timerId,cycleNumber) VALUES('".$iOn1."','".$iOff1."','".$iFreq1."','".$userId."','".$timerId."',1)");
//		$database->insertInto("cycle", $iOn1, $iOff1, $iFreq1, $userId, $timerId, "1");
		$cycle1Id = $database->select("cycle", "cycleId", "timerId", $timerId, "userId", $userId, "cycleNumber", "1", "cycleStart", $iOn1, "cycleEnd", $iOff1, "freq", $iFreq1)[0];
		$database->setValue("usertimer", "cycle1", $cycle1Id, "timerId", $timerId, "userId", $userId);

	}
	else{

		$database->setValue("cycle", "cycleStart", $iOn1, "cycleId", $database->select("usertimer", "cycle1", "timerId", $timerId, "userId", $userId)[0]);
		$database->setValue("cycle", "cycleEnd", $iOff1, "cycleId", $database->select("usertimer", "cycle1", "timerId", $timerId, "userId", $userId)[0]);
		$database->setValue("cycle", "freq", $iFreq1, "cycleId", $database->select("usertimer", "cycle1", "timerId", $timerId, "userId", $userId)[0]);

	}

	$database->setValue("usertimer", "cycle2", "0", "timerId", $timerId, "userId", $userId);
	$database->setValue("usertimer", "cycle3", "0", "timerId", $timerId, "userId", $userId);

}
else if($cycleNumber == 2){

	$iOn1 = $_GET['iOn1'];
	$iOff1 = $_GET['iOff1'];
	$iFreq1 = $_GET['iFreq1'];

	$iOn2 = $_GET['iOn2'];
	$iOff2 = $_GET['iOff2'];
	$iFreq2 = $_GET['iFreq2'];

	$database->setValue("usertimer", "timerNamePerso", utf8_encode($name), "timerId", $timerId, "userId", $userId);

	if($database->select("usertimer", "cycle1", "timerId", $timerId, "userId", $userId)[0] == "0"){

		$database->sqlRequestInsert("INSERT INTO cycle(cycleStart,cycleEnd,freq,userId,timerId,cycleNumber) VALUES('".$iOn1."','".$iOff1."','".$iFreq1."','".$userId."','".$timerId."',1)");
//		$database->insertInto("cycle", "", $iOn1, $iOff1, $iFreq1, $userId, $timerId, "1");
		$cycle1Id = $database->select("cycle", "cycleId", "timerId", $timerId, "userId", $userId, "cycleNumber", "1", "cycleStart", $iOn1, "cycleEnd", $iOff1, "freq", $iFreq1)[0];
		$database->setValue("usertimer", "cycle1", $cycle1Id, "timerId", $timerId, "userId", $userId);

	}
	else{

		$database->setValue("cycle", "cycleStart", $iOn1, "cycleId", $database->select("usertimer", "cycle1", "timerId", $timerId, "userId", $userId)[0]);
		$database->setValue("cycle", "cycleEnd", $iOff1, "cycleId", $database->select("usertimer", "cycle1", "timerId", $timerId, "userId", $userId)[0]);
		$database->setValue("cycle", "freq", $iFreq1, "cycleId", $database->select("usertimer", "cycle1", "timerId", $timerId, "userId", $userId)[0]);

	}

	if($database->select("usertimer", "cycle2", "timerId", $timerId, "userId", $userId)[0] == "0"){

		$database->sqlRequestInsert("INSERT INTO cycle(cycleStart,cycleEnd,freq,userId,timerId,cycleNumber) VALUES('".$iOn2."','".$iOff2."','".$iFreq2."','".$userId."','".$timerId."',2)");
//		$database->insertInto("cycle", "", $iOn2, $iOff2, $iFreq2, $userId, $timerId, "2");
		$cycle2Id = $database->select("cycle", "cycleId", "timerId", $timerId, "userId", $userId, "cycleNumber", "2", "cycleStart", $iOn2, "cycleEnd", $iOff2, "freq", $iFreq2)[0];
		$database->setValue("usertimer", "cycle2", $cycle2Id, "timerId", $timerId, "userId", $userId);

	}
	else{

		$database->setValue("cycle", "cycleStart", $iOn2, "cycleId", $database->select("usertimer", "cycle2", "timerId", $timerId, "userId", $userId)[0]);
		$database->setValue("cycle", "cycleEnd", $iOff2, "cycleId", $database->select("usertimer", "cycle2", "timerId", $timerId, "userId", $userId)[0]);
		$database->setValue("cycle", "freq", $iFreq2, "cycleId", $database->select("usertimer", "cycle2", "timerId", $timerId, "userId", $userId)[0]);

	}

	$database->setValue("usertimer", "cycle3", "0", "timerId", $timerId, "userId", $userId);

}
else if($cycleNumber == 3){

	$iOn1 = $_GET['iOn1'];
	$iOff1 = $_GET['iOff1'];
	$iFreq1 = $_GET['iFreq1'];

	$iOn2 = $_GET['iOn2'];
	$iOff2 = $_GET['iOff2'];
	$iFreq2 = $_GET['iFreq2'];

	$iOn3 = $_GET['iOn3'];
	$iOff3 = $_GET['iOff3'];
	$iFreq3 = $_GET['iFreq3'];

	$database->setValue("usertimer", "timerNamePerso", utf8_encode($name), "timerId", $timerId, "userId", $userId);

	if($database->select("usertimer", "cycle1", "timerId", $timerId, "userId", $userId)[0] == "0"){
		$database->sqlRequestInsert("INSERT INTO cycle(cycleStart,cycleEnd,freq,userId,timerId,cycleNumber) VALUES('".$iOn1."','".$iOff1."','".$iFreq1."','".$userId."','".$timerId."',1)");
//		$database->insertInto("cycle", "", $iOn1, $iOff1, $iFreq1, $userId, $timerId, "1");
		$cycle1Id = $database->select("cycle", "cycleId", "timerId", $timerId, "userId", $userId, "cycleNumber", "1", "cycleStart", $iOn1, "cycleEnd", $iOff1, "freq", $iFreq1)[0];
		$database->setValue("usertimer", "cycle1", $cycle1Id, "timerId", $timerId, "userId", $userId);

	}
	else{

		$database->setValue("cycle", "cycleStart", $iOn1, "cycleId", $database->select("usertimer", "cycle1", "timerId", $timerId, "userId", $userId)[0]);
		$database->setValue("cycle", "cycleEnd", $iOff1, "cycleId", $database->select("usertimer", "cycle1", "timerId", $timerId, "userId", $userId)[0]);
		$database->setValue("cycle", "freq", $iFreq1, "cycleId", $database->select("usertimer", "cycle1", "timerId", $timerId, "userId", $userId)[0]);

	}

	if($database->select("usertimer", "cycle2", "timerId", $timerId, "userId", $userId)[0] == "0"){
		$database->sqlRequestInsert("INSERT INTO cycle(cycleStart,cycleEnd,freq,userId,timerId,cycleNumber) VALUES('".$iOn2."','".$iOff2."','".$iFreq2."','".$userId."','".$timerId."',2)");
//		$database->insertInto("cycle", "", $iOn2, $iOff2, $iFreq2, $userId, $timerId, "2");
		$cycle2Id = $database->select("cycle", "cycleId", "timerId", $timerId, "userId", $userId, "cycleNumber", "2", "cycleStart", $iOn2, "cycleEnd", $iOff2, "freq", $iFreq2)[0];
		$database->setValue("usertimer", "cycle2", $cycle2Id, "timerId", $timerId, "userId", $userId);

	}
	else{

		$database->setValue("cycle", "cycleStart", $iOn2, "cycleId", $database->select("usertimer", "cycle2", "timerId", $timerId, "userId", $userId)[0]);
		$database->setValue("cycle", "cycleEnd", $iOff2, "cycleId", $database->select("usertimer", "cycle2", "timerId", $timerId, "userId", $userId)[0]);
		$database->setValue("cycle", "freq", $iFreq2, "cycleId", $database->select("usertimer", "cycle2", "timerId", $timerId, "userId", $userId)[0]);

	}

	if($database->select("usertimer", "cycle3", "timerId", $timerId, "userId", $userId)[0] == "0"){
		$database->sqlRequestInsert("INSERT INTO cycle(cycleStart,cycleEnd,freq,userId,timerId,cycleNumber) VALUES('".$iOn3."','".$iOff3."','".$iFreq3."','".$userId."','".$timerId."',3)");
//		$database->insertInto("cycle", "", $iOn3, $iOff3, $iFreq3, $userId, $timerId, "3");
		$cycle3Id = $database->select("cycle", "cycleId", "timerId", $timerId, "userId", $userId, "cycleNumber", "3", "cycleStart", $iOn3, "cycleEnd", $iOff3, "freq", $iFreq3)[0];
		$database->setValue("usertimer", "cycle3", $cycle3Id, "timerId", $timerId, "userId", $userId);

	}
	else{

		$database->setValue("cycle", "cycleStart", $iOn3, "cycleId", $database->select("usertimer", "cycle3", "timerId", $timerId, "userId", $userId)[0]);
		$database->setValue("cycle", "cycleEnd", $iOff3, "cycleId", $database->select("usertimer", "cycle3", "timerId", $timerId, "userId", $userId)[0]);
		$database->setValue("cycle", "freq", $iFreq3, "cycleId", $database->select("usertimer", "cycle3", "timerId", $timerId, "userId", $userId)[0]);

	}


}

//Post to IBM timer configuration after uploading it in database
$deviceId = $_GET['deviceId'];

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,"selfedenapp.eu-gb.mybluemix.net/OrdreTimerChange");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "deviceId=".$deviceId);

// in real life you should use something like:
// curl_setopt($ch, CURLOPT_POSTFIELDS, 
//          http_build_query(array('postvar1' => 'value1')));

// receive server response ...
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec ($ch);

curl_close ($ch);

?>
