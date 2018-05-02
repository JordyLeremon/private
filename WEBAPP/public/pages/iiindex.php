
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

	$noteId = [];
	$note = [];
  	$noteDate = [];
	$sensors_serials2 = [];
	$type_id2 = [];
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

    


    <title>Selfeden</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">


	
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/jquery-ui.min.js"></script>
	<script src="http://code.highcharts.com/stock/highstock.js"></script>
	<script src="http://code.highcharts.com/stock/modules/exporting.js"></script>


    <div id="wrapper"> 

      
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
				<h1 align="center"class="page-header"><img src="../img/selfeden_logo.png"></h1>
			</div>
			<!-- /.col-lg-12 -->
		</div>
		
                        </head>
<body >
<script type="text/javascript">

$(function() {

$.getJSON('http://www.highcharts.com/samples/data/jsonp.php?filename=aapl-c.json&callback=?', function(data) {
    // Create the chart
    window.chart = new Highcharts.StockChart({
        chart: {
            renderTo: 'container'
        },

        rangeSelector: {
            selected: 1,
            inputDateFormat: '%Y-%m-%d'
        },

        title: {
            text: 'AAPL Stock Price'
        },

        series: [{
            name: 'AAPL',
            data: data,
            tooltip: {
                valueDecimals: 2
            }}]

    }, function(chart) {

        // apply the date pickers
        setTimeout(function() {
            $('input.highcharts-range-selector', $('#' + chart.options.chart.renderTo)).datepicker()
        }, 0)
    });
});


// Set the datepicker's date format
$.datepicker.setDefaults({
    dateFormat: 'yy-mm-dd',
    onSelect: function(dateText) {
        this.onchange();
        this.onblur();
    }
});

});
</script>



<div class="container" style="float:left">

<br/>



<div class="row">

	<div class="col-md-10 col-md-offset-1">

		<div class="panel panel-default" style="width : 1700px ">

			<div class="panel-heading ">Dashboard</div>
			            <div class="pull-right">
                          
						<form action="#" method="get">
                				<p>	<label for="list" style="float:right">Type selection :</label> </p>
                					    <select name="list" id="list" onchange="update_Selection();" >
							<p>	<option value="" type="checkbox"selected="selected" style="float:right"type="checkbox">Choisissez un type</option> </p>
								<?php
        		                                                $j=1;
                        		                                $type_id2[0] = $type_id[0];
                                        		                for ($i=1;$i<count($type_id);$i++){
                                                        		        $flag=0;
                    		                                            //echo '<option value="'.$sensors_serials[$i].'" ></option>';
                                		                                for($k=0;$k<count($type_id2);$k++){
                                                		                        if($type_id[$i] == $type_id2[$k]){
                                                                		                $flag=1;
		                                                                        }
                		                                                }
                                		                                if($flag == 0){
																			$type_id2[$j] = $type_id[$i];
                                                                		        $j++;
          		                                                        }
                        		                                }
                                        		                for ($i=0;$i<count($type_id2);$i++){
								       		//echo '<option value='.$i.'>'.$sensors_serials2[$i].'</option>';
                                                        			if($type_id2[$i] == $type_id2[$_COOKIE['selectedValue']]){
	                                                      		        	echo '<option value='.$i.' selected="selected">'.$type_id2[$i].'</option>';
										}else{
									       		echo '<option value='.$i.'>'.$type_id2[$i].'</option>';
										}
									}
                		                                ?>
							         </select>
					 	         </label>
						</form>
						<form action="#" method="get">
                				<p>	<label for="list" style="float:right">device selection :</label> </p>
                					    <select name="list" id="list" onchange="update_Selection();" >
							<p>	<option value="" type="checkbox"selected="selected" style="float:right"type="checkbox">Choisissez un device</option> </p>
								<?php
        		                                                $j=1;
                        		                                $sensors_serials2[0] = $sensors_serials[0];
                                        		                for ($i=1;$i<count($sensors_serials);$i++){
                                                        		        $flag=0;
                    		                                            //echo '<option value="'.$sensors_serials[$i].'" ></option>';
                                		                                for($k=0;$k<count($sensors_serials2);$k++){
                                                		                        if($sensors_serials[$i] == $sensors_serials2[$k]){
                                                                		                $flag=1;
		                                                                        }
                		                                                }
                                		                                if($flag == 0){
																			$sensors_serials2[$j] = $sensors_serials[$i];
                                                                		        $j++;
          		                                                        }
                        		                                }
                                        		                for ($i=0;$i<count($sensors_serials2);$i++){
								       		//echo '<option value='.$i.'>'.$sensors_serials2[$i].'</option>';
                                                        			if($sensors_serials2[$i] == $sensors_serials2[$_COOKIE['selectedValue']]){
	                                                      		        	echo '<option value='.$i.' selected="selected">'.$sensors_serials2[$i].'</option>';
										}else{
									       		echo '<option value='.$i.'>'.$sensors_serials2[$i].'</option>';
										}
									}
                		                                ?>
							         </select>
					 	         </label>
						</form>
			



							    <?php /*<div class="btn-group">
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
							     </div>*/ ?>
						</div>
			</div>

			<div class="panel-body">

				<div id="container" style="height: 400px; min-width: 600px"></div>

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

   /* function offset(el) {


	    var rect = el.getBoundingClientRect(),
	    scrollLeft = window.pageXOffset || document.documentElement.scrollLeft,
	    scrollTop = window.pageYOffset || document.documentElement.scrollTop;
	    return { top: rect.top + scrollTop, left: rect.left + scrollLeft }

    }*/

	/*function center (el) {

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

    }*/

	/*
	 *
	 * When the user clicks on <div>, open the popup
	 *
	 */
	/*function popup(myPopup) {

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

    }*/

	/*function closeAllIFrames(val){

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

    }*/

	//Cookies management
        /*function createCookie(name,value,days) {
                if (days) {
                        var date = new Date();
                        date.setTime(date.getTime()+(days*24*60*60*1000));
                        var expires = "; expires="+date.toGMTString();
                }
                else var expires = "";
                document.cookie = name+"="+value+expires+"; path=/";
        }*/
        /* function readCookie(name) {
                var nameEQ = name + "=";
                var ca = document.cookie.split(';');
                for(var i=0;i < ca.length;i++) {
                        var c = ca[i];
                        while (c.charAt(0)==' ') c = c.substring(1,c.length);
                        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
                }
                return null;
        }*/
        /* function eraseCookie(name) {
                createCookie(name,"",-1);
        }
 
        function update_Selection(){
                var v=document.getElementById('list').value;
                if(v)
                        createCookie('selectedValue',v);
                else
                        eraseCookie('selectedValue');
                self.location.href="http://localhost:8080/APPLICATION_WEB/WEBAPP/public/pages/index.php";
        }*/
 
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
