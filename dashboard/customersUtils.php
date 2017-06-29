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

function getCustomers($id){
    $conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
    mysqli_set_charset($conn,"utf8");
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $sql = $conn->query("SELECT * FROM `plugins` WHERE `id`='".$id."'");
    if($sql->num_rows > 0):
        while($cr = $sql->fetch_assoc()) {
            $d = $cr["customers"];
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

function newCustomer($id, $user){
	if(getMemberID($user) == "null"){
		return false;
	}
    $conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
    mysqli_set_charset($conn,"utf8");
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $sql = $conn->query("SELECT * FROM `plugins` WHERE `id`='".$id."'");
    if($sql->num_rows > 0):
        while($cr = $sql->fetch_assoc()) {
            $d = $cr["customers"];
			if($d == null || $d == ""){
				$d = "";
			} else {
				$g = explode(" ", $d);
				$result = "";
				for($i = 0; $i < count($g); $i++){
					if($g[$i] == $user){
						return false;
					}
				}
				$d = " " . $d;
			}
            $d = $user.$d;
            $update = $conn->query("UPDATE `plugins` SET `customers`='".$d."' WHERE `id`='".$id."'");
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

function removeCustomer($id, $user){
	if(getMemberID($user) == "null"){
		return false;
	}
    $conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
    mysqli_set_charset($conn,"utf8");
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $sql = $conn->query("SELECT * FROM `plugins` WHERE `id`='".$id."'");
    if($sql->num_rows > 0):
        while($cr = $sql->fetch_assoc()) {
            $d = $cr["customers"];
			if($d == null || $d == ""){
				return false;
			}
			$g = explode(" ", $d);
            $result = "";
            for($i = 0; $i < count($g); $i++){
				if($g[$i] == $user){
					continue;
				} else {
					$result .= " " . $g[$i];
				}
            }
            $result = preg_replace('/\ /', '', $result, 1);
            $update = $conn->query("UPDATE `plugins` SET `customers`='".$result."' WHERE `id`='".$id."'");
            if($update === TRUE):
					$r = "";
					$z = getWhiteList($id);
						$t = "";
						for($f = 0; $f < count($z); $f++){
							$b = explode(":", $z[$f]);
							$usera = $b[0];
							if($usera == $user){
								continue;
							} else {
								$t .= "#".$usera.":".$b[1];
							}
						}
						$r = $t;
							$r = preg_replace('/\#/', '', $r, 1);

					
					$update1 = $conn->query("UPDATE `plugins` SET `whitelist`='".$r."' WHERE `id`='".$id."'");
					if ($update1 === TRUE):
						return true;
					else:
						return false;
					endif;
			
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
		$get = getCustomers($id);
		if($get == null){
			echo "Lỗi: Không có người mua nào!";
			exit;
		} else{
            $g = explode(" ", $get);
            $result = "";
            for($i = 0; $i < count($g); $i++){
				$result .= "<div class='customer-list-sub' data-customer-name='" . $g[$i] . "'><span>" . $g[$i] . "</span><button class='btn'><i class='fa fa-times' aria-hidden='true'></i></button></div>";
            }
			echo $result;
			exit;
		}
	}if($action == "remove"){
		$remove = removeCustomer($id,$value);
		if(!$remove){
			echo "Có lỗi xảy ra!";
			exit;
		} else{
			echo "Đã xóa người chơi ".$value;
			exit;
		}
	}if($action == "add"){
		$add = newCustomer($id,$value);
		if(!$add){
			echo "Có lỗi xảy ra!";
			exit;
		} else{
			echo "Đã thêm người chơi ".$value;
			exit;
		}
	}
}