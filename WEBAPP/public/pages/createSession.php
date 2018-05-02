<?php

session_start();

?>

<?php

require_once('./../php/RecupConf.php');
require_once('./../php/Database.php');

// Get all info from conf file
$conf = new RecupConf();

$email = $_GET['email'];

$_SESSION['email'] = $email;

?>
