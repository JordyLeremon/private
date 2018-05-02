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

$sql = "SELECT * FROM sensortimer";
$result = $mysqli->query($sql);
$row = $result->fetch_array(MYSQLI_ASSOC);
echo json_encode($row);

while($row!=null){
	$row = $result->fetch_array(MYSQLI_ASSOC);
	if($row!=null){
		echo json_encode($row);
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
