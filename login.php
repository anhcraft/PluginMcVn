<?php
/**

	    Plugin MCVN
	-=-=-=-=-=-=-=-=-=-
	 @Author: Anh Craft
	  @Copyright 2016
	
**/
session_start();
require './include/main.php';
require_once './include/Google-API/vendor/autoload.php';

if(isLogin()){
	header("Location: ". regurl("@p://@d/"));
}

class Setup {
	var $title = "Plugin MCVN - Đăng nhập";
	var $robots = "{public}";
	var $description = "Đăng nhập cộng đồng Plugin MCVN để chia sẻ và thảo luận những plugin Bukkit hay Spigot do người Việt làm ra.";
	var $importCSS = array();
	var $importJS = array();
	var $importHTML = "<style>.pluginmcvn-container{background-size: 100%;background-image: url(http://include.pluginmcvn.cf/D3ncEe6.png);background-repeat: no-repeat;background-attachment: fixed;padding-top:80px !important;height: 900px}#pluginmcvn-logo{display:block;margin:auto;width:200px;border-radius:200px}#pluginmcvn-logo:hover{border-radius:0 !important;-moz-transform: rotate(360deg);-webkit-transform: rotate(360deg);-o-transform: rotate(360deg);-ms-transform: rotate(360deg);transform: rotate(360deg);-webkit-transition: all 1s ease-in-out;-moz-transition: all 1s ease-in-out;-ms-transition: all 1s ease-in-out;-o-transition: all 1s ease-in-out;transition: all 1s ease-in-out}@media only screen and (max-width: 1000px){img{width:100%;display:block}.pluginmcvn-container{background-size:auto 700px !important;}}form {background-color: #fff;width: 25% !important;margin: auto;color: #6d6d6d;margin-top: 20px;}@media (max-width: 1299px) and (min-width: 1100px){form {width: 35% !important;}}@media (max-width: 1099px) and (min-width: 800px) {form {width: 45% !important;}}@media (max-width: 799px) and (min-width: 600px) {form {width: 55% !important;}}@media (max-width: 599px) and (min-width: 400px) {form {width: 60% !important;}}@media (max-width: 399px) {form {width: 95% !important;}}.social-login-btn {display: inline-block;border-radius: 100%;padding: 5px 10px;margin-top: 5px;color: #fff;}.social-login > label {display: block;}.social-login {margin: 20px 0;}#form-title {color: #38a2ff;}form > .form-group > label {font-weight: normal !important;}</style>";
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

/*********************************/
$error = null;
$ready = true;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// kiểm tra thông tin
	
	if (empty($_POST["user"])):
		$error .= "Tài khoản không được để trống!<br>";
		$ready = false;
	else:
		$user = strtolower($_POST["user"]);
	endif;

    if (empty($_POST["pass"])):
		$error .= "Mật khẩu không được để trống!<br>";
		$ready = false;
	else:
		$pass = $_POST["pass"];
	endif;
	
	if (strlen($user) < 5 || strlen($user) > 35){
		$error .= "Tài khoản phải từ 5 đến 35 kí tự!<br>";
		$ready = false;
	}
	if (strlen($pass) < 12 || strlen($pass) > 45){
		$error .= "Mật khẩu phải từ 12 đến 45 kí tự!<br>";
		$ready = false;
	}

    $redirect = ($_POST['redirect']) ?? regurl('@p://@d/');

    if($ready){
		$pass = md5($pass);
		$user = reword($user);
		
		$conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
		mysqli_set_charset($conn,"utf8");
		$hasin = $conn->query("SELECT * FROM `account` WHERE `user`='". $user ."'");

		if ($hasin->num_rows > 0):
			while($aif = $hasin->fetch_assoc()) {
				if($aif["pass"] == $pass):
					$updates = $conn->query("UPDATE `account` SET `lastip`='". getIP() ."' WHERE `user`='". $user ."'");
					if ($updates === TRUE):
						$_SESSION[$GLOBALS['session_login_username']] = $user;
						echo "<script>window.location.href = '".$redirect."';</script><meta http-equiv='refresh' content='0; url=".$redirect."'>";
					else:
						$error .= "Hệ thống không thể cập nhật tài khoản!<br>";
					endif;
				else:
					$error .= "Sai mật khẩu!<br>";
				endif;
			}
		else:
			$error .= "Sai tài khoản!<br>";
		endif;
		$conn->close();
	}
}
/*********************************/
if($error != null):
	$error = '<div class="alert danger">'. $error .'</div>';
else:
	$error = '';
endif;
?>
<div class="container pluginmcvn-container">
    <form action="" method="post" class="padd-large">
        <h3 id="form-title">Đăng nhập</h3>
        <input type="hidden" style="display:none" value="<?php
        if (isset($_SERVER['HTTP_REFERER'])) {
    echo $_SERVER['HTTP_REFERER'];
    }
    ?>" name="redirect">
		<div class="form-group">
			<?php echo $error; ?>
		</div>
		<div class="form-group">
			<label>Tên tài khoản:</label>
			<input type="text" required name="user" />
		</div>
		<div class="form-group">
			<label>Mật khẩu:</label>
			<input type="password" required name="pass" />
		</div>
        <div class="social-login">
<?php
$client_id = '...................................';
$client_secret = '..........................';
$redirect_uri = 'http://pluginmcvn.cf/auth/google.pluginmcvn';

$client = new Google_Client();
$client->setClientId($client_id);
$client->setClientSecret($client_secret);
$client->setRedirectUri($redirect_uri);
$client->addScope("email");
$client->addScope("profile");

$authUrl = "";
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);
} else {
    $authUrl = $client->createAuthUrl();
}

?>
            <label>Hoặc đăng nhập bằng:</label>
            <a href="<?php echo $authUrl; ?>" class="social-login-btn danger"><i class="fa fa-google" aria-hidden="true"></i></a>
        </div>
		<div class="form-group">
			<button class="btn success">Hoàn tất</button>
		</div>
	</form>
	<br><br>
	
</div>
<?php
$base->registerFooter(
	$setup->googleAnalyticsCode,
	$setup->importJS
);
?>