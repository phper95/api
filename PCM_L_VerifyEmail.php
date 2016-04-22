<?php
/**
* @api {post} /gmspanel/interface/zh-cn/3.1/PCM_L_VerifyEmail.php 验证邮件
* @apiPermission pxseven
* @apiVersion 0.2.0
* @apiName VerifyEmail
* @apiGroup User
* @apiSampleRequest http://ser3.graphmovie.com/gmspanel/interface/zh-cn/3.1/PCM_L_VerifyEmail.php

* @apiDescription 出于安全性考虑，验证码还是交付于服务器来验证。

* @apiParam (POST) {String} [pk=""] 用户PC的机器码(MAC地址)
* @apiParam (POST) {Integer} [v=0] 用户GraphMovieStudios的内部版本号
* @apiParam (POST) {String} email 需要验证的Email地址.
* @apiParam (POST) {String} vfcode 验证码.
* @apiParam (POST) {Integer} [type=1] 验证邮箱邮箱的类别:1-注册验证,2-找回密码验证,3-更改邮箱验证.
*
* @apiSuccess (ResponseJSON) {Integer} status 接口响应状态（0-失败,1-成功,2-需要弹出提示框,提示desc内容）.
* @apiSuccess (ResponseJSON) {Float} usetime 接口响应时间,调试用.
* @apiSuccess (ResponseJSON) {String} error 接口响应出错时的错误描述.
* @apiSuccess (ResponseJSON) {String} debug 接口响应出错时的过程描述,调试用.
* @apiSuccess (ResponseJSON) {String} desc status=2时需要弹窗提示此内容.

*
* @apiSuccessExample Success-Response[验证成功]:

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
* @apiError VerifyCodeInvalid 验证码非法(超时、邮箱地址更改、验证码输错、码已被用等情况)

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
*       "desc": "额服务器开小差了,请稍后重试..."
*     }

*     VerifyCodeInvalid:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "VerifyCodeInvalid",
*       "debug": "",
*       "desc": "验证码错误(tips:Ctrl+C,Ctrl+V)"
*     }

*/


	//config
	require_once(dirname(__FILE__).'/'.'inc/interface.json.inc.php');
	require_once(dirname(__FILE__).'/'.'inc/methods.inc.php');
	require_once(dirname(__FILE__).'/'.'inc/config.inc.php');
	
	//时间记录
	$start_time = starttime();
	
	//获取JSON基本模板
	$json = getBasicJsonModel();
	$json["desc"] = "";
	
	//初始化POST参数
	$post_pk = 'na';
	$post_v = 0;
	$post_vfcode = '';
	$post_email = '';
	$post_type = 1;
	
	//获取Header
	//模拟请求采用的是request payload 发上来的参数
	$request_body = file_get_contents('php://input');
	$data = json_decode($request_body);
	if($data && count($data)>0 && (!isset($_POST) || count($_POST)==0) && isset($data->vfcode) && strlen($data->vfcode)>0 && isset($data->email) && strlen($data->email)>0){
		//curl提交的参数
		if(isset($data->pk)){
			$post_pk = htmlspecialchars(addslashes($data->pk));
		}
		
		if(isset($data->v)){
			$post_v = htmlspecialchars(addslashes($data->v));
		}
		
		$post_vfcode = strtoupper(htmlspecialchars(addslashes(trim($data->vfcode))));
		$post_email = strtolower(htmlspecialchars(addslashes(trim($data->email))));
		
		if(isset($data->type)){
			$post_type = htmlspecialchars(addslashes($data->type));
		}
		
	}else if(
		isset($_POST['vfcode']) && strlen($_POST['vfcode'])>0
		&& isset($_POST['email']) && strlen($_POST['email'])>0
		){
		//获取参数
		$post_pk = 'na';
		$post_v = 0;
		$post_type = 1;
		if(isset($_POST['pk']) && strlen($_POST['pk'])>0){$post_pk = htmlspecialchars(addslashes($_POST['pk']));}
		if(isset($_POST['v'])){$post_v = htmlspecialchars(addslashes($_POST['v']));}
		$post_vfcode = strtoupper(htmlspecialchars(addslashes(trim($_POST['vfcode']))));
		if(isset($_POST['type'])){$post_type = htmlspecialchars(addslashes($_POST['type']));}
		$post_email = strtolower(htmlspecialchars(addslashes(trim($_POST['email']))));
		
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
	
	//是否有此验证码
	$now = 	date('Y-m-d H:i:s');
	$query = 'SELECT * FROM `pcmaker_email_verify` WHERE `email`=\''.$post_email.'\' AND `verify_code`=\''.$post_vfcode.'\' AND `over_time`>\''.$now.'\' AND `confirmed`=0 AND `type`='.$post_type.';';
	$result = mysqli_query($connection,$query);
	if($result){
		if(mysqli_num_rows($result)>0){
			//有
			//更新验证成功验证
			$record = mysqli_fetch_assoc($result);
			
			//只更新这一条
			$query = 'UPDATE `pcmaker_email_verify` SET `confirmed`=1 WHERE `id`= '.$record['id'].';';
			mysqli_query($connection, $query);
			
			//成功
			//判断新的account表中是否有记录
			//没有记录就INSERT
			//无法判断 因为没有userid
			
			//关闭连接
			if($connection)mysqli_close($connection);
	
			$json['status']= 1;
			$json['usetime'] = endtime($start_time);
			$json['error'] = '';
			$json_code = json_encode($json);
			echo $json_code;
			die();	
			
		}
	}
	
	
	//关闭连接
	if($connection)mysqli_close($connection);
	
	//发送失败
	$json['status']= 2;
	$json['usetime'] = endtime($start_time);
	$json['error'] = 'VerifyCodeInvalid';
	$json['desc'] = "验证码错误(tips:Ctrl+C,Ctrl+V)";
	$json_code = json_encode($json);
	echo $json_code;
	die();
		
?>