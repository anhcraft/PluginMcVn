<?php
require '../include/pluginUtils.php';
 
$name = $email = $user = $birth = $gender = $introduce = $avatar = $coverimg = $google = $minecraftvn = $facebook = $spigotmc = $twitter = $github = $website = "";
$isdev = null;
$conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
mysqli_set_charset($conn,"utf8");
if($id != null){
	$sql = $conn->query("SELECT * FROM `account` WHERE `id`='".$id."'");
} else {
	$sql = $conn->query("SELECT * FROM `account` WHERE `user`='".$uv."'");
}
if($sql->num_rows > 0){
	while($r = $sql->fetch_assoc()) {
        if(getLogger() == $r["user"]){
            header("Location: ". regurl("@p://@d/profile/"));
        }

		if($r["name"] == ""){
			$name = $r["user"];
		} else {
			$name = $r["name"];
		}
		$id = $r["id"];
		$user = $r["user"];
		if($r["email"] == ""){
			$email = "Không có email";
		} else {
			$email = $r["email"];
		}
		if($r["birth"] == ""){
			date_default_timezone_set('Asia/Ho_Chi_Minh');
			$birth = date("d/m/Y", time());
		} else {
			$birth = $r["birth"];
		}
		if($r["gender"] == ""){
			$gender = "Không xác định";
		} else {
			if($r["gender"] == "female"){
				$gender = "Nữ";
			} else {
				$gender = "Nam";
			}
		}
		if($r["isdev"] == "false"){
			$isdev = false;
		} else {
			$isdev = true;
		}
		if($r["introduce"] == ""){
			$introduce = "Tôi là người mới :)";
		} else {
			$introduce = deword($r["introduce"]);
		}
		if($r["avatar"] == ""){
			$avatar = regurl("@p://@d/include/default_avatar.png");
		} else {
			$avatar = $r["avatar"];
		}
		if($r["coverimg"] == ""){
			$coverimg = regurl("@p://@d/include/default_cover_image.png");
		} else {
			$coverimg = $r["coverimg"];
		}
		if($r["website"] == ""){
			$website = $r["id"];
		} else {
			$website = $r["website"];
		}
		if($r["google"] == ""){$google = "#";} else {$google = $r["google"];}
		if($r["minecraftvn"] == ""){$minecraftvn = "#";} else {$minecraftvn = $r["minecraftvn"];}
		if($r["spigotmc"] == ""){$spigotmc = "#";} else {$spigotmc = $r["spigotmc"];}
		if($r["facebook"] == ""){$facebook = "#";} else {$facebook = $r["facebook"];}
		if($r["twitter"] == ""){$twitter = "#";} else {$twitter = $r["twitter"];}
		if($r["github"] == ""){$github = "#";} else {$github = $r["github"];}
	}
} else {
	echo "<script>window.location.href = '".regurl("@p://@d/error.php?c=404")."';</script><meta http-equiv='refresh' content='0; url=".regurl("@p://@d/error.php?c=404")."'>";
}
$conn->close();

class Setup {
	var $title = "Plugin MCVN - Trang cá nhân";
	var $robots = "{private}";
	var $description = "";
	var $importCSS = array("jquery.modal.min.css");
	var $importJS = array("jquery.modal.min.js","jquery.form.js");
	var $importHTML = "<style>@media screen and (max-width:1000px){#cover-image {background-size: auto 500px;background-position: 50% 0;}#side-left,#side-right{width:100% !important;}#side-area>div{min-height:0 !important;}#side-left{float:none;border-bottom:1px #DADADA solid;border-right:none !important;}#member-infoarea{text-align:center !important;}}#cover-area,#cover-image{width:100%;height:350px}#avatar-image,#cover-image{background-size:100%;background-repeat:no-repeat}.pluginmcvn-container{padding:0!important}.menu{position:relative!important}#cover-edit{position:absolute;right:10px;font-size:30px;background-color:#000;padding:0 6px 0 12px}.btn{padding:6px 12px!important;font-size:15px!important}.progress-bar{padding:0!important;font-size:14px!important}#side-left{float:left;width:28%;border-right:1px #DADADA solid;padding:20px 2%}#avatar-image{width:150px;border-radius:100%;border:3px solid #008AFF;margin:auto;display:block;height:150px}#member-name{text-align:center;font-family:Itim,'Open Sans';font-size:30px}#member-name .label{background-color:#ce29d8;margin-left:10px;padding:3px 6px;text-transform:lowercase;text-align:center}#avatar-area{padding-bottom:10px}#member-username{text-align:center;color:#0c8c48}#member-infoarea{padding-top:20px;font-size:15px}#member-infoarea>div>i{width:15px;color:#ff7474}#member-social a{color:#0091C0;font-size:20px;padding:0 10px}#member-social{padding-top:30px;text-align:center}#side-area>div{min-height:800px}.plugin-list-sub{background:#fff;padding:10px 20px;font-size:15px}.plugin-list-title a{color:#ffaa16!important;font-size:20px;font-weight:700}.plugin-list-title a span{color:#aeaeae!important}.plugin-list-title{padding-bottom:10px}#success-box{position:fixed;z-index:99999;bottom:0}#side-right{padding:10px 2%;display:inline-block;width:72%}form input[type=email],form input[type=password],form input[type=text],form textarea{display:block;border:2px solid #3e92ff!important;width:100%;padding:6px 12px;cursor:text;border-radius:5px}#edit-info-form input[type=number]{display:inline-block;border:2px solid #3e92ff;padding:6px 12px;width:100px;cursor:text;border-radius:5px;margin-right:5px}#edit-info-form input[type=radio]{margin-right:5px}#edit-info-form label{margin-top:15px;margin-bottom:5px;display:block}#edit-info-form{width:100%;height:300px;overflow:auto}</style>";
	var $googleSiteVerification = "kNkDxfzm97qD6sJJbkDGt9S36SuhMFiVBKPyjeS0t10";
	var $googleAnalyticsCode = "UA-86116936-1";
}

$setup = new Setup;
$base = new PluginMCVN;
$base->newHeader(true);
$base->registerHeader(
	$setup->title . " của " . $name,
	$setup->robots,
	$setup->description,
	$setup->googleSiteVerification,
	$setup->importCSS,
	$setup->importHTML
);
$base->createMenu();
?>
<div class="container pluginmcvn-container">
	<div id="cover-area">
		<div id="cover-image" style="background-image: url(<?php echo $coverimg; ?>)"></div>
	</div>
	<div id="side-area">
		<div id="side-left">
			<div id="avatar-area">
				<a><div style="background-image: url(<?php echo $avatar; ?>)" id="avatar-image"></div></a>
			</div>
			<div id="member-name"><?php 
				echo $name; 
				if($isdev){
					//echo "<div class='label'>Dev</div>";
				}
				?></div>
			<div id="member-username">#<?php echo $user; ?></div>		
			<div id="member-infoarea">
				<div id="member-gender"><i class="fa fa-male" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<?php echo $gender; ?></div>
				<div id="member-birth"><i class="fa fa-birthday-cake" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<?php echo $birth; ?></div>
				<div id="member-introduce"><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<?php echo $introduce; ?></div>
				<div id="member-website"><i class="fa fa-link" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<?php echo $website; ?></div>
			</div>
			<div id="member-social">
				<a href="<?php echo $facebook; ?>"><i class="fa fa-facebook" aria-hidden="true"></i></a>
				<a href="<?php echo $google; ?>"><i class="fa fa-google-plus" aria-hidden="true"></i></a>
				<a href="<?php echo $twitter; ?>"><i class="fa fa-twitter" aria-hidden="true"></i></a>
				<a href="<?php echo $minecraftvn; ?>"><i class="fa fa-cube" aria-hidden="true"></i></a>
				<a href="<?php echo $spigotmc; ?>"><i class="fa fa-share-alt" aria-hidden="true"></i></a>
				<a href="<?php echo $github; ?>"><i class="fa fa-github" aria-hidden="true"></i></a>
			</div>
		</div>
		<div id="side-right"><br>
			<h4>Plugin của <?php echo $name; ?></h4>
			<div id="plugin-list"><?php echo getAllPluginForAuthor($user,false,5); ?></div><br><br>
			<div id="disqus_thread"></div>
<script>

/**
*  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
*  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables*/
/*
var disqus_config = function () {
this.page.url = PAGE_URL;  // Replace PAGE_URL with your page's canonical URL variable
this.page.identifier = PAGE_IDENTIFIER; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
};
*/
(function() { // DON'T EDIT BELOW THIS LINE
var d = document, s = d.createElement('script');
s.src = '//pluginmcvn-profile.disqus.com/embed.js';
s.setAttribute('data-timestamp', +new Date());
(d.head || d.body).appendChild(s);
})();
</script>
<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
                                
		</div>
	</div>
</div>
<?php
$base->registerFooter(
	$setup->googleAnalyticsCode,
	$setup->importJS
);
?>