
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
/*for(
	$i=0;
	$i < sizeof($value1) && $i < sizeof($date);
	$i++ )
	
	{
		$buff = array(strtotime($date[$i])*1000, floatval($value1[$i]));
		$data[]=$buff;
		
		
  }*/
  
  for(
    $i=0;
    $i < sizeof($sensors_id);
    $i++ )
    {
      $buff = array(floatval($sensors_id[$i]));
      $data[]=$buff;
      
      
    }
    $data2 = json_encode($data);
  
	

?>
<!DOCTYPE html>
<html>

        <head>
                <meta charset="utf-8">
                <title>Cookie Checkbox</title>
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css"><script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
                
                <script src="jquery.cookie.js"></script>
                <script src="cookie-checkbox.js"></script>
        </head>
                <body>
                <div class="container">
                <div class="page-header">
                
                </div>
                <div class="row">
                <p> your check result will be saved.</p>
                
                </div>
               
                <!-- /<h1>device selection :</h1> -->
                <label  >device selection :</label> 
                <div class="row">
                <div class="form-group">
                <div class="checkbox">
                <label >
                <input data-cookie-checkbox="true" data-cookie-checkbox-key="CookieCheckBoxSample1"  data-cookie-checkbox-value="[]" name="sensors_id[]" type="checkbox" > 
                <?php
                 for ($i=0;$i<count($sensors_id);$i++){
                  echo "$sensors_id[$i]<br/> ";
                  
                 }
                ?>
                </label>
                </div>
                <div class="checkbox">
                <label >
                <input data-cookie-checkbox="true" data-cookie-checkbox-key="CookieCheckBoxSample1"  data-cookie-checkbox-value="[]" name="sensors_id[]" type="checkbox" > 
                <?php
                 for ($i=0;$i<count($sensors_id);$i++){
                  echo " $sensors_id[$i]<br/>"  ;
                  
                 }
                ?>
                </label>
                </div>

                
               <!-- <div class="checkbox">
                <label>
                <input data-cookie-checkbox="true" data-cookie-checkbox-key="CookieCheckBoxSample1" data-cookie-checkbox-value="01" type="checkbox"> Option 01
                </label>
                </div> -->
                <!--<div class="checkbox">
                <label>
                <input data-cookie-checkbox="true" data-cookie-checkbox-key="CookieCheckBoxSample1" data-cookie-checkbox-value="02" type="checkbox"> Option 02
                </label>
                </div>  -->
                <!--<div class="checkbox">
                <label>
                <input data-cookie-checkbox="true" data-cookie-checkbox-key="CookieCheckBoxSample1" data-cookie-checkbox-value="03" type="checkbox"> Option 03
                </label>
                </div> -->
                <!--<div class="checkbox">
                <label>
                <input data-cookie-checkbox="true" data-cookie-checkbox-key="CookieCheckBoxSample1" data-cookie-checkbox-value="04" type="checkbox"> Option 04
                </label>
                </div> -->
                <!--<div class="checkbox">
                <label>
                <input data-cookie-checkbox="true" data-cookie-checkbox-key="CookieCheckBoxSample1" data-cookie-checkbox-value="05" type="checkbox"> Option 05
                </label>
                </div> -->
                <!--<div class="checkbox">
                <label>
                <input data-cookie-checkbox="true" data-cookie-checkbox-key="CookieCheckBoxSample1" data-cookie-checkbox-value="06" type="checkbox"> Option 06
                </label>
                </div> -->
               <!-- <div class="checkbox">
                <label>
                <input data-cookie-checkbox="true" data-cookie-checkbox-key="CookieCheckBoxSample1" data-cookie-checkbox-value="07" type="checkbox"> Option 07
                </label>
                </div> -->
               <!-- <div class="checkbox">
                <label>
                <input data-cookie-checkbox="true" data-cookie-checkbox-key="CookieCheckBoxSample1" data-cookie-checkbox-value="08" type="checkbox"> Option 08
                </label>
                </div> -->
                <!--<div class="checkbox">
                <label>
                <input data-cookie-checkbox="true" data-cookie-checkbox-key="CookieCheckBoxSample1" data-cookie-checkbox-value="09" type="checkbox"> Option 09
                </label>
                </div> -->
               <!-- <div class="checkbox">
                <label>
                <input data-cookie-checkbox="true" data-cookie-checkbox-key="CookieCheckBoxSample1" data-cookie-checkbox-value="10" type="checkbox"> Option 10
                </label>
                </div> -->
                </div>
                <input id="btnGetChecked" value="Get Selected Values" class="btn btn-primary" type="button">
                <input id="btnClearCheck" value="Clear Selection" class="btn btn-primary" type="button">
                </div>
                
                </div>
                <script>
                      $(document).ready(function() {
                      enableCookieCheckBox();
                      
                      $('#btnGetChecked').click(function () {
                       var result = JSON.stringify(getCookieCheckboxValues('CookieCheckBoxSample1'));
                       alert(result);
                      });
                      
                        $('#btnClearCheck').click(function () {
                          clearCookieCheckBox('CookieCheckBoxSample1');
                        });
                      
                      });
                    </script>
                
                </body></html>