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

//PH
$sql = 'UPDATE sensortype SET value=? WHERE sensorId=7';
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('d', $data['id']);
$res = $stmt->execute();
//EC
$sql = 'UPDATE sensortype SET value=? WHERE sensorId=8';
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('d', $data['ppm']);
$res = $stmt->execute();
//Temp1
$sql = 'UPDATE sensortype SET value=? WHERE sensorId=9';
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('d', $data['SSID']);
$res = $stmt->execute();
//Temp2
$sql = 'UPDATE sensortype SET value=? WHERE sensorId=10';
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('d', $data['temp']);
$res = $stmt->execute();
//Humidity terre
$sql = 'UPDATE sensortype SET value=? WHERE sensorId=11';
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('d', $data['humidity']);
$res = $stmt->execute();



//$email = $database->sqlRequest("SELECT email FROM user WHERE id = ".$user_id." LIMIT 1" , "email")[0];
//$noteId = $database->sqlRequest("SELECT noteId FROM note WHERE note.userId = ".$user_id." ORDER BY date DESC" , "noteId");



//PH history
$sql = 'INSERT INTO historysensor(typeId,value,min,max,date,sensorId) VALUES(5,?,0,0,NOW(),7)';
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('d', $data['id']);
$res = $stmt->execute();
//EC history
$sql = 'INSERT INTO historysensor(typeId,value,min,max,date,sensorId) VALUES(5,?,0,0,NOW(),8)';
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('d', $data['ppm']);
$res = $stmt->execute();
//Temp1 history
$sql = 'INSERT INTO historysensor(typeId,value,min,max,date,sensorId) VALUES(5,?,0,0,NOW(),9)';
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('d', $data['SSID']);
$res = $stmt->execute();
//Temp2 history
$sql = 'INSERT INTO historysensor(typeId,value,min,max,date,sensorId) VALUES(5,?,0,0,NOW(),10)';
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('d', $data['temp']);
$res = $stmt->execute();
//Humidity terre
$sql = 'INSERT INTO historysensor(typeId,value,min,max,date,sensorId) VALUES(5,?,0,0,NOW(),11)';
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('d', $data['humidity']);
$res = $stmt->execute();


if($stmt->error)
    die(json_encode(['status' => 'error', 'msg' => 'mysql error:'.$stmt->error]));
if ($res)
    echo json_encode(['status' => 'ok']);
else
    echo json_encode(['status' => 'error', 'msg' => $mysqli->error]);
?>
