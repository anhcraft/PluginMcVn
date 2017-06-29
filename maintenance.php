<?php
session_start();
$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if($_POST['user'] == 'admin' && $_POST['pass'] == '........................'){
		$_SESSION['pluginmcvn_maintenance'] = 'Blaze!';
		echo "<script>window.location.href = 'http://pluginmcvn.cf/';</script><meta http-equiv='refresh' content='0; url=http://pluginmcvn.cf/'>";
	} else {
		$error = "Sai tài khoản hoặc mật khẩu!<br>";
	}
}
?>
<!Doctype html>
<html lang="vi">

<head>
	<title>Thông báo bảo trì</title>

	<link rel="stylesheet" href="http://include.pluginmcvn.cf/css/onv.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js" type="text/javascript"></script>
	<script src="http://include.pluginmcvn.cf/js/onv.js" type="text/javascript"></script>
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta charset="utf-8">
	<meta name="robots" content="noindex,nofollow">
	<style>
body {
    background: url(http://include.pluginmcvn.cf/NpDz38k.jpg);
    background-repeat: no-repeat;
    padding: 50px;
    background-attachment: fixed;
    color: #fff;
    background-position: 0px -80px;
    background-size: 100%;
}

input {
    border: 1px #555 solid;
    cursor: text;
    padding: 6px 12px;
    font-size: 15px;
}

button {
    border: 1px #555 solid;
    cursor: pointer;
    padding: 6px 12px;
    font-size: 15px;
    border: 1px #555 solid;
    background-color: #6292ff;
    color: #fff;
}

form {
    margin-top: 10px;
}
	</style>
</head>
<body>
	<h3>Hệ thống đang được bảo trì hoặc nâng cấp</h3>
	<p>Hãy đăng nhập để truy cập website</p>
	<form method="post" action="">
		<input type="text" name="user">
		<input type="password" name="pass">
        <?php echo $error; ?>
		<button>Đồng ý</button>
	</form>
</body>
</html>	
