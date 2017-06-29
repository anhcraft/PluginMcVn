<?php
$id = isset($_GET['id']) ? $_GET['id'] : null;
if($id !== null):
	$conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
	mysqli_set_charset($conn,"utf8");
	$get = $conn->query("SELECT * FROM `post` WHERE `id`='". $id ."'");
	if ($get->num_rows > 0):
		while($aif = $get->fetch_assoc()) {
			$tit = deword($aif["title"]);
			$cont = deword($aif["content"]);
			$date = $aif["date"];
		}
	else:
		header("Location: http://pluginmcvn.cf/error.php?c=404");
	endif;
else:
	header("Location: http://pluginmcvn.cf/error.php?c=404");
endif;

class Setup {
	var $title = "Plugin MCVN | ";
	var $robots = "{public}";
	var $importCSS = array();
	var $importJS = array();
	var $importHTML = "<style>.pluginmcvn-container{background-repeat: no-repeat;background-attachment: fixed;padding-top:80px !important;height: 900px}#pluginmcvn-bigtitle{color: #fff;font-family: 'Itim', 'Open Sans';font-size:45px}#pluginmcvn-logo{display:block;margin:auto;width:200px;border-radius:200px}#pluginmcvn-logo:hover{border-radius:0 !important;-moz-transform: rotate(360deg);-webkit-transform: rotate(360deg);-o-transform: rotate(360deg);-ms-transform: rotate(360deg);transform: rotate(360deg);-webkit-transition: all 1s ease-in-out;-moz-transition: all 1s ease-in-out;-ms-transition: all 1s ease-in-out;-o-transition: all 1s ease-in-out;transition: all 1s ease-in-out}@media only screen and (max-width: 1000px){img{width:100%;display:block}.pluginmcvn-container{background-size:auto 700px !important;}}#note {border: 1px dashed #aaa;margin: 10px 0;padding: 0.5% 1%;}#note span:last-child {float: right;}#content {margin: 45px 1.2%;}#post-area{margin: 1% 2%;}</style>";
	var $googleSiteVerification = "kNkDxfzm97qD6sJJbkDGt9S36SuhMFiVBKPyjeS0t10";
	var $googleAnalyticsCode = "UA-86116936-1";
}
?>