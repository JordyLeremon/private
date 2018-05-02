<!DOCTYPE html>
<html lang="en">

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
		
		if(empty($_GET['id'])){
			echo "<script>window.close();</script>";
		}
		$user_id = $database->select("user", "id", "email", $_SESSION['email'])[0];
		$timer_id = $_GET['id'];
		$timer_serial = $database->sqlRequest("SELECT serialNumberTimer FROM timer WHERE timer.id = ".$timer_id , "serialNumberTimer");
		$timer_name = $database->sqlRequest("SELECT type FROM type, timertype WHERE type.id = timertype.typeId AND timertype.timerId = ".$timer_id , "type");
		$timers_name_perso = $database->sqlRequest("SELECT timerNamePerso FROM usertimer WHERE usertimer.timerId = ".$timer_id." AND usertimer.userId = ".$user_id, "timerNamePerso");

		$utf8 = 0;
		if($timers_name_perso[0] != ""){
			$timer_name = $timers_name_perso;
			$utf8 = 1;
		}

		$assoc = 0;
	?>

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
	<h2 style="text-align: center">Configuration Timer</h2>
	<br>
	<form>
		
		<div class="row">
			<div class="col-xs-offset-3 col-xs-4 col-sm-offset-4 col-sm-2 col-md-offset-4 col-md-2">
				<p><u>N° de série :</u></p>
			</div>
			<div class="col-xs-2 col-sm-2 col-md-2">
				<?php echo "#".$timer_serial[0];?>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-offset-2 col-xs-3 col-sm-offset-3 col-sm-2 col-md-offset-4 col-md-2">
				<p><u>Nom du timer :</u></p>
			</div>
			<div class="col-xs-5 col-sm-4 col-md-2">
				<?php 

					if($utf8)
						echo '<input type="text" id="timerName" value="'.utf8_decode($timer_name[0]).'">'; 
					else
						echo '<input type="text" id="timerName" value="'.utf8_encode($timer_name[0]).'">'; 

				?>
			</div>
		</div>
		<?php
			if($assoc){
				echo '
				<div class="row">
					<div class="col-xs-offset-2 col-xs-6 col-sm-offset-3 col-sm-6 col-md-offset-4 col-md-4">
						<p>Cet actionneur est associé au capteur "sensorName"</p>
					</div>
				</div>';
			}
			else{
				echo '<div class="row">
					<div class="col-xs-offset-2 col-xs-6 col-sm-offset-3 col-sm-6 col-md-offset-4 col-md-6">
						<div style="display: inline-block;"><button type=button onclick="addCycle()">Ajouter Programme</button> <button type=button onclick="removeCycle()">Supprimer Programme</button></div>
					</div>
				</div>
				<br>';
			}
		?>
		<div id="cycle1">
		</div>
		<div id="cycle2">
		</div>
		<div id="cycle3">
		</div>
		<br>
		<div class="row">
			<div class="col-xs-offset-4 col-xs-4 col-sm-offset-5 col-sm-3 col-md-offset-5 col-md-2">
				<button type="button" id="exitButton" onclick="saveTimerConf()">Sauvegarder configuration</button>
			</div>
		</div>
	</form>
</body>

<script>

	var cycle = 0;

	function initCycle(){

		$.ajax({
			type: "GET",
			url: "getCycle.php",
			data: { select: '1', timerId: <?php echo $timer_id ?>, userId: <?php echo $user_id; ?> },
			success: function(data1){
				$.ajax({
					type: "GET",
					url: "getCycle.php",
					data: { select: '2', timerId: <?php echo $timer_id ?>, userId: <?php echo $user_id; ?> },
					success: function(data2){
						$.ajax({
							type: "GET",
							url: "getCycle.php",
							data: { select: '3', timerId: <?php echo $timer_id ?>, userId: <?php echo $user_id; ?> },
							success: function(data3){
								if(data1.substr(1) != "0"){

									var html = "";

									$.ajax({
										type: "GET",
										url: "getCycleStart.php",
										data: { cycleId: data1 },
										success: function(dataOn1){
											iOn1 = dataOn1.substr(1);

											$.ajax({
												type: "GET",
												url: "getCycleEnd.php",
												data: { cycleId: data1 },
												success: function(dataOff1){
													iOff1 = dataOff1.substr(1);

													$.ajax({
														type: "GET",
														url: "getCycleFreq.php",
														data: { cycleId: data1 },
														success: function(dataFreq1){
															iFreq1 = dataFreq1.substr(1);
															html = '<div class="row"><div class="col-xs-offset-2 col-xs-3 col-sm-offset-3 col-sm-2 col-md-offset-4 col-md-2"><p>Cycle 1 : </p></div>';
															html += '<div class="col-xs-5 col-sm-4 col-md-4">On : <input type="time" value="' + iOn1 + '" id="iOn1"> Off : <input type="time" value="' + iOff1 + '" id="iOff1"> Fréquence : <input type="number" id="iFreq1" value="' + iFreq1 + '" min="0" max="1440"> minutes </div></div>';
															document.getElementById('cycle1').innerHTML = html;
															cycle = 1;
														}
													});
												}
											});
											
										}
									});
									
									if(data2.substr(1) != "0"){

										$.ajax({
											type: "GET",
											url: "getCycleStart.php",
											data: { cycleId: data2 },
											success: function(dataOn2){
												iOn2 = dataOn2.substr(1);
												$.ajax({
													type: "GET",
													url: "getCycleEnd.php",
													data: { cycleId: data2 },
													success: function(dataOff2){
														iOff2 = dataOff2.substr(1);
														$.ajax({
															type: "GET",
															url: "getCycleFreq.php",
															data: { cycleId: data2 },
															success: function(dataFreq2){
																iFreq2 = dataFreq2.substr(1);
																html = '<div class="row"><div class="col-xs-offset-2 col-xs-3 col-sm-offset-3 col-sm-2 col-md-offset-4 col-md-2"><p>Cycle 2 : </p></div>';
																html += '<div class="col-xs-5 col-sm-4 col-md-4">On : <input type="time" value="' + iOn2 + '" id="iOn2"> Off : <input type="time" value="' + iOff2 + '" id="iOff2"> Fréquence : <input type="number" id="iFreq2" value="' + iFreq2 + '" min="0" max="1440"> minutes </div></div>';
																document.getElementById('cycle2').innerHTML = html;
																cycle = 2;
															}
														});
													}
												});
											}
										});

										if(data3.substr(1) != '0'){

											$.ajax({
												type: "GET",
												url: "getCycleStart.php",
												data: { cycleId: data3 },
												success: function(dataOn3){
													iOn3 = dataOn3.substr(1);
													$.ajax({
														type: "GET",
														url: "getCycleEnd.php",
														data: { cycleId: data3 },
														success: function(dataOff3){
															iOff3 = dataOff3.substr(1);
															$.ajax({
																type: "GET",
																url: "getCycleFreq.php",
																data: { cycleId: data3 },
																success: function(dataFreq3){
																	iFreq3 = dataFreq3.substr(1);
																	html = '<div class="row"><div class="col-xs-offset-2 col-xs-3 col-sm-offset-3 col-sm-2 col-md-offset-4 col-md-2"><p>Cycle 3 : </p></div>';
																	html += '<div class="col-xs-5 col-sm-4 col-md-4">On : <input type="time" value="' + iOn3 + '" id="iOn3"> Off : <input type="time" value="' + iOff3 + '" id="iOff3"> Fréquence : <input type="number" id="iFreq3" value="' + iFreq3 + '" min="0" max="1440"> minutes </div></div>';
																	document.getElementById('cycle3').innerHTML = html;
																	cycle = 3;
																}
															});
														}
													});
												}
											});
									
										}
									
									}

								}
							}
						});

					}
				});

			}
		});


		

	}


	function addCycle(){
		var html = "";
		if(cycle<3){
			cycle++;
			html = '<div class="row"><div class="col-xs-offset-2 col-xs-3 col-sm-offset-3 col-sm-2 col-md-offset-4 col-md-2"><p>Cycle ' + cycle + ' : </p></div>';
			html += '<div class="col-xs-5 col-sm-4 col-md-4">On : <input type="time" id="iOn' + cycle + '"> Off : <input type="time" id="iOff' + cycle + '"> Fréquence : <input type="number" id="iFreq' + cycle + '" value="0" min="0" max="1440"> minutes </div></div>';
			document.getElementById('cycle'+cycle).innerHTML = html;
		}
	}
	
	function removeCycle(){
		if(cycle>0){
			document.getElementById('cycle'+cycle).innerHTML = "";
			cycle--;
		}
	}
	
	function saveTimerConf(){

		cycleNumber = 0;
		errorMessage = "";

		name = document.getElementById("timerName").value;

		iOn1 = document.getElementById("iOn1");
		iOff1 = document.getElementById("iOff1");
		iFreq1 = document.getElementById("iFreq1");

		iOn2 = document.getElementById("iOn2");
		iOff2 = document.getElementById("iOff2");
		iFreq2 = document.getElementById("iFreq2");

		iOn3 = document.getElementById("iOn3");
		iOff3 = document.getElementById("iOff3");
		iFreq3 = document.getElementById("iFreq3");

		if(cycleNumber >= 0 && iOn1 && iOn1.value != "" && iOff1 && iOff1.value != "" && iFreq1 && iFreq1.value != ""){
			cycleNumber++;
		}
		else if(iOn1 && iOn1.value == ""){
			errorMessage += " Il manque l'heure de départ du cycle 1.";
			cycleNumber = -1;
		}
		else if(iOff1 && iOff1.value == ""){
			errorMessage += " Il manque l'heure de fin du cycle 1.";
			cycleNumber = -1;
		}
		else if(iFreq1 && iFreq1.value == ""){
			errorMessage += " Il manque la fréquence du cycle 1.";
			cycleNumber = -1;
		}

		if(cycleNumber >= 0 && iOn2 && iOn2.value != "" && iOff2 && iOff2.value != "" && iFreq2 && iFreq2.value != ""){
			cycleNumber++;
		}
		else if(iOn2 && iOn2.value == ""){
			errorMessage += " Il manque l'heure de départ du cycle 2.";
			cycleNumber = -1;
		}
		else if(iOff2 && iOff2.value == ""){
			errorMessage += " Il manque l'heure de fin du cycle 2.";
			cycleNumber = -1;
		}
		else if(iFreq2 && iFreq2.value == ""){
			errorMessage += " Il manque la fréquence du cycle 2.";
			cycleNumber = -1;
		}

		if(cycleNumber >= 0 && iOn3 && iOn3.value != "" && iOff3 && iOff3.value != "" && iFreq3 && iFreq3.value != ""){
			cycleNumber++;
		}
		else if(iOn3 && iOn3.value == ""){
			errorMessage += " Il manque l'heure de départ du cycle 3.";
			cycleNumber = -1;
		}
		else if(iOff3 && iOff3.value == ""){
			errorMessage += " Il manque l'heure de fin du cycle 3.";
			cycleNumber = -1;
		}
		else if(iFreq3 && iFreq3.value == ""){
			errorMessage += " Il manque la fréquence du cycle 3.";
			cycleNumber = -1;
		}

//		$.ajax({
//			type: "GET",
//			url: "postTimerIBM.php",
//			data: {deviceId : <?php echo $timer_serial[0] ?> }
//		});

		if(cycleNumber == 0){

			$.ajax({
				type: "GET",
				url: "saveTimerConf.php",
				data: {deviceId : <?php echo $timer_serial[0] ?>, name: name, timerId: <?php echo $timer_id ?>, userId: <?php echo $user_id; ?>, cycleNumber: cycleNumber }
			});

		}

		else if(cycleNumber == 1){

			$.ajax({
				type: "GET",
				url: "saveTimerConf.php",
				data: {deviceId : <?php echo $timer_serial[0] ?>,  name: name, timerId: <?php echo $timer_id ?>, userId: <?php echo $user_id; ?>, cycleNumber: cycleNumber, iOn1: iOn1.value, iOff1: iOff1.value, iFreq1: iFreq1.value }
			});

		}

		else if(cycleNumber == 2){

			$.ajax({
				type: "GET",
				url: "saveTimerConf.php",
				data: {deviceId : <?php echo $timer_serial[0] ?>, name: name, timerId: <?php echo $timer_id ?>, userId: <?php echo $user_id; ?>, cycleNumber: cycleNumber, iOn1: iOn1.value, iOff1: iOff1.value, iFreq1: iFreq1.value, iOn2: iOn2.value, iOff2: iOff2.value, iFreq2: iFreq2.value }
			});

		}

		else if(cycleNumber == 3){

			$.ajax({
				type: "GET",
				url: "saveTimerConf.php",
				data: {deviceId : <?php echo $timer_serial[0] ?>,  name: name, timerId: <?php echo $timer_id ?>, userId: <?php echo $user_id; ?>, cycleNumber: cycleNumber, iOn1: iOn1.value, iOff1: iOff1.value, iFreq1: iFreq1.value, iOn2: iOn2.value, iOff2: iOff2.value, iFreq2: iFreq2.value, iOn3: iOn3.value, iOff3: iOff3.value, iFreq3: iFreq3.value }
			});

		}

		else{

			if(errorMessage != ""){

				alert(errorMessage);

			}
			else{

				alert("Unexpected error: contacter un administrateur.")

			}

		}

		parent.closeAllIFrames(1);

	}

	$(document).ready(function() { initCycle(); });

</script>
