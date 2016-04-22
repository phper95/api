<!DOCTYPE>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>查看验证码</title>
</head>
<body>
<?php
	//config
	require_once(dirname(__FILE__).'/'.'inc/interface.json.inc.php');
	require_once(dirname(__FILE__).'/'.'inc/methods.inc.php');
	require_once(dirname(__FILE__).'/'.'inc/config.inc.php');
	
	//链接数据库
	$connection = mysqli_connect(HOST,USER,PSD,DB);
	if(!$connection){ 
		//服务器问题
		$json['status']= 2;
		$json['usetime'] = endtime($start_time);
		$json['error'] = 'ServerError';
		$json['desc'] = "额服务器开小差了,请稍后重试...";
		$json_code = json_encode($json);
		echo $json_code;
		die();
	}
	mysqli_query($connection, "SET NAMES 'UTF8'");
	
	//所有验证码
	$query = 'SELECT * FROM `pcmaker_email_verify` ORDER BY `id` DESC';	
	
	$result = mysqli_query($connection,$query);
	if($result){
		if(mysqli_num_rows($result)==0){
			//关闭连接
			if($connection)mysqli_close($connection);
			die('没有查到验证码记录');
		}else{
			//找到了
			$i = 0;
			while($i<mysqli_num_rows($result)){
				
				$record = mysqli_fetch_assoc($result);	
				
				echo '<p>';
				echo '<b>email</b>:'.$record['email'].'<br/>';
				echo '<b>验证码</b>:'.$record['verify_code'].'<br/>';
				echo '<b>类别(0-缺省 1-注册验证 2-找回密码验证)</b>:'.$record['type'].'<br/>';
				echo '<b>是否已验证</b>:'.$record['confirmed'].'<br/>';
				echo '<b>添加时间</b>:'.$record['add_time'].'<br/>';
				echo '<b>过期时间</b>:'.$record['over_time'].'<br/>';
				echo '</p><hr/>';
				$i++;
			}
		}
	}

		
	//关闭连接
	if($connection)mysqli_close($connection);
?>

</body>
</html>