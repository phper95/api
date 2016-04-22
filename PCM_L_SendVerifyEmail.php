<?php
/**
* @api {post} /gmspanel/interface/zh-cn/3.1/PCM_L_SendVerifyEmail.php 发送验证邮件
* @apiPermission pxseven
* @apiVersion 0.2.0
* @apiName SendVerifyEmail
* @apiGroup User
* @apiSampleRequest http://ser3.graphmovie.com/gmspanel/interface/zh-cn/3.1/PCM_L_SendVerifyEmail.php

* @apiDescription 用户输入邮箱后，请求此接口发送验证邮件，验证码为该接口随机生成，记录入数据库待验证，有效期1小时。

* @apiParam (POST) {String} [pk=""] 用户PC的机器码(MAC地址)
* @apiParam (POST) {Integer} [v=0] 用户GraphMovieStudios的内部版本号
* @apiParam (POST) {String} email 用户需要验证的邮箱.
* @apiParam (POST) {Integer} [type=1] 验证邮箱邮箱的用途:1-注册验证,2-找回密码验证,3-更换邮箱验证.
*
* @apiSuccess (ResponseJSON) {Integer} status 接口响应状态（0-失败,1-成功,2-需要弹出提示框,提示desc内容）.
* @apiSuccess (ResponseJSON) {Float} usetime 接口响应时间,调试用.
* @apiSuccess (ResponseJSON) {String} error 接口响应出错时的错误描述.
* @apiSuccess (ResponseJSON) {String} debug 接口响应出错时的过程描述,调试用.
* @apiSuccess (ResponseJSON) {String} desc status=2时需要弹窗提示此内容.

*
* @apiSuccessExample Success-Response[邮件已发送]:

*     {
*       "status": 1,
*       "usetime": 0.0024,
*       "error": "",
*       "debug": "",
*       "desc": ""
*     }

*
* @apiError PostError 请求的参数缺失或者参数格式错误.
* @apiError ServerError 服务器状态异常.
* @apiError SendFailed 邮件发送失败.
* @apiError EmailInvalid Email地址不合法.

*
* @apiErrorExample Error-Response:

*     PostError:

*     {
*       "status": 0,
*       "usetime": 0.0024,
*       "error": "PostError",
*       "debug": "",
*       "desc": ""
*     }

*     ServerError:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "ServerError",
*       "debug": "",
*       "desc": "服务器开小差了"
*     }

*     SendFailed:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "SendFailed",
*       "debug": "",
*       "desc": "邮件发送失败"
*     }

*     EmailInvalid:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "EmailInvalid",
*       "debug": "",
*       "desc": "邮箱地址好奇怪"
*     }

*/


	//config
	require_once(dirname(__FILE__).'/'.'inc/interface.json.inc.php');
	require_once(dirname(__FILE__).'/'.'inc/methods.inc.php');
	require_once(dirname(__FILE__).'/'.'inc/config.inc.php');
	
	//email
	require_once(dirname(__FILE__).'../../../../'.'EmailVerifyCodeUtil/Mail.class.php');
	
	//时间记录
	$start_time = starttime();
	
	//获取JSON基本模板
	$json = getBasicJsonModel();
	$json["desc"] = "";
	
	//初始化POST参数
	$post_pk = 'na';
	$post_v = 0;
	$post_email = '';
	$post_type = 1;
	
	//获取Header
	//模拟请求采用的是request payload 发上来的参数
	$request_body = file_get_contents('php://input');
	$data = json_decode($request_body);
	if($data && count($data)>0 && (!isset($_POST) || count($_POST)==0) && isset($data->email) && strlen($data->email)>0){
		//curl提交的参数
		if(isset($data->pk)){
			$post_pk = htmlspecialchars(addslashes($data->pk));
		}
		
		if(isset($data->v)){
			$post_v = htmlspecialchars(addslashes($data->v));
		}
		
		$post_email = strtolower(htmlspecialchars(addslashes(trim($data->email))));
		
		if(isset($data->type)){
			$post_type =  htmlspecialchars(addslashes($data->type));
		}
		
	}else if(
		isset($_POST['email']) && strlen($_POST['email'])>0
		){
		//获取参数
		$post_pk = 'na';
		$post_v = 0;
		$post_type = 1;
		if(isset($_POST['pk']) && strlen($_POST['pk'])>0){$post_pk = htmlspecialchars(addslashes($_POST['pk']));}
		if(isset($_POST['v'])){$post_v = htmlspecialchars(addslashes($_POST['v']));}
		$post_email = strtolower(htmlspecialchars(addslashes(trim($_POST['email']))));
		if(isset($_POST['type'])){$post_type = htmlspecialchars(addslashes($_POST['type']));}
		
	}else{
		//缺少参数
		$json['status']= 0;
		$json['usetime'] = endtime($start_time);
		$json['error'] = 'PostError';
		$json_code = json_encode($json);
		echo $json_code;
		die();
	}
	
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
	
	
	$verify_code = '';
	
	//检查此邮箱是否有没过期的验证码
	//有的话继续使用
	$now = 	date('Y-m-d H:i:s');
	$query = 'SELECT * FROM `pcmaker_email_verify` WHERE `email`=\''.$post_email.'\' AND `over_time`>\''.$now.'\' AND `confirmed`=0 AND `type`='.$post_type.';';
	
	$result = mysqli_query($connection,$query);
	if($result){
		if(mysqli_num_rows($result)>0){
			//有
			//取出该验证码
			$record = mysqli_fetch_assoc($result);
			$verify_code = $record['verify_code'];
			
		}else{
			//没有
			//生成一个新的验证码
			$chars = '0123456789';
			$verify_code = '';
			for ( $i = 0; $i < 6; $i++ ){  
				$verify_code .= $chars[ mt_rand(0, strlen($chars) - 1) ];  
			} 	
			
			//记录入数据库
			$nowtime = time(); //获取当前的时间戳
			$now = date('Y-m-d H:i:s'); 
			$overtime =  date('Y-m-d H:i:s',$nowtime+3600);
			$query = 'INSERT INTO pcmaker_email_verify(email,verify_code,type,confirmed,add_time,over_time) VALUES ('.
								 '\''.$post_email.'\','.
								 '\''.$verify_code.'\','.
								 $post_type.','.
								 '0,'.
								 '\''.$now.'\','.
								 '\''.$overtime.'\''.
			');';
			mysqli_query($connection, $query);
			
		}
	}
	
	if(strlen(trim($verify_code))<6){
		//错误
		$json['status']= 2;
		$json['usetime'] = endtime($start_time);
		$json['error'] = 'ServerError';
		$json['desc'] = "额服务器开小差了,请稍后重试...";
		$json_code = json_encode($json);
		echo $json_code;
		die();
	}
	
	//关闭连接
	if($connection)mysqli_close($connection);
	
	//发邮件
	$mail = new Mail();
	
	//$json['debug'] .= '[validate_email:'.$post_email.']';
	
	if (!$mail->validate_email($post_email)){
		//邮箱格式不合法
		//EmailInvalid
		$json['status']= 2;
		$json['usetime'] = endtime($start_time);
		$json['error'] = 'EmailInvalid';
		$json['desc'] = "这个邮箱地址好奇怪啊";
		$json_code = json_encode($json);
		echo $json_code;
		die();
	}

	$body = 'Hi~  '
		  . '<br />&nbsp;&nbsp;您的【图解电影】邮箱验证码为：<b>'.$verify_code.'</b>，有效期60分钟。'
		  . '<br />&nbsp;&nbsp;如非本人操作，请忽略此邮件。'
		  . "<br /><br /><br />本邮件是系统自动发送的，请勿直接回复！感谢您的访问，祝您使用愉快！";
	
	if ($mail->send ( '【图解电影】', $post_email, '你本次的邮箱验证码是：'.$verify_code.'，有效期60分钟', $body )) {
		//发送成功
		$json['status']= 1;
		$json['usetime'] = endtime($start_time);
		$json['error'] = '';
		$json_code = json_encode($json);
		echo $json_code;
		die();	
	} else {
		//发送失败
		$json['status']= 2;
		$json['usetime'] = endtime($start_time);
		$json['error'] = 'SendFailed';
		$json['desc'] = "啊咧邮件发送失败...请稍后重试下吧";
		$json['debug'] = $mail->getErrorInfo();
		$json_code = json_encode($json);
		echo $json_code;
		die();
	}
	
		
?>