
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


    $data2 = json_encode($data);
  
	

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



var data3 = <?php echo $data2;?>;



$('#container').highcharts('StockChart', {


	chart: {

		type: 'line',
		width: 1520,
		height: 700

	},

	title: {

		text: 'Courbe de suivi'

	},

	/*rangeSelector : {
                selected : 1,
				
            },*/
			


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
	tooltip: {
        
        valueSuffix: ' Pa'
    },
	

	series: [{

		name: 'Hygro Terre',
		//yAxis: 1,
		//data: data_click
		data: data3

	}/*,  {

		name: 'result',

		data: data3

}*/

	],
	credits: {
		text: '©Selfeden',
		href: 'http://localhost:8080/APPLICATION_WEB/WEBAPP/public/pages/test.php'
		}

}
);

});
</script>



<div class="container" style="float:left">
<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-default" style="width : 1700px ">
			<div class="panel-heading ">Dashboard</div>
			            <div class="pull-right">
						<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
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
	window.onload=function(){

document.getElementById('id').checked = console.log(getCookie('linksNewWindow'))==1? true : false;
}
function set_check(){
setCookie('linksNewWindow', document.getElementById('linksNewWindow').checked? 1 : 0, 100);
}
</script>
						<label  >device selection :</label> 
<?php
	for($i=0;$i<count($sensors_name_perso);$i++):
?>

<div class="checkbox">
                <label >
                
                <input type="checkbox"  data-cookie-checkbox-id="linksNewWindow" data-cookie-checkbox="true" data-cookie-checkbox-key="sensors_name_perso"  data-cookie-checkbox-value="sensors_name_perso<?php echo $sensors_name_perso[$i]; ?>" data-cookie-checkbox-name="sensorsensors_name_perso[]" <?php if(isset($_GET['sensors_name_perso']) && in_array('sensors_name_perso'.$sensors_name_perso[$i], $_GET['sensors_name_perso'])){echo ' checked="checked"';} ?> onchange="set_check();" /> <?php echo $sensors_name_perso[$i]; ?> 
                
                
                </label>
                </div>
                <?php
  endfor;
?>

<label  >type selection :</label> 
<?php
	for($i=0;$i<count($type_id);$i++):
?>

<div class="checkbox">
                <label >
                
                <input type="checkbox"  Toto-id="linksNewWindow"  Toto="true"  Toto-key="type_id"   Toto-value="type_id<?php echo $type_id[$i]; ?>"  Toto-name="type_id[]" <?php if(isset($_GET['type_id']) && in_array('type_id'.$sensors_name_perso[$i], $_GET['type_id'])){echo ' checked="checked"';} ?> onchange="set_check();" /> <?php echo $type_id[$i]; ?> 
                
                
                </label>
                </div>
                <?php
  endfor;
?>
				
							  
						</div>
		

			

				<div id="container" >  </div>
				<button type="button" id="button">Refresh Data</button>
				

		</div>

	</div>

</div>

</div>
					
</body>

</html>
