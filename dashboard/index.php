<?php
/**

	    Plugin MCVN
	-=-=-=-=-=-=-=-=-=-
	 @Author: Anh Craft
	  @Copyright 2016
	
**/
include_once(__DIR__ . '/../firewall/evf_load.php');
require '../include/main.php';
require '../include/pluginUtils.php';

if(!isLogin() || !isDev()){
	header("Location: ". regurl("@p://@d/"));
}

class Setup {
	var $title = "Plugin MCVN - Bảng điều khiển";
	var $robots = "{private}";
	var $description = "";
	var $importCSS = array();
	var $importJS = array();
	var $importHTML = "<style>.pluginmcvn-container{background-repeat: no-repeat;background-attachment: fixed;padding-top:80px !important;padding: 80px 2% !important}#pluginmcvn-bigtitle{font-family: 'Itim', 'Open Sans';font-size:45px}#pluginmcvn-logo{display:block;margin:auto;width:200px;border-radius:200px}#pluginmcvn-logo:hover{border-radius:0 !important;-moz-transform: rotate(360deg);-webkit-transform: rotate(360deg);-o-transform: rotate(360deg);-ms-transform: rotate(360deg);transform: rotate(360deg);-webkit-transition: all 1s ease-in-out;-moz-transition: all 1s ease-in-out;-ms-transition: all 1s ease-in-out;-o-transition: all 1s ease-in-out;transition: all 1s ease-in-out}@media only screen and (max-width: 1000px){img{width:100%;display:block}.pluginmcvn-container{background-size:auto 700px !important;}}.plugin-list-sub {background: #fff;padding: 10px 20px;font-size: 15px;}.plugin-list-title a {color: #ffaa16 !important;font-size: 20px;font-weight: bold;}.plugin-list-title a span {color: #aeaeae !important;}.plugin-list-title {padding-bottom: 10px;}#success-box {position: fixed;z-index: 99999;bottom: 0;}</style>";
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
$scs = isset($_GET['success']) ? $_GET['success'] : null;
if($scs != null){
	if($scs == "added_plugin"){
		echo "<div id='success-box' class='alert success'>Đã thêm plugin</div>";
	} else if($scs == "edited_plugin"){
		echo "<div id='success-box' class='alert success'>Đã sửa plugin</div>";	
	} else if($scs == "removed_plugin"){
		echo "<div id='success-box' class='alert success'>Đã xóa plugin</div>";	
	} else if($scs == "updated_plugin"){
		echo "<div id='success-box' class='alert success'>Đã cập nhật phiên bản</div>";	
	} else if($scs == "changedmode_tofree"){
		echo "<div id='success-box' class='alert success'>Đã chuyển plugin sang chế độ Miễn Phí</div>";	
	} else if($scs == "changedmode_topre"){
		echo "<div id='success-box' class='alert success'>Đã chuyển Plugin sang chế độ Premium</div>";	
	}
}
/*********************************/
?>
<div class="container pluginmcvn-container">
	<h1 id="pluginmcvn-bigtitle" class="center">Quản lý plugin của bạn</h1>
	<br><br><br>
	<a class="btn success" href="http://pluginmcvn.cf/dashboard/do.php?action=add_plugin">Đăng plugin mới</a>
	<br><br>
	<div id="plugin-list"><?php echo getAllPluginForAuthor(getLogger())?>
	</div>
</div>
<?php
$base->registerFooter(
	$setup->googleAnalyticsCode,
	$setup->importJS
);
?>