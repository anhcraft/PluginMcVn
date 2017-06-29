<?php
/**

	    Plugin MCVN
	-=-=-=-=-=-=-=-=-=-
	 @Author: Anh Craft
	  @Copyright 2016
	
**/
session_start();
require '../include/main.php';
require '../include/pluginUtils.php';

if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $mess = "";
    $success = false;
    if(isLogin()){
        $n = isset($_GET['n']) ? $_GET['n'] : null;
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if($n == null || $id == null){
            $mess .= "Thiếu thông tin!";
        } else {
            $vote = newVote($id, getLogger(), $n);
            if(!$vote){
                $mess .= "Lỗi SQL!";
            } else{
                $success = true;
                $mess .= "Cảm ơn bạn đã đánh giá!";
            }
        }
    } else {
        $mess .= "Bạn chưa đăng nhập!";
    }
    if($success) {
        echo "<div class='alert success'>" . $mess . "</div>";
    }else {
        echo "<div class='alert danger'>" . $mess . "</div>";
    }
}