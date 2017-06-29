<?php 
/**

	    Plugin MCVN
	-=-=-=-=-=-=-=-=-=-
	 @Author: Anh Craft
	  @Copyright 2016
	
**/
session_start();
require_once '../include/main.php';

$ready = true;
$error = "";
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

	if (empty($_POST["opass"])):
		$error .= "Mật khẩu cũ không được để trống!";
		$ready = false;
	else:
		$opass = trim($_POST["opass"]);
	endif;
	
	if (empty($_POST["npass"])):
		$error .= "Mật khẩu mới không được để trống!";
		$ready = false;
	else:
		$npass = trim($_POST["npass"]);
	endif;
	
	if (strlen($opass) < 12 || strlen($opass) > 45 || strlen($npass) < 12 || strlen($npass) > 45){
		$error .= "Mật khẩu phải từ 12 đến 45 kí tự!<br>";
		$ready = false;
	}
	
	if($ready){
		$conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
		mysqli_set_charset($conn,"utf8");
		$sql = $conn->query("SELECT * FROM `account` WHERE `user`='".getLogger()."'");
		if($sql->num_rows > 0){
			while($r = $sql->fetch_assoc()) {
				if($r["pass"] == md5($opass)){
					$cp = $conn->query("UPDATE `account` SET `pass`='".md5($npass)."' WHERE `user`='".getLogger()."'");
					if($cp === TRUE):
						session_destroy();
						$error = "changed";
					else:
						$error .= "Xảy ra lỗi trong khi đổi mật khẩu!<br>";
					endif;
				} else {
					$error .= "Mật khẩu cũ không đúng!<br>";
				}
			}
		}
		$conn->close();
	}
	if($error == "changed"){
		echo $error;
	} else {
		echo '<div class="alert danger">' . $error . '</div>';
	}
} else {
	header("Location: ". regurl("@p://@d/error.php?c=403"));
}
?>