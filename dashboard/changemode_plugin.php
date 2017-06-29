<?php
class Setup {
	var $title = "Plugin MCVN - Thay chế độ";
	var $robots = "{private}";
	var $description = "";
	var $importCSS = array();
	var $importJS = array();
	var $importHTML = "<script src='./editor/ckeditor.js'></script><style>.pluginmcvn-container{padding: 100px 40px !important}body {
    overflow-x: hidden;
}form input[type=text], form input[type=number] {
    display: block;
    width: 100%;
    margin-top: 10px;
    margin-bottom: 20px;
    border: 2px #03a6ff solid;
    padding: 8px 16px;
    border-radius: 8px;
    cursor: text;
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
$title = $pre = $pcontact = $pprice = $plimit_ips = "";
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
			$pre = $cr["pre"];
			$pcontact = $cr["contact"];
			$pprice = $cr["price"];
			$plimit_ips = $cr["limit_ips"];
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
	if (empty($_POST["premium"])):
		$conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
		mysqli_set_charset($conn,"utf8");
		$update = $conn->query("UPDATE `plugins` SET `pre`=NULL WHERE `id`='".$id."'");
		if ($update === TRUE):
			$error = "<script>window.location.href = 'http://pluginmcvn.cf/dashboard/?success=changedmode_tofree';</script><meta http-equiv='refresh' content='0; url=http://pluginmcvn.cf/dashboard/?success=changedmode_tofree'>";
		else:
			$error = "Lỗi: " . $conn->error;
		endif;
		$conn->close();
	else:
		if (empty($_POST["contact"])):
			$error .= "Link liên hệ không được để trống!<br>";
			$ready = false;
		else:
			$contact = trim(htmlspecialchars($_POST["contact"]));
		endif;
		
		if (empty($_POST["price"])):
			$error .= "Gía tiền không được để trống!<br>";
			$ready = false;
		else:
			$price = trim(htmlspecialchars($_POST["price"]));
			
			$options = array(
				'options' => array(
					'min_range' => 10000,
					'max_range' => 500000
				)
			);

			if (!filter_var($price, FILTER_VALIDATE_INT, $options)) {
				$error .= "Gía tiền phải từ 10k đến 500k<br>";
				$ready = false;
			}
		endif;
		
		if (empty($_POST["limit_ips"])):
			$error .= "IP tối đa không được để trống!<br>";
			$ready = false;
		else:
			$limit_ips = trim(htmlspecialchars($_POST["limit_ips"]));
			
			$options = array(
				'options' => array(
					'min_range' => 1,
					'max_range' => 20
				)
			);

			if (!filter_var($limit_ips, FILTER_VALIDATE_INT, $options)) {
				$error .= "IP tối đa phải từ 1 đến 20<br>";
				$ready = false;
			}
		endif;
		
		if($ready){	
			$conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
			mysqli_set_charset($conn,"utf8");
			$update = $conn->query("UPDATE `plugins` SET `pre`='1', `price`='".$price."', `contact`='".$contact."', `limit_ips`='".$limit_ips."' WHERE `id`='".$id."'");
			if ($update === TRUE):
				$error = "<script>window.location.href = 'http://pluginmcvn.cf/dashboard/?success=changedmode_topre';</script><meta http-equiv='refresh' content='0; url=http://pluginmcvn.cf/dashboard/?success=changedmode_topre'>";
			else:
				$error = "Lỗi: " . $conn->error;
			endif;
			$conn->close();
		}
	endif;
}
/*********************************/
?>
<div class="container pluginmcvn-container">
	<h2 id="pluginmcvn-bigtitle" class="center">Thay chế độ plugin <?php echo $title; ?></h2>
	<p class="center color-danger">Chủ plugin: <?php echo getLogger(); ?></p>
	<br>
	<?php 
	if($error != null){
		echo "<div class='alert danger'>".$error."</div>";
	}
	?>
	<br>
	<form method="post" action="">
		<b>Link liên hệ, dùng cho việc chuyển tiền giữa bạn & người mua (vd mua qua Facebook)</b>
		<input type="text" name="contact" value="<?php 
		if($pcontact != ""){
			echo $pcontact;
		}
		?>" />
		<b>Gía tiền</b>
		<input type="number" name="price" min="10000" max="500000" value="<?php 
		if($pprice == ""){
			echo "10000";
		} else {
			echo $pprice;
		}
		?>" /><br><br>
		<b>IP tối đa cho mỗi người mua</b>
		<input type="number" name="limit_ips" min="1" max="20" value="<?php 
		if($plimit_ips == ""){
			echo "1";
		} else {
			echo $plimit_ips;
		}
		?>" /><br><br>
		<p>
Bằng việc tick vào ô dưới đây, bạn đã đồng ý chấp nhận mọi <a href="http://pluginmcvn.cf/post.pluginmcvn?id=2058ba37432987b93a0c303c82efe97c">Quy định về plugin Premium giữa PluginMCVN - Chủ Plugin - Người mua</a>		
		</p><br>
		<input type="checkbox" name="premium" <?php 
		if($pre != null){
			echo "checked";
		}
		?>>&nbsp;&nbsp;Plugin này sẽ sang chế độ premium
		<br><br>
		<button class="btn success">Cập nhật</button>
	</form>
</div>
<?php
$base->registerFooter(
	$setup->googleAnalyticsCode,
	$setup->importJS
);
?>