<?php
/*
  * EpicVietFirewall 1.1
  * - Hệ thống Anti DDOS code bởi Anh Craft
  * - Bản quyền (c) 2017 Anh Craft. Giấy phép GNU
  * - Báo lỗi & góp ý tại https://www.facebook.com/anhcraft
  * - Trang web chính thức: http://evf.anhcraft.org
*/
session_start();
include_once(__DIR__ . '/evf_config.php');
include_once(__DIR__ . '/crawler-detect/CrawlerDetect.php');
$CrawlerDetect = new CrawlerDetect;
$isChecked = false;
function get_query_string(){
    $arr = $_SERVER['QUERY_STRING'];
    if (strlen($arr) == 0){
        return "";
    }else{
        return "?".$arr;
    }
}
if($_SERVER["REQUEST_METHOD"] != "POST") {
    if (!$CrawlerDetect->isCrawler()) {
        if (!isset($_SESSION["evf_check"])) {
			echo '<script>
			function b64EncodeUnicode(str) {
    return btoa(encodeURIComponent(str).replace(/%([0-9A-F]{2})/g,
        function toSolidBytes(match, p1) {
            return String.fromCharCode("0x" + p1);
    }));
}
        window.location.href = "'.EVF_FIREWALL_REDIRECT_URL.'?rdi="+(b64EncodeUnicode(encodeURI(window.location.href)));
		</script>';
        } else {
            echo '<script>
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {}
        };
        xmlhttp.open("GET", "' . EVF_FIREWALL_DELSESSIONS_URL . '", true);
        xmlhttp.send();</script>';
        }
    }
}
?>