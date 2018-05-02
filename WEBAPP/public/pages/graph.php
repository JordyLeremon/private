<!DOCTYPE html>
<html lang="en">
<head>
	<?php
		session_start();
		if(!isset($_SESSION['email']) || empty($_SESSION['email']))
			header('Location: login.php');
		if(empty($_GET['id']) || empty($_GET['typeID'])){
			echo "<script>window.close();</script>";
		}
		else{
			require_once('../php/Database.php');
			require_once('../php/RecupConf.php');
			$conf = new RecupConf();
			$database = new Database($conf->getDbName(), $conf->getDbLogin(), $conf->getDbPasswd());
			$idd=$_GET['id'];
            $ndd=$_GET['typeID'];
			$sensor_name = $database->sqlRequest("SELECT sensorNamePerso FROM usersensor WHERE usersensor.sensorId = ".$_GET['id']." AND usersensor.typeId= ".$_GET['typeID'] , "sensorNamePerso");
            //file_put_contents ( "graph.log" , "Input args        : ".$idd." ".$ndd."\n",FILE_APPEND);
            file_put_contents ( "graph.log" , "Sensor name found : ".$sensor_name[0]."\n",FILE_APPEND);
			if($sensor_name[0] == ""){
				$sensor_name[0] = utf8_encode($database->sqlRequest("SELECT nom FROM type WHERE type.id = ".$_GET['typeID'] , "nom")[0]);
			}
			else
				$sensor_name[0] = utf8_decode($sensor_name[0]);
			
		}
	?>
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
    <!-- WARNING: Respond.js doesn't work if you view the page via file: -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body style="overflow-x:hidden">

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>pan class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">Selfeden</a>
            </div>
            <!-- /.navbar-header -->
        </nav>
		<div class="row">
			<div class="col-sm-offset-1 col-sm-10 col-md-offset-1 col-md-10 col-lg-offset-1 col-lg-10">
				<h1 class="page-header">Graphique : <?php echo $sensor_name[0]?></h1>
			</div>
			<!-- /.col-lg-12 -->
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-offset-1 col-sm-10 col-md-offset-1 col-md-10 col-lg-offset-1 col-lg-10">
				<div class="panel panel-default">
					<div class="panel-heading">
						<i class="fa fa-bar-chart-o fa-fw"></i> <?php echo $sensor_name[0]?>
						<?php
							if(!isset($graph))
							{
						?>
							<div class="pull-right">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                        Historique
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu pull-right" role="menu">
                                        <li><a href="#" onClick="changeLocIframe('monthly')">Par mois</a>
                                        </li>
                                        <li><a href="#" onClick="changeLocIframe('weekly')">Par semaine</a>
                                        </li>
                                        <li><a href="#" onClick="changeLocIframe('daily')">Par jour</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
						<?php
							}
						?>
					</div>
					<!-- /.panel-heading -->
					<div class="panel-body">
						<?php
							//if(!isset($graph))
							//{
								echo '<iframe id="charts" src="charts.php?id='.$_GET['id'].'&typeID='.$_GET['typeID'].'&period=" width="100%" frameBorder="0" onload="resizeIframe(this)"></iframe>';
							//}
							/*else{
								echo "<br><p align='center'>Pas assez de valeurs pour afficher un graphique !</p><br>";
							}*/
						?>
					</div>
					<!-- /.panel-body -->
				</div>
			</div>
		</div>
    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="../vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>
	<?php
		echo '	<script>
		  function resizeIframe(obj) {
			obj.style.height = obj.contentWindow.document.body.scrollHeight + "px";
		  }
		  function changeLocIframe(){
				var iframe = document.getElementById("charts");
				iframe.src = "charts.php?id='.$_GET['id'].'&typeID='.$_GET['typeID'].'&period=" +arguments[0];
		  }
		</script>';
	?>
</body>
</html>
