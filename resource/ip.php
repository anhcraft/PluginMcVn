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

if(!isLogin()){
        header("Location: ". regurl("@p://@d/error.php?c=require_login"));
    }
	
class Setup {
	var $title = "Plugin MCVN - Thay ip";
	var $robots = "{private}";
	var $description = "";
	var $importCSS = array();
	var $importJS = array();
	var $importHTML = "<style>.pluginmcvn-container{padding: 100px 40px !important}body {
    overflow-x: hidden;
}form input[type=text], form input[type=number] {
    display: block;
    width: 100%;
    margin-top: 10px;
    margin-bottom: 20px;
    border: 2px #03a6ff solid;
    padding: 8px 16px;
    border-radius: 8px;
    cursor: text;
}</style>";
	var $googleSiteVerification = "kNkDxfzm97qD6sJJbkDGt9S36SuhMFiVBKPyjeS0t10";
	var $googleAnalyticsCode = "UA-86116936-1";
}

$setup = new Setup;
$base = new PluginMCVN;
$base->newHeader(true);
$base->registerHeader(
	$setup->title,
	$setup->robots,
	$setup->description,
	$setup->googleSiteVerification,
	$setup->importCSS,
	$setup->importHTML
);
$base->createMenu();


$id = isset($_GET['id']) ? $_GET['id'] : null;
if($id == null){
	header("Location: ". regurl("@p://@d/"));
}

/*********************************/
$title = "";
$check = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
mysqli_set_charset($check,"utf8");
date_default_timezone_set('Asia/Ho_Chi_Minh');
$checksql = $check->query("SELECT * FROM `plugins` WHERE `id`='".$id."'");
if($checksql->num_rows > 0):
	while($cr = $checksql->fetch_assoc()) {
		$d = $cr["customers"];
		if($d == null || $d == ""){
			$d = "";
		}
		$g = explode(" ", $d);
		$sc = false;
		for($i = 0; $i < count($g); $i++){
			if($g[$i] == getLogger()){
				$sc = true;
				break;
			}
		}
		if(!$sc){
			header("Location: ". regurl("@p://@d/"));
		}
		$title = $cr["title"];
    }
else:
	header("Location: ". regurl("@p://@d/"));
endif;

$check->close();

/*********************************/
$ready = true;
$error = null;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (empty($_POST["ip"]) || $_POST["ip"] == null || count($_POST["ip"]) == 0):
		$error .= "IP không được để trống!<br>";
		$ready = false;
	else:
		$ip = $_POST["ip"];
	endif;
	
	if($ready){
		$x = "";
		for($i = 0; $i < count($ip); $i++){
			$x .= ";" . $ip[$i];
		}
		$x = preg_replace('/\;/', '', $x, 1);
		$x = getLogger().":".$x;
		
		$r = "";
		$z = getWhiteList($id);
		if($z == null){
			$r = $x;
		} else {	
			$t = "";
			for($f = 0; $f < count($z); $f++){
				$b = explode(":", $z[$f]);
				$user = $b[0];
				if($user == getLogger()){
					continue;
				} else {
					$t .= "#".$user.":".$b[1];
				}
			}
			$r = $x.$t;
		}
		
		$conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
		mysqli_set_charset($conn,"utf8");
		$update = $conn->query("UPDATE `plugins` SET `whitelist`='".$r."' WHERE `id`='".$id."'");
		if ($update === TRUE):
			$error = "<script>window.location.href = 'http://pluginmcvn.cf/plugin/".$id."';</script><meta http-equiv='refresh' content='0; url=http://pluginmcvn.cf/plugin/".$id."'>";
		else:
			$error = "Lỗi: " . $conn->error;
		endif;
		$conn->close();
	}
}
/*********************************/
?>
<div class="container pluginmcvn-container">
	<h3 id="pluginmcvn-bigtitle" class="center">Thay ip cho plugin <?php echo $title; ?></h3>
	<br>
	<?php 
	if($error != null){
		echo "<div class='alert danger'>".$error."</div>";
	}
	?>
	<br>
	<form method="post" action="">
		<b>IP của máy tính, VPS, Server bạn (khác 192.x.x.x)</b>
		<?php
$m = getMaxLimitIP($id);
$ips = getListIP($id,getLogger(),$m);
for($i = 0; $i < $m; $i++){
	if($ips == null || count($ips) <= $i) {
		echo '<input type="text" name="ip[]" value="" />';
	} else {
		echo '<input type="text" name="ip[]" value="'.$ips[$i].'" />';
	}
}
		?>
		<button class="btn success">Cập nhật</button>
	</form>
</div>
<?php
$base->registerFooter(
	$setup->googleAnalyticsCode,
	$setup->importJS
);
?>