
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>二维码登录</title>
</head>

<body>



<?php
	/*
	测试扫描二维码登录
	这里目前只获取lgk以及userid 然后上报到接口 http://ser3.graphmovie.com/gmspanel/interface/zh-cn/3.1/PCM_L_ScanQrcode.php
	*/
	require_once(dirname(__FILE__).'/'.'inc/post.methods.inc.php');
	require_once(dirname(__FILE__).'/'.'inc/headreader.inc.php');
	$p_head = getallheaders();
	
	//file_put_contents('scan_qr_to_login_1.txt',json_encode($_GET).PHP_EOL,FILE_APPEND);
	//file_put_contents('scan_qr_to_login_2.txt',json_encode($p_head).PHP_EOL,FILE_APPEND);
	
	if(isset($_GET['lgk']) && ($p_head['HTTP_GW2CUID'] || $p_head['HTTP_GW2C_UID'] || isset($_GET['userid']) ||  $p_head['Gw2cuid'] || $p_head['Gw2c_uid'])){
		//转POST
		$seckey = '';
		//生成验证key
		$seckey = md5(md5($_GET['lgk']).'graphmovie');  
		
		if(isset($_GET['userid'])){$userid = $_GET['userid'];}
		if(isset($p_head['HTTP_GW2C_UID'])){$userid = $p_head['HTTP_GW2C_UID'];}
		if(isset($p_head['HTTP_GW2CUID'])){$userid = $p_head['HTTP_GW2CUID'];}
		if(isset($p_head['Gw2cuid'])){$userid = $p_head['Gw2cuid'];}
		if(isset($p_head['Gw2c_uid'])){$userid = $p_head['Gw2c_uid'];}
		
		$post_string = 'userid='.$userid.'&qrcode='.$_GET['lgk'].'&seckey='.$seckey;
		$response_json = request_by_curl('http://ser3.graphmovie.com/gmspanel/interface/zh-cn/3.1/PCM_L_ScanQrcode.php',$post_string);
		
		//file_put_contents('scan_qr_to_login_1.txt',$response_json.PHP_EOL,FILE_APPEND);
		
		
		$json_struct = @json_decode($response_json);
		if($json_struct && isset($json_struct->status)){
			if($json_struct->status==1){
				echo '登录成功！';
					
			}else{
				echo '登录失败...';	
			}
		}else{
			echo '服务器未响应';	
		}
	}else{
		echo '参数错误';	
	}
?>

</body>
</html>