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

//Humdité
$sql = 'UPDATE sensortype SET value=? WHERE sensorId=12';
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('d', $data['id']);
$res = $stmt->execute();
//Temperature
$sql = 'UPDATE sensortype SET value=? WHERE sensorId=13';
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('d', $data['ppm']);
$res = $stmt->execute();
//Hygro terre
$sql = 'UPDATE sensortype SET value=? WHERE sensorId=14';
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('d', $data['SSID']);
$res = $stmt->execute();


//Humidité
$sql = 'INSERT INTO historysensor(typeId,value,min,max,date,sensorId) VALUES(1,?,0,0,NOW(),12)';
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('d', $data['id']);
$res = $stmt->execute();
//Temperature
$sql = 'INSERT INTO historysensor(typeId,value,min,max,date,sensorId) VALUES(2,?,0,0,NOW(),13)';
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('d', $data['ppm']);
$res = $stmt->execute();
//Hygro terre
$sql = 'INSERT INTO historysensor(typeId,value,min,max,date,sensorId) VALUES(5,?,0,0,NOW(),14)';
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('d', $data['SSID']);
$res = $stmt->execute();


if($stmt->error)
    die(json_encode(['status' => 'error', 'msg' => 'mysql error:'.$stmt->error]));
if ($res)
    echo json_encode(['status' => 'ok']);
else
    echo json_encode(['status' => 'error', 'msg' => $mysqli->error]);
?>
