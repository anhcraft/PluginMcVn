<?php 
/**

	    Plugin MCVN
	-=-=-=-=-=-=-=-=-=-
	 @Author: Anh Craft
	  @Copyright 2016
	
**/
session_start();
require '../include/main.php';
require '../include/pluginUtils.php';

function getAllIps($id){
    $conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
    mysqli_set_charset($conn,"utf8");
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $sql = $conn->query("SELECT * FROM `plugins` WHERE `id`='".$id."'");
    if($sql->num_rows > 0):
        while($cr = $sql->fetch_assoc()) {
            $d = $cr["banned_ips"];
			if($d == null || $d == ""){
				return null;
			}
            return $d;
        }
    else:
        return null;
    endif;

    $conn->close();
}

function newIP($id, $ip){
    $conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
    mysqli_set_charset($conn,"utf8");
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $sql = $conn->query("SELECT * FROM `plugins` WHERE `id`='".$id."'");
    if($sql->num_rows > 0):
        while($cr = $sql->fetch_assoc()) {
            $d = $cr["banned_ips"];
			if($d == null || $d == ""){
				$d = "";
			} else {
				$g = explode(" ", $d);
				$result = "";
				for($i = 0; $i < count($g); $i++){
					if($g[$i] == $ip){
						return false;
					}
				}
				$d = " " . $d;
			}
            $d = $ip.$d;
            $update = $conn->query("UPDATE `plugins` SET `banned_ips`='".$d."' WHERE `id`='".$id."'");
            if($update === TRUE):
                return true;
            else:
                return false;
            endif;
        }
    else:
        return false;
    endif;

    $conn->close();
}

function removeIP($id, $ip){
    $conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
    mysqli_set_charset($conn,"utf8");
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $sql = $conn->query("SELECT * FROM `plugins` WHERE `id`='".$id."'");
    if($sql->num_rows > 0):
        while($cr = $sql->fetch_assoc()) {
            $d = $cr["banned_ips"];
			if($d == null || $d == ""){
				return false;
			}
			$g = explode(" ", $d);
            $result = "";
            for($i = 0; $i < count($g); $i++){
				if($g[$i] == $ip){
					continue;
				} else {
					$result .= " " . $g[$i];
				}
            }
            $result = preg_replace('/\ /', '', $result, 1);
            $update = $conn->query("UPDATE `plugins` SET `banned_ips`='".$result."' WHERE `id`='".$id."'");
            if($update === TRUE):
				return true;
            else:
                return false;
            endif;
        }
    else:
        return false;
    endif;

    $conn->close();
}

$id = isset($_GET['id']) ? $_GET['id'] : null;
if($id == null || trim($id) == ""){
	echo "Lỗi: Thiếu id!";
	exit;
}
$id = htmlspecialchars($id);
$action = isset($_GET['action']) ? $_GET['action'] : null;
if($action == null || trim($action) == ""){
	echo "Lỗi: Thiếu action!";
	exit;
}
$action = htmlspecialchars($action);
$value = isset($_GET['value']) ? $_GET['value'] : null;
if($value == null || trim($value) == ""){
	echo "Lỗi: Thiếu value!";
	exit;
}
$value = str_replace(" ", "", trim(htmlspecialchars($value)));
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if($action == "get"){
		$get = getAllIps($id);
		if($get == null){
			echo "Lỗi: Không có ip nào!";
			exit;
		} else{
            $g = explode(" ", $get);
            $result = "";
            for($i = 0; $i < count($g); $i++){
				$result .= "<div class='banned-ip-list-sub' data-customer-name='" . $g[$i] . "'><span>" . $g[$i] . "</span><button class='btn'><i class='fa fa-times' aria-hidden='true'></i></button></div>";
            }
			echo $result;
			exit;
		}
	}if($action == "remove"){
		if (filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
			$remove = removeIP($id,$value);
			if(!$remove){
				echo "Có lỗi xảy ra!";
				exit;
			} else{
				echo "Đã xóa ip ".$value;
				exit;
			}
		} else {
			echo "Vui lòng nhập một IP v4 hợp lệ!";
			exit;
		}
	}if($action == "add"){
		if (filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
			$add = newIP($id,$value);
			if(!$add){
				echo "Có lỗi xảy ra!";
				exit;
			} else{
				echo "Đã thêm ip ".$value;
				exit;
			}
		} else {
			echo "Vui lòng nhập một IP v4 hợp lệ!";
			exit;
		}
	}
}