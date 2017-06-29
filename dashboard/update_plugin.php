<?php
class Setup {
	var $title = "Plugin MCVN - Cập nhật phiên bản";
	var $robots = "{private}";
	var $description = "";
	var $importCSS = array();
	var $importJS = array();
	var $importHTML = "<script src='./editor/ckeditor.js'></script><style>.pluginmcvn-container{padding: 100px 40px !important}body {
    overflow-x: hidden;
}form input {
    display: block;
    width: 100%;
    margin-top: 10px;
    margin-bottom: 20px;
    border: 2px #03a6ff solid;
    padding: 8px 16px;
    border-radius: 8px;
    cursor: text;
}form input[type=file] {
  cursor: pointer !important;
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


$id = isset($_GET['id']) ? $_GET['id'] : null;
if($id == null){
	header("Location: ". regurl("@p://@d/dashboard/"));
}

/*********************************/
$title = $version = $download = "";
$check = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
mysqli_set_charset($check,"utf8");
date_default_timezone_set('Asia/Ho_Chi_Minh');

$checksql = $check->query("SELECT * FROM `plugins` WHERE `id`='".$id."'");
if($checksql->num_rows > 0):
	while($cr = $checksql->fetch_assoc()) {
		if($cr["author"] !== getLogger()){
			echo "<script>window.location.href = '".regurl("@p://@d/error.php?c=403")."';</script><meta http-equiv='refresh' content='0; url=".regurl("@p://@d/error.php?c=403")."'>";
		} else {
			$title = $cr["title"];
			$download = $cr["download"];
			$version = $cr["version"];
		}
    }
else:
	header("Location: ". regurl("@p://@d/dashboard/"));
endif;

$check->close();

/*********************************/
$ready = true;
$error = null;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (empty($_POST["pnv"])):
		$error .= "Phiên bản (mới) không được để trống!<br>";
		$ready = false;
	else:
		$pnv = trim(htmlspecialchars($_POST["pnv"]));
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
			$pdownload = htmlspecialchars(trim($_POST["pdownload"]));
		}
    }
	
	if (strlen($pnv) < 1 || strlen($pnv) > 12){
		$error .= "Phiên bản (mới) phải từ 1 đến 12 kí tự!<br>";
		$ready = false;
	}
	
	if($ready){
		if(strpos($download, 'http://pluginmcvn.cf/resource/files/') !== false){
			$path = "../resource/files/".str_replace("http://pluginmcvn.cf/resource/files/","",$download);
			if(file_exists($path)){
				unlink($path);
			}
		}
					
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
		$update = $conn->query("UPDATE `plugins` SET `date`='".getTime()."',`download`='".$downloadURL."',`version`='".$pnv."' WHERE `id`='".$id."'");
		if ($update === TRUE):
			$error = "<script>window.location.href = 'http://pluginmcvn.cf/dashboard/?success=updated_plugin';</script><meta http-equiv='refresh' content='0; url=http://pluginmcvn.cf/dashboard/?success=updated_plugin'>";
		else:
			$error .= "Cập nhật phiên bản cho plugin ". $title ." thất bại!<br>" . $conn->error;
		endif;
		$conn->close();
	}
}
/*********************************/
?>
<div class="container pluginmcvn-container">
	<h2 id="pluginmcvn-bigtitle" class="center">Cập nhật phiên bản plugin <?php echo $title; ?></h2>
	<p class="center color-danger">Chủ plugin: <?php echo getLogger(); ?></p>
	<p class="center color-info">Phiên bản hiện tại: <?php echo $version; ?></p>
	<br>
	<?php 
	if($error != null){
		echo "<div class='alert danger'>".$error."</div>";
	}
	?>
	<br>
	<form method="post" action="" enctype="multipart/form-data">
		<p>Lưu ý: file cũ đã tải lên sẽ bị xóa</p>
		<input type="text" required placeholder="Phiên bản mới" name="pnv" />
		<br><br>
		Tải file plugin (.jar):<br>
		<input type="file" name="pfile" />
		hoặc bằng url (lưu ý: ưu tiên tải file)
		<input type="text" name="pdownload" />
		<br>
		<button class="btn success">Cập nhật</button>
	</form>
</div>
<?php
$base->registerFooter(
	$setup->googleAnalyticsCode,
	$setup->importJS
);
?>