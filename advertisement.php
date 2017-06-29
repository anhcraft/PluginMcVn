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
	var $title = "Plugin MCVN - Liên hệ quảng cáo";
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
	<h1 id="pluginmcvn-bigtitle" class="center">Liên hệ quảng cáo</h1>
	<div class="padd-big">
		<p>Bảng giá quảng cáo</p><br>
		<table class="table table-border table-hover">
			<thead>
				<tr>
					<th>Vị trí</th>
					<th>Chi phí/tháng</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Chân trang (có vài trang sẽ không có)</td>
					<td>40.000/tháng</td>
				</tr>
				<tr>
					<td>Trang đăng ký</td>
					<td>70.000/tháng</td>
				</tr>
				<tr>
					<td>Trang đăng nhập</td>
					<td>100.000/tháng</td>
				</tr>
				<tr>
					<td>Hiển thị khi vào trang (có nút X & hiện mọi trang)</td>
					<td>500.000/tháng</td>
				</tr>
			</tbody>
		</table>
		<br>
		<p>
		Người đặt lần đầu được giảm 80%.<br>
		Developer nếu <b>quảng cáo plugin</b> sẽ được giảm 50%.<br>
		Chỉ cho phép quảng cáo server, website, plugin.<br>
		Sau 3 ngày kể từ khi hết hạn, nếu người đặt không trả phí để tiếp tục duy trì thì quảng cáo sẽ bị xóa.<br>
		Liên hệ: <b>huynhduyanh123123@gmail.com</b>
		</p>
	</div>
</div>
<?php
$base->registerFooter(
	$setup->googleAnalyticsCode,
	$setup->importJS
);
?>