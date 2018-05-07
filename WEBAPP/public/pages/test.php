
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
	$type_id = [];
	$timers_values = [];
	$timers_disables = [];
	$type_id = [];
	$sensors_id = [];

	$noteId = [];
	$note = [];
	$noteDate = [];
	$sensors_id2 = [];
	$sensors_serials2 = [];
	$type_id2 = [];
	  // EXECUTE SQL REQUEST !!!!!;  
	
  
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
	$sensors_types = $database->sqlRequest("SELECT (value) FROM sensortype, sensor, usersensor WHERE sensortype.sensorId = sensor.id AND sensor.id = usersensor.sensorId AND usersensor.userID = ".$user_id." ORDER BY sensortype.sensorId", "value");


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

$cond = "WHERE sensorId=".$sensors_id[0]." AND typeId= ".$type_id[0]." AND date >= DATE_ADD(now(),INTERVAL -1 MONTH) ORDER BY date DESC";
$value1 = $database->sqlRequest("SELECT value FROM historysensor ".$cond, "value");
$date = $database->sqlRequest("SELECT date FROM historysensor ".$cond, "date");


$data=array();
for(
	$i=0;
	$i < sizeof($value1) && $i < sizeof($date);
	$i++ )
	
	{
		$buff = array(strtotime($date[$i])*1000, floatval($value1[$i]));
		$data[]=$buff;
		
		
	}

	//$test=array("name" => "Hygro Terre", "data" => $data);
	$data2 = json_encode($data);
	//$data2 = json_encode($data);
	


	$cookieName_sensors = json_encode($sensors_id);
	echo $sensors_id[0];
	echo $sensors_id[1];

	$cookieName_type =  json_encode($type_id);
	echo $type_id[0];
	echo $type_id[1];


	//chart: {type: 'line',width: 1520,height: 700},
	//title: {text: 'Courbe de suivi'},
	//xAxis: {type: 'datetime'},
	//yAxis: [{lineColor: '#FF0000',	lineWidth: 1,gridLineWidth: 1,labels: {formatter: function() {return this.value +'Pa';},style: { color: '#FF0000'}},title: {text: 'Pression (Pa)'},plotLines: [{value: 0,width: 1,color: '#FF0000'}]},],
	//tooltip: { valueSuffix: ' Pa' },
	//series: [{name: 'Hygro Terre',data: data3},{name: 'result',	data: data3}],
	//credits: {text: '©Selfeden',href: 'http://localhost:8080/APPLICATION_WEB/WEBAPP/public/pages/test.php'}});



?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Selfeden</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.js"></script>
	<script src="http://code.highcharts.com/stock/highstock.js"></script>
	<script src="http://code.highcharts.com/stock/modules/exporting.js"></script>
	
	<script src="jquery.cookie.js"></script>
    <script src="cookie-checkbox.js"></script>
	
</head>
<body>
	<!-- Bloc Connexion --> 
    <header id="wrapper"> 
		<nav>
            <!-- /.navbar-header -->
            <ul class="nav navbar-top-links navbar-right" >
				
                <li >
                <a href="#"><i class="fa fa-user fa-fw"></i> <?php echo $_SESSION['email']; ?></a>	
                </li>
                <li>                    		
                    <a href="deconnect.php"><i class="fa fa-sign-out fa-fw"></i>Déconnexion</a>  
				
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->
        </nav>
		
		<div class="row">
			<div class="col-sm-offset-1 col-sm-10 col-md-offset-1 col-md-10 col-lg-offset-1 col-lg-10">
				<h1 align="center" class="page-header"><img src="../img/selfeden_logo.png"></h1>
			</div>
			<!-- /.col-lg-12 -->
		</div>
	</header>

<script type="text/javascript">

 


$(function () { 
var data3 = <?php echo $data2;?>

//options.series[0].data = [[35.00,35.91,36.82,37.73,38.64]];



var chart = $('#container').highcharts('StockChart', {

	chart: {

		type: 'line',
		width: 1520,
		height: 700

	},

	title: {

		text: 'Courbe de suivi'

	},


	xAxis: {

		//categories: ['2013','2014','2015', '2016']
		type: 'datetime'

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
		
	],
	tooltip: {
        
        valueSuffix: ' Pa'
    },
	
	
	series: [{
		name: 'Hygro Terre',
		//yAxis: 1,
		//data: data_click
		data: data3

	}

	],
	credits: {
		text: '©Selfeden',
		href: 'http://localhost:8080/APPLICATION_WEB/WEBAPP/public/pages/test.php'
		}
		
});
$('#button').click(function () {
	console.log(chart.series);
    //chart.series.setData([129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4, 29.9, 71.5, 106.4]);
});
});



</script>



<div class="container" style="float:left">
<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-default" style="width : 1700px ">
			<div class="panel-heading ">Dashboard</div>
			            <div class="pull-right">
						<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
					

						
						<label  >device selection :</label> 
<?php
	for($i=0;$i<count($sensors_name_perso);$i++):
?>

<div class="checkbox">
                <label >
                
                <input type="checkbox"  id="<?php echo $sensors_id[$i]; ?>"    value="<?php echo $sensors_name_perso[$i]; ?>" name="sensorsensors_name_perso[]" <?php if(isset($_GET['sensors_name_perso']) && in_array('sensors_name_perso'.$sensors_name_perso[$i], $_GET['sensors_name_perso'])){echo ' checked="checked"';} ?> onchange="set_check();" /> <?php echo $sensors_name_perso[$i]; ?> 
                
                
                </label>
                </div>
                <?php
  endfor;
?>
			
							  
						</div>

						<script type="text/javascript">
  function setCookie(c_name,value,expiredays) {
        var exdate=new Date()
        exdate.setDate(exdate.getDate()+expiredays)
        document.cookie=c_name+ "=" +escape(value)+((expiredays==null) ? "" : ";expires="+exdate)
    }

    function getCookie(c_name) {
        if (document.cookie.length>0) {
            c_start=document.cookie.indexOf(c_name + "=")
            if (c_start!=-1) { 
                c_start=c_start + c_name.length+1 
                c_end=document.cookie.indexOf(";",c_start)
                if (c_end==-1) c_end=document.cookie.length
                    return unescape(document.cookie.substring(c_start,c_end))
            } 
    	}
        return null
    }

onload=function(){
	var cookieName_sensors = <?php echo$cookieName_sensors;?>;
	for (var i = 0; i < cookieName_sensors.length; i++) {
		document.getElementById(cookieName_sensors[i]).checked = getCookie(cookieName_sensors[i])==1? true : false;
	}
	console.log(cookieName_sensors.length);

	
	
}


function set_check(){
	var cookieName_sensors = <?php echo $cookieName_sensors;?>;
	for (var i = 0; i < cookieName_sensors.length; i++) {
		setCookie(cookieName_sensors[i], document.getElementById(cookieName_sensors[i]).checked? 1 : 0, 100);
	}

}

/*
onload=function(){
document.getElementById('toto').checked = getCookie('toto')==1? true : false;
}
function set_check(){
setCookie('toto', document.getElementById('toto').checked? 1 : 0, 100);
}*/
</script>
				
							  
						</div>
						<!--<input type="checkbox" id="<?//php echo $sensors_id[0];?>" onchange="set_check();">
						<input type="checkbox" id="<?//php echo $sensors_id[1];?>" onchange="set_check();">-->
		

			

				<div id="container" >  </div>
				<button id="button" class="autocompare">Set new data</button>
				

		</div>

	</div>

</div>

</div>
					
</body>

</html>
