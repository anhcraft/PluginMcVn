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

$listPlugin = "";
$pluginsPerPage = 10; // hiển thị 5 plugin mỗi trang
$page = isset($_GET['page']) ? $_GET['page'] : null;
if($page == null || !filter_var($page, FILTER_VALIDATE_INT) || ((int) $page) < 1){
    $page = 1;
}
$pbegin = ($page-1)*$pluginsPerPage;
$pend = ($page*$pluginsPerPage);

$conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
mysqli_set_charset($conn,"utf8");
$sql = "SELECT * FROM `plugins` ORDER BY `date` DESC";
$res = $conn->query($sql);
$category = isset($_GET['category']) ? $_GET['category'] : null;
$s = 0;
$total = 0;
if ($res->num_rows > 0):
    while($aif = $res->fetch_assoc()) {
        $s += 1;
        $total += 1;
        if(($category != null && $aif["category"] != $category) || $aif["pre"] != NULL || $aif["frgpl"] != "1"){
            $s -= 1;
            continue;
        }
        if($pbegin < $s && $s <= $pend){
            $listPlugin .= '
<div class="list-plugin-sub">
	<img src="'.$aif["icon"].'" />
	<div id="list-plugin-sub-content">
		<div id="list-plugin-sub-title">
			<a href="http://pluginmcvn.cf/plugin/'.$aif["id"].'">'.$aif["title"].'</a>
			<span>'.$aif["version"].'</span>
		</div>
		<div id="list-plugin-sub-info">
			<span><a href="http://pluginmcvn.cf/u/'.$aif["author"].'">'.$aif["author"].'</a></span>,
			<span><a href="http://pluginmcvn.cf/resource/foreign.plugin?category='.$aif["category"].'">'.toCategory($aif["category"]).'</a></span>
		</div>
		<div id="list-plugin-sub-description">'.$aif["description"].'</div>
	</div>
</div>		
			';
        } else {
            continue;
        }
    }
else:
    $listPlugin = "Không có plugin nào!";
endif;

$conn->close();

if($listPlugin == ""){
    if(1 < ((int) $page)){
        if($category == null){
            header("Location: ". regurl("@p://@d/resource/foreign.plugin?page=".(((int) $page)-1)));
        } else {
            header("Location: ". regurl("@p://@d/resource/foreign.plugin?page=".(((int) $page)-1)."&category=".$category));
        }
    } else {
        header("Location: ". regurl("@p://@d/resource/foreign.plugin"));
    }
}

/***********************/
$tz = $s;
$rs = $tz/$pluginsPerPage;
$pagination = "";
if(strpos(strval($rs),".")){
    $rs = ((int) explode(".",$rs)[0]) + 1;
}
if(1 < $rs){
    $pagemenu = "";
    for($i = 0; $i < $rs; $i++){
        $pms = "";
        if($i == $page-1){
            $pms = "class='pagination-selected'";
        }
        if($i == 0){
            $pagemenu .= '<li '.$pms.'><a href="http://pluginmcvn.cf/resource/foreign.plugin">'.($i+1).'</a></li>';
            continue;
        }
        $pagemenu .= '<li '.$pms.'><a href="http://pluginmcvn.cf/resource/foreign.plugin?page='.($i+1).'">'.($i+1).'</a></li>';
    }
    $pagination .= '<p>Trang thứ '.$page.' trong '.$rs.' trang</p> <ul class="pagination danger">'.$pagemenu.'</ul>';
}

/**********************/



class Setup {
    var $title = "Plugin MCVN - Plugin Nước ngoài";
    var $robots = "{public}";
    var $description = "";
    var $importCSS = array();
    var $importJS = array();
    var $importHTML = "<style>.pluginmcvn-container{padding:0!important}#side-menu{display:inline-block;width:100%}#pluginmcvn-bigtitle{margin:0!important;color:#fff;font-family:Itim,'Open Sans';background: #418ffc;background: -moz-linear-gradient(-45deg, #418ffc 0%, #ca8ff7 100%);background: -webkit-linear-gradient(-45deg, #418ffc 0%,#ca8ff7 100%); background: linear-gradient(135deg, #418ffc 0%,#ca8ff7 100%); filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#418ffc', endColorstr='#ca8ff7',GradientType=1 );}.menu{position:relative!important}.side-top-heading{font-size:20px;margin-bottom:5px}.side-top-menu a{color:#989898;padding:0 20px}.side-top-menu a:first-child{padding:0!important}.side-top-menu a:hover{-webkit-transition:all 1s ease;-moz-transition:all 1s ease;-ms-transition:all 1s ease;-o-transition:all 1s ease;transition:all 1s ease;color:#666}.list-plugin-sub{width:100%;margin: 1%;display: inline-block;}.list-plugin-sub img{width:100px;float: left;margin-right: 25px;}#list-plugin-sub-title a{font-size:20px;color:#1DAB92}#list-plugin-sub-title span{color:#888}#list-plugin-sub-info{font-size:14px;color:#009fd4;margin-top:-3px}#list-plugin-sub-description{font-size:15px;color:#555}.pagination-selected{background-color:#11C09B}.pagination>li.pagination-selected>a,.pagination>li.pagination-selected>span{color:#fff!important}.pagination>li.pagination-selected>a:hover,.pagination>li.pagination-selected>span:hover{border:1px solid transparent!important;transition:none!important}</style>";
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
        <h1 id="pluginmcvn-bigtitle" class="padd-big">Plugin Nước ngoài</h1>
        <div id="side-area">
            <div id="side-top" class="padd-large">
                <div class="side-top-heading">Danh mục</div>
                <div class="side-top-menu">
                    <a href="http://pluginmcvn.cf/resource/foreign.plugin?page=<?php echo $page; ?>">Toàn bộ</a>
                    <a href="http://pluginmcvn.cf/resource/foreign.plugin?category=1&page=<?php echo $page; ?>">Công cụ và tiện ích</a>
                    <a href="http://pluginmcvn.cf/resource/foreign.plugin?category=2&page=<?php echo $page; ?>">Vui vẻ</a>
                    <a href="http://pluginmcvn.cf/resource/foreign.plugin?category=3&page=<?php echo $page; ?>">Quản lý world</a>
                    <a href="http://pluginmcvn.cf/resource/foreign.plugin?category=4&page=<?php echo $page; ?>">Kinh tế</a>
                    <a href="http://pluginmcvn.cf/resource/foreign.plugin?category=5&page=<?php echo $page; ?>">Chế độ chơi</a>
                    <a href="http://pluginmcvn.cf/resource/foreign.plugin?category=6&page=<?php echo $page; ?>">Thư viện & API</a>
                </div>
            </div>
            <div id="side-bottom" class="padd-large padd-top-small">
                <?php
                if(isLogin()){
                    echo '<a class="btn success" href="http://pluginmcvn.cf/dashboard/do.php?action=add_plugin">Đăng plugin mới</a>';
                }
                echo $listPlugin; ?>
            </div>
            <div id="side-menu" class="padd-large padd-top-medium">
                <?php echo $pagination; ?>
            </div>
        </div>
    </div>
<?php
$base->registerFooter(
    $setup->googleAnalyticsCode,
    $setup->importJS
);
?>