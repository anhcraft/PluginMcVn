<?php
class Setup {
	var $title = "Plugin MCVN - Xoá plugin";
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
$title = $pass = $download = "";
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
			$passsql = $check->query("SELECT * FROM `account` WHERE `user`='".getLogger()."'");
			if($passsql->num_rows > 0){
				while($passer = $passsql->fetch_assoc()) {
					$pass = $passer["pass"];
				}
			} else {
				echo "<script>window.location.href = '".regurl("@p://@d/error.php?c=404")."';</script><meta http-equiv='refresh' content='0; url=".regurl("@p://@d/error.php?c=404")."'>";
			}
		}
    }
else:

	header("Location: ". regurl("@p://@d/dashboard/"));
endif;

$check->close();

/*********************************/


function removeVote($id)
{
    $conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
    mysqli_set_charset($conn, "utf8");
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $sql = $conn->query("SELECT * FROM `vote_plugin`");
    if ($sql->num_rows > 0){
        while ($cr = $sql->fetch_assoc()) {
            $d = $cr["data"];
            // [555,5]#333,2#454,1
            $allPlugin = explode("#", $d);
            $result = "";
            for ($i = 0; $i < count($allPlugin); $i++) {
                $info = explode(",", $allPlugin[$i]);
                // nếu có rùi
                if ($info[0] == $id) {
                    continue;
                }
                $result .= "#" . $info[0] . "," . $info[1];
            }
            $result = preg_replace('/\#/', '', $result, 1);
            $update = $conn->query("UPDATE `vote_plugin` SET `data`='" . $result . "' WHERE `user`='" . $cr["user"] . "'");
            if ($update === TRUE):
                return true;
            else:
                return false;
            endif;
        }
    } else {
        return true;
    }

    $conn->close();
}

$error = null;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (empty($_POST["pass"])):
		$error .= "Bạn phải nhập mật khẩu!<br>";
	else:
		$pass = $_POST["pass"];
		$conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
		mysqli_set_charset($conn,"utf8");
		date_default_timezone_set('Asia/Ho_Chi_Minh');
		$u = $conn->query("SELECT * FROM `account` WHERE `user`='".getLogger()."'");
		if($u->num_rows > 0){
			while($passer = $u->fetch_assoc()) {
				if(md5($pass) == $passer["pass"]){
					if(strpos($download, 'http://pluginmcvn.cf/resource/files/') !== false){
						$path = "../resource/files/".str_replace("http://pluginmcvn.cf/resource/files/","",$download);
						if(file_exists($path)){
							unlink($path);
						}
					}
			
					$d = $conn->query("DELETE FROM `plugins` WHERE `id`='".$id."'");
					if ($d === TRUE && removeVote($id)):
						$error = "<script>window.location.href = 'http://pluginmcvn.cf/dashboard/?success=removed_plugin';</script><meta http-equiv='refresh' content='0; url=http://pluginmcvn.cf/dashboard/?success=removed_plugin'>";
					else:
						$error .= "Xóa plugin ". $title ." thất bại!<br>" . $conn->error;
					endif;
				} else {
					$error .= "Sai mật khẩu!<br>";
				}
			}
		} else {
			$error .= "<script>window.location.href = '".regurl("@p://@d/error.php?c=404")."';</script><meta http-equiv='refresh' content='0; url=".regurl("@p://@d/error.php?c=404")."'>";
		}
		$conn->close();
	endif;
}
/*********************************/
?>
<div class="container pluginmcvn-container">
	<h2 id="pluginmcvn-bigtitle" class="center">Xóa plugin <?php echo $title; ?></h2>
	<p class="center color-danger">Chủ plugin: <?php echo getLogger(); ?></p>
	<br>
	<?php 
	if($error != null){
		echo "<div class='alert danger'>".$error."</div>";
	}
	?>
	<br>
	<form method="post" action="">
		<p>Bạn chấp nhận xóa plugin, kể cả file đã tải lên?</p>
		<p>Nhập mật khẩu của bạn để xóa:</p>
		<input type="password" required name="pass" />
		<button class="btn success">Tôi đã hiểu và đồng ý xóa</button>
	</form>
</div>
<?php
$base->registerFooter(
	$setup->googleAnalyticsCode,
	$setup->importJS
);
?>