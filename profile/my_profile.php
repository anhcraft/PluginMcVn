<?php
require '../include/pluginUtils.php';
 
if(!isLogin()){
	header("Location: ". regurl("@p://@d/error.php?c=403"));
}

class Setup {
	var $title = "Plugin MCVN - Trang cá nhân";
	var $robots = "{private}";
	var $description = "";
	var $importCSS = array("jquery.modal.min.css");
	var $importJS = array("jquery.modal.min.js","jquery.form.js");
	var $importHTML = "<style>@media screen and (max-width:1000px){#cover-image {background-size: auto 500px;background-position: 50% 0;}#side-left,#side-right{width:100% !important;}#side-area>div{min-height:0 !important;}#side-left{float:none;border-bottom:1px #DADADA solid;border-right:none !important;}#member-infoarea{text-align:center !important;}}#cover-area,#cover-image{width:100%;height:350px}#avatar-image,#cover-image{background-size:100%;background-repeat:no-repeat}.pluginmcvn-container{padding:0!important}.menu{position:relative!important}#cover-edit{position:absolute;right:80px;font-size:30px;background-color:#000;padding:0 6px 0 12px}.btn{padding:6px 12px!important;font-size:15px!important}.progress-bar{padding:0!important;font-size:14px!important}#side-left{float:left;width:28%;border-right:1px #DADADA solid;padding:20px 2%}#avatar-image{width:150px;border-radius:100%;border:3px solid #008AFF;margin:auto;display:block;height:150px}#member-name{text-align:center;font-family:Itim,'Open Sans';font-size:30px}#member-name .label{background-color:#ce29d8;margin-left:10px;padding:3px 6px;text-transform:lowercase;text-align:center}#avatar-area{padding-bottom:10px}#member-username{text-align:center;color:#0c8c48}#member-infoarea{padding-top:20px;font-size:15px}#member-infoarea>div>i{width:15px;color:#ff7474}#member-social a{color:#0091C0;font-size:20px;padding:0 10px}#member-social{padding-top:30px;text-align:center}#side-area>div{min-height:800px}.plugin-list-sub{background:#fff;padding:10px 20px;font-size:15px}.plugin-list-title a{color:#ffaa16!important;font-size:20px;font-weight:700}.plugin-list-title a span{color:#aeaeae!important}.plugin-list-title{padding-bottom:10px}#success-box{position:fixed;z-index:99999;bottom:0}#side-right{padding:10px 2%;display:inline-block;width:72%}form input[type=email],form input[type=password],form input[type=text],form textarea{display:block;border:2px solid #3e92ff!important;width:100%;padding:6px 12px;cursor:text;border-radius:5px}#edit-info-form input[type=number]{display:inline-block;border:2px solid #3e92ff;padding:6px 12px;width:100px;cursor:text;border-radius:5px;margin-right:5px}#edit-info-form input[type=radio]{margin-right:5px}#edit-info-form label{margin-top:15px;margin-bottom:5px;display:block}#edit-info-form{width:100%;height:300px;overflow:auto}</style>";
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

$name = $id = $email = $birth = $gender = $introduce = $avatar = $coverimg = $google = $minecraftvn = $facebook = $spigotmc = $twitter = $github = $website = "";
$isdev = null;
$conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
mysqli_set_charset($conn,"utf8");
$sql = $conn->query("SELECT * FROM `account` WHERE `user`='".getLogger()."'");
if($sql->num_rows > 0){
	while($r = $sql->fetch_assoc()) {
		if($r["name"] == ""){
			$name = getLogger();
		} else {
			$name = $r["name"];
		}
		$id = $r["id"];
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
?>
<div class="container pluginmcvn-container">
	<div id="cover-area">
		<div id="cover-edit"><a href="#upload_cover_image" rel="modal:open"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></div>
		<div id="cover-image" style="background-image: url(<?php echo $coverimg; ?>)"></div>
		<div id="upload_cover_image" style="display:none">
			<h3>Sửa ảnh bìa</h3>
			<form id="upload-cover-image-form" action="upload_cover_image.php" method="post" enctype="multipart/form-data">
				<input type="file" accept="image/*" name="cover_image" style="margin-bottom: 10px;display:block" />
				<input type="checkbox" name="clear_cover_image" />&nbsp;&nbsp;Xóa ảnh
				<br><br>
				<div class="progress">
					<div style="width:0%" class="progress-bar progress-striped success" id="upload-cover-image-form-progress">0%</div>
				</div>
				<br>
				<p id="upload-cover-image-form-message"></p>
				<br>
				<button class="btn info"><i class="fa fa-cloud-upload" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Tải ảnh</button>
			</form>
			<script>
$(document).ready(function(){
	var options = { 
		beforeSend: function(){
			$("#upload-cover-image-form-progress").html("0%");
			$("#upload-cover-image-form-progress").width('0%');
			$("#upload-cover-image-form-message").html("");
		}, uploadProgress: function(event, position, total, percentComplete){
			$("#upload-cover-image-form-progress").width(percentComplete+'%');
			$("#upload-cover-image-form-progress").html(percentComplete+'%');
		}, success: function(){
			$("#upload-cover-image-form-progress").width('100%');
			$("#upload-cover-image-form-progress").html('100%');
		}, complete: function(response){
			if(response.responseText == "uploaded" || response.responseText == "cleared"){
				location.reload(true);
			} else {
				$("#upload-avatar-image-form-progress").html("0%");
				$("#upload-avatar-image-form-progress").width('0%');
				$("#upload-cover-image-form-message").html(response.responseText);
			}
		}, error: function(){
			$("#upload-cover-image-form-message").html("Không thể tải ảnh!");
		}
	};
	
	$("#upload-cover-image-form").ajaxForm(options);
});
</script>
		</div>
	</div>
	<div id="side-area">
		<div id="side-left">
			<div id="avatar-area">
				<a href="#upload_avatar" rel="modal:open"><div style="background-image: url(<?php echo $avatar; ?>)" id="avatar-image"></div></a>
				<div id="upload_avatar" style="display:none">
					<h3>Sửa ảnh đại diện</h3>
					<form id="upload-avatar-image-form" action="upload_avatar.php" method="post" enctype="multipart/form-data">
						<input type="file" accept="image/*" name="avatar_image" style="margin-bottom: 10px;display:block" />
						<input type="checkbox" name="clear_avatar" />&nbsp;&nbsp;Xóa ảnh
						<br><br>
						<div class="progress">
							<div style="width:0%" class="progress-bar progress-striped success" id="upload-avatar-image-form-progress">0%</div>
						</div>
						<br>
						<p id="upload-avatar-image-form-message"></p>
						<br>
						<button class="btn info"><i class="fa fa-cloud-upload" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Tải ảnh</button>
					</form>
					<script>
		$(document).ready(function(){
			var options = { 
				beforeSend: function(){
					$("#upload-avatar-image-form-progress").html("0%");
					$("#upload-avatar-image-form-progress").width('0%');
					$("#upload-avatar-image-form-message").html("");
				}, uploadProgress: function(event, position, total, percentComplete){
					$("#upload-avatar-image-form-progress").width(percentComplete+'%');
					$("#upload-avatar-image-form-progress").html(percentComplete+'%');
				}, success: function(){
					$("#upload-avatar-image-form-progress").width('100%');
					$("#upload-avatar-image-form-progress").html('100%');
				}, complete: function(response){
					if(response.responseText == "uploaded" || response.responseText == "cleared"){
						location.reload(true);
					} else {
						$("#upload-avatar-image-form-progress").html("0%");
						$("#upload-avatar-image-form-progress").width('0%');
						$("#upload-avatar-image-form-message").html(response.responseText);
					}
				}, error: function(){
					$("#upload-avatar-image-form-message").html("Không thể tải ảnh!");
				}
			};
			
			$("#upload-avatar-image-form").ajaxForm(options);
		});
		</script>
				</div>
			</div>
			<div id="member-name"><?php 
				echo $name; 
				if($isdev){
					//echo "<div class='label'>Dev</div>";
				}
				?></div>
			<div id="member-username">#<?php echo getLogger(); ?></div>		
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
			<a href="#edit_info" rel="modal:open" class="btn success">Sửa thông tin</a>&nbsp;&nbsp;&nbsp;
			<!-- FORM SỬA THÔNG TIN -->
			<div id="edit_info" style="display:none">
				<h3>Sửa thông tin cá nhân</h3>
				<form action="edit_info.php" method="post" id="edit-info-form">
					<label>Tên</label>
					<input type="text" name="name" value="<?php echo $name; ?>" />
					<label>Email</label>
					<input type="email" required name="email" value="<?php echo $email; ?>" />
					<label>Ngày sinh</label>
					<div>
						<?php $births = explode("/",$birth); ?>
						<input type="number" name="birth-day" min="1" max="31" value="<?php echo $births[0]; ?>">/
						<input type="number" name="birth-month" min="1" max="12" value="<?php echo $births[1]; ?>">/
						<input type="number" name="birth-year" min="1990" max="2017" value="<?php echo $births[2]; ?>">
					</div>
					<label>Giới tính</label>
					<div>
						<?php
							$genderradio_a = "";
							$genderradio_b = "";
							if($gender == "Nam" || $gender == "Không xác định"){
								$genderradio_a = "checked";
							} else {
								$genderradio_b = "checked";
							}
						?>
						<input type="radio" name="gender" value="male" <?php echo $genderradio_a; ?>>Nam&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="radio" name="gender" value="female" <?php echo $genderradio_b; ?>>Nữ
					</div>
					<label>Giới thiệu</label>
					<textarea name="introduce"><?php echo $introduce; ?></textarea>
					<label>Địa chỉ web</label>
					<input type="text" name="website" value="<?php echo $website; ?>" />
					<label>Link Facebook</label>
					<input type="text" name="facebook" value="<?php echo $facebook; ?>" />
					<label>Link Google+</label>
					<input type="text" name="google" value="<?php echo $google; ?>" />
					<label>Link Twitter</label>
					<input type="text" name="twitter" value="<?php echo $twitter; ?>" />
					<label>Link MinecraftVN</label>
					<input type="text" name="minecraftvn" value="<?php echo $minecraftvn; ?>" />
					<label>Link SpigotMC</label>
					<input type="text" name="spigotmc" value="<?php echo $spigotmc; ?>" />
					<label>Link Github</label>
					<input type="text" name="github" value="<?php echo $github; ?>" />
					<br>
					<p id="edit-info-form-message"></p>
					<br>
					<button class="btn info" id="edit-info-form-submit"><i class="fa fa-check" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Cập nhật</button>
				</form>
				<script>
$(document).ready(function(){
	var options = { 
		complete: function(response){
			if(response.responseText == "edited"){
				location.reload(true);
			} else {
				$("#edit-info-form-message").html(response.responseText);
			}
		}, error: function(){
			$("#edit-info-form-message").html("Không thể cập nhật!");
		}
	};
	
	$("#edit-info-form").ajaxForm(options);
});
				</script>
			</div>
			<!-- ------------------ -->
			<a href="#change_password" rel="modal:open" class="btn primary">Đổi mật khẩu</a>
			<div id="change_password" style="display:none">
				<h3>Đổi mật khẩu</h3><br>
				<form action="change_password.php" method="post" id="change-password-form">
					<input name="opass" placeholder="Mật khẩu cũ" required style="margin-bottom: 10px;" type="password">					
					<input type="password" name="npass" placeholder="Mật khẩu mới" required />
					<p id="change-password-form-message"></p>
					<br>
					<button class="btn info" id="change-password-form-submit">Đồng ý</button>
				</form>
				<script>
$(document).ready(function(){
	var options = { 
		complete: function(response){
			if(response.responseText == "changed"){
				location.reload(true);
			} else {
				$("#change-password-form-message").html(response.responseText);
			}
		}, error: function(){
			$("#change-password-form-message").html("Không thể đổi mật khẩu!");
		}
	};
	
	$("#change-password-form").ajaxForm(options);
});
				</script>
			</div>
            <p>Link chia sẻ trang cá nhân: <a href="http://pluginmcvn.cf/u/<?php echo getLogger(); ?>">http://pluginmcvn.cf/u/<?php echo getLogger(); ?></a></p>
			
			<h4></h4>
		</div>
	</div>
</div>
<?php
$base->registerFooter(
	$setup->googleAnalyticsCode,
	$setup->importJS
);
?>