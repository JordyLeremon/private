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
$row = $result->fetch_array(MYSQLI_NUM);
$id[$i]=$row[0];
$id2[$i]=$row[1];

while($row!=null){
	$row = $result->fetch_array(MYSQLI_NUM);
	$i++;
	$id[$i]=$row[0];
	$id2[$i]=$row[1];
}

for($j=0;$j<$i;$j++){
	$sql = "SELECT * FROM sensor where id='".$id[$j]."'";
	$result = $mysqli->query($sql);
	$row = $result->fetch_array(MYSQLI_NUM);
	$row2[0]=$id[$j];
	$row2[1]=$id2[$j];
	$row2[2]=$row[0];
	$row2[3]=$row[1];
	$row2[4]=$row[2];
	$row2[5]=$row[3];
	echo json_encode($row2);
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
