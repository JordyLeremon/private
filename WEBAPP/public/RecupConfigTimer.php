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
$i=3;
$row3=[];

$sql = "select cycle1,cycle2,cycle3 from usertimer where userId=1 and timerId=1";
$result = $mysqli->query($sql);
$row = $result->fetch_array(MYSQLI_NUM);

for($j=0;$j<3;$j++){
	if($row[$j] != "0"){
		$sql = "select cycleStart, cycleEnd, timerId, cycleNumber from cycle where cycleId='".$row[$j]."'";
		$result = $mysqli->query($sql);
		$row2 = $result->fetch_array(MYSQLI_ASSOC);
		echo json_encode($row2);
	}
}


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
