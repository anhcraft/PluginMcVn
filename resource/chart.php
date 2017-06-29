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

$prit = "";
$limit = 5;
$conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
mysqli_set_charset($conn,"utf8");
$sql = "SELECT * FROM `plugins` ORDER BY `total_download` DESC";
$res = $conn->query($sql);
$s = 0;
if ($res->num_rows > 0):
	while($aif = $res->fetch_assoc()) {
		$s += 1;
		if(0 < $limit && $limit < $s){
			continue;
		}
		$prit .= '<div class="top-download-sub"><a href="http://pluginmcvn.cf/plugin/'.$aif["id"].'">'.$s . '. '.$aif["title"].' của '.$aif["author"].'</a></div>';
	}
else:
	$prit = "<p>Không có plugin nào!</p>";
endif;
$conn->close();

class Setup {
	var $title = "Plugin MCVN - Bảng xếp hạng";
	var $robots = "{public}";
	var $description = "";
	var $importCSS = array();
	var $importJS = array();
	var $importHTML = "<style>.pluginmcvn-container{padding:0!important}#side-menu{display:inline-block;width:100%}#pluginmcvn-bigtitle{margin:0!important;color:#fff;font-family:Itim,'Open Sans';background:#2d89e5;background:-moz-linear-gradient(-45deg,#2d89e5 2%,#30bbf2 30%,#2abad3 46%,#26c174 75%,#22ad6c 94%);background:-webkit-linear-gradient(-45deg,#2d89e5 2%,#30bbf2 30%,#2abad3 46%,#26c174 75%,#22ad6c 94%);background:linear-gradient(135deg,#2d89e5 2%,#30bbf2 30%,#2abad3 46%,#26c174 75%,#22ad6c 94%);filter:progid:DXImageTransform.Microsoft.gradient( startColorstr='#2d89e5', endColorstr='#22ad6c', GradientType=1 )}.menu{position:relative!important}.side-top-heading{font-size:20px;margin-bottom:5px}.side-top-menu a{color:#989898;padding:0 20px}.side-top-menu a:first-child{padding:0!important}.side-top-menu a:hover{-webkit-transition:all 1s ease;-moz-transition:all 1s ease;-ms-transition:all 1s ease;-o-transition:all 1s ease;transition:all 1s ease;color:#666}.list-plugin-sub{width:20%;padding:2%;float:left}.list-plugin-sub img{width:50%}#list-plugin-sub-title a{font-size:20px;color:#1DAB92}#list-plugin-sub-title span{color:#888}#list-plugin-sub-info{font-size:14px;color:#009fd4;margin-top:-3px}#list-plugin-sub-description{font-size:15px;color:#555}.pagination-selected{background-color:#11C09B}.pagination>li.pagination-selected>a,.pagination>li.pagination-selected>span{color:#fff!important}.pagination>li.pagination-selected>a:hover,.pagination>li.pagination-selected>span:hover{border:1px solid transparent!important;transition:none!important}</style>";
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
	<h1 id="pluginmcvn-bigtitle" class="padd-big">Bảng xếp hạng</h1>
	<div id="side-area">
		<!--
		<div id="side-top" class="padd-large">
			<div class="side-top-heading">Danh mục Plugin miễn phí</div>
			<div class="side-top-menu">
				<a href="http://pluginmcvn.cf/resource/free.plugin">Toàn bộ</a>
				<a href="http://pluginmcvn.cf/resource/free.plugin?category=1">Công cụ và tiện ích</a>
				<a href="http://pluginmcvn.cf/resource/free.plugin?category=2">Vui vẻ</a>
				<a href="http://pluginmcvn.cf/resource/free.plugin?category=3">Quản lý world</a>
				<a href="http://pluginmcvn.cf/resource/free.plugin?category=4">Kinh tế</a>
				<a href="http://pluginmcvn.cf/resource/free.plugin?category=5">Chế độ chơi</a>
				<a href="http://pluginmcvn.cf/resource/free.plugin?category=6">Thư viện & API</a>
			</div>
		</div>-->
		<div id="side-bottom" class="padd-large padd-top-small">
			<div id="top-download">
				<h3>Top 5 plugin miễn phí được tải nhiều nhất</h3>
				<?php echo $prit; ?>
			</div>
		</div>
	</div>
</div>
<?php
$base->registerFooter(
	$setup->googleAnalyticsCode,
	$setup->importJS
);
?>