<?php
/**

	    Plugin MCVN
	-=-=-=-=-=-=-=-=-=-
	 @Author: Anh Craft
	  @Copyright 2016
	
**/
session_start();
require './include/main.php';

if(!isLogin()){
	header("Location: ". regurl("@p://@d/"));
}
$redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : regurl('@p://@d/');

$conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
mysqli_set_charset($conn,"utf8");
$hasinu = $conn->query("SELECT * FROM `account` WHERE `user`='". getLogger() ."'");

if ($hasinu->num_rows > 0):
	$sql = "UPDATE `account` SET `lastip`='". getIP() ."' WHERE `user`='". getLogger() ."'";
	if ($conn->query($sql) === TRUE):
		unset($_SESSION[$GLOBALS['session_login_username']]);
		header("Location: ". $redirect);
	else:
		header("Location: ". $redirect);
	endif;
else:
	header("Location: ". $redirect);
endif;

$conn->close();
?>