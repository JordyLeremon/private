<?php

header('Content-type: text/plain; charset=utf8', true);

//never echo beacuase don't works!!!!
//echo"************************************** \n";
//$tag="SERVER_PROTOCOL";
//echo "$_SERVER[$tag] \n";
//$tag="HTTP_X_ESP8266_STA_MAC";
//echo "$_SERVER[$tag] \n";
//$tag="HTTP_X_ESP8266_AP_MAC";
//echo "$_SERVER[$tag] \n";
//$tag="HTTP_X_ESP8266_FREE_SPACE";
//echo "$_SERVER[$tag] \n";
//$tag="HTTP_X_ESP8266_SDK_VERSION";
//echo "$_SERVER[$tag] \n";
//$tag="HTTP_X_ESP8266_VERSION";
//echo "$_SERVER[$tag] \n";
//$tag="HTTP_USER_AGENT";
//echo "$_SERVER[$tag] \n";
//echo"**************************************";


function check_header($name, $value = false) {
	if(!isset($_SERVER[$name])) {
		return false;
	}
	if($value && $_SERVER[$name] != $value) {
		return false;
	}
	return true;
}

function sendFile($path) {
	header($_SERVER["SERVER_PROTOCOL"].' 200 OK', true, 200);
	header('Content-Type: application/octet-stream', true);
	header('Content-Disposition: attachment; filename='.basename($path));
	header('Content-Length: '.filesize($path), true);
	header('x-MD5: '.md5_file($path), true);
	readfile($path);
}

if(!check_header('HTTP_USER_AGENT', 'ESP8266-http-Update')) {
	header($_SERVER["SERVER_PROTOCOL"].' 403 Forbidden', true, 403);
	echo "only for ESP8266 updater!\n";
//	exit();
}

if(
	!check_header('HTTP_X_ESP8266_STA_MAC') || 
	!check_header('HTTP_X_ESP8266_AP_MAC') ||
	!check_header('HTTP_X_ESP8266_FREE_SPACE') ||
	!check_header('HTTP_X_ESP8266_SKETCH_SIZE') ||
//	!check_header('HTTP_X_ESP8266_SKETCH_MD5') 
	!check_header('HTTP_X_ESP8266_CHIP_SIZE') ||
	!check_header('HTTP_X_ESP8266_SDK_VERSION')
) {
	header($_SERVER["SERVER_PROTOCOL"].' 403 Forbidden', true, 403);
	echo "only for ESP8266 updater! (header)\n";
//	exit();
}

$db = array(
	"5C:CF:7F:A3:2C:0A" => "_54287545airV3",
	"5C:CF:7F:D9:50:F6" => "TH_paul_1_4",
	"5C:CF:7F:FA:2F:17" => "_57489547airV3",
	"5C:CF:7F:FA:31:BE" => "_58964785airV1",
	"5C:CF:7F:FA:2E:F2" => "_59514587eauV1",
	"5C:CF:7F:FA:A8:6C" => "_52365825eauV1",
	"5C:CF:7F:D9:4B:44" => "_53215245eauV1",
	"5C:CF:7F:FA:A9:1D" => "_55214587airV3",
	"5C:CF:7F:A3:2F:EC" => "_51245852eauV1"
);

//if(!isset($db[$_SERVER['HTTP_X_ESP8266_STA_MAC']])) {
//	header($_SERVER["SERVER_PROTOCOL"].' 500 ESP MAC not configured for updates', true, 500);
//}

$localBinary = "./".$db[$_SERVER['HTTP_X_ESP8266_STA_MAC']].".bin";
// Check if version has been set and does not match, if not, check if
// MD5 hash between local binary and ESP8266 binary do not match if not.
// then no update has been found.
//$tag="HTTP_X_ESP8266_STA_MAC";
//$tag="5C:CF:7F:D9:50:F6";
//echo "$db[$tag]";
//sendFile("./test.bin");
if((!check_header('HTTP_X_ESP8266_SDK_VERSION') || $db[$_SERVER['HTTP_X_ESP8266_STA_MAC']] != $_SERVER['HTTP_X_ESP8266_VERSION'])) {
	//echo "send file \n";
	sendFile($localBinary);

} else {
	header($_SERVER["SERVER_PROTOCOL"].' 304 Not Modified', true, 304);
}

header($_SERVER["SERVER_PROTOCOL"].' 500 no version for ESP MAC', true, 500);

?>
