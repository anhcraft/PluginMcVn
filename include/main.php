<?php
/**

	    Plugin MCVN
	-=-=-=-=-=-=-=-=-=-
	 @Author: Anh Craft
	  @Copyright 2016
	
**/
declare(strict_types = 1);
require 'options.php';
include_once(__DIR__ . '/phpmailer/PHPMailerAutoload.php');
//include_once(__DIR__ . '../firewall/evfw_load.php');

if($GLOBALS['autoDeleteLogFile'] && file_exists("error_log")){
	unlink("error_log");
}

// riêng admin đã có session thì ko cần
if($GLOBALS['enable_maintenance'] && (!isset($_SESSION[$GLOBALS['session_maintenance']]) && !isset($_SESSION["pluginmcvn_admin"]))) {
    header('Location: '.$GLOBALS['maintenance_redirect_url']);
}

function getIP(){  
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function getTime(){
	date_default_timezone_set('Asia/Ho_Chi_Minh');
	$times = time();
	$date = date("Y-m-d H:i:s", $times);
	return $date;
}
	
function reword($data) {
  $data = addslashes($data); // add " or ' before special character
  $data = trim($data); // add " or ' before special character
  $data = str_replace("'","\'",$data);
  $data = str_replace('"','\"',$data);
  $data = str_replace("`","",$data);
  return $data;
}

function getLogger(){
	if(isLogin()):
		return $_SESSION[$GLOBALS['session_login_username']];
	else:
		return null;
	endif;
}

function deword($data) {
  $data = stripslashes($data); // add " or ' before special character
  $data = str_replace("\'","'",$data);
  $data = str_replace('\"','"',$data);
  return $data;
}

function isDev(){
	/*$resul = false;
	if(isLogin()) {
		$conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
		mysqli_set_charset($conn,"utf8");
		$sql = "SELECT * FROM `account` WHERE `user`='" . getLogger() . "'";
		$result = $conn->query($sql);

		if ($result->num_rows > 0){
			while($aif = $result->fetch_assoc()) {
				if($aif["isdev"] == "true"){
					$resul = true;
				}
			}
		}
		$conn->close();
	}
	
	return $resul;*/
	return true;
}

function getMemberID($aut){
	$conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
	mysqli_set_charset($conn,"utf8");
	$sql = "SELECT * FROM `account` WHERE `user`='".$aut."'";
	$res = $conn->query($sql);
	$a = "";
	if ($res->num_rows > 0):
		while($aif = $res->fetch_assoc()) {
			$a = $aif["id"];
		}
	else:
		$a = "null";
	endif;	
	$conn->close();	
	return $a;
}

function isLogin(){
	$resul = false;
	if(isset($_SESSION[$GLOBALS['session_login_username']])) {
		$conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
		mysqli_set_charset($conn,"utf8");
		$sql = "SELECT * FROM `account` WHERE `user`='" . $_SESSION[$GLOBALS['session_login_username']] . "'";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
			$resul = true;
		}
		$conn->close();
	}
	
	return $resul;
}

function regurl($url = ''){
	$url = str_replace("@p",$GLOBALS['protocol'],$url);
	$url = str_replace("@d",$GLOBALS['domain'],$url);
	return $url;
}

function sendMail($title, $content, $nTo, $mTo, $des){
    $nFrom = 'PluginMCVN Support';
    $mFrom = 'pluginmcvn@gmail.com';
    $mPass = '.......................';
    $mail = new PHPMailer;
    $mail->IsSMTP();
    $mail->setLanguage('vi', '');
    $mail->SMTPDebug  = 0;
    $mail->Debugoutput = "html";
    $mail->Host       = "tls://smtp.gmail.com";
    $mail->Port       = 587;
    $mail->SMTPSecure = "tls";
    $mail->SMTPAuth   = true;
    $mail->CharSet   = "utf-8";
    $mail->Username   = $mFrom;
    $mail->Password   = $mPass;
    $mail->SetFrom($mFrom, $nFrom);
    $mail->AddReplyTo('pluginmcvn@gmail.com', 'pluginmcvn.cf');
    $mail->AddAddress($mTo, $nTo);
    $mail->Subject = $title;
    $mail->MsgHTML($content);
    $mail->AltBody = $des;
    $mail->isHTML(true);
    if($mail->Send()) {
        return true;
    } else {
        return false;
    }
}

function sendMailAttachment($title, $content, $nTo, $mTo,$diachicc='',$file,$filename){
    $nFrom = 'PluginMCVN Support';
    $mFrom = 'pluginmcvn@gmail.com';
    $mPass = '....................................';
    $mail = new PHPMailer();
    $body = $content;
    $mail->CharSet   = "utf-8";
    $mail->SMTPDebug  = 0;
    $mail->SMTPAuth   = true;
    $mail->SMTPSecure = "tls";
    $mail->Host       = "smtp.gmail.com";
    $mail->Port       = 587;
    $mail->Username   = $mFrom;
    $mail->Password   = $mPass;
    $mail->SetFrom($mFrom, $nFrom);
    $ccmail = explode(',', $diachicc);
    $ccmail = array_filter($ccmail);
    if(!empty($ccmail)){
        foreach ($ccmail as $k => $v) {
            $mail->AddCC($v);
        }
    }
    $mail->Subject = $title;
    $mail->MsgHTML($body);
    $address = $mTo;
    $mail->AddAddress($address, $nTo);
    $mail->AddReplyTo('pluginmcvn@gmail.com', 'pluginmcvn.cf');
    $mail->AddAttachment($file,$filename);
    if($mail->Send()) {
        return true;
    } else {
        return false;
    }
}
	
class PluginMCVN {
	function newHeader(
		$XFrameOptions = false){
			
		if($XFrameOptions){
			header('X-Frame-Options: '. $XFrameOptions);
		}
	}
	
	function registerHeader(
			$title = '',
			$robots = '',
			$description = '',
			$googleSiteVerification = '',
			$importCSS = array(),
			$importHTML = ''
		){
		for($x = 0; $x < count($importCSS); $x++) {
			$importHTML .= '<link href="'. regurl($importCSS[$x]) .'" rel="stylesheet" />';
		}
		$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		
echo '<!Doctype html>
<html lang="vi" class="pluginmcvn">
	<!--
	
	    Plugin MCVN
	-=-=-=-=-=-=-=-=-=-
	 @Author: Anh Craft
	  @Copyright 2016
	  
	-->
<head>
	<title>'.$title.'</title>
	
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<meta name="google-site-verification" content="'.$googleSiteVerification.'" />
	<meta name="description" content="'.$description.'" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="robots" content="'. str_replace("{public}","noodp,index,follow", str_replace("{private}","noindex,nofollow",$robots)) .'">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="'. regurl("@p://include.@d/favicon/ms-icon-144x144.png") .'">
	<meta name="theme-color" content="#ffffff">
	<link rel="alternate" href="'.$actual_link.'" hreflang="vi-vn" />
	<meta http-equiv="content-language" content="vi" />
	
	<link rel="apple-touch-icon" sizes="57x57" href="'. regurl("@p://include.@d/favicon/apple-icon-57x57.png") .'">
	<link rel="apple-touch-icon" sizes="60x60" href="'. regurl("@p://include.@d/favicon/apple-icon-60x60.png") .'">
	<link rel="apple-touch-icon" sizes="72x72" href="'. regurl("@p://include.@d/favicon/apple-icon-72x72.png") .'">
	<link rel="apple-touch-icon" sizes="76x76" href="'. regurl("@p://include.@d/favicon/apple-icon-76x76.png") .'">
	<link rel="apple-touch-icon" sizes="114x114" href="'. regurl("@p://include.@d/favicon/apple-icon-114x114.png") .'">
	<link rel="apple-touch-icon" sizes="120x120" href="'. regurl("@p://include.@d/favicon/apple-icon-120x120.png") .'">
	<link rel="apple-touch-icon" sizes="144x144" href="'. regurl("@p://include.@d/favicon/apple-icon-144x144.png") .'">
	<link rel="apple-touch-icon" sizes="152x152" href="'. regurl("@p://include.@d/favicon/apple-icon-152x152.png") .'">
	<link rel="apple-touch-icon" sizes="180x180" href="'. regurl("@p://include.@d/favicon/apple-icon-180x180.png") .'">
	<link rel="icon" type="image/png" sizes="192x192"  href="'. regurl("@p://include.@d/favicon/android-icon-192x192.png") .'">
	<link rel="icon" type="image/png" sizes="32x32" href="'. regurl("@p://include.@d/favicon/favicon-32x32.png") .'">
	<link rel="icon" type="image/png" sizes="96x96" href="'. regurl("@p://include.@d/favicon/favicon-96x96.png") .'">
	<link rel="icon" type="image/png" sizes="16x16" href="'. regurl("@p://include.@d/favicon/favicon-16x16.png") .'">
	<link rel="manifest" href="'. regurl("@p://include.@d/favicon/manifest.json") . '">
	<link href="//www.google-analytics.com" rel="dns-prefetch" />
	
	'. $importHTML .'
	<link rel="stylesheet" href="http://include.pluginmcvn.cf/css/onv.css" />
		<link rel="stylesheet" href="http://include.pluginmcvn.cf/core.css" />
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js" type="text/javascript"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js" type="text/javascript"></script>
    <script src="http://include.pluginmcvn.cf/js/onv.js" type="text/javascript"></script>
</head><body><!-- <style>#caudoitet-left {position: fixed;top: 0;z-index: 99999999;left: 0;}#caudoitet-right {position: fixed;top: 0;z-index: 99999999;right: 0;}#caudoitet-left, #caudoitet-right {width: 100px !important;}</style><img id="caudoitet-left" src="http://enc.anhcraft.org/includes/tet1.png" />
<img id="caudoitet-right" src="http://enc.anhcraft.org/includes/tet2.png" /> -->';
	}
	
	function createMenu($oclass = ''){
		if(isLogin()){
			$conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
			mysqli_set_charset($conn,"utf8");
			$sql = $conn->query("SELECT * FROM `account` WHERE `user`='".getLogger()."'");
			if($sql->num_rows > 0){
				while($r = $sql->fetch_assoc()) {
					$avatar = $r["avatar"];
				}
			}
			$conn->close();
			
			$a = '
<li>
	<a href="javascript:void()"><div style="background-image: url('.$avatar .')" id="menu-avatar-image"></div> Chào '.getLogger().' !</a>
	<ul>
		<li>
			<a href="'. regurl("@p://@d/profile/") .'"><i class="fa fa-user color-info" aria-hidden="true"></i>&nbsp; Trang cá nhân</a>
		</li>
		<li>
			<a href="'. regurl("@p://@d/logout.pluginmcvn") .'"><i class="fa fa-sign-out color-danger" aria-hidden="true"></i>&nbsp; Thoát</a>
		</li>
	</ul>
</li>
			';
			if(isDev()){
				$a = '
<li>
	<a href="'. regurl("@p://@d/dashboard/") .'"><i class="fa fa-database color-success" aria-hidden="true"></i>&nbsp; Bảng điều khiển</a>
</li>
				'. $a;
			}
		} else {
			$a = '
<li style="background-color: #ff8c8c">
	<a href="javascript:void()" style="color: #fff !important;text-align: center"><i class="fa fa-user" aria-hidden="true"></i>&nbsp;Thành viên</a>
	<ul>
		<li>
			<a href="'. regurl("@p://@d/login.pluginmcvn") .'"><i class="fa fa-user color-success" aria-hidden="true"></i>&nbsp; Đăng nhập</a>
		</li>
		<li>
			<a href="'. regurl("@p://@d/register.pluginmcvn") .'"><i class="fa fa-user-plus color-success" aria-hidden="true"></i>&nbsp; Đăng ký</a>
		</li>
	</ul>
	</a>
</li>
			';
		}
		
		echo '<div class="menu '.$oclass.'" style="z-index: 999;position: fixed;">
<div class="menu-title"><i class="fa fa-code color-info" aria-hidden="true"></i>&nbsp; Plugin MCVN</div>
<ul>
	<li>
		<a href="'. regurl("@p://@d/") .'"><i class="fa fa-home color-success" aria-hidden="true"></i>&nbsp; Trang chủ</a>
	</li><!--
	<li>
		<a href="'. regurl("@p://@d/post.pluginmcvn") .'"><i class="fa fa-newspaper-o color-success" aria-hidden="true"></i>&nbsp; Bài viết</a>
	</li>-->
	<li>
		<a href="javascript:void()"><i class="fa fa-hashtag color-success" aria-hidden="true"></i>&nbsp; Plugin</a>
		<ul>
			<li><a href="'. regurl("@p://@d/resource/free.plugin") .'"><i class="fa fa-share color-danger" aria-hidden="true"></i>&nbsp; Plugin Miễn phí</a></li>
			<li><a href="'. regurl("@p://@d/resource/premium.plugin") .'"><i class="fa fa-share color-danger" aria-hidden="true"></i>&nbsp; Plugin Premium</a></li>
			<li><a href="'. regurl("@p://@d/resource/foreign.plugin") .'"><i class="fa fa-share color-danger" aria-hidden="true"></i>&nbsp; Plugin Nước ngoài</a></li>
		</ul>
	</li>
	<li>
		<a href="'. regurl("@p://@d/wiki/") .'"><i class="fa fa-book color-success" aria-hidden="true"></i>&nbsp; Wiki</a>
	</li>
	'.$a.'
</ul></div>';
	}
	
	function registerPageFooter(){
		echo '<footer class="pluginmcvn-footer" style="background-color: #202020;color: #fff;padding: 5% 2%;display: inline-block;width: 100%;">
<div class="btn-group">
	<a href="http://napngay.com/tc/@huynhduyanh123123@gmail.com" class="btn danger">Quyên góp !</a>
	<a href="'. regurl("@p://@d/advertisement.pluginmcvn") .'" class="btn primary">Liên hệ quảng cáo</a>
	<a href="'. regurl("@p://@d/about.pluginmcvn") .'" class="btn warning">Về PluginMCVN</a>
</div><br><br>
<img class="flt-left marg-right-big marg-bottom-big" src="'. regurl("@p://include.@d/footer_chibi_picture.png") .'" style="width: 250px;"><div style="">
(c) Copyright 2016-'.date("Y").' by Anh Craft. All Rights Reserved.
<br>
Cấm sao chép, làm giả bất cứ nội dung gì trong website trừ khi được chủ website hoặc chủ plugin (nếu có) đồng ý.
<br><br>
<a href="http://www.dmca.com/Protection/Status.aspx?ID=c749bd3d-b821-4476-853d-d22e58202c0b" title="DMCA.com Protection Status" class="dmca-badge"> <img src="//images.dmca.com/Badges/dmca-badge-w200-5x1-06.png?ID=c749bd3d-b821-4476-853d-d22e58202c0b" alt="DMCA.com Protection Status"></a> <script src="//images.dmca.com/Badges/DMCABadgeHelper.min.js"> </script>
<br><br><br>
<!--<a href="https://minecraftvn.org/forum/" title="Diễn đàn Minecraft VN"><img src="'. regurl("@p://include.@d/ad_lT6zzCr.png") .'" style="width:auto" /></a>-->
  </div>
</footer>';
	}
	
	function registerFooter(
		$googleAnalyticsCode = null,
		$importJS = array()){
		
		$importHTML = '';
		for($y = 0; $y < count($importJS); $y++) {
			$importHTML .= '<script src="'. regurl($importJS[$y]) .'"></script>';
		}
		
		if($googleAnalyticsCode != null){
echo $importHTML . "<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', '".$googleAnalyticsCode."', 'auto');
  ga('send', 'pageview');

</script></body></html>";
		}
	}
}
?>