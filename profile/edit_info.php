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
	if (empty($_POST["name"])):
		$name = "";
	else:
		$name = trim(htmlspecialchars($_POST["name"]));
	endif;
	
	if (empty($_POST["email"])):
		$error .= "Email không được để trống!";
		$ready = false;
	else:
		$email = trim(htmlspecialchars($_POST["email"]));
	endif;
	
	if (empty($_POST["birth-day"])):
		$birth_day = "";
	else:
		$birth_day = $_POST["birth-day"];
	endif;
	
	if (empty($_POST["birth-month"])):
		$birth_month = "";
	else:
		$birth_month = $_POST["birth-month"];
	endif;
	
	if (empty($_POST["birth-year"])):
		$birth_year = "";
	else:
		$birth_year = $_POST["birth-year"];
	endif;
	
	if (empty($_POST["gender"])):
		$gender = "";
	else:
		$gender = $_POST["gender"];
	endif;
	
	if (empty($_POST["introduce"])):
		$introduce = "";
	else:
		$introduce = reword($_POST["introduce"]);
	endif;
	
	if (empty($_POST["website"])):
		$website = "";
	else:
		$website = trim(htmlspecialchars($_POST["website"]));
	endif;
	
	if (empty($_POST["facebook"])):
		$facebook = "";
	else:
		$facebook = trim(htmlspecialchars($_POST["facebook"]));
	endif;
	
	if (empty($_POST["google"])):
		$google = "";
	else:
		$google = trim(htmlspecialchars($_POST["google"]));
	endif;
	
	if (empty($_POST["twitter"])):
		$twitter = "";
	else:
		$twitter = trim(htmlspecialchars($_POST["twitter"]));
	endif;
	
	if (empty($_POST["minecraftvn"])):
		$minecraftvn = "";
	else:
		$minecraftvn = trim(htmlspecialchars($_POST["minecraftvn"]));
	endif;
	
	if (empty($_POST["spigotmc"])):
		$spigotmc = "";
	else:
		$spigotmc = trim(htmlspecialchars($_POST["spigotmc"]));
	endif;
	
	if (empty($_POST["github"])):
		$github = "";
	else:
		$github = trim(htmlspecialchars($_POST["github"]));
	endif;
	
	if (strlen($name) < 0 || strlen($name) > 30){
		$error .= "Tên phải từ 0 đến 30 kí tự!<br>";
		$ready = false;
	}
	if (strlen($introduce) < 0 || strlen($introduce) > 200){
		$error .= "Giới thiệu bản thân phải từ 0 đến 200 kí tự!<br>";
		$ready = false;
	}
	if (strlen($email) < 8 || strlen($email) > 45){
		$error .= "Email phải từ 8 đến 45 kí tự!<br>";
		$ready = false;
	}
	
	if($ready){
		$conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
		mysqli_set_charset($conn,"utf8");
		$sql = $conn->query("SELECT * FROM `account` WHERE `email`='".$email."'");
		$can = true;
		if($sql->num_rows > 0){
			while($r = $sql->fetch_assoc()) {
				if($r["user"] != getLogger()){
					$error .= "Email đã có người sử dụng!<br>";
					$can = false;
				}
			}
		} 

		if($can){
			$birth = $birth_day . '/' . $birth_month . '/'. $birth_year;
			$update = $conn->query("UPDATE `account` SET `name`='".$name."',`email`='".$email."',`birth`='".$birth."',`gender`='".$gender."',`introduce`='".$introduce."',`google`='".$google."',`minecraftvn`='".$minecraftvn."',`facebook`='".$facebook."',`spigotmc`='".$spigotmc."',`twitter`='".$twitter."',`github`='".$github."',`website`='".$website."' WHERE `user`='".getLogger()."'");
			if($update === TRUE):
				$error = "edited";
			else:
				$error .= "Xảy ra lỗi trong khi cập nhật!<br>";
			endif;
		}
		$conn->close();
	}
	if($error == "edited"){
		echo $error;
	} else {
		echo '<div class="alert danger">' . $error . '</div>';
	}
} else {
	header("Location: ". regurl("@p://@d/error.php?c=403"));
}
?>