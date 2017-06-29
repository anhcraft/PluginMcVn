<?php
class Setup {
	var $title = "Plugin MCVN - Sửa plugin";
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
$title = $category = $description = $icon = $mcversions = $content = $github = "";
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
			$category = $cr["category"];
			$description = deword($cr["description"]);
			$icon = $cr["icon"];
			$mcversions = $cr["mcversions"];
			$content = deword($cr["content"]);
			$github = $cr["github"];
		}
    }
else:

	header("Location: ". regurl("@p://@d/dashboard/"));
endif;

$check->close();

/*********************************/

$error = null;

$ready = true;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// kiểm tra thông tin
	
	if (empty($_POST["pname"])):
		$error .= "Tên plugin không được để trống!<br>";
		$ready = false;
	else:
		$pname = trim(htmlspecialchars($_POST["pname"]));
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
	$mcversion = "";
	if (!empty($_POST["mcversion18"])){
		$v18 = true;
		$mcversion .= "#1.8";
	}
	if (!empty($_POST["mcversion19"])){
		$v19 = true;
		$mcversion .= "#1.9";
	}
	if (!empty($_POST["mcversion110"])){
		$v110 = true;
		$mcversion .= "#1.10";
	}
	if (!empty($_POST["mcversion111"])){
		$v111 = true;
		$mcversion .= "#1.11";
	}
	
	if(!$v18 && !$v19 && !$v110 && !$v111){
		$error .= "Plugin phải dùng được ít nhất 1 bản Minecraft!<br>";
		$ready = false;
	}
	
	$picon = "http://pluginmcvn.cf/include/logo.png";
	$pgithub = "";
	if (!empty($_POST["picon"])){
		$picon = trim(htmlspecialchars($_POST["picon"]));
	}
	
	if (!empty($_POST["pgithub"])){
		$pgithub = trim(htmlspecialchars($_POST["pgithub"]));
	}
	
	$pcategory = $_POST["pcategory"];
	
	if (empty($_POST["pcontent"])):
		$error .= "Nội dung plugin không được để trống!<br>";
		$ready = false;
	else:
		$pcontent = $_POST["pcontent"];
	endif;
	
	if (strlen($pname) < 3 || strlen($pname) > 30){
		$error .= "Tên plugin phải từ 3 đến 30 kí tự!<br>";
		$ready = false;
	}
	if (strlen($pdescription) < 10 || strlen($pdescription) > 100){
		$error .= "Miêu tả plugin phải từ 10 đến 100 kí tự!<br>";
		$ready = false;
	}
	if (strlen($pcontent) < 100 || strlen($pcontent) > 1000000){
		$error .= "Nội dung plugin phải từ 100 đến 1 000 000 kí tự!<br>";
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
		
		$conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
		mysqli_set_charset($conn,"utf8");
		$author = getLogger();
		$insert = $conn->query("UPDATE `plugins` SET `title`='".$pname."',`category`='".$pcategory."',`description`='".$pdescription."',`icon`='".$picon."',`mcversions`='".$mcversion."',`content`='".$pcontent."',`github`='".$pgithub."' WHERE `id`='".$id."'");
		if ($insert === TRUE):
			$error = "<script>window.location.href = 'http://pluginmcvn.cf/dashboard/?success=edited_plugin';</script><meta http-equiv='refresh' content='0; url=http://pluginmcvn.cf/dashboard/?success=edited_plugin'>";
		else:
			$error .= "Sửa plugin ". $pname ." thất bại!<br>" . $conn->error;
		endif;
		$conn->close();
	}
}
/*********************************/
?>
<div class="container pluginmcvn-container">
	<h2 id="pluginmcvn-bigtitle" class="center">Sửa plugin <?php echo $title; ?></h2>
	<p class="center color-danger">Chủ plugin: <?php echo getLogger(); ?></p>
	<br>
	<?php 
	if($error != null){
		echo "<div class='alert danger'>".$error."</div>";
	}
	?>
	<br>
	<form method="post" action="">
		<input type="text" required placeholder="Tên plugin" name="pname" value="<?php echo $title; ?>" />
		<input type="text" required placeholder="Miêu tả ngắn gọn về plugin" name="pdescription" value="<?php echo $description; ?>" />
		<input type="text" placeholder="Link icon plugin" name="picon" value="<?php if($icon !== "http://pluginmcvn.cf/include/logo.png"){echo $icon;} ?>" />
		<input type="text" placeholder="Github" name="pgithub" value="<?php echo $github; ?>" />
		Phiên bản Minecraft<br>
		<?php 
		$v18 = "";
		$v19 = "";
		$v110 = "";
		$v111 = "";
	
		$mcversions = substr_replace($mcversions,"",0,strlen("#"));
		$versions = explode("#",$mcversions);
		foreach($versions as $v) {
			if($v == "1.8"){
				$v18 = "checked='checked'";
			}
			if($v == "1.9"){
				$v19 = "checked='checked'";
			}
			if($v == "1.10"){
				$v110 = "checked='checked'";
			}
			if($v == "1.11"){
				$v111 = "checked='checked'";
			}
		}
		?>
		<input type="checkbox" name="mcversion18" value="1.8" <?php echo $v18; ?>>&nbsp;&nbsp;1.8&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="checkbox" name="mcversion19" value="1.9" <?php echo $v19; ?>>&nbsp;&nbsp;1.9&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="checkbox" name="mcversion110" value="1.10" <?php echo $v110; ?>>&nbsp;&nbsp;1.10&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="checkbox" name="mcversion111" value="1.11" <?php echo $v111; ?>>&nbsp;&nbsp;1.11
		<br>
		Danh mục<br>
		<select id="category" required name="pcategory">
			<option value="1" <?php if($category == "1"){echo "selected";} ?>>Công cụ và tiện ích</option>
			<option value="2" <?php if($category == "2"){echo "selected";} ?>>Vui vẻ</option>
			<option value="3" <?php if($category == "3"){echo "selected";} ?>>Quản lý world</option>
			<option value="4" <?php if($category == "4"){echo "selected";} ?>>Kinh tế</option>
			<option value="5" <?php if($category == "5"){echo "selected";} ?>>Chế độ chơi</option>
			<option value="6" <?php if($category == "6"){echo "selected";} ?>>Thư viện & API</option>
		</select>
		<br><br>

		Nội dung<br>
		<textarea name="pcontent" required id="pcontent" cols="120"><?php echo $content; ?></textarea>
		<script>
			CKEDITOR.replace('pcontent');
		</script>
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