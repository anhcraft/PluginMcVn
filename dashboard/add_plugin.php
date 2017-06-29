<?php
class Setup {
	var $title = "Plugin MCVN - Thêm plugin";
	var $robots = "{private}";
	var $description = "";
	var $importCSS = array();
	var $importJS = array();
	var $importHTML = "<script src='./editor/ckeditor.js'></script><style>.pluginmcvn-container{padding: 100px 40px !important}form select {
  border: 1px #ddd solid;
  width: 100%;
  height: 45px;
  padding: 8px 12px;
}body {
    overflow-x: hidden;
}
form input[type=file] {
  cursor: pointer !important;
}form input {
    display: block;
    width: 100%;
    margin-top: 10px;
    margin-bottom: 20px;
    border: 2px #03a6ff solid;
    padding: 8px 16px;
    border-radius: 8px;
    cursor: text;
}input[type=checkbox] {
    display: inline !important;
    width: 20px !important;
    cursor: pointer !important;
}input[type=radio] {
    display: inline!important;
    width: 20px!important;
    cursor: pointer!important;
}</style>";
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
$error = null;
$ready = true;
$pname = $pversion = $pdescription = $picon = $pgithub = $pcategory = $pcontent = $pdownload = "";
$v18 = $v19 = $v110 = $v111 = false;
$frgpl = "0";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// kiểm tra thông tin
	
	if (empty($_POST["pname"])):
		$error .= "Tên plugin không được để trống!<br>";
		$ready = false;
	else:
		$pname = trim(htmlspecialchars($_POST["pname"]));
	endif;
	
	if (empty($_POST["pversion"])):
		$error .= "Phiên bản plugin không được để trống!<br>";
		$ready = false;
	else:
		$pversion = trim(htmlspecialchars($_POST["pversion"]));
	endif;
	
	if (empty($_POST["pdescription"])):
		$error .= "Miêu tả plugin không được để trống!<br>";
		$ready = false;
	else:
		$pdescription = trim(htmlspecialchars($_POST["pdescription"]));
	endif;
	
	$v18 = false;
	$v19 = false;
	$v110 = false;
	$v111 = false;
	$mcversions = "";
	if (!empty($_POST["mcversion18"])){
		$v18 = true;
		$mcversions .= "#1.8";
	}
	if (!empty($_POST["mcversion19"])){
		$v19 = true;
		$mcversions .= "#1.9";
	}
	if (!empty($_POST["mcversion110"])){
		$v110 = true;
		$mcversions .= "#1.10";
	}
	if (!empty($_POST["mcversion111"])){
		$v111 = true;
		$mcversions .= "#1.11";
	}
	
	if(!$v18 && !$v19 && !$v110 && !$v111){
		$error .= "Plugin phải dùng được ít nhất 1 bản Minecraft!<br>";
		$ready = false;
	}
	
	$picon = "http://pluginmcvn.cf/include/logo.png";
	$pgithub = "";
	$frgpl = "";
	if (!empty($_POST["picon"])){
		$picon = trim(htmlspecialchars($_POST["picon"]));
	}
	
	if (!empty($_POST["pgithub"])){
		$pgithub = trim(htmlspecialchars($_POST["pgithub"]));
	}

    if (!empty($_POST["frgpl"])) {
        $frgpl = $_POST["frgpl"];
    }
	
	$pcategory = $_POST["pcategory"];
	
	if (empty($_POST["pcontent"])):
		$error .= "Nội dung plugin không được để trống!<br>";
		$ready = false;
	else:
		$pcontent = $_POST["pcontent"];
	endif;
	
	$pdownload = null;
	$pfile = null;
	$pfilename = null;
	if($_FILES['pfile']['name'] == NULL && empty($_POST["pdownload"])){
		$error .= "File plugin chưa được chọn hoặc chưa được ghi URL!<br>";
		$ready = false;
    } else{
		if($_FILES['pfile']['name'] != NULL){
				if($_FILES['pfile']['size'] > 3 * 1024 * 1024){
					$error .= "File plugin không được lớn hơn 3MB!<br>";
					$ready = false;	
				} else{
					$pfile = $_FILES['pfile']['tmp_name'];
					$pfilename = $_FILES['pfile']['name'];
				}
		} else {
			$pdownload = trim(htmlspecialchars($_POST["pdownload"]));
		}
    }
	
	if (strlen($pname) < 3 || strlen($pname) > 50){
		$error .= "Tên plugin phải từ 3 đến 50 kí tự!<br>";
		$ready = false;
	}
	if (strlen($pversion) < 1 || strlen($pversion) > 25){
		$error .= "Phiên bản plugin phải từ 1 đến 25 kí tự!<br>";
		$ready = false;
	}
	if (strlen($pdescription) < 10 || strlen($pdescription) > 500){
		$error .= "Miêu tả plugin phải từ 10 đến 500 kí tự!<br>";
		$ready = false;
	}
	if (strlen($pcontent) < 80 || strlen($pcontent) > 2000000){
		$error .= "Nội dung plugin phải từ 80 đến 2000000 kí tự!<br>";
		$ready = false;
	}
	
	if($ready){
		$pdescription = reword($pdescription);
		$pcontent = reword($pcontent);
		
		function replaceAll($str,$replaces){
			foreach($replaces as $x => $y) {
				$str = str_replace($x, $y, $str);
			}
			return $str;
		}
		
		$replaceArray = array(
			"<meta" => "",
			"<body" => "",
			"<head" => "",
			"<!Doctype" => "",
			"<link" => "",
			"<title" => "",
			"<script" => "",
			"<style" => "",			
			"</meta" => "",
			"</body" => "",
			"</head" => "",
			"</!Doctype" => "",
			"</link" => "",
			"</title" => "",
			"</script" => "",
			"</style" => ""
		);
		
		$pcontent = replaceAll($pcontent,$replaceArray);

		$downloadURL = $pdownload;
		if($pfile != null){
			$newpath = "../resource/files/".$pfilename;
			if(!file_exists($newpath)){
				move_uploaded_file($pfile,$newpath);
			}
			$downloadURL = regurl("@p://@d/resource/files/".$pfilename);
		}
		
		$conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
		mysqli_set_charset($conn,"utf8");
		$id = generatorNewPluginID();
		$author = getLogger();
		$insert = $conn->query("INSERT INTO `plugins` (`id`, `title`, `author`, `category`, `date`, `description`, `download`, `total_download`, `version`, `icon`, `mcversions`, `content`, `github`, `frgpl`) VALUES ('".$id."','".$pname."','".$author."','".$pcategory."','".getTime()."','".$pdescription."','".$downloadURL."','0','".$pversion."','".$picon."','".$mcversions."','".$pcontent."','".$pgithub."', '".$frgpl."')");
		if ($insert === TRUE):
			$error = "<script>window.location.href = 'http://pluginmcvn.cf/dashboard/?success=added_plugin';</script><meta http-equiv='refresh' content='0; url=http://pluginmcvn.cf/dashboard/?success=added_plugin'>";
		else:
			$error .= "Thêm plugin ". $pname ." thất bại!<br>" . $conn->error;
		endif;
		$conn->close();
	}
}
/*********************************/
?>
<div class="container pluginmcvn-container">
	<h2 id="pluginmcvn-bigtitle" class="center">Thêm plugin mới</h2>
	<p class="center color-danger">Chủ plugin: <?php echo getLogger(); ?></p>
	<br>
	<?php 
	if($error != null){
		echo "<div class='alert danger'>".$error."</div>";
	}
	?>
	<br>
	<form method="post" action="" enctype="multipart/form-data">
		<input value="<?php echo $pname; ?>" type="text" required placeholder="Tên plugin" name="pname" />
		<input value="<?php echo $pversion; ?>" type="text" required placeholder="Phiên bản plugin" name="pversion" />
		<input value="<?php echo $pdescription; ?>" type="text" required placeholder="Miêu tả ngắn gọn về plugin" name="pdescription" />
		<input value="<?php echo $picon; ?>" type="text" placeholder="Link icon plugin" name="picon" />
		<input value="<?php echo $pgithub; ?>" type="text" placeholder="Github" name="pgithub" />
        <b>Loại Plugin (KHÔNG ĐỔI ĐƯỢC)</b><br>
        Bạn cần cẩn trọng ở bước lựa chọn này, nếu chọn sai, plugin của bạn sẽ bị xóa không báo trước<br>
        Plugin Việt Nam đăng trên Spigot phải chọn Plugin Việt Nam<br>
        Chỉ chủ Plugin mới được đăng Plugin Việt Nam<br>
        <input type="radio" name="frgpl" <?php if($frgpl == "0"){echo "checked";}?> value="NULL">&nbsp;&nbsp;Plugin Việt Nam (có free và premium)<br>
        <input type="radio" name="frgpl" <?php if($frgpl == "1"){echo "checked";}?> value="1">&nbsp;&nbsp;Plugin nước ngoài (chỉ có free)<br>
        <br>
		Phiên bản Minecraft<br>
		<input <?php if($v18){echo "checked";} ?> type="checkbox" name="mcversion18" value="1.8">&nbsp;&nbsp;1.8
		<input <?php if($v19){echo "checked";} ?> type="checkbox" name="mcversion19" value="1.9">&nbsp;&nbsp;1.9
		<input <?php if($v110){echo "checked";} ?> type="checkbox" name="mcversion110" value="1.10">&nbsp;&nbsp;1.10
		<input <?php if($v111){echo "checked";} ?> type="checkbox" name="mcversion111" value="1.11">&nbsp;&nbsp;1.11
		<br>
		Danh mục<br>
		<select id="category" required name="pcategory">
			<option value="1" <?php if($pcategory=="1"){echo "selected";}?>>Công cụ và tiện ích</option>
			<option value="2" <?php if($pcategory=="2"){echo "selected";}?>>Vui vẻ</option>
			<option value="3" <?php if($pcategory=="3"){echo "selected";}?>>Quản lý world</option>
			<option value="4" <?php if($pcategory=="4"){echo "selected";}?>>Kinh tế</option>
			<option value="5" <?php if($pcategory=="5"){echo "selected";}?>>Chế độ chơi</option>
			<option value="6" <?php if($pcategory=="6"){echo "selected";}?>>Thư viện & API</option>
		</select>
		<br><br>

		Nội dung<br>
		<textarea name="pcontent" required id="pcontent" cols="120"><?php echo $pcontent; ?></textarea>
		<script>
			CKEDITOR.replace('pcontent');
		</script>
		<br>
		
		Tải file plugin (.jar):<br>
		<input type="file" name="pfile" />
		hoặc bằng url (lưu ý: ưu tiên tải file)
		<input type="text" name="pdownload" value="<?php echo $pdownload; ?>" />
		<br>
		<button class="btn success">Hoàn tất</button>
	</form>
</div>
<?php
$base->registerFooter(
	$setup->googleAnalyticsCode,
	$setup->importJS
);
?>