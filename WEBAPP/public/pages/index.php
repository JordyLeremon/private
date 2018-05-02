<html lang="en">
<?php
	session_start();
	require_once('../php/Database.php');
	require_once('../php/RecupConf.php');
	if(!isset($_SESSION['email']) || empty($_SESSION['email']))
		header('Location: login.php');
	$conf = new RecupConf();
	
	$sensors = [];$database = new Database($conf->getDbName(), $conf->getDbLogin(), $conf->getDbPasswd());
	$user_id = $database->select("user", "id", "email", $_SESSION['email'])[0];
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
	

	$timers_id = $database->sqlRequest("SELECT id FROM timer, usertimer WHERE timer.id = usertimer.timerId AND usertimer.userId = ".$user_id , "id");

	$timers_serials = $database->sqlRequest("SELECT serialNumberTimer FROM timer, usertimer WHERE timer.id = usertimer.timerId AND usertimer.userId = ".$user_id , "serialNumberTimer");

	$timers = $database->sqlRequest("SELECT type FROM type, timertype, timer, usertimer WHERE type.id = timertype.typeId AND timertype.timerId = timer.id AND timer.id = usertimer.timerId AND usertimer.userID = ".$user_id , "type");

	$timers_name_perso = $database->sqlRequest("SELECT timerNamePerso FROM type, timertype, timer, usertimer WHERE type.id = timertype.typeId AND timertype.timerId = timer.id AND timer.id = usertimer.timerId AND usertimer.userID = ".$user_id , "timerNamePerso");

	$timers_values = $database->sqlRequest("SELECT value FROM timertype, timer, usertimer WHERE timertype.timerId = timer.id AND timer.id = usertimer.timerId AND usertimer.userID = ".$user_id , "value");

	$timers_disables = $database->sqlRequest("SELECT timertype.disable FROM timertype, timer, usertimer WHERE timertype.timerId = timer.id AND timer.id = usertimer.timerId AND usertimer.userID = ".$user_id , "disable");

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
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.js"></script>

	<script src="https://code.highcharts.com/highcharts.js"></script>


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
						<h3><i class="fa fa-clock-o fa-fw"></i> Timers</h3>

						<?php
						

						


   // echo $result2;

/*

	$viewer = mysqli_query($mysqli,$sql);

	$viewer = mysqli_fetch_all($viewer,MYSQLI_ASSOC);

	$viewer = json_encode(array_column($viewer, 'count'),JSON_NUMERIC_CHECK);


	/* Getting demo_click table data */

/*	$sql = "SELECT SUM(numberofclick) as count FROM demo_click 

			//GROUP BY YEAR(created_at) ORDER BY created_at";

	$click = mysqli_query($mysqli,$sql);

    $click = mysqli_fetch_all($click,MYSQLI_ASSOC);
    
	$click = json_encode(array_column($click, 'count'),JSON_NUMERIC_CHECK);*/

   
    
//echo $data;
//echo "*********";
	//echo $click;
	





	/*
    $query_date = "SELECT date1 FROM  historysensor";
    $date = mysqli_query($mysqli,$query_date );
    $row_date = mysqli_fetch_assoc($date);
    $tab = $database->sqlRequest("SELECT date1, value1 FROM historysensor WHERE sensorId=28 AND typeId=9  ORDER BY date1 ASC");
    $resolv = mysqli_query($mysqli, $tab);
    $xdata=array();
    $ydata=array();
    $data1=array();
	$i=0;
	
    if (mysqli_num_rows($resolv) > 0)
{
     // output data of each row
    
     while($row = mysqli_fetch_array($resolv))
     {
         //echo "valeur: " . $row["value1"]. " - date: " . strtotime($row["date1"]). "<br>";
        //$xdata[]= $row['value1'];
        //$ydata[]= strtotime($row['date1']);
        $buff=array(strtotime($row['date1'])*1000,floatval($row['value1']));
        $data1[]=$buff;
        //echo $data1[$i][0];
        //echo "   ";
        //echo $data1[$i][1];
        $i++;
    }
     }

else
{
     echo "0 results";
}
*/



echo $sensors_id[0];
echo "*******************";
echo $type_id[0];

//$cond = "WHERE sensorId=".$sensors_id[0]." AND typeId= ".$type_id[0]."  AND date >= DATE_ADD(now(),INTERVAL -1 DAY) ORDER BY date DESC";
$cond = "WHERE sensorId=".$sensors_id[0]." AND typeId= ".$type_id[0]." AND date >= DATE_ADD(now(),INTERVAL -1 MONTH) ORDER BY date DESC";
$value1 = $database->sqlRequest("SELECT value FROM historysensor ".$cond, "value");
$date = $database->sqlRequest("SELECT date FROM historysensor ".$cond, "date");


echo "*******************";
echo sizeof($date);
echo "*******************";
//echo $date[0];




/*foreach ($value1 as &$value) {
    echo("<br>".$value."</br>");
}*/

/*foreach ($date as &$date1) {
	$alph = strtotime($date1);
    echo("<br>".$alph."</br>");
}*/
echo "******************";
echo("<br>");
$data=array();
for(
	$i=0;
	$i < sizeof($value1) && $i < sizeof($date);
	$i++ )
	
	{
		$buff = array(strtotime($date[$i]), floatval($value1[$i]));
		//$data[$i][1]=floatval($value1[$i]);
		//$data[$i][0]=strtotime($date[$i]);
		$data[]=$buff;
		//echo $value1[$i]." ".strtotime($date[$i])." ";
		
	}


 

/*foreach($result as $resultat){
	
	echo $result['$value'].'<br/>';
	echo $result['$date'].'<br/>';
}*/




// Initialisation des tableaux vide
//$xdata=array();
//$ydata=array();
 
//while($row = mysqli_fetch_array($resolv))
/*{
    $xdata[]= $row['value1'];
    $ydata[]= strtotime($row['date1']);
}*/


 

    //echo $xdata;
    //echo $ydata;
   // $data1 =array($ydata,$xdata);
    $data2 = json_encode($data);
    echo $data2;
	
	$array1 = array(1167609600000, 2);
	$array2 = array(1167696000000, 4);
	$array3 = array(1167782400000, 6);
	$data1 = array($array1,$array2,$array3);
	$data2 = json_encode($data1);
	echo $data2;

?>

<script type="text/javascript">

$(function () { 



var data3 = <?php echo $data2;?>;



$('#container').highcharts({

	chart: {

		type: 'line',
		width: 1600,
		height: 700

	},

	title: {

		text: 'Courbe de suivi'

	},
	subtitle: {
	//date("d/m/Y - H:i", strtotime($row_date['Date'])) permet la mise en forme de la date:
	//Dans le cas actuel: Jour / mois / Année - Heure : Minutes
		text: 'Premier enregistrement: <?php echo date("d/m/Y - H:i", strtotime($date[$i])); ?> <br/>'

	},
	

	xAxis: {

		//categories: ['2013','2014','2015', '2016']
		//type: 'datetime'

	},

	yAxis: [{
		lineColor: '#FF0000',
		lineWidth: 1,
		gridLineWidth: 1,
		  labels: {formatter: function() {return this.value +'Pa';
				},
		  style: {
				  color: '#FF0000'
					}
			   },
		title: {
				text: 'Pression (Pa)'	
			},
			plotLines: [{
				value: 0,
				width: 1,
				color: '#FF0000'
			}]

	},
	/*{ // 2ème yaxis (numero 1)
			lineColor: '#3336FF',
			lineWidth: 1,
			gridLineWidth: 2,
			min:0,
			tickInterval:0.2,
			labels: {formatter: function() {return this.value +'mm';
				},
				style: {
					color: '#4572A7'
				}
			},
			title: {
				text: 'Hygro terre(mm)',
				style: {
					color: '#4572A7'
				}
			},
			
				opposite: true
		},*/
		
	],

	series: [{

		name: 'Hygro Terre',
		//yAxis: 1,
		//data: data_click
		data: ['125','525','1554','254']

	}/*,  {

		name: 'result',

		data: data3

}*/

	],
	credits: {
		text: '©Selfeden',
		href: 'http://localhost:8080/testing/indexing.php'
		}

});

});


</script>



<div class="container" style="float:left">

<br/>

<h2 class="text-center ">GRAPHIQUES : </h2>

<div class="row">

	<div class="col-md-10 col-md-offset-1">

		<div class="panel panel-default" style="width : 1700px ">

			<div class="panel-heading ">Dashboard</div>
			<div class="pull-right">
							<div class="btn-group">
								<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
								  Device selection
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
			</div>

			<div class="panel-body">

				<div id="container" ></div>

			</div>

		</div>

	</div>

</div>

</div>
					</div>
					<!-- /.panel-body -->
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

		}
		else{

			closeAllIFrames(0);
	    	var popup = document.getElementById(myPopup);
	    	popup.classList.toggle("show");

	    	popup.setAttribute("style","width:" + (screen.width / 2.0) + "px");
	    	popup.getElementsByTagName("iframe")[0].setAttribute("style","height:" + (screen.height / 2.0) + "px");

	    	center(popup);

    	}

	}

	function closeAllIFrames(val){

		var i = 0;
		while(document.getElementById("myPopupTimer" + i) != null){

			if(document.getElementById("myPopupTimer" + i).classList[1] == "show"){

				document.getElementById("myPopupTimer" + i).classList.toggle("show");

			}
			i++;

		}

		i = 0;
		while(document.getElementById("myPopupSensor" + i) != null){

			if(document.getElementById("myPopupSensor" + i).classList[1] == "show"){

				document.getElementById("myPopupSensor" + i).classList.toggle("show");

			}
			i++;

		}


		i = 0;
		while(document.getElementById("myPopupGraph" + i) != null){

			if(document.getElementById("myPopupGraph" + i).classList[1] == "show"){

				document.getElementById("myPopupGraph" + i).classList.toggle("show");

			}
			i++;

		}

		if(val){

			location.reload();
		}

	}

	//Cookies management
        function createCookie(name,value,days) {
                if (days) {
                        var date = new Date();
                        date.setTime(date.getTime()+(days*24*60*60*1000));
                        var expires = "; expires="+date.toGMTString();
                }
                else var expires = "";
                document.cookie = name+"="+value+expires+"; path=/";
        }
        function readCookie(name) {
                var nameEQ = name + "=";
                var ca = document.cookie.split(';');
                for(var i=0;i < ca.length;i++) {
                        var c = ca[i];
                        while (c.charAt(0)==' ') c = c.substring(1,c.length);
                        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
                }
                return null;
        }
        function eraseCookie(name) {
                createCookie(name,"",-1);
        }
 
        function update_Selection(){
                var v=document.getElementById('list').value;
                if(v)
                        createCookie('selectedValue',v);
                else
                        eraseCookie('selectedValue');
                self.location.href="http://localhost:8080/APPLICATION_WEB/WEBAPP/public/pages/index.php";
        }
 
//        function init_Selection(){
//                var v=readCookie('selectedValue');
//                if(v)document.getElementById('list').options[v].selected=true;
//        }

//        window.onload=init_Selection;
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
