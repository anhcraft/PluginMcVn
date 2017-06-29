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
	var $title = "Plugin MCVN - Trang chủ | Cộng đồng plugin Minecraft Việt Nam";
	var $robots = "{public}";
	var $description = "Cộng đồng plugin Minecraft Việt Nam chia sẻ và thảo luận những plugin Bukkit hay Spigot do người Việt làm ra.";
	var $importCSS = array("flipclock.css", "https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css","@p://include.@d/css/subtle-hover-effects.css");
	var $importJS = array("flipclock.js", "http://include.pluginmcvn.cf/js/jquery.slides.js");
	var $importHTML = "<style>@media screen and (max-width:500px){#pluginmcvn-bigtitle{font-size:40px !important;}#pluginmcvn-logo{width:150px !important;}}@font-face {font-family: Minecraft;src: url(./include/font/Minecraft.ttf);}.pluginmcvn-container{background-repeat: no-repeat;padding-top:80px !important;height: 700px}#pluginmcvn-bigtitle{color: #fff;font-family: Minecraft, 'Open Sans';font-size: 52px;text-shadow: 3px 3px 20px #212121;font-weight: 700}#pluginmcvn-box-2fx59wk{margin-top: 60px}#pluginmcvn-logo{display:block;margin:auto;width:200px;border-radius:200px}#pluginmcvn-logo:hover{border-radius:0 !important;-moz-transform: rotate(360deg);-webkit-transform: rotate(360deg);-o-transform: rotate(360deg);-ms-transform: rotate(360deg);transform: rotate(360deg);-webkit-transition: all 1s ease-in-out;-moz-transition: all 1s ease-in-out;-ms-transition: all 1s ease-in-out;-o-transition: all 1s ease-in-out;transition: all 1s ease-in-out}.pluginmcvn-container-q5dj0h{background-color: #000;color: #888;padding: 2.5%}.pluginmcvn-container-0xw8q1{background-color: #000;color: #888;padding: 2.5%}@media screen and (max-width: 800px) {
		.pluginmcvn-container-5zb0s4-sub {
    width: 100%;
		display: block;}
	}
	.pluginmcvn-container-5zb0s4{
background-color: #fff;color: #555;padding: 2.5%}.pluginmcvn-container-5zb0s4-sub {width: 33%;display: inline-table;padding: 5%;}.pluginmcvn-container-5zb0s4-sub > i.fa {display: block;text-align: center;font-size: 100px;color: #44a8ff;    margin-bottom: 10px;}.pluginmcvn-container-5zb0s4-sub > b {display: block;font-size: 30px;text-align: center;margin-bottom: 20px;}.pluginmcvn-picture-0xw8q1{display:block;margin:auto}@media only screen and (max-width: 1000px){img{width:100%;display:block}}#clock{padding:0 20%;display:block}@media only screen and (max-width:1120px){#clock{padding:0 12%;display:block}}@media only screen and (max-width:1180px){#clock{padding:0 30%;display:block}}@media only screen and (max-width:910px){#clock{padding:0 8%;display:block}.flip-clock-wrapper ul{width:30px!important;height:80px!important;font-weight:100 !important}.flip-clock-wrapper ul li a div div.inn{font-size:50px!important}}@media only screen and (max-width:780px){#clock{padding:0;display:block}}@media only screen and (max-width:510px){.flip-clock-wrapper ul{margin:1px!important;height:80px!important}.flip-clock-wrapper ul li a div div.inn{font-size:30px!important}}@media only screen and (max-width:400px){.flip-clock-wrapper ul{margin:1px!important;height:60px!important;width:5px!important}.flip-clock-wrapper ul li a div div.inn{font-size:15px!important}}.container {padding: 80px 0 0 0 !important;}#slides {position: absolute;left: -10px;right: -10px;top: -10px;z-index: -5;}.slidesjs-container,.slidesjs-control {height: 710px !important;}.slidesjs-control img {width: auto !important;}</style>";
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
$base->createMenu("animated fadeInDown");
?>
<div class="container pluginmcvn-container">
    <div id="slides">
<?php
$dirname = "./include/warppers/";
$images = glob($dirname."*.png");
array_merge($images,glob($dirname."*.jpg"));
foreach($images as $image) {
    echo '<img src="' . str_replace("./include","http://include.pluginmcvn.cf",$image) . '" />';
}
?></div>
<script>
$(function(){
    $("#slides").slidesjs({
        width: $(window).width(),
        navigation: {
            active: false
        },
        pagination: {
            active: false
        },
        play: {
            active: false,
            effect: "slide",
            interval: 5000,
            auto: true,
            swap: true
        },
        effect: {
            slide: {
                speed: 250
            }
        }
    });
});
</script>
	<img id="pluginmcvn-logo" class="animated rotateIn" src="<?php echo regurl("@p://@d/include/logo.png") ?>">
	<h1 id="pluginmcvn-bigtitle" class="center animated slideInDown">MINECRAFT PLUGIN VIETNAM COMMUNITY
	<!-- Cộng đồng plugin Minecraft Việt Nam -->
	</h1>	
	<div id="pluginmcvn-box-04aq9z" class="center x-large">
		<span></span>
	</div>
	<div id="pluginmcvn-box-2fx59wk" class="center x-large animated slideInUp">
		<h3><i class="color-danger fa fa-users" aria-hidden="true"></i>&nbsp;&nbsp;
			<span style="color:#d7d7d7!important"><?php
$conn_ = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
mysqli_set_charset($conn_,"utf8");
$sql_ = "SELECT * FROM `account`";
$res_ = $conn_->query($sql_);
echo $res_->num_rows;
$conn_->close();
		?>&nbsp;thành viên tham gia</span></h3>
	</div>
</div>
<div class="pluginmcvn-container-q5dj0h">
	<h2 class="center"><i class="fa fa-cube color-danger" aria-hidden="true"></i> Chia sẻ và khám phá những plugin hay nhất...</h2>
	<br><br>
    <div class="subtle-hover-effects">
    <?php
$connBestPlugin = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
mysqli_set_charset($connBestPlugin,"utf8");
$sqlBestPlugin = "SELECT * FROM `plugins` ORDER BY `total_download` DESC LIMIT 4";
$resBestPlugin = $connBestPlugin->query($sqlBestPlugin);
if ($resBestPlugin->num_rows > 0){
    while($aif = $resBestPlugin->fetch_assoc()) {
        echo '
		<figure class="effect-marley animated zoomIn">
			<a href="http://pluginmcvn.cf/plugin/'.$aif["id"].'">
				<img src="picture_q5dj0h.png" />
				<figcaption>
					<h2>'.$aif["title"].'</h2>
					<p>
						'.$aif["description"].'
						<br><i class="fa fa-paint-brush color-warning" aria-hidden="true"></i> Tạo bởi '.$aif["author"].'
					</p>
				</figcaption>
			</a>
		</figure>
        ';
    }
}
$connBestPlugin->close();
    ?>
	</div>
</div>
    <div class="pluginmcvn-container-5zb0s4 animated zoomInUp">
        <div class="pluginmcvn-container-5zb0s4-sub animated bounceInDown">
            <i class="fa fa-lock" aria-hidden="true"></i>
            <b>An toàn</b>
            Trang web được code cản thận nhằm tránh mọi vấn đề về bug
        </div>
        <div class="pluginmcvn-container-5zb0s4-sub animated bounceInDown">
            <i class="fa fa-plug" aria-hidden="true"></i>
            <b>Kết nối</b>
            Cam kết sử dụng đường truyền ổn định 24/24 để bạn có thể truy cập mọi lúc mọi nơi
        </div>
        <div class="pluginmcvn-container-5zb0s4-sub animated bounceInDown">
            <i class="fa fa-life-ring" aria-hidden="true"></i>
            <b>Hỗ trợ</b>
            Đội ngũ hỗ trợ nhiệt tình, nhanh chóng giúp bạn dễ dàng làm quen website
        </div>
    </div>
<div class="pluginmcvn-container-0xw8q1 animated zoomInUp">
	<h2 class="center"><i class="fa fa-th-large color-danger" aria-hidden="true"></i> Thảo luận và học làm plugin với mọi người...</h2>
	<br>
	<img src="picture_0xw8q1.png" class="pluginmcvn-picture-0xw8q1" />
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