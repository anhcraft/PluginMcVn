<?php 
/**

	    Plugin MCVN
	-=-=-=-=-=-=-=-=-=-
	 @Author: Anh Craft
	  @Copyright 2016
	
**/
session_start();
require '../include/main.php';

if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	if(isLogin()){
		$id = isset($_GET['id']) ? $_GET['id'] : null;
		if($id == null){
			echo "0";
		} else {
			/*********************************/
			$conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
			mysqli_set_charset($conn,"utf8");
			date_default_timezone_set('Asia/Ho_Chi_Minh');
			$sql = $conn->query("SELECT * FROM `plugins` WHERE `id`='".$id."'");
			if($sql->num_rows > 0):
				while($cr = $sql->fetch_assoc()) {
					$d = $cr["total_download"];
					$nd = $conn->query("UPDATE `plugins` SET `total_download`='".(((int) $d) + 1)."' WHERE `id`='".$id."'");
					if($nd === TRUE):
						echo "1";
					else:
						echo "0";
					endif;
				}
			else:
				echo "0";	
			endif;

			$conn->close();
		}
	}
}
?>