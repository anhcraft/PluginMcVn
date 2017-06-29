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
$rdi = ($_GET['rdi']) ?? null;
if($rdi == null){
    echo "
Bạn đang cố gắng truy cập trái phép trang này!<br>
Mọi thắc mắc bạn có thể liên hệ ".EVF_EMAIL;
    exit;
}
$mess = "";
$redirect = urldecode(base64_decode($rdi));
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
$_SESSION["evf_check"] = getIP();
function connection(){
    $ip = getIP();
    $p = "./cache/$ip.ip";
    if (file_exists($p)){
        chmod($p, 0666);
        $f = fopen($p, "r+") or die("Unable to open file!");
        $line = explode("|",fgets($f));
        $point = (int) $line[0];
        $tm = (int) $line[1];
        fclose($f);
        // nếu đã vượt mức kết nối
        if(EVF_MAX_CONNECTIONS_BLOCKED <= $point){
            // ban vĩnh viễn
            if((time()-$tm) <= EVF_ADD_POINT_DELAY){
                unlink($p);
                chmod(EVF_HTACCESS_PATH, 0666);
                $ft = fopen(EVF_HTACCESS_PATH,"a");
                fwrite($ft,"\ndeny from $ip");
                fclose($ft);
                echo "
IP của bạn đã bị chặn vĩnh viễn vì có hành vi DDOS<br>
Nếu bạn cho đây là lỗi, hãy liên hệ ".EVF_EMAIL." để chúng tôi xem xét và bỏ chặn IP bạn";
                exit;
            }
            // giảm point
            else {
                $point = 0;
                $ft = fopen($p,"w");
                fwrite($ft,"$point|".time());
                fclose($ft);
            }
        } else {
            // tăng point
            if((time()-$tm) <= EVF_ADD_POINT_DELAY){
                $point += 1;
                $ft = fopen($p,"w");
                fwrite($ft,"$point|".time());
                fclose($ft);
            }
            // giảm point
            else {
                $point = 0;
                $ft = fopen($p,"w");
                fwrite($ft,"$point|".time());
                fclose($ft);
            }
        }
    } else {
        $ft = fopen($p,"w");
        fwrite($ft,"0|".time());
        fclose($ft);
    }
}
connection();
if(isset($_SESSION["evf_check_once"])){
	header("Location: $redirect");
} else {
	$_SESSION["evf_check_once"] = true;
}
?>
<!Doctype html>
<html lang="vi">
    <head>
        <title>EpicVietFirewall</title>

        <link rel="stylesheet" href="style.css" />
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="utf-8">
        <meta name="robots" content="noindex,nofollow">
        <style>
            body {
                padding: 80px;
                color: #333;
                text-align: center;
            }

            #title {
                color: #ff70df;
                text-shadow: #d2d0d0 2px -2px 1px;
            }

            #intro-2 {
                background: #555;
                color: #fff;
                display: initial;
                padding: 10px 20px;
            }

            #intro-1 {
                margin-bottom: 30px;
            }

            #delay-time {
                max-width: 80%;
                margin: auto;
                margin-top: 50px;
            }

            #progress-loading {
                padding: 5px 0;
            }
        </style>
    </head>
    <body>
        <h1 id="title">EpicVietFirewall</h1>
        <p id="intro-1">Hệ thống Anti DDos code bởi <a href="http://anhcraft.org/">Anh Craft</a> :D</p>
        <p id="intro-2">Link bạn sắp truy cập: <?php echo $redirect; ?></p>
        <div id="delay-time">
            <div class="progress">
                <div style="width:0" class="progress-bar progress-striped success" id="progress-loading"></div>
            </div>
            <p>Chuyển hướng sau <span id="times">3</span> giây</p>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js" type="text/javascript"></script>
        <script>
            $(document).ready(function() {
                var load = 0;
                var i = setInterval(function(){
                    load += 1;
                    $("#progress-loading").css("width", load+"%");
                    if(load == 100){
                        clearInterval(i);
                    }
                }, 10);
                setTimeout(function() {
                    $("#times").text("2");
                }, 100);
                setTimeout(function() {
                    $("#times").text("1");
                }, 500);
                setTimeout(function() {
                    $("#times").text("0");
                }, 1000);
                setTimeout(function() {
                    window.location.href = "<?php echo $redirect; ?>";
                }, 1500);
            });
        </script>
    </body>
</html>
