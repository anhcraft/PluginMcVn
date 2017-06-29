<?php
/**

	    Plugin MCVN
	-=-=-=-=-=-=-=-=-=-
	 @Author: Anh Craft
	  @Copyright 2016
	
**/
require '../include/options.php';
header("Content-Type: text/plain");

function getListIPS($id){
	$list = array();
    $conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
    mysqli_set_charset($conn,"utf8");
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $sql = $conn->query("SELECT * FROM `plugins` WHERE `id`='".$id."'");
    if($sql->num_rows > 0){
        while($cr = $sql->fetch_assoc()) {
            $d = $cr["whitelist"];
			if($d != null && $d != ""){
				$g = explode("#", $d);
				for($i = 0; $i < count($g); $i++){
					$r = explode(":", $g[$i]);
					$user = $r[0];
					if($r[1] != ""){
						// chuy?n dãy list ip sang d?ng array 
						$ips = explode(";", $r[1]);
						$list = array_merge($list, $ips);
					}
				}
			}
        }
	}

    $conn->close();
    return $list;
}

function getBannedIPS($id){
	$list = array();
    $conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
    mysqli_set_charset($conn,"utf8");
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $sql = $conn->query("SELECT * FROM `plugins` WHERE `id`='".$id."'");
    if($sql->num_rows > 0){
        while($cr = $sql->fetch_assoc()) {
            $d = $cr["banned_ips"];
			if($d != null && $d != ""){
				$list = explode(" ", $d);
			}
        }
	}

    $conn->close();
    return $list;
}

function getRealIPAddress(){  
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}


$id = isset($_GET['id']) ? $_GET['id'] : null;
$version = isset($_GET['version']) ? $_GET['version'] : null;
if($id == null || $version == null){
	echo "2/null";
} else {
	$conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
	mysqli_set_charset($conn,"utf8");
	date_default_timezone_set('Asia/Ho_Chi_Minh');
	$sql = $conn->query("SELECT * FROM `plugins` WHERE `id`='".$id."'");
	if($sql->num_rows > 0):
		while($cr = $sql->fetch_assoc()) {
			$d = $cr["version"];
			$p = $cr["pre"];
			if($p == null){
				if($d == $version){
					echo "0/".$d;
				} else {
					echo "1/".$d;	
				}
			} else {
				$lip = getListIPS($id);
				if (is_array($lip)) {
					if(in_array(getRealIPAddress(),$lip)){
						$bbip = getBannedIPS($id);
						if(is_array($bbip) && in_array(getRealIPAddress(),$bbip)){
							echo "4/".$d;
						} else {
							if($d == $version){
								echo "0/".$d;
							} else {
								echo "1/".$d;	
							}
						}
					} else {
						echo "4/".$d;
					}	
				} else {
					echo "4/".$d;
				}	
			}
		}
	else:
		echo "3/null";	
	endif;
	$conn->close();
}
?>