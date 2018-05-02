<html lang="en">
<?php
	session_start();
	require_once('../php/Database.php');
	require_once('../php/RecupConf.php');
	if(!isset($_SESSION['email']) || empty($_SESSION['email']))
		header('Location: login.php');
	$conf = new RecupConf();
	$database = new Database($conf->getDbName(), $conf->getDbLogin(), $conf->getDbPasswd());
	$user_id = $database->select("user", "id", "email", $_SESSION['email'])[0];
	$sensors = [];
	$sensors_serials = [];
	$sensors_values = [];
	$timersTypeId = [];
	$timers = [];
	$timers_serials = [];
	$timers_values = [];
	$timers_disables = [];

	$noteId = [];
	$note = [];
  	$noteDate = [];
	$sensors_serials2 = [];
  	// EXECUTE SQL REQUEST !!!!!
	
	$email = $database->sqlRequest("SELECT email FROM user WHERE id = ".$user_id." LIMIT 1" , "email")[0];
	
	$noteId = $database->sqlRequest("SELECT noteId FROM note WHERE note.userId = ".$user_id." ORDER BY date DESC" , "noteId");

	$note = $database->sqlRequest("SELECT note FROM note WHERE note.userId = ".$user_id." ORDER BY date DESC" , "note");

	$noteDate = $database->sqlRequest("SELECT date FROM note WHERE note.userId = ".$user_id." ORDER BY date DESC" , "date");


	$sensors_id = $database->sqlRequest("SELECT id FROM sensor, usersensor WHERE sensor.id = usersensor.sensorId AND usersensor.userId = ".$user_id, "id");

	$sensors_serials = $database->sqlRequest("SELECT serialNumber FROM sensor, usersensor WHERE sensor.id = usersensor.sensorId AND usersensor.userId = ".$user_id , "serialNumber");

	$sensors = $database->sqlRequest("SELECT nom FROM type, sensortype, sensor, usersensor WHERE type.id = sensortype.typeId AND sensortype.sensorId = sensor.id AND sensor.id = usersensor.sensorId AND usersensor.userID = ".$user_id , "nom");

	$sensors_name_perso = $database->sqlRequest("SELECT sensorNamePerso FROM usersensor WHERE usersensor.userID = ".$user_id, "sensorNamePerso");
    //$sensors_values = $database->sqlRequest("SELECT (value) FROM sensortype, sensor, usersensor WHERE sensortype.sensorId = sensor.id AND sensor.id = usersensor.sensorId AND usersensor.userID = ".$user_id." ORDER BY sensortype.sensorId,sensortype.typeId", "value");
	$sensors_values = $database->sqlRequest("SELECT (value) FROM sensortype, sensor, usersensor WHERE sensortype.sensorId = sensor.id AND sensor.id = usersensor.sensorId AND usersensor.userID = ".$user_id." ORDER BY sensortype.sensorId", "value");

    //$type_id = $database->sqlRequest("SELECT typeId FROM usersensor ORDER BY sensorId,typeId","typeId");
	$type_id = $database->sqlRequest("SELECT typeId FROM usersensor WHERE usersensor.userID = ".$user_id,"typeId");
	
	$sensor_alertMin = $database->sqlRequest("SELECT infMin FROM sensortype ORDER BY sensorId,typeId","infMin");
	
	$sensor_alertMax = $database->sqlRequest("SELECT supMax FROM sensortype ORDER BY sensorId,typeId","supMax");
	

?>

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">


    <title>Selfeden</title>

    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">
	<link href="../dist/css/selfeden.css" rel="stylesheet">
	<link href="../dist/css/slider.css?d=05" rel="stylesheet">

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
<!-- jQuery -->
<script src="../vendor/jquery/jquery.min.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

<body style="overflow-x:hidden">
    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">Selfeden</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
				<li>
                    <a href="#"><i class="fa fa-user fa-fw"></i> <?php echo $_SESSION['email']; ?></a>
                </li>
                <li>
                    <a href="deconnect.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->
        </nav>
		
		<div class="row">
			<div class="col-sm-offset-1 col-sm-10 col-md-offset-1 col-md-10 col-lg-offset-1 col-lg-10">
				<h1 align="center"class="page-header"><img src="../img/selfeden_logo.png"></h1>
			</div>
			<!-- /.col-lg-12 -->
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-offset-1 col-sm-10 col-md-offset-1 col-md-10 col-lg-offset-1 col-lg-10">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3><i class="fa fa-thermometer-three-quarters fa-fw"></i> Etalonnage capteurs</h3>
					</div>
					<!-- /.panel-heading -->

					<?php
						if(count($sensors) > 0){
					?>
						<div class="panel-body">
							<div class="row">
								<div align="center" class="col-xs-2 col-sm-offset-1 col-sm-2 col-md-offset-1 col-md-2" style="font-size:17px">
									<b>Nom</b><br>
									<?php
                                                                                $k = 0;
                                                                                        for($i=0;$i<count($sensors_id);$i++){
                                                                                                if($type_id[$i] < 5){
                                                                                                        if($sensors_name_perso[$i] == ""){
                                                                                                                echo utf8_encode($sensors[$i]);
                                                                                                        }
                                                                                                        else{
                                                                                                                echo utf8_decode($sensors_name_perso[$i]);
                                                                                                        }
                                                                                                        echo '<br>';
                                                                                                }
                                                                                        }
                                                                        ?>
								</div>
								<div style="font-size:17px">
									<div align="center" class="col-xs-offset-1 col-xs-2 col-sm-offset-1 col-sm-2 col-md-offset-1 col-md-2">
										<b>1 point d'étalonage</b><br>
										<?php
                                                                                        for($i=0;$i<count($sensors_id);$i++){
                                                                                                if($type_id[$i] < 5){
                                                                                                        if($type_id[$i] == 1){
                                                                                            			echo '	<div class="popup" onclick="popup( \'myPopupTimer'.$i.'\')">PH4
					  										<span class="popuptext" id="myPopupTimer'.$i.'">
						  										<iframe src=etalonageConf.php?Sid='.$sensors_id[$i].'&Did='.$sensors_serials[$i].'&etalon=1 width="100%" height="300">
						  											<p>Your browser does not support iframes.</p>
																</iframe>
															</span>
														</div>';
											                }else if($type_id[$i] == 2){
												        	echo '	<div class="popup" onclick="popup( \'myPopupTimer'.$i.'\')">EC 0ms/cm
					  										<span class="popuptext" id="myPopupTimer'.$i.'">
						  										<iframe src=etalonageConf.php?Sid='.$sensors_id[$i].'&Did='.$sensors_serials[$i].'&etalon=1 width="100%" height="300">
						  											<p>Your browser does not support iframes.</p>
																</iframe>
															</span>
														</div>';
                                                                                                        }else if($type_id[$i] == 4){
												        	echo '	<div class="popup" onclick="popup( \'myPopupTimer'.$i.'\')">DO 0mg/L
					  										<span class="popuptext" id="myPopupTimer'.$i.'">
						  										<iframe src=etalonageConf.php?Sid='.$sensors_id[$i].'&Did='.$sensors_serials[$i].'&etalon=1 width="100%" height="300">
						  											<p>Your browser does not support iframes.</p>
																</iframe>
															</span>
														</div>';
                                                                                                        }

                                                                                                        echo '<br>';
                                                                                                }
                                                                                        }
                                                                        ?>
									</div>
									<div align="center" class="col-xs-offset-1 col-xs-2 col-sm-offset-1 col-sm-2 col-md-offset-1 col-md-2">
										<b>2 point d'étalonage</b><br>
										<?php
                                                	                        	for($i=0;$i<count($sensors_id);$i++){
                                                                                        	if($type_id[$i] < 5){
                                                                                                        if($type_id[$i] == 1){
                                                                                            			echo '	<div class="popup" onclick="popup( \'myPopupTimer'.$i.'\')">PH7
					  										<span class="popuptext" id="myPopupTimer'.$i.'">
						  										<iframe src=etalonageConf.php?Sid='.$sensors_id[$i].'&Did='.$sensors_serials[$i].'&etalon=2 width="100%" height="300">
						  											<p>Your browser does not support iframes.</p>
																</iframe>
															</span>
														</div>';
											                }else if($type_id[$i] == 2){
												        	echo '	<div class="popup" onclick="popup( \'myPopupTimer'.$i.'\')">EC 1.14ms/cm
					  										<span class="popuptext" id="myPopupTimer'.$i.'">
						  										<iframe src=etalonageConf.php?Sid='.$sensors_id[$i].'&Did='.$sensors_serials[$i].'&etalon=2 width="100%" height="300">
						  											<p>Your browser does not support iframes.</p>
																</iframe>
															</span>
														</div>';
                                                                                                        }else if($type_id[$i] == 4){
												        	echo '	<div class="popup" onclick="popup( \'myPopupTimer'.$i.'\')">DO air
					  										<span class="popuptext" id="myPopupTimer'.$i.'">
						  										<iframe src=etalonageConf.php?Sid='.$sensors_id[$i].'&Did='.$sensors_serials[$i].'&etalon=2 width="100%" height="300">
						  											<p>Your browser does not support iframes.</p>
																</iframe>
															</span>
														</div>';
                                                                                                        }
    
                                                                                                        echo '<br>';
        	                                                                        	}
											}
	                                                        		?>
									</div>
								</div>
							</div>
						</div>
						<!-- /.panel-body -->
					<?php
						}
					?>
				</div>
			</div>
		</div>
		<!-- /.row -->
    </div>
    <!-- /#wrapper -->

    <script>

    function offset(el) {


	    var rect = el.getBoundingClientRect(),
	    scrollLeft = window.pageXOffset || document.documentElement.scrollLeft,
	    scrollTop = window.pageYOffset || document.documentElement.scrollTop;
	    return { top: rect.top + scrollTop, left: rect.left + scrollLeft }

	}

	function center (el) {

    	el.style.left = "0px";
    	el.style.top = "0px";

    	var divOffset = offset(el.getElementsByTagName("iframe")[0]);

		if( (($(window).width() / 2) - ($(el).width() / 2 )) > divOffset.left){

			el.style.left = Math.abs((divOffset.left - (($(window).width() / 2) - ($(el).width() / 2 )) )) + "px";

		}
		else{

			el.style.left = "-" + Math.abs((divOffset.left - (($(window).width() / 2) - ($(el).width() / 2 )) )) + "px";

		}

		if( (($(window).height() / 2) - ($(el.getElementsByTagName("iframe")[0]).height() / 2 )) > divOffset.top){

			el.style.top = Math.abs((divOffset.top - (($(window).height() / 2) - ((el.getElementsByTagName("iframe")[0].getBoundingClientRect().bottom - el.getElementsByTagName("iframe")[0].getBoundingClientRect().top) / 2 )) ) ) + "px";

		}
		else{

			el.style.top = "-" + Math.abs((divOffset.top - (($(window).height() / 2) - ((el.getElementsByTagName("iframe")[0].getBoundingClientRect().bottom - el.getElementsByTagName("iframe")[0].getBoundingClientRect().top) / 2 )) ) ) + "px";

		}

	    var divOffset = offset(el);

	}

	/*
	 *
	 * When the user clicks on <div>, open the popup
	 *
	 */
	function popup(myPopup) {

		if(document.getElementById(myPopup).classList[1] == "show"){

			closeAllIFrames(0);

		}else{

			closeAllIFrames(0);
	    	var popup = document.getElementById(myPopup);
	    	popup.classList.toggle("show");

	    	popup.setAttribute("style","width:" + (screen.width / 2.0) + "px");
	    	popup.getElementsByTagName("iframe")[0].setAttribute("style","height:" + (screen.height / 2.0) + "px");

	    	center(popup);

    		}

	}

	function closeAllIFrames(val){
		if(val){
			self.location.href="http://selfeden.fr/pages/etalonage2.php";
		}
	}

	</script>
    <!-- Bootstrap Core JavaScript -->
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>
	<script src="../dist/js/bootstrap-slider.js"></script>
</body>

</html>
