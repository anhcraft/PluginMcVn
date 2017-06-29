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
$id = isset($_GET['id']) ? $_GET['id'] : null;
if($id == null){header("Location: ". regurl("@p://@d/resource/free.plugin"));}

$title = $author = $category = $date = $description = $download = $total_download = $version = $icon = $mcversions = $content = $github = $price = $pre = $contact = $frgpl = "";
$conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
mysqli_set_charset($conn,"utf8");
date_default_timezone_set('Asia/Ho_Chi_Minh');
$sql = $conn->query("SELECT * FROM `plugins` WHERE `id`='".$id."'");
if($sql->num_rows > 0):
	while($cr = $sql->fetch_assoc()) {
		$title = $cr["title"];
		$author = $cr["author"];
		$category = $cr["category"];
		$date = $cr["date"];
		$description = deword($cr["description"]);
		$download = $cr["download"];
		$total_download = $cr["total_download"];
		$version = $cr["version"];
		$icon = $cr["icon"];
		$mcversions = $cr["mcversions"];
		$content = deword($cr["content"]);
		$github = $cr["github"];
		$price = $cr["price"];
		$contact = $cr["contact"];
		$pre = $cr["pre"];
        $frgpl = $cr["frgpl"];
    }
else:
	header("Location: ". regurl("@p://@d/resource/free.plugin"));
endif;

$conn->close();

// pre
if($pre != null){
    if(!isLogin()){
        header("Location: ". regurl("@p://@d/error.php?c=require_login"));
    }
	$style = "#plugin-header{background-color:#8b00ae}#plugin-description{color:#6fffa3;margin-top:10px}#download-button{width: 100%;background-color:#ec9337;color:#fff;padding:6px 30px;float:left;margin-right:10px;font-size:18px}#download-button:hover{background-color:#ca6e0f;-webkit-transition:all 2s ease;-moz-transition:all 2s ease;-ms-transition:all 2s ease;-o-transition:all 2s ease;transition:all 2s ease}#plugin-header a.btn{background-color:#3a75f9;color:#fff}";
}
// free
else {
    if($frgpl == "") {
        $style = "#plugin-header{background-color:#18578e}#plugin-description{color:#aef563;margin-top:10px}#download-button{width: 100%;background-color:#39c7ff;color:#fff;padding:6px 30px;float:left;margin-right:10px;font-size:25px}#download-button:hover{background-color:#15bdff;-webkit-transition:all 2s ease;-moz-transition:all 2s ease;-ms-transition:all 2s ease;-o-transition:all 2s ease;transition:all 2s ease}";
    } else {
        $style = "#plugin-header{background-color:#057b6a}#plugin-description{color:#fff;margin-top:10px}#download-button{width: 100%;background-color:#0082a7;color:#fff;padding:6px 30px;float:left;margin-right:10px;font-size:25px}#download-button:hover{background-color:#007596;-webkit-transition:all 2s ease;-moz-transition:all 2s ease;-ms-transition:all 2s ease;-o-transition:all 2s ease;transition:all 2s ease}";
    }
}
$style .= "#plugin-content img {
    max-width: 100%;
    max-height: 400px;
}.pluginmcvn-container{padding:0!important}#plugin-title{font-size:42px;margin:0!important;color:#fff;font-family:Itim,'Open Sans'}.menu{position:relative!important}.side-top-heading{font-size:20px;margin-bottom:5px}.side-top-menu a{color:#989898;padding:0 20px}.side-top-menu a:first-child{padding:0!important}.side-top-menu a:hover{-webkit-transition:all 1s ease;-moz-transition:all 1s ease;-ms-transition:all 1s ease;-o-transition:all 1s ease;transition:all 1s ease;color:#666}#plugin-info span,#plugin-info span a{color:#fff!important}#plugin-icon{float:left;margin-right:30px}#plugin-icon img{width:150px}#plugin-title span{font-size:25px!important;color:#78fffa}#plugin-info span{padding-right:20px}#plugin-info span i{color:#ffc164!important}#plugin-header{height:220px}#side-top-right{float:right;width:25%;background-color:#fff;margin:40px 50px 30px 0;padding:15px 30px}#plugin-mcversions{border-bottom:1px #C2C2C2 dashed;padding-bottom:10px}#plugin-content{word-break:break-word;min-height:500px}#vote i.fa{font-size:20px;padding-left:5px}#vote-view img{height:10px}#side-top-left{width:65%;float:left;background-color:#fff;margin:40px 0 30px 50px;padding:15px 30px}#download-button>span{display:block;font-size:15px}#side-area{float:left;width:100%;background-color:#cacaca}#side-top-left,#side-top-right{border-radius:20px;-webkit-box-shadow:0 0 10px .1px #A89E9E;box-shadow:0 0 10px .1px #A89E9E}.fb_iframe_widget{float: left;margin-right: 10px}@media (max-width: 1250px) and (min-width: 1000px){#side-top-left{padding:0 15px;margin:20px 0 30px 20px}#side-top-right{margin:20px 20px 30px 0;padding:20px 30px;width:28%}}@media (max-width:999px) and (min-width:800px){#side-top-left{padding:0 15px;margin:20px 0 30px 20px;width:62%}#side-top-right{margin:20px 20px 30px 0;padding:20px 30px;width:30%}}@media (max-width:799px) and (min-width:700px){#side-top-left{padding:0 15px;margin:20px 0 30px 20px;width:62%}#side-top-right{margin:20px 20px 30px 0;padding:20px 30px;width:28%}#download-button{font-size:20px}#download-button > span{font-size:15px}}@media (max-width:699px){#side-top-left{padding:0 15px;margin:20px 30px;width:90%}#side-top-right{margin:20px 30px;padding:20px 30px;width:90%}#download-button{font-size:20px}#download-button > span{font-size:15px}#plugin-icon img{width:120px}#plugin-header{height:360px}#plugin-icon{float:none;text-align:center;margin-right:0}#plugin-title,#plugin-info,#plugin-description{text-align:center}}#plugin-manage{margin-bottom: 20px}#plugin-manage a.btn{width: 100%;display: block}";
/*********************************/

class Setup {
	var $robots = "{public}";
    var $importCSS = array("http://pluginmcvn.cf/profile/jquery.modal.min.css");
    var $importJS = array("http://pluginmcvn.cf/profile/jquery.modal.min.js");
	var $googleSiteVerification = "kNkDxfzm97qD6sJJbkDGt9S36SuhMFiVBKPyjeS0t10";
	var $googleAnalyticsCode = "UA-86116936-1";
}

$setup = new Setup;
$base = new PluginMCVN;
$base->newHeader(true);
$base->registerHeader(
	"Plugin MCVN | ".$title,
    $setup->robots,
	$description,
	$setup->googleSiteVerification,
	$setup->importCSS,
	"<style>".$style."</style><div id='fb-root'></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = '//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.8&appId=643311355855403';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>"
);
$base->createMenu();
?>

<div class="container pluginmcvn-container">
	<div id="plugin-header" class="padd-big">
		<div id="plugin-icon"><img src="<?php echo $icon; ?>" /></div>
		<div id="plugin-title"><?php echo $title; ?> <span><?php echo $version; ?></span></div>
		<div id="plugin-info">
			<span><i class="fa fa-user" aria-hidden="true"></i>&nbsp;&nbsp;<a href="http://pluginmcvn.cf/u/<?php echo $author; ?>"><?php echo $author; ?></a></span>
				<?php if($pre != null){?>
			<span><i class="fa fa-bookmark" aria-hidden="true"></i>&nbsp;&nbsp;<a href="http://pluginmcvn.cf/resource/premium.plugin?category=<?php echo $category; ?>"><?php echo toCategory($category); ?></a></span>
				<?php } else { ?>
			<span><i class="fa fa-bookmark" aria-hidden="true"></i>&nbsp;&nbsp;<a href="http://pluginmcvn.cf/resource/free.plugin?category=<?php echo $category; ?>"><?php echo toCategory($category); ?></a></span>
								<?php } ?>
			<span><i class="fa fa-calendar" aria-hidden="true"></i>&nbsp;&nbsp;<?php echo $date; ?></span>
		</div><div id="plugin-description"><?php echo $description; ?></span></div>
	</div>
	<div id="side-area">
        <div id="side-top-left">
            <div id="side-top">
                <? if($pre != null && isPlayerBought($id,getLogger())){ ?>
                    <br><a href="http://pluginmcvn.cf/resource/ip.php?id=<?php echo $id; ?>" class="btn success">Cập nhật Ip Server</a><?php } ?>
                <br>
            </div>

            <div id="side-bottom" class="padd-large">
                <?php
                if($github != ""){
                    echo '<div id="plugin-github">
                    <i class="fa fa-github" aria-hidden="true"></i>&nbsp;&nbsp;
                    Github: <a href="'.$github.'" target="_blank">'.$github.'</a>
                </div>
                    ';
                }

            $mcversions = substr_replace($mcversions,"",0,strlen("#"));
            $versions = explode("#",$mcversions);
            $mvs = "";
            foreach($versions as $v) {
                $mvs .= $v . ", ";}
                ?>
                <div id="plugin-mcversions">
                    <?php if($pre != null){?>
                    <i class="fa fa-money" aria-hidden="true"></i>&nbsp;&nbsp;
                    Giá: <?php echo $price; ?><br>
                    <?php } ?>
                    <i class="fa fa-life-ring" aria-hidden="true"></i>&nbsp;&nbsp;
                    Phiên bản Minecraft: <?php echo preg_replace('/(, (?!.*, ))/', '', $mvs); ?>
                </div>
                <br>
                <br>
                <div id="plugin-content">
                    <?php echo $content; ?></div>
                <br><br>
                <div id="plugin-footer">
<div id="disqus_thread"></div>
<script>

/**
*  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
*  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables*/

var disqus_config = function () {
this.page.url = window.location;  // Replace PAGE_URL with your page's canonical URL variable
this.page.identifier = "pluginmcvn-plugin-page"; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
};

(function() { // DON'T EDIT BELOW THIS LINE
var d = document, s = d.createElement('script');
s.src = 'https://pluginmcvn-plugin-page.disqus.com/embed.js';
s.setAttribute('data-timestamp', +new Date());
(d.head || d.body).appendChild(s);
})();
</script>
<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
                </div>
            </div>
        </div>
        <div id="side-top-right">
            <?php if($author == getLogger()) {?>
            <div id="plugin-manage">
                <a class="btn info" href="http://pluginmcvn.cf/dashboard/do.php?action=update_plugin&id=<?php echo $id; ?>">Cập nhật phiên bản</a>
                <a class="btn warning" href="http://pluginmcvn.cf/dashboard/do.php?action=edit_plugin&id=<?php echo $id; ?>">Sửa plugin</a>
                <?php
                if($frgpl == "") {
                    echo '<a class="btn success" href="http://pluginmcvn.cf/dashboard/do.php?action=changemode_plugin&id='.$id.'">Thay chế độ</a>';
                }
                ?>
                <a class="btn danger" href="http://pluginmcvn.cf/dashboard/do.php?action=remove_plugin&id=<?php echo $id; ?>">Xóa plugin</a>
                <?php
                if($pre != null){
                    echo '<a class="btn primary" href="http://pluginmcvn.cf/dashboard/do.php?action=customer_manage&id='.$id.'">Quản lý người mua</a>';
                }
                ?>
            </div>
            <?php } ?>
            <a id="download-button" target="_blank" href="http://pluginmcvn.cf/resource/redirect.php?url=<?php
            if($pre == null){
                echo $download;
            } else {
                echo $contact;
            }
            ?>">
                <i class="fa fa-cloud-download" aria-hidden="true"></i>&nbsp;&nbsp;<?php
                if($pre == null){
                    echo "Tải về";
                } else {
                    $ct = "Mua qua chủ plugin";
                    if(isPlayerBought($id,getLogger())){
                        $ct = "Tải qua chủ plugin";
                    }
                    echo $ct;
                }

                function formatSizeUnits($bytes){
                    if ($bytes >= 1073741824) {
                        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
                    } elseif ($bytes >= 1048576) {
                        $bytes = number_format($bytes / 1048576, 2) . ' MB';
                    } elseif ($bytes >= 1024) {
                        $bytes = number_format($bytes / 1024, 2) . ' kB';
                    } elseif ($bytes > 1) {
                        $bytes = $bytes . ' bytes';
                    } elseif ($bytes == 1) {
                        $bytes = $bytes . ' byte';
                    } else {
                        $bytes = '0 bytes';
                    }
                    return $bytes;
                }
                if($pre == null){?>
                    <span><span id="downloaded-total"><?php echo $total_download; ?></span> lượt tải</span>
                <?php
                    if(strpos($download, "http://pluginmcvn.cf/resource/files/") != false) {
                        echo "<span id='size-total'>" . formatSizeUnits(filesize("./files/" . str_replace("http://pluginmcvn.cf/resource/files/", "", $download))) . "</span>";
                    }
                }
                ?>
            </a>
            <?php if($pre == null){?>
                <script>
                    $(document).ready(function(){
                        $("#download-button").click(function(){
                            $.get("http://pluginmcvn.cf/resource/new_download.php?id=<?php echo $id; ?>", function(data, status){
                                if(data == "1"){
                                    $("#downloaded-total").html($("#downloaded-total").html()*1+1);
                                }
                            });
                        });
                    });
                </script>
            <?php } ?>
            <br>
            <br>
            <br>
            <?php
            function createVote($loop){
                $result = "";
                for($i = 0; $i < $loop; $i++){
                    $result .= '<i class="fa fa-star color-warning" aria-hidden="true"></i>';
                }
                for($i = 0; $i < (5 - $loop); $i++){
                    $result .= '<i class="fa fa-star" style="color:#aaa !important" aria-hidden="true"></i>';
                }
                return $result . '&nbsp;&nbsp;&nbsp;(' . $loop . ' sao)';}

            if(isLogin()){
                ?>
                <a href="#vote" class="btn primary" rel="modal:open" style="padding: 3px 9px;font-size: 15px;margin-top: 10px;">Đánh giá plugin này</a>
                <div id="vote" style="display:none">
                    <h3>Đánh giá plugin</h3>
                    <div id="vote-area">
                        <input type="radio" name="vote"
                               value="0" onclick="newVote(this.value)"> <?php echo createVote(5); ?> <br>
                        <input type="radio" name="vote"
                               value="1" onclick="newVote(this.value)"> <?php echo createVote(4); ?> <br>
                        <input type="radio" name="vote"
                               value="2" onclick="newVote(this.value)"> <?php echo createVote(3); ?> <br>
                        <input type="radio" name="vote"
                               value="3" onclick="newVote(this.value)"> <?php echo createVote(2); ?> <br>
                        <input type="radio" name="vote"
                               value="4" onclick="newVote(this.value)"> <?php echo createVote(1); ?>
                    </div>
                    <script>
                        function newVote(num) {
                            $(function () {
                                $.get("http://pluginmcvn.cf/resource/vote.php?n="+num+"&id="+<?php echo $id; ?>,
                                    function (data, status) {
                                        $("#vote-area").html(data);
                                    });
                            });
                        }
                    </script>
                    <?php if(getVote($id,getLogger()) != null && getVote($id,getLogger()) != ""){?>
                        <p id="revote">Bạn đã đánh giá plugin này. <a href='javascript:void(0)'>Bình chọn lại</a></p>
                        <script>
                            $(document).ready(function(){
                                $("#vote-area").hide();
                                $("#revote a").click(function(){
                                    $("#revote").hide();
                                    $("#vote-area").show();});});
                        </script>
                    <?php } ?>
                </div>
            <?php }

            $s1 = $s2 = $s3 = $s4 = $s5 = 0;
            $conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
            mysqli_set_charset($conn,"utf8");
            $sql = $conn->query("SELECT * FROM `vote_plugin`");
            if($sql->num_rows > 0){
                while($cr = $sql->fetch_assoc()) {
                    $d = $cr["data"];
                    $allPlugin = explode("#", $d);
                    for($i = 0; $i < count($allPlugin); $i++){
                        $info = explode(",",$allPlugin[$i]);
                        if($info[0] == $id){
                            if($info[1] == "0"){
                                $s5 += 1;} else if($info[1] == "1"){
                                $s4 += 1;} else if($info[1] == "2"){
                                $s3 += 1;} else if($info[1] == "3"){
                                $s2 += 1;} else if($info[1] == "4"){
                                $s1 += 1;}
                        }
                    }
                }
            }
            $conn->close();

            $stotal = $s5 + $s4 + $s3 + $s2 + $s1;
            if(0 < $s5){
                $ts5 = 100*round($s5/$stotal,5);} else {
                $ts5 = 0;}if(0 < $s4){
                $ts4 = 100*round($s4/$stotal,5);} else {
                $ts4 = 0;}if(0 < $s3){
                $ts3 = 100*round($s3/$stotal,5);} else {
                $ts3 = 0;}if(0 < $s2){
                $ts2 = 100*round($s2/$stotal,5);} else {
                $ts2 = 0;}if(0 < $s1){
                $ts1 = 100*round($s1/$stotal,5);} else {
                $ts1 = 0;}
            ?><br>
            <div id="vote-view" class="padd-top-small">
                Tổng cộng <?php echo $stotal; ?> lượt bình chọn
                <br>5&nbsp; <i class="fa fa-star
                    color-warning" aria-hidden="true"></i>&nbsp; <img
                        src="http://pluginmcvn.cf/resource/vote.gif"
                        width='<?php echo $ts5; ?>'>&nbsp; <?php echo $ts5; ?>%
                <br>4&nbsp; <i class="fa fa-star
                    color-warning" aria-hidden="true"></i>&nbsp; <img
                        src="http://pluginmcvn.cf/resource/vote.gif"
                        width='<?php echo $ts4; ?>'>&nbsp; <?php echo $ts4; ?>%
                <br>3&nbsp; <i class="fa fa-star
                    color-warning" aria-hidden="true"></i>&nbsp; <img
                        src="http://pluginmcvn.cf/resource/vote.gif"
                        width='<?php echo $ts3; ?>'>&nbsp; <?php echo $ts3; ?>%
                <br>2&nbsp; <i class="fa fa-star
                    color-warning" aria-hidden="true"></i>&nbsp; <img
                        src="http://pluginmcvn.cf/resource/vote.gif"
                        width='<?php echo $ts2; ?>'>&nbsp; <?php echo $ts2; ?>%
                <br>1&nbsp; <i class="fa fa-star
                    color-warning" aria-hidden="true"></i>&nbsp; <img
                        src="http://pluginmcvn.cf/resource/vote.gif"
                        width='<?php echo $ts1; ?>'>&nbsp; <?php echo $ts1; ?>%
            </div>
            <br>
            <br>
            <div class="fb-share-button" data-href="http://pluginmcvn.cf/plugin/<?php echo $id; ?>" data-layout="button_count" data-size="large" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=http://pluginmcvn.cf/plugin/<?php echo $id; ?>&src=sdkpreparse">Chia sẻ</a></div>
            <a class="twitter-share-button"
               href="https://twitter.com/intent/tweet?text=Plugin%20này%20hay%20nè:"
               data-size="large">
                Tweet</a>
            <script>window.twttr = (function(d, s, id) {
                    var js, fjs = d.getElementsByTagName(s)[0],
                        t = window.twttr || {};
                    if (d.getElementById(id)) return t;
                    js = d.createElement(s);
                    js.id = id;
                    js.src = "https://platform.twitter.com/widgets.js";
                    fjs.parentNode.insertBefore(js, fjs);
                    t._e = [];
                    t.ready = function(f) {
                        t._e.push(f);
                    };
                    return t;
                }(document, "script", "twitter-wjs"));</script>
        </div>
	</div>
</div>
<?php
$base->registerFooter(
	$setup->googleAnalyticsCode,
	$setup->importJS
);
?>