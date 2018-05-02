<?php
	session_start();
	require_once('../php/Database.php');
	require_once('../php/RecupConf.php');
	if(!isset($_SESSION['email']) || empty($_SESSION['email']))
		header('Location: login.php');
	
	$conf = new RecupConf();
	$database = new Database($conf->getDbName(), $conf->getDbLogin(), $conf->getDbPasswd());
	$user_id = $database->select("user", "id", "email", $_SESSION['email'])[0];
	
	if(!empty($_POST['message']))
		$database->insertInto("note (userId, note)", $user_id, utf8_decode($_POST['message']));
	header('Location: index.php');
?>