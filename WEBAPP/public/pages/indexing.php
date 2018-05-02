<?php


	require('db001.php');


	/* Getting demo_viewer table data */

	$sql = "SELECT value1 as count FROM historysensor 
            WHERE historysensor.typeId = 9
            GROUP BY historysensor.value1
			ORDER BY  historysensor.date1 ";

	$viewer = mysqli_query($mysqli,$sql);

	$viewer = mysqli_fetch_all($viewer,MYSQLI_ASSOC);

    $viewer = json_encode(array_column($viewer, 'count'),JSON_NUMERIC_CHECK);
    $query_date = "SELECT date1 FROM  historysensor where  date1 between '2018-04-12 08:23:26' and '2018-04-12 23:00' ";
    $date = mysqli_query($mysqli,$query_date );
    $row_date = mysqli_fetch_assoc($date);


	/* Getting demo_click table data */

	$sql = "SELECT value1 as count FROM historysensor 
            WHERE historysensor.dateId BETWEEN 130 AND 138
            GROUP BY historysensor.value1
			ORDER BY  historysensor.date1 ";
	$click = mysqli_query($mysqli,$sql);

	$click = mysqli_fetch_all($click,MYSQLI_ASSOC);

    $click = json_encode(array_column($click, 'count'),JSON_NUMERIC_CHECK);
   
    $result=mysqli_query($mysqli,$query_date)or die(mysql_error());
 if(mysqli_num_rows($result)>0){
    while($row=mysqli_fetch_array($result,MYSQLI_ASSOC))
    {
        $uts=strtotime($row['date1']); //convert to Unix Timestamp
        $date1=date("l, F j, Y H:i:s",$uts); //standard template for draw chart

       // echo $date1 . "\t" . $row['date1']. "\n";  //only this template work 
    }
 }

// $array1 = array(1167609600000, 2);
//$array2 = array(1167696000000, 4);
//$array3 = array(1167782400000, 6);
//$data1 = array($array1,$array2,$array3);
//$data = json_encode($data1);
echo $viewer;
//echo $data;

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

// Built in Highcharts date formatter based on the PHP strftime (see API reference for usage)
//Highcharts.dateFormat("Month: %m Day: %d Year: %Y", 20, false);
$(function () { 


    var data_click = <?php echo $click; ?>;

   var data_viewer = <?php echo $viewer; ?>;
  // var data = <?//php echo $data; ?>;
   


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
       /* tooltip: {
    crosshairs: [true]
},*/
        xAxis: {

            type: 'datetime',
            //tickInterval: 3600 * 2000,
            //title: {
              //      text: null
               // }
               //categories: ['<?//php echo join($date1, "','"); ?>']

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
        { // 2ème yaxis (numero 1)
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
			},
            
        ],

        series: [{

            name: 'Hygro Terre',
           
            //categories : [<?//php do { ?>'<?//php echo date("H:i:s ", strtotime($row_date['date1'])); ?>', <?//php } while ($row_date = mysqli_fetch_assoc($date)); ?>],
            //pointInterval: 3600 * 1000,
                //pointStart: Date.UTC(2018, 3, 12, 0, 0, 0, 0),
            data: data_viewer

        }, {

            name: 'Pression',
            //categories : [<?//php do { ?>'<?//php echo date("H:i:s ", strtotime($row_date['date1'])); ?>', <?//php } while ($row_date = mysqli_fetch_assoc($date)); ?>],
            //pointInterval: 3600 * 1000,
                //pointStart: Date.UTC(2018, 3, 12, 0, 0, 0, 0),
                data: data_click
           

        }]

    });
    


});


</script>


<div class="container" style="float:left">

	<br/>

	<h2 class="text-center ">Highcharts </h2>

    <div class="row">

        <div class="col-md-10 col-md-offset-1 ">
          
            <div class="panel panel-default  " style="width : 1700px " >

                <div class="panel-heading ">
                <h>Dashboard</h>
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
                </div>

                <div class="panel-body" >

                    
                        <div id="container" ></div>
                    

                </div>

            </div>
          

        </div>

    </div>

</div>


</body>

</html>