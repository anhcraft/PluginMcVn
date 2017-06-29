<?php
/**

	    Plugin MCVN
	-=-=-=-=-=-=-=-=-=-
	 @Author: Anh Craft
	  @Copyright 2016
	
**/
session_start();
require './include/main.php';

class Setup {
	var $title = "Plugin MCVN - Lỗi";
	var $robots = "{private}";
	var $description = "Cộng đồng plugin Minecraft Việt Nam chia sẻ và thảo luận những plugin Bukkit hay Spigot do người Việt làm ra.";
	var $importCSS = array();
	var $importJS = array();
	var $importHTML = "<style>.pluginmcvn-container{background-repeat: no-repeat;padding-top:80px !important;height: 700px}#pluginmcvn-bigtitle{font-family: 'Itim', 'Open Sans';font-size:60px}#pluginmcvn-logo{display:block;margin:auto;width:200px;border-radius:200px}#pluginmcvn-logo:hover{border-radius:0 !important;-moz-transform: rotate(360deg);-webkit-transform: rotate(360deg);-o-transform: rotate(360deg);-ms-transform: rotate(360deg);transform: rotate(360deg);-webkit-transition: all 1s ease-in-out;-moz-transition: all 1s ease-in-out;-ms-transition: all 1s ease-in-out;-o-transition: all 1s ease-in-out;transition: all 1s ease-in-out}@media only screen and (max-width: 1000px){img{width:100%;display:block}.pluginmcvn-container{background-size:auto 700px !important;}}</style>";
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
?>
<div class="container pluginmcvn-container">
	<img id="pluginmcvn-logo" src="<?php echo regurl("@p://@d/include/logo.png") ?>">
	<h3 id="pluginmcvn-bigtitle" class="center"><i class="fa fa-exclamation-triangle color-warning" aria-hidden="true"></i>&nbsp; <?php
$cd = isset($_GET['c']) ? $_GET['c'] : null;
if ($cd !== null):
    if($cd == "404"){
		echo "Không tồn tại liên kết bạn cần !";
	}
	elseif ($cd == "403"){
		echo "Bạn không có quyền vào đường dẫn đó !";
	}
	elseif ($cd == "500"){
		echo "Server đang gặp lỗi, thử lại sau !";
	}
    elseif ($cd == "require_login"){
        echo "Yêu cầu đăng nhập để truy cập!";
    }
	else {
		header("Location: ".regurl("@p://@d/"));
	}
else:
	header("Location: ". regurl("@p://@d/error.php?c=403"));
endif;?>
</h3>
</div>
<?php
$base->registerPageFooter();
?>
<?php
$base->registerFooter(
	$setup->googleAnalyticsCode,
	$setup->importJS
);
?>