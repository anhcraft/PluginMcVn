<?php
class Setup {
	var $title = "Plugin MCVN - Quản lý người mua";
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
}#customer-manager-list .customer-list-sub {
    background: #4688ff;
    color: #fff;
    padding: 5px 30px;
    border-bottom: 1px #fff solid;
    font-size: 16px;
}#customer-manager-list .customer-list-sub .btn {
    padding: 0px 12px;
    float: right;
}#customer-manager-list .customer-list-sub .btn:hover {
    background-color: #05d49c;
    color: #fff;
}#customer-manager-list {
    height: 300px;
    overflow: auto;
    width: 100%;
}#customer-manager-add input {
    outline: 0;
    display: inline-block;
    white-space: nowrap;
    padding: 8px 12px;
    margin-bottom: 5px;
    border: 1px #ccc solid;
    vertical-align: middle;
    cursor: text;
    min-width: 80%;
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
$title = "";
$check = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
mysqli_set_charset($check,"utf8");
date_default_timezone_set('Asia/Ho_Chi_Minh');

$checksql = $check->query("SELECT * FROM `plugins` WHERE `id`='".$id."'");
if($checksql->num_rows > 0):
	while($cr = $checksql->fetch_assoc()) {
		if($cr["author"] !== getLogger()){
			echo "<script>window.location.href = '".regurl("@p://@d/error.php?c=403")."';</script><meta http-equiv='refresh' content='0; url=".regurl("@p://@d/error.php?c=403")."'>";
		} else if($cr["pre"] == null) {
			echo "<script>window.location.href = '".regurl("@p://@d/dashboard/")."';</script><meta http-equiv='refresh' content='0; url=".regurl("@p://@d/dashboard/")."'>";
		} else {
			$title = $cr["title"];
		}
    }
else:
	header("Location: ". regurl("@p://@d/dashboard/"));
endif;

$check->close();
?>
<div class="container pluginmcvn-container">
	<h2 id="pluginmcvn-bigtitle" class="center">Quản lý người mua plugin <?php echo $title; ?></h2>
	<p class="center color-danger">Chủ plugin: <?php echo getLogger(); ?></p>
	<br>
	<br>
	<div id="customer-manager-list">Đang tải...</div>
	<br>
	<div id="customer-manage-messages">&nbsp;</div>
	<div id="customer-manager-add">
		<input type="text" placeholder="Nhập tài khoản PluginMCVN..." />
		<button class="btn success">Thêm</button>
	</div>
	<br>
	<p>
		Nếu bạn thấy "Có lỗi xảy ra!" có thể  do các nguyên nhân sau:<br>
		- Thiếu giá trị id, value, action do trang web kết nối với server<br>
		- Người dùng không có trong CSDL hoặc chưa đăng ký<br>
		- Kết nối CSDL bị lỗi, hãy liên hệ admin<br>
	</p>
	<script>
function remove(){
	$("#customer-manager-list .customer-list-sub button").on("click", function(){
		$.get("<?php echo regurl("@p://@d/dashboard/customersUtils.php?id=".$id."&action=remove&value=")?>"+$(this).parent().attr("data-customer-name"), function(data, status){
			$("#customer-manage-messages").html(data);
		});
		setTimeout(function(){
			$("#customer-manager-list").load("<?php echo regurl("@p://@d/dashboard/customersUtils.php?id=".$id."&action=get&value=none")?>");
			setTimeout(function(){
				remove();
			}, 500);
		}, 500);
	});
}
$(document).ready(function(){
	$.get("<?php echo regurl("@p://@d/dashboard/customersUtils.php?id=".$id."&action=get&value=none")?>", function(data, status){		
		$("#customer-manager-list").html(data);
		remove();
	});
	$("#customer-manager-add input").keyup(function(event){
		if(event.keyCode == 13){
			$.get("<?php echo regurl("@p://@d/dashboard/customersUtils.php?id=".$id."&action=add&value=")?>"+$(this).val(), function(data, status){
				$("#customer-manage-messages").html(data);
				$("#customer-manager-add input").val("");
			});
			setTimeout(function(){
				$("#customer-manager-list").load("<?php echo regurl("@p://@d/dashboard/customersUtils.php?id=".$id."&action=get&value=none")?>");
				setTimeout(function(){
					remove();
				}, 500);
			}, 500);
			return false;
		}
	});
	$("#customer-manager-add button").click(function(){
		$.get("<?php echo regurl("@p://@d/dashboard/customersUtils.php?id=".$id."&action=add&value=")?>"+$("#customer-manager-add input").val(), function(data, status){
			$("#customer-manage-messages").html(data);
			$("#customer-manager-add input").val("");
		});
		setTimeout(function(){
			$("#customer-manager-list").load("<?php echo regurl("@p://@d/dashboard/customersUtils.php?id=".$id."&action=get&value=none")?>");
			setTimeout(function(){
				remove();
			}, 500);
		}, 500);
	});
});
	</script>
</div>
<?php
$base->registerFooter(
	$setup->googleAnalyticsCode,
	$setup->importJS
);
?>