<?php
/**

	    Plugin MCVN
	-=-=-=-=-=-=-=-=-=-
	 @Author: Anh Craft
	  @Copyright 2016
	
**/
include_once(__DIR__ . '/../firewall/evf_load.php');
require '../include/main.php';

$id = isset($_GET['id']) ? $_GET['id'] : null;
$uv = isset($_GET['u']) ? $_GET['u'] : null;

if($id == null && $uv == null){
	require 'my_profile.php';
} else {
	require 'view_profile.php';
}
?>