<?php

require('db_config.php');
	/* Getting demo_viewer table data */

  /*
	$sql = "SELECT SUM(numberofview) as count FROM demo_viewer 

            //GROUP BY YEAR(created_at) ORDER BY created_at";
            
    //$sensor_name = $database->sqlRequest("SELECT sensorNamePerso FROM usersensor WHERE usersensor.sensorId = ".$_GET['id']." AND usersensor.typeId= ".$_GET['typeID'] , "sensorNamePerso");
    //$cond = "WHERE sensorId=".$_GET['id']." AND typeId= ".$_GET['typeID']."  AND date >= DATE_ADD(now(),INTERVAL -1 DAY) ORDER BY date DESC";
    $cond = "SELECT date1  as count FROM historysensor WHERE sensorId=28 AND typeId=9  ORDER BY date1 DESC";
    //$value = $database->sqlRequest("SELECT value FROM historysensor ".$cond, "value");
    //$date = $database->sqlRequest("SELECT date FROM historysensor ".$cond, "date");

    $result = mysqli_query($mysqli,$cond);
    
	$result = mysqli_fetch_all($result,MYSQLI_ASSOC);

    $result = json_encode(array_column($result, 'count'),JSON_NUMERIC_CHECK);

   // echo $result;

    $cond = "SELECT value1  as count FROM historysensor WHERE sensorId=28 AND typeId=9  ORDER BY date1 DESC";
    //$value = $database->sqlRequest("SELECT value FROM historysensor ".$cond, "value");
    //$date = $database->sqlRequest("SELECT date FROM historysensor ".$cond, "date");

    $result2 = mysqli_query($mysqli,$cond);
    
	$result2 = mysqli_fetch_all($result2,MYSQLI_ASSOC);

    $result2 = json_encode(array_column($result2, 'count'),JSON_NUMERIC_CHECK);

    */
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
    $query_date = "SELECT date1 FROM  historysensor";
    $date = mysqli_query($mysqli,$query_date );
    $row_date = mysqli_fetch_assoc($date);
    $tab = "SELECT date1, value1 FROM historysensor WHERE sensorId=28 AND typeId=9  ORDER BY date1 ASC";
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
    $data2 = json_encode($data1);
    //echo $data2;
    
?>


<!DOCTYPE html>

<html>

<head>

	<title>HighChart</title>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.js"></script>

	<script src="https://code.highcharts.com/highcharts.js"></script>

</head>

<body>


<script type="text/javascript">


$(function () { 



    var data3 = <?php echo $data2; ?>;


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
            text: 'Premier enregistrement: <?php echo date("d/m/Y - H:i", strtotime($row_date['date1'])); ?> <br/>'
 
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
            data: data3

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


<
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


</body>

</html>