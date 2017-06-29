<?php
/*
  * EpicVietFirewall 1.1
  * - Hệ thống Anti DDOS code bởi Anh Craft
  * - Bản quyền (c) 2017 Anh Craft. Giấy phép GNU
  * - Báo lỗi & góp ý tại https://www.facebook.com/anhcraft
  * - Trang web chính thức: http://evf.anhcraft.org
*/

// số point tối đa để chặn ip
define("EVF_MAX_CONNECTIONS_BLOCKED", 5);

// thời gian (giây) kết nối so với trước để tăng point
define("EVF_ADD_POINT_DELAY", 1);

// vị trí htaccess
define("EVF_HTACCESS_PATH", "../.htaccess");

// giao thức http hoặc https
define("EVF_PROTOCOL", "http");

// tên miền
define("EVF_DOMAIN", "pluginmcvn.cf");

// email liên hệ
define("EVF_EMAIL", "huynhduyanh123123@gmail.com");

// url chuyển hướng sang EpicVietFirewall
define("EVF_FIREWALL_REDIRECT_URL", EVF_PROTOCOL . "://" . EVF_DOMAIN . "/firewall/");

// url xóa sessions
define("EVF_FIREWALL_DELSESSIONS_URL", EVF_PROTOCOL . "://" . EVF_DOMAIN . "/firewall/evf_delsessions.php");
?>