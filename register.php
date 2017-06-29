<?php
/**

	    Plugin MCVN
	-=-=-=-=-=-=-=-=-=-
	 @Author: Anh Craft
	  @Copyright 2016
	
**/
session_start();
require './include/main.php';

if(isLogin()){
	header("Location: ". regurl("@p://@d/"));
}

class Setup {
	var $title = "Plugin MCVN - Đăng ký";
	var $robots = "{public}";
	var $description = "Đăng ký cộng đồng Plugin MCVN để chia sẻ và thảo luận những plugin Bukkit hay Spigot do người Việt làm ra.";
	var $importCSS = array();
	var $importJS = array("https://www.google.com/recaptcha/api.js");
	var $importHTML = "<style>.pluginmcvn-container{background-size: 100%;background-image: url(http://include.pluginmcvn.cf/D3ncEe6.png);background-repeat: no-repeat;background-attachment: fixed;padding-top:80px !important;height: 900px}#pluginmcvn-logo{display:block;margin:auto;width:200px;border-radius:200px}#pluginmcvn-logo:hover{border-radius:0 !important;-moz-transform: rotate(360deg);-webkit-transform: rotate(360deg);-o-transform: rotate(360deg);-ms-transform: rotate(360deg);transform: rotate(360deg);-webkit-transition: all 1s ease-in-out;-moz-transition: all 1s ease-in-out;-ms-transition: all 1s ease-in-out;-o-transition: all 1s ease-in-out;transition: all 1s ease-in-out}@media only screen and (max-width: 1000px){img{width:100%;display:block}.pluginmcvn-container{background-size:auto 700px !important;}}form {background-color: #fff;width: 25% !important;margin: auto;color: #6d6d6d;margin-top: 20px;}@media (max-width: 1299px) and (min-width: 1100px){form {width: 35% !important;}}@media (max-width: 1099px) and (min-width: 800px) {form {width: 45% !important;}}@media (max-width: 799px) and (min-width: 600px) {form {width: 55% !important;}}@media (max-width: 599px) and (min-width: 400px) {form {width: 60% !important;}}@media (max-width: 399px) {form {width: 95% !important;}}#form-title {color: #38a2ff;}form > .form-group > label {font-weight: normal !important;}.g-recaptcha {margin-left: -20px;}</style>";
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
$sitekey = "...............................";
$secretkey = ".......................................";
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
	
	if (empty($_POST["repass"])):
		$error .= "Mật khẩu nhập lại không được để trống!<br>";
		$ready = false;
	else:
		$repass = $_POST["repass"];
	endif;
	
	if (empty($_POST["email"])):
		$error .= "Email không được để trống!<br>";
		$ready = false;
	else:
		$email = strtolower($_POST["email"]);
	endif;

	if (strlen($email) < 8 || strlen($email) > 45){
		$error .= "Email phải từ 8 đến 45 kí tự!<br>";
		$ready = false;
	}
	if (strlen($pass) < 12 || strlen($pass) > 45){
		$error .= "Mật khẩu phải từ 12 đến 45 kí tự!<br>";
		$ready = false;
	}
	if (strlen($user) < 5 || strlen($user) > 35){
		$error .= "Tài khoản phải từ 5 đến 35 kí tự!<br>";
		$ready = false;
	}

    $redirect = ($_POST['redirect']) ?? regurl('@p://@d/');

    if($ready){
		$pass = md5($pass);
		$repass = md5($repass);
		$user = reword($user);
		$email = reword($email);
		
		if($pass == $repass):
			if(filter_var($email, FILTER_VALIDATE_EMAIL)):
				if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])):
					$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secretkey.'&response='.$_POST['g-recaptcha-response']);
					$responseData = json_decode($verifyResponse);
					if($responseData->success):
						$conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
						mysqli_set_charset($conn,"utf8");
						$hasinu = $conn->query("SELECT * FROM `account` WHERE `user`='". $user ."'");

						if ($hasinu->num_rows > 0):
							$error .= "Tài khoản này đã được đăng ký!<br>";
						else:
							$hasine = $conn->query("SELECT * FROM `account` WHERE `email`='". $email ."'");

							if ($hasine->num_rows > 0):
								$error .= "Email này đã được đăng ký với một tài khoản khác!<br>";
							else:
								$id = md5(md5($user));
								$creates = $conn->query("INSERT INTO `account`(`id`, `user`, `pass`, `email`, `dayjoin`, `lastip`, `isdev`) VALUES ('".$id."', '".$user."', '".$pass."', '".$email."', '".getTime()."', '".getIP()."', 'false')");
								if ($creates === TRUE):
									$_SESSION[$GLOBALS['session_login_username']] = $user;
									echo "<script>window.location.href = '".$redirect."';</script><meta http-equiv='refresh' content='0; url=".$redirect."'>";
								else:
									$error .= "Hệ thống không thể tạo tài khoản!<br>".$conn->error;
								endif;
							endif;
						endif;
						$conn->close();
					else:
						$error .= "Captcha chưa được xác thực đúng !<br>";
					endif;
				else:
					$error .= "Bạn chưa xác thực captcha !<br>";
				endif;
			else:
				$error .= "Email không đúng định dạng!<br>";
			endif;
		else:
			$error .= "Mật khẩu nhập lại không đúng!<br>";
		endif;
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
        <h3 id="form-title">Đăng kí</h3>
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
		<div class="form-group">
			<label>Nhập lại mật khẩu:</label>
			<input type="password" required name="repass" />
		</div>
		<div class="form-group">
			<label>Địa chỉ email:</label>
			<input type="email" required name="email" />
		</div>
		<div class="form-group">
			<div class="g-recaptcha" data-sitekey="........................."></div>
		</div>
		<div class="form-group">
			<button class="btn success">Hoàn tất</button>
		</div>
	</form>
</div>
<?php
$base->registerFooter(
	$setup->googleAnalyticsCode,
	$setup->importJS
);
?>