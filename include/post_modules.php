<?php

function get_postlength(){
	$conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
	mysqli_set_charset($conn,"utf8");
	$sql = "SELECT * FROM `post`";
	$result = $conn->query($sql);
	return $result->num_rows;
	$conn->close();
}

function get_postlist(){
	$prit = "";
	
	$conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
	mysqli_set_charset($conn,"utf8");
	$sql = "SELECT * FROM `post` ORDER BY `date` DESC";
	$res = $conn->query($sql);
	
	if ($res->num_rows > 0):
		while($aif = $res->fetch_assoc()) {
			$prit .= '
<div class="post-list-sub">
	<div class="post-list-title"><a href="http://pluginmcvn.cf/post.pluginmcvn?id=' . $aif["id"] . '">' . $aif["title"] . '</a></div>
	<div>
		<div style="float: left;padding-right: 20px;">
			<img src="http://pluginmcvn.cf/include/logo.png" style="" class="post-image">
		</div>
		<div class="post-list-date"><span><i class="fa fa-calendar color-info" aria-hidden="true"></i>&nbsp;&nbsp;Ngày đăng & cập nhật:</span>&nbsp;' . $aif["date"] . '</div>
	</div>
</div>
			';
		}
	else:
		$prit = "<p>Không có bài viết !</p>";
	endif;	
	$conn->close();	
	return $prit;
}

function header_post(){
	return '<div class="alert danger">Cấm sao chép bài viết dưới mọi hình thức ! (Bản quyền DMCA)</div>';
}

function footer_post(){
	return '';
}

function new_post(){
	$cd = "";
	$conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
	mysqli_set_charset($conn,"utf8");
	$get = $conn->query("SELECT * FROM `post` ORDER BY `date` DESC LIMIT 10");
	if ($get->num_rows > 0){
		while($aif = $get->fetch_assoc()) {
			$cd .= '<li><a href="http://pluginmcvn.cf/post.pluginmcvn?id='.$aif["id"].'">'.$aif["title"].'</a> <span>'.$aif["date"].'</span></li>';
		}
	}
	return $cd;
}
?>