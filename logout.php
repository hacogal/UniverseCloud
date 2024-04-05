<?php
session_start();


//unset all session variables
$_SESSION=array();

session_destroy();

header("Location:login.php");
exit;

?>
