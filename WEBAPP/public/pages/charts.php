<!DOCTYPE html>
<html lang="en">
	<?php
		session_start();
		require_once('../php/Database.php');
		require_once('../php/RecupConf.php');
		$conf = new RecupConf();
		$database = new Database($conf->getDbName(), $conf->getDbLogin(), $conf->getDbPasswd());
		$sensor_name = $database->sqlRequest("SELECT sensorNamePerso FROM usersensor WHERE usersensor.sensorId = ".$_GET['id']." AND usersensor.typeId= ".$_GET['typeID'] , "sensorNamePerso");
		if($_GET['period'] == "")
		//	$cond = "WHERE sensorId='".$_GET['id']."' AND typeId= '".$_GET['typeID']."'  AND date >= DATE_ADD(now(),INTERVAL -1 DAY)  AND (minute(date)=30 OR minute(date)=0 OR minute(date)=1 OR minute(date)=3 OR minute(date)=31 OR minute(date)=32) ORDER BY date ASC";
			$cond = "WHERE sensorId=".$_GET['id']." AND typeId= ".$_GET['typeID']."  AND date >= DATE_ADD(now(),INTERVAL -1 DAY) ORDER BY date DESC";
		//	$cond = "WHERE sensorId=".$_GET['id']." AND typeId= ".$_GET['typeID']."  ORDER BY date desc LIMIT 31";
		//	$cond = "WHERE sensorId=".$_GET['id']." AND typeId= ".$_GET['typeID']."  AND date >= DATE_ADD(now(),INTERVAL -12 HOUR) GROUP BY hour(date), floor(minute(date)/30) desc LIMIT 24";
		else if($_GET['period'] == "monthly"){
		//	$cond = "WHERE sensorId=".$_GET['id']." AND typeId= ".$_GET['typeID']."  AND date >= DATE_ADD(now(),INTERVAL -1 MONTH)  AND (minute(date)=30 OR minute(date)=0 OR minute(date)=1 OR minute(date)=3 OR minute(date)=31 OR minute(date)=32) ORDER BY date ASC ";
		//	$cond = "WHERE sensorId=".$_GET['id']." AND typeId= ".$_GET['typeID']."  AND date >= DATE_ADD(now(),INTERVAL -1 MONTH) GROUP BY week(date), day(date), hour(date), floor(minute(date)/30) DESC LIMIT 1344";
			$cond = "WHERE sensorId=".$_GET['id']." AND typeId= ".$_GET['typeID']." AND date >= DATE_ADD(now(),INTERVAL -1 MONTH) ORDER BY date DESC";
		}
		else if($_GET['period'] == "weekly"){
		//	$cond = "WHERE sensorId=".$_GET['id']." AND typeId= ".$_GET['typeID']."  AND date >= DATE_ADD(now(),INTERVAL -1 WEEK)  AND (minute(date)=30 OR minute(date)=0 OR minute(date)=1 OR minute(date)=3 OR minute(date)=31 OR minute(date)=32) ORDER BY date ASC ";
		//	$cond = "WHERE sensorId=".$_GET['id']." AND typeId= ".$_GET['typeID']."  AND date >= DATE_ADD(now(),INTERVAL -1 WEEK) GROUP BY day(date), hour(date), floor(minute(date)/30) DESC LIMIT 336";
			$cond = "WHERE sensorId=".$_GET['id']." AND typeId= ".$_GET['typeID']." AND date >= DATE_ADD(now(),INTERVAL -1 WEEK) ORDER BY date DESC";
		}
		else {
			$cond = "WHERE sensorId=".$_GET['id']." AND typeId= ".$_GET['typeID']."  AND date >= DATE_ADD(now(),INTERVAL -1 DAY) ORDER BY date DESC";
//			$cond = "WHERE sensorId=".$_GET['id']." AND typeId= ".$_GET['typeID']."  AND date >= DATE_ADD(now(),INTERVAL -1 DAY)  AND (minute(date)=30 OR minute(date)=0 OR minute(date)=1 OR minute(date)=3 OR minute(date)=31 OR minute(date)=32) ORDER BY date ASC ";
		//	$cond = "WHERE sensorId=".$_GET['id']." AND typeId= ".$_GET['typeID']."  AND date >= DATE_ADD(now(),INTERVAL -1 DAY) GROUP BY hour(date), floor(minute(date)/30) DESC LIMIT 48";
//			$cond = "WHERE sensorId=".$_GET['id']." AND typeId= ".$_GET['typeID']." AND date >= DATE_ADD(now(),INTERVAL -1 DAY) ORDER BY date DESC";
		}
		$value = $database->sqlRequest("SELECT value FROM historysensor ".$cond, "value");
		$date = $database->sqlRequest("SELECT date FROM historysensor ".$cond, "date");
		
		if(count($value) < 1){
			$graph = "null";
		}
	?>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">

		<title>Selfeden - Graph - <?php echo $sensor_name[0]?></title>

		<!-- Bootstrap Core CSS -->
		<link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

		<!-- MetisMenu CSS -->
		<link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

		<!-- Custom CSS -->
		<link href="../dist/css/sb-admin-2.css" rel="stylesheet">
		<link href="../dist/css/selfeden.css" rel="stylesheet">

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

	</head>

	<body style="overflow:hidden">
		<?php
			//if(!isset($graph))
			//{
				echo '<div id="morris-line-chart"></div>';
			//}
			//else{
				//echo "<br><p align='center'>Pas assez de valeurs pour afficher un graphique !</p><br>";
			//}
		?>
	
		<!-- jQuery -->
		<script src="../vendor/jquery/jquery.min.js"></script>

		<!-- Bootstrap Core JavaScript -->
		<script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

		<!-- Metis Menu Plugin JavaScript -->
		<script src="../vendor/metisMenu/metisMenu.min.js"></script>
	
		<!-- Morris Charts JavaScript -->
		<script src="../vendor/raphael/raphael.min.js"></script>
		<script src="../vendor/morrisjs/morris.min.js"></script>
		
		<?php
		//if(!isset($graph))
		//{
			echo "<script>
			Morris.Line({
				element: 'morris-line-chart',
				
				data: [";
			
			for($i=0;$i<count($date);$i++){
				echo "	{
						period: '".$date[$i]."',
						value: ".$value[$i];
				if($i == (count($date)-1))
					echo "}";
				else
					echo "},";
			}
			echo "	],
				xkey: 'period',
				//ykeys: ['min', 'value', 'max'],
				ykeys: ['value'],
				//labels: ['Minimum', 'Valeur', 'Maximum'],
				labels: ['Valeur'],
				pointSize: 2,
				hideHover: 'auto',
				resize: true
			});
			</script>";
		//}
		?>
	</body>
</html>
