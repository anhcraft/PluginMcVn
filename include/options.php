<?php
/**

	    Plugin MCVN
	-=-=-=-=-=-=-=-=-=-
	 @Author: Anh Craft
	  @Copyright 2016
	
**/

$XFrameOptions = 'SAMEORIGIN';
$protocol = 'https';
$domain = 'pluginmcvn.cf'; // pluginmcvn.cf
$autoDeleteLogFile = true;
$servername = "localhost";
$username = "....................................................................";
$password = ".......................................................";
$dbname = '.............................................';
$session_login_username = 'pluginmcvn_login_account';

/* - Hệ thống bảo trì - */
$enable_maintenance = false;
$maintenance_redirect_url = $protocol . '://' . $domain . '/maintenance.php';
$session_maintenance = 'pluginmcvn_maintenance'; // và ở maintenance.php
/* -------------------- */

$firewall_redirect_url = $protocol . '://' . $domain . '/firewall/';
?>