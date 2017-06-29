<?php 
function getMaxLimitIP($id){
	$conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
    mysqli_set_charset($conn,"utf8");
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $sql = $conn->query("SELECT * FROM `plugins` WHERE `id`='".$id."'");
    if($sql->num_rows > 0):
        while($cr = $sql->fetch_assoc()) {
            $d = $cr["limit_ips"];
			if($d != null && $d != ""){
				return ((int) $d);
			} else {
				return 1;
			}
        }
    else:
        return 1;
    endif;

    $conn->close();
}

function getListIP($id,$u,$l){
	$list = null;
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
					// chuyển dãy list ip sang dạng array 
					$ips = explode(";", $r[1]);						
					$ips = array_slice($ips, 0, $l, true);
					if($user == $u){
						$list = $ips;
						break;
					}
				}
			}
        }
	}

    $conn->close();
    return $list;
}

function getWhiteList($id){
	$list = null;
    $conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
    mysqli_set_charset($conn,"utf8");
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $sql = $conn->query("SELECT * FROM `plugins` WHERE `id`='".$id."'");
    if($sql->num_rows > 0){
        while($cr = $sql->fetch_assoc()) {
            $d = $cr["whitelist"];
			if($d != null && $d != ""){
				$list = explode("#", $d);
			}
        }
	}

    $conn->close();
    return $list;
}

function newListIP($id, $user){
	if(getMemberID($user) == "null"){
		return false;
	}
    $conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
    mysqli_set_charset($conn,"utf8");
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $sql = $conn->query("SELECT * FROM `plugins` WHERE `id`='".$id."'");
    if($sql->num_rows > 0):
        while($cr = $sql->fetch_assoc()) {
            $d = $cr["whitelist"];
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
            $update = $conn->query("UPDATE `plugins` SET `whitelist`='".$d."' WHERE `id`='".$id."'");
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

function removeListIP($id, $user){
	if(getMemberID($user) == "null"){
		return false;
	}
    $conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
    mysqli_set_charset($conn,"utf8");
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $sql = $conn->query("SELECT * FROM `plugins` WHERE `id`='".$id."'");
    if($sql->num_rows > 0):
        while($cr = $sql->fetch_assoc()) {
            $d = $cr["whitelist"];
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
            $update = $conn->query("UPDATE `plugins` SET `whitelist`='".$result."' WHERE `id`='".$id."'");
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

function isPlayerBought($id,$user){
	$r = false;
	$conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
    mysqli_set_charset($conn,"utf8");
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $sql = $conn->query("SELECT * FROM `plugins` WHERE `id`='".$id."'");
	if($sql->num_rows > 0){
		while($cr = $sql->fetch_assoc()) {
			$d = $cr["customers"];
			if($d == null || $d == ""){
				$d = "";
			} else {
				$g = explode(" ", $d);
				for($i = 0; $i < count($g); $i++){
					if($g[$i] == $user){
						$r = true;
						break;
					}
				}
			}
		}
	}
	return $r;
}

function toCategory($num){
	$num = (int) $num;
	switch ($num) {
		case 1:
			return "Công cụ và tiện ích";
			break;
		case 2:
			return "Vui vẻ";
			break;
		case 3:
			return "Quản lý world";
			break;
		case 4:
			return "Kinh tế";
			break;
		case 5:
			return "Chế độ chơi";
			break;
		case 6:
			return "Thư viện & API";
			break;
		default:
			return "null";
	}
}

function generateRandomString($length = 12) {
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function hasPluginID($id){
	$a = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
	mysqli_set_charset($a,"utf8");
	$b = $a->query("SELECT * FROM `plugins` WHERE `id`='". $id ."'");
	if($b->num_rows > 0){
		return true;
	} else {
		return false;
	}
	$a->close();
}

function generatorNewPluginID(){
	$id = "";
	$x = 0;
	$y = 1;
	while($x <= $y) {	
		$id = generateRandomString(12);
		if(!hasPluginID($id)){
			break;
		} else {
			$y += 1;
			continue;
		}
		$x++;
	}
	return $id;
}

function getAllPluginForAuthor($aut, $menu = true, $limit = 0){
		$prit = "";
	
	$conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
	mysqli_set_charset($conn,"utf8");
	$sql = "SELECT * FROM `plugins` WHERE `author`='".$aut."' ORDER BY `date` DESC";
	$res = $conn->query($sql);
	
	$m = "";
	$s = 0;
	if ($res->num_rows > 0):
		while($aif = $res->fetch_assoc()) {
			$s += 1;
			if(0 < $limit && $limit < $s){
				continue;
			}
			$z = '<div class="plugin-list-description"><i class="color-warning fa fa-star" aria-hidden="true"></i>&nbsp;&nbsp;Chế độ Premium</div>';
			if($aif["pre"] == null){
				$z = '<div class="plugin-list-description"><i class="fa fa-star" style="color:#555;" aria-hidden="true"></i>&nbsp;&nbsp;Chế độ Miễn Phí</div>';
			}
			
			if($menu && $aif["frgpl"] != "1"){
				$m = '<br>
	<a class="btn info" href="http://pluginmcvn.cf/dashboard/do.php?action=update_plugin&id='.$aif["id"].'">Cập nhật phiên bản</a>&nbsp;&nbsp;&nbsp;
	<a class="btn warning" href="http://pluginmcvn.cf/dashboard/do.php?action=edit_plugin&id='.$aif["id"].'">Sửa plugin</a>&nbsp;&nbsp;&nbsp;
	<a class="btn success" href="http://pluginmcvn.cf/dashboard/do.php?action=changemode_plugin&id='.$aif["id"].'">Thay chế độ</a>&nbsp;&nbsp;&nbsp;
	<a class="btn danger" href="http://pluginmcvn.cf/dashboard/do.php?action=remove_plugin&id='.$aif["id"].'">Xóa plugin</a>&nbsp;&nbsp;&nbsp;
	<a class="btn" href="http://pluginmcvn.cf/dashboard/do.php?action=banned_ips&id='.$aif["id"].'" style="background-color: #4c4c4c;color: #fff !important">Danh sách đen</a>
	';
			}
            if($menu && $aif["frgpl"] == "1"){
                $m = '<br>
	<a class="btn info" href="http://pluginmcvn.cf/dashboard/do.php?action=update_plugin&id='.$aif["id"].'">Cập nhật phiên bản</a>&nbsp;&nbsp;&nbsp;
	<a class="btn warning" href="http://pluginmcvn.cf/dashboard/do.php?action=edit_plugin&id='.$aif["id"].'">Sửa plugin</a>&nbsp;&nbsp;&nbsp;
	<a class="btn danger" href="http://pluginmcvn.cf/dashboard/do.php?action=remove_plugin&id='.$aif["id"].'">Xóa plugin</a>
	';
            }
			if($aif["pre"] != null && $menu){
				$m .= '<br>
<a class="btn primary" href="http://pluginmcvn.cf/dashboard/do.php?action=customer_manage&id='.$aif["id"].'">Quản lý người mua</a>
				';
			}
            $plnc = "";
			if($aif["frgpl"] == "1"){
                $plnc = ' <span style="background-color: #000;color: #fff;padding: 6px 12px;border-radius: 10px;margin-left: 5px">Nước ngoài</span>';
            }
			$prit .= '
<div class="plugin-list-sub">
	<div class="plugin-list-title"><a href="http://pluginmcvn.cf/plugin/' . $aif["id"] . '">' . $aif["title"] . '&nbsp;&nbsp;&nbsp;<span>'.$aif["version"].'</span></a> '.$plnc.'</div>
	<div>
		<div style="float: left;padding-right: 20px;">
			<img src="'.$aif["icon"].'" style="width: 100px;" class="plugin-icon">
		</div>
		<div class="plugin-list-date"><span><i class="fa fa-calendar color-success" aria-hidden="true"></i>&nbsp;&nbsp;Ngày đăng:</span>&nbsp;' . $aif["date"] . '</div>
		<div class="plugin-list-category"><span><i class="fa fa-bookmark color-danger" aria-hidden="true"></i>&nbsp;&nbsp;Danh mục:</span>&nbsp;' .toCategory($aif["category"]). '</div>
		<div class="plugin-list-downloaded"><span><i class="fa fa-cloud-download color-info" aria-hidden="true"></i>&nbsp;&nbsp;Đã tải:</span>&nbsp;' . $aif["total_download"] . '</div>
		<div class="plugin-list-description"><i class="fa fa-pencil color-primary" aria-hidden="true"></i>&nbsp;&nbsp;' . $aif["description"] . '</div>
		'.$z.'
	</div>
	<style>
	.show-plugin-menu i.fa {
    border: 1px #aaa solid;
    padding: 2px 5px;
    color: #333;
    border-radius: 100%;
}

.plugin-menu {
    display: none;
}

.plugin-menu-show {
    display: block !important;
}
	</style>
	<a href="javascript:void()" class="show-plugin-menu"><i class="fa fa-arrow-down" aria-hidden="true"></i>&nbsp;&nbsp;Hiện menu</a>
		<div class="plugin-menu">
	'.$m.'
	</div>
	
	<script>
	$(document).ready(function(){
		$(".show-plugin-menu").click(function(){
			$(this).parent().children(".plugin-menu").toggleClass("plugin-menu-show");
		});
	});
	</script>
</div>
			';
		}
	else:
		$prit = "<p>Không có plugin nào!</p>";
	endif;	
	$conn->close();	
	return $prit;
}

function getVote($id, $user){
    $conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
    mysqli_set_charset($conn,"utf8");
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $sql = $conn->query("SELECT * FROM `vote_plugin` WHERE `user`='".$user."'");
    if($sql->num_rows > 0):
        while($cr = $sql->fetch_assoc()) {
            $d = $cr["data"];
            $allPlugin = explode("#", $d);
            $result = "";
            for($i = 0; $i < count($allPlugin); $i++){
                $info = explode(",",$allPlugin[$i]);
                if($info[0] == $id){
                    $result = $info[1];
                    break;
                }
            }
            return $result;
        }
    else:
        return null;
    endif;

    $conn->close();
}

function newVote($id, $user, $num){
    $conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
    mysqli_set_charset($conn,"utf8");
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $sql = $conn->query("SELECT * FROM `vote_plugin` WHERE `user`='".$user."'");
    if($sql->num_rows > 0):
        while($cr = $sql->fetch_assoc()) {
            $d = $cr["data"];
            // [555,5]#333,2#454,1
            $allPlugin = explode("#", $d);
            $result = "";
            for($i = 0; $i < count($allPlugin); $i++){
                $info = explode(",",$allPlugin[$i]);
                // nếu có rùi
                if($info[0] == $id){
                    continue;
                }
                $result .= "#".$info[0].",".$info[1];
            }
            $result .= "#".$id.",".$num;
            $result = preg_replace('/\#/', '', $result, 1);
            $update = $conn->query("UPDATE `vote_plugin` SET `data`='".$result."' WHERE `user`='".$user."'");
            if($update === TRUE):
                return true;
            else:
                return false;
            endif;
        }
    else:
        $data = $id . "," . $num;
        $insert = $conn->query("INSERT INTO `vote_plugin`(`user`, `data`) VALUES ('".$user."','".$data."')");
        if($insert === TRUE):
            return true;
        else:
            return false;
        endif;
    endif;

    $conn->close();
}
?>