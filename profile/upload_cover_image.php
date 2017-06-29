<?php 
/**

	    Plugin MCVN
	-=-=-=-=-=-=-=-=-=-
	 @Author: Anh Craft
	  @Copyright 2016
	
**/
session_start();
require_once '../include/main.php';

function getExtension($str) {
	$i = strrpos($str,".");
	if (!$i) { return ""; }
	$l = strlen($str) - $i;
	$ext = substr($str,$i+1,$l);
	return $ext;
}

function getMaxSize(){
	if(isDev()){
		return 2000;
	} else {
		return 2000;
	}
}

if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	$image = $_FILES['cover_image']['name'];
	if ($image != null && !isset($_POST['clear_cover_image'])){
		$filename = stripslashes($_FILES['cover_image']['name']);
		$extension = getExtension($filename);
		$extension = strtolower($extension);
		
		if ($extension !== "png" && $extension !== "jpg" && $extension !== "gif"):
			echo '<div class="alert danger">Ảnh không đúng định dạng .png, .jpg hoặc .gif !</div>';
		else:
			$size = filesize($_FILES['cover_image']['tmp_name']);
			if (getMaxSize()*1024 < $size):
				echo '<div class="alert danger">Ảnh có dung lượng quá lớn (tối đa '.getMaxSize().' KB) !</div>';
			else:
				$url = "http://pluginmcvn.cf/include/user_cover_images/".getLogger().".".$extension;
				$path = "../include/user_cover_images/".getLogger().".".$extension;
				$conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
				mysqli_set_charset($conn,"utf8");
				$sql = $conn->query("UPDATE `account` SET `coverimg`='".$url."' WHERE `user`='".getLogger()."'");
				if ($sql === TRUE):	
					if (file_exists($path)) {
						unlink($path);
					}
					move_uploaded_file($_FILES['cover_image']['tmp_name'], $path);
					
					echo 'uploaded';
				else:
					echo '<div class="alert danger">Xảy ra lỗi: ' . $conn->error . '</div>';
				endif;
				$conn->close();
			endif;
		endif;
	} else if(isset($_POST['clear_cover_image'])) {
		$conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
		mysqli_set_charset($conn,"utf8");
		$sql = $conn->query("UPDATE `account` SET `coverimg`='' WHERE `user`='".getLogger()."'");
		if ($sql === TRUE):	
			$path1 = "../include/user_cover_images/".getLogger().".png";
			if (file_exists($path1)) {unlink($path1);}
			$path2 = "../include/user_cover_images/".getLogger().".jpg";
			if (file_exists($path2)) {unlink($path2);}
			$path3 = "../include/user_cover_images/".getLogger().".gif";
			if (file_exists($path3)) {unlink($path3);}
			echo 'cleared';
		else:
			echo '<div class="alert danger">Xảy ra lỗi: ' . $conn->error . '</div>';
		endif;
		$conn->close();
	}
} else {
	header("Location: ". regurl("@p://@d/error.php?c=403"));
}
?>