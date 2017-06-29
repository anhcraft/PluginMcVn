<?php
include_once(__DIR__ . '/../firewall/evf_load.php');
require '../include/main.php';
require '../include/pluginUtils.php';

if(!isLogin() || !isDev()){
	header("Location: ". regurl("@p://@d/"));
}

$a = isset($_GET['action']) ? $_GET['action'] : null;
if($a == null){
	header("Location: ". regurl("@p://@d/dashboard/"));
} else {
	switch ($a) {
		case "add_plugin":
			require 'add_plugin.php';
			break;
		case "update_plugin":		
			require 'update_plugin.php';
			break;
		case "edit_plugin":		
			require 'edit_plugin.php';
			break;
		case "changemode_plugin":
			require 'changemode_plugin.php';
			break;
		case "remove_plugin":
			require 'remove_plugin.php';
			break;
		case "customer_manage":
			require 'customer_manage.php';
			break;
		case "banned_ips":
			require 'banned_ips.php';
			break;
		default:
			header("Location: ". regurl("@p://@d/dashboard/"));		
	}
}
?>