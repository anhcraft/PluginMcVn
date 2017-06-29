<?php
/**

	    Plugin MCVN
	-=-=-=-=-=-=-=-=-=-
	 @Author: Anh Craft
	  @Copyright 2016
	
**/
include_once(__DIR__ . '/firewall/evf_load.php');
require './include/main.php';

class Setup {
	var $title = "Plugin MCVN - Về PluginMCVN";
	var $robots = "{private}";
	var $description = "";
	var $importCSS = array();
	var $importJS = array();
	var $importHTML = "<style>.pluginmcvn-container{padding-top:80px !important}#pluginmcvn-bigtitle{font-family: 'Itim', 'Open Sans';font-size:45px}#pluginmcvn-logo{display:block;margin:auto;width:200px;border-radius:200px}#pluginmcvn-logo:hover{border-radius:0 !important;-moz-transform: rotate(360deg);-webkit-transform: rotate(360deg);-o-transform: rotate(360deg);-ms-transform: rotate(360deg);transform: rotate(360deg);-webkit-transition: all 1s ease-in-out;-moz-transition: all 1s ease-in-out;-ms-transition: all 1s ease-in-out;-o-transition: all 1s ease-in-out;transition: all 1s ease-in-out}@media only screen and (max-width: 1000px){img{width:100%;display:block}.pluginmcvn-container{background-size:auto 700px !important;}}</style>";
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
	<h1 id="pluginmcvn-bigtitle" class="center">Về PluginMCVN</h1>
	<div class="padd-big">
		<p>PluginMCVN được tạo ra để giúp mọi người chơi Minecraft chia sẻ những plugin do mình làm ra. Không những vậy, đây còn là nơi để học tập & thảo luận kiến thức làm plugin. Tôi rất mong bạn và mọi người sẽ luôn ủng hộ tích cực để PluginMCVN ngày một phát triển hơn.</p>
		<br>
		<p>Hãy quyên góp PluginMCVN để trang web có thể phục vụ nhu cầu ngày càng cao hơn của mọi người.</p>
		<br>
		<br>
		<hr />
		<p><b>Website</b>: Anh Craft</p>
		<p><b>Hosting</b>: 7host.vn</p>
		<p><b>Domain</b>: Freenom</p>
		<p><b>Icons</b>: Font Awesome</p>
		<p><b>Analytics</b>: Google Analytics</p>
		<p><b>Protection & Takedown Services</b>: DMCA</p>
		<p><b>Web framework</b>: Orenive</p>
	</div>
</div>
<?php
$base->registerFooter(
	$setup->googleAnalyticsCode,
	$setup->importJS
);
?>