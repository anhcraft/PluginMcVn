<?php
/**

	    Plugin MCVN
	-=-=-=-=-=-=-=-=-=-
	 @Author: Anh Craft
	  @Copyright 2016
	
**/
include_once(__DIR__ . '/firewall/evf_load.php');
require './include/main.php';
require './include/post_modules.php';

$tit = "";
$id = isset($_GET['id']) ? $_GET['id'] : null;
if($id == null):
	class Setup {
		var $title = "Plugin MCVN - Bài viết";
		var $robots = "{public}";
		var $description = "Bài viết, tin tức diễn biến trong Plugin MCVN và Minecraft";
		var $importCSS = array();
		var $importJS = array();
		var $importHTML = "<style>.pluginmcvn-container{background-repeat: no-repeat;background-attachment: fixed;height: 900px; padding: 80px 80px !important}#pluginmcvn-bigtitle{font-family: 'Itim', 'Open Sans';font-size:45px}#pluginmcvn-logo{display:block;margin:auto;width:200px;border-radius:200px}#pluginmcvn-logo:hover{border-radius:0 !important;-moz-transform: rotate(360deg);-webkit-transform: rotate(360deg);-o-transform: rotate(360deg);-ms-transform: rotate(360deg);transform: rotate(360deg);-webkit-transition: all 1s ease-in-out;-moz-transition: all 1s ease-in-out;-ms-transition: all 1s ease-in-out;-o-transition: all 1s ease-in-out;transition: all 1s ease-in-out}@media only screen and (max-width: 1000px){img{width:100%;display:block}.pluginmcvn-container{background-size:auto 700px !important;}}.post-list-sub {background-color: #fff;width: 420px;float: left;margin-right: 20px;margin-bottom:20px;padding: 15px 20px;border: 1px #ccc solid;}.post-list-title {font-size: 25px;font-family: 'Itim';padding-bottom: 12px}.post-image {width: 120px;}@media screen and (max-width: 600px) {.pluginmcvn-container {padding: 20px 20px !important}}</style>";
		var $googleSiteVerification = "kNkDxfzm97qD6sJJbkDGt9S36SuhMFiVBKPyjeS0t10";
		var $googleAnalyticsCode = "UA-86116936-1";
	}
	$setup = new Setup;
	$des = $setup->description;
else:
	require './include/post_page.php';
	$setup = new Setup;
endif;

$base = new PluginMCVN;
$base->newHeader(true);
$base->registerHeader(
	$setup->title . $tit,
	$setup->robots,
	$des,
	$setup->googleSiteVerification,
	$setup->importCSS,
	$setup->importHTML
);
$base->createMenu();

/*********************************/
if($id == null){
?>
<div class="container pluginmcvn-container">
	<h1 id="pluginmcvn-bigtitle" class="center">Bài viết</h1>
	<br><br>
	<?php echo get_postlist(); ?>
</div>
<?php 
} else {
?><div class="container">
<div id="post-area">

	<br><br>
	<br>
	<!-------------------------------->
	<?php echo header_post(); ?>
	
	<div id="main">
		<div id="note">
			<span><i class="fa fa-user color-success" aria-hidden="true"></i>&nbsp;&nbsp;PluginMCVN</span>
			<span><i class="fa fa-calendar color-info" aria-hidden="true"></i>&nbsp;&nbsp;<?php echo $date; ?></span>
		</div>
		<div id="content">
			<?php echo $cont; ?>
		</div>
	</div>
	
	<?php echo footer_post(); ?>
	<!-------------------------------->	
	<br /><br /><br />
	
	<div id="new-post">
		<h3>Bài viết mới</h3>
		<ul>
			<?php echo new_post(); ?>
		</ul>
	</div>
	</div>
</div>
	
	<br /><br /><br />
	<?php
}
?>
<?php
$base->registerFooter(
	$setup->googleAnalyticsCode,
	$setup->importJS
);
?>