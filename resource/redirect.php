<?php 
/**

	    Plugin MCVN
	-=-=-=-=-=-=-=-=-=-
	 @Author: Anh Craft
	  @Copyright 2016
	
**/
$url = isset($_GET['url']) ? $_GET['url'] : null;
if($url == null){
	header("Location: ". regurl("@p://@d/"));
} else {
	header("Location: ". $url);
}
?>