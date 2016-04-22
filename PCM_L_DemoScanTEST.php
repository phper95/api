<?php
	//config
	require_once(dirname(__FILE__).'/'.'inc/interface.json.inc.php');
	require_once(dirname(__FILE__).'/'.'inc/methods.inc.php');
	require_once(dirname(__FILE__).'/'.'inc/config.inc.php');
	
	//模拟扫描二维码
	//GET key uid
	if(
		!(isset($_GET['key']) && isset($_GET['uid']))
		
		){
		die('error get');
	}
	
	//链接数据库
	$connection = mysqli_connect(HOST,USER,PSD,DB);
	if(!$connection){ 
		
		die('connection lost!');
	}
	mysqli_query($connection, "SET NAMES 'UTF8'");
	
	//所有验证码
	$query = 'SELECT * FROM `pcmaker_qrcode_login` WHERE `qrkey`=\''.$_GET['key'].'\';';	
	
	$result = mysqli_query($connection,$query);
	if($result){
		if(mysqli_num_rows($result)==0){
			//关闭连接
			if($connection)mysqli_close($connection);
			die('key error');
		}else{
			//找到了
			$record = mysqli_fetch_assoc($result);	
			//UPDATE
			$query = 'UPDATE `pcmaker_qrcode_login` SET `user_id`='.userIdKeyDecode($_GET['uid']).' WHERE `id`='.$record['id'].';';	
	
			$result = mysqli_query($connection,$query);
			
		}
	}
	
	die('ok!');

		
	//关闭连接
	if($connection)mysqli_close($connection);
?>