<?php
if (!$_REQUEST['data'])
    die(json_encode(['status' => 'error', 'msg' => 'no request data!']));
require_once('db.php');
$data = $_REQUEST['data'];
$data = json_decode($data, 1);
if (!$data)
    die(json_encode(['status' => 'error', 'msg' => json_last_error()]));
#file_put_contents('1.txt',var_export($_REQUEST,1)."\n\n\n".var_export($data,1)."\n\n".json_last_error());
//$sql = 'INSERT INTO data3(added,sensor_id,ppm,ssid,ram,temp,humidity) VALUES(NOW(),?,?,?,?,?,?)';
$id=[];
$i=0;

$requestDeviceId = "select id from sensor WHERE serialNumber=".$data['deviceId'];
$rowSensor = array();
$arr = array();
$i=0;

foreach  ($mysqli->query($requestDeviceId) as $row) {
	//get association and min/max
	$sql = "select * from sensortimer WHERE sensorId=".$row['id'];
	$result = $mysqli->query($sql);
	$row2 = $result->fetch_array(MYSQLI_ASSOC);
	//get alerte
	$sql2 = "select * from sensortype WHERE sensorId=".$row['id'];
	$result2 = $mysqli->query($sql2);
	$row3 = $result2->fetch_array(MYSQLI_ASSOC);
	//echo $row2;
//        if($row2[sensorId] == $row['id']){
		$rowSensor["T".$i] = array(SId => $row['id'], TId => $row2[timerId], Imin => $row2[infMin], Smin => $row2[supMin], Smax => $row2[supMax], min => $row2[min], max => $row2[max], A0 => $row3[infMin], A1 => $row3[supMax]);
		$i++;
//	}
}

$rowSensor["config"]["name"]=$data['deviceId'];
$row4=json_encode($rowSensor);
//$row4=json_encode($rowSensor);
echo $row4;

//$stmt = $mysqli->prepare($sql);
//$stmt->bind_param('d', $id);
//$result = $stmt->execute();
//$row = $result->fetch_array(MYSQLI_NUM);
//while($row!=null){
//	$row = $result->fetch_array(MYSQLI_NUM);
//	if($row!=null){
//		echo json_encode($row);
//	}
//}



?>
