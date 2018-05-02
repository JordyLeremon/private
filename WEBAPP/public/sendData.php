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

$ID=$_SERVER['HTTP_USER_AGENT'];
//Recherche sensor ID en fonction du deviceId (mac address)
$sql = "select id from sensor WHERE serialNumber=".$ID;
//$result = $mysqli->query($sql);
//$rowSensorId = $result->fetch_array(MYSQLI_ASSOC);
//echo $rowSensorId['id'];

$sql2 = 'UPDATE sensortype SET value=? WHERE sensorId=?';
$stmt = $mysqli->prepare($sql2);
$stmt->bind_param('di',$data['value'], $data['id']);
$res = $stmt->execute();

$sql2 = 'INSERT INTO historysensor(typeId,value,date,sensorId) VALUES(?,?,NOW(),?)';
$stmt = $mysqli->prepare($sql2);
$stmt->bind_param('idi',$data['type'], $data['value'], $data['id']);
$res = $stmt->execute();



if($stmt->error)
    die(json_encode(['status' => 'error', 'msg' => 'mysql error:'.$stmt->error]));
if ($res)
    echo json_encode(['status' => 'ok']);
else
    echo json_encode(['status' => 'error', 'msg' => $mysqli->error]);
?>
