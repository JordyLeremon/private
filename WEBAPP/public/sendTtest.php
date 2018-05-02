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
$row5="";
$arr=array();
$test=array();

$sql = "select id from timer WHERE serialNumberTimer=".$data['deviceId'];
$result = $mysqli->query($sql);
$rowUserId = $result->fetch_array(MYSQLI_NUM);

$sql = "select userId from usertimer WHERE timerId=".$rowUserId[0];
$result = $mysqli->query($sql);
$rowUserId = $result->fetch_array(MYSQLI_NUM);

$sql = "select timerId from usertimer where userId=".$rowUserId[0];
$rowTimerId = array();
foreach  ($mysqli->query($sql) as $row) {
    $rowTimerId[] = $row['timerId'];
}

for($k=0;$k<3;$k++){
	$sql = "select cycle1,cycle2,cycle3 from usertimer where userId=".$rowUserId[0]." and timerId=".$rowTimerId[$k]."";
	$result = $mysqli->query($sql);
	$row = $result->fetch_array(MYSQLI_NUM);

//	if($row[$j] != "null"){
		for($j=0;$j<3;$j++){
		//	if($row[$j] != "0"){
				$sql = "select cycleStart, cycleEnd, freq, timerId, cycleNumber from cycle where cycleId=".$row[$j]."";
				$result = $mysqli->query($sql);
				$row2 = $result->fetch_array(MYSQLI_ASSOC);
				$arr["T".($k+1)]["C".$j] = array(CS => $row2[cycleStart], CE => $row2[cycleEnd], F => $row2[freq]);
		//	}
		}
//	}
}

$arr["config"]["name"]=$data['deviceId'];
$row4=json_encode($arr);
echo $row4;
//echo "{\"deviceId\":\"nodemcu\"}";

//echo "{\"d\":".$row4."}";

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
