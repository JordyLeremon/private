<?php

session_start();
require_once('db.php');
if(!isset($_SESSION['email']) || empty($_SESSION['email']))
        header('Location: login.php');


//Requete SQL
$sql = "SELECT sensorNamePerso from usersensor where sensorId=".$_GET['id'];
$result = $mysqli->query($sql);
$row2 = $result->fetch_array(MYSQLI_ASSOC);
$xls_output = "value;date";
$xls_output .= "\n";


$sql = "SELECT value,date from historysensor where sensorId=".$_GET['id']." order by date desc limit 50000";
$result = $mysqli->query($sql);

//Boucle sur les resultats
while($row = $result->fetch_array(MYSQLI_ASSOC))
{
	$xls_output .= "$row[value];$row[date]\n";
}

header("Content-type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=".$row2['sensorNamePerso'].".csv");
//header("Content-disposition: attachment; filename=".$row2['sensorNamePerso']."_".date("Ymd").".csv");
print $xls_output;
exit;
?>
