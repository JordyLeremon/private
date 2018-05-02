
	<?php
		session_start();
		require_once('../php/Database.php');
		require_once('../php/RecupConf.php');
		$conf = new RecupConf();
		$database = new Database($conf->getDbName(), $conf->getDbLogin(), $conf->getDbPasswd());
		
		if(!isset($_SESSION['email']) || empty($_SESSION['email']))
			header('Location: login.php');
		if(empty($_GET['id'])){
		   echo "<script>window.close();</script>";
		}

//		$user_id = $database->select("user", "id", "email", $_SESSION['email'])[0];
		$sensorId = $_GET['Sid'];
		$deviceId = $_GET['Did'];
		$typeEtalon = $_GET['etalon'];
//		$timer_serial = $database->sqlRequest("SELECT serialNumberTimer FROM timer WHERE timer.id = ".$timer_id , "serialNumberTimer");
//		$timer_name = $database->sqlRequest("SELECT type FROM type, timertype WHERE type.id = timertype.typeId AND timertype.timerId = ".$timer_id , "type");
//		$timers_name_perso = $database->sqlRequest("SELECT timerNamePerso FROM usertimer WHERE usertimer.timerId = ".$timer_id." AND usertimer.userId = ".$user_id, "timerNamePerso");

	?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Configuration du timer</title>

    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">
	<link href="../dist/css/selfeden.css" rel="stylesheet">
	<link href="../dist/css/slider.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="../vendor/morrisjs/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
	<script src="../vendor/jquery/jquery.min.js"></script>
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
</head>
<body>
	<h2 style="text-align: center">Mettre la sonde dans la solution puis clicker sur ETALONNER</h2>
	<br>
	<form>
		<div class="row">
			<div class="col-xs-offset-4 col-xs-4 col-sm-offset-5 col-sm-3 col-md-offset-5 col-md-2">
				<button type="button" id="exitButton" onclick="etalonage()">ETALONNER</button>
			</div>
		</div>
	</form>
</body>

<script>
	var delayMillis = 1000; //1 second
	function etalonage(){
		$.ajax({
                                type: "POST",
                                url: "http://selfedenapp.eu-gb.mybluemix.net/etalonageIBM",
                                data: {deviceId : <?php echo $deviceId ?>, sensorId: <?php echo $sensorId ?>, etalonId: <?php echo $typeEtalon ?>}
                });
		setTimeout(function() {
			parent.closeAllIFrames(1);
		}, delayMillis);
	}



</script>
