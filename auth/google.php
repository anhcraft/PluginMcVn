<?php
session_start();
require_once '../include/options.php';
require_once '../include/Google-API/vendor/autoload.php';
$client_id = '.........................................apps.googleusercontent.com';
$client_secret = '...........................';
$redirect_uri = 'http://pluginmcvn.cf/auth/google.pluginmcvn';

$client = new Google_Client();
$client->setClientId($client_id);
$client->setClientSecret($client_secret);
$client->setRedirectUri($redirect_uri);
$client->addScope("email");
$client->addScope("profile");

$service = new Google_Service_Oauth2($client);

function getIP(){
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

// Nếu kết nối thành công, sau đó xử lý thông tin và lưu vào database
if (isset($_GET['code'])) {
    $client->authenticate($_GET['code']);
    $_SESSION['access_token'] = $client->getAccessToken();
    $user = $service->userinfo->get();
    $conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
    mysqli_set_charset($conn,"utf8");
    $hasin = $conn->query("SELECT * FROM `account` WHERE `email`='". $user["email"] ."'");

    if ($hasin->num_rows > 0):
        while($aif = $hasin->fetch_assoc()) {
            $u = $aif["user"];
            $updates = $conn->query("UPDATE `account` SET `lastip`='". getIP() ."' WHERE `user`='". $u ."'");
            if ($updates === TRUE):
                $_SESSION[$GLOBALS['session_login_username']] = $u;
                echo "<script>window.location.href = 'http://pluginmcvn.cf/';</script><meta http-equiv='refresh' content='0; url=http://pluginmcvn.cf/'>";
            else:
                echo "Lỗi cập nhật";
            endif;
        }
    else:
        echo "Không có tài khoản nào dùng email này";
    endif;
    $conn->close();
    exit;
} else {
    echo "Không có giá trị";
    exit;
}
?>