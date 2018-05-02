<?php
/**
 * Envoie un SMS puis affiche la liste des SMS en attente d’envoi.
 * 
 * Rendez-vous sur https://api.ovh.com/createToken/index.cgi?GET=/sms&GET=/sms/*&PUT=/sms/*&DELETE=/sms/*&POST=/sms/*
 * pour générer les clés d'accès API pour:
 *
 * GET /sms
 * GET /sms/*
 * POST /sms/*
 * PUT /sms/*
 * DELETE /sms/*
 */
        session_start();
        require_once('../php/Database.php');
        require_once('../php/RecupConf.php');
        if(!isset($_SESSION['email']) || empty($_SESSION['email']))
                header('Location: login.php');

require __DIR__ . '/../vendor/autoload.php';
use \Ovh\Api;


$endpoint = 'ovh-eu';
$applicationKey = "64N7JNRx3zqvRkxm";
$applicationSecret = "Kp0NhemHFq0XYKCkLUGrrIiDYCU85QJ2";
$consumer_key = "UbYAgynv3RPhPMp7hOyOquqMK2H5SRhm";

$conn = new Api(    $applicationKey,
                    $applicationSecret,
                    $endpoint,
                    $consumer_key);

$smsServices = $conn->get('/sms/');
foreach ($smsServices as $smsService) {

    print_r($smsService);
}

$content = (object) array(
	"charset"=> "UTF-8",
	"class"=> "phoneDisplay",
	"coding"=> "7bit",
	"message"=> "Bonjour les SMS OVH par api.ovh.com",
	"noStopClause"=> false,
	"priority"=> "high",
	"receivers"=> [ "+33658154699" ],
	"senderForResponse"=> true,
	"validityPeriod"=> 2880
);

//$resultPostJob = $conn->post('/sms/'. $smsServices[0] . '/jobs/', $content);

//print_r($resultPostJob);

//$smsJobs = $conn->get('/sms/'. $smsServices[0] . '/jobs/');
//print_r($smsJobs);
echo "fini";
?>
