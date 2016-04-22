<?php
/**
* @api {post} /gmspanel/interface/zh-cn/3.1/PCM_L_ChangeEmail.php 更改邮箱
* @apiPermission pxseven
* @apiVersion 0.1.0
* @apiName ChangeEmail
* @apiGroup User
* @apiSampleRequest http://ser3.graphmovie.com/gmspanel/interface/zh-cn/3.1/PCM_L_ChangeEmail.php

* @apiDescription 用户输入密码验证通过后，更换邮箱

* @apiParam (POST) {String} [pk=""] 用户PC的机器码(MAC地址)
* @apiParam (POST) {Integer} [v=0] 用户GraphMovieStudios的内部版本号
* @apiParam (POST) {String} old_email 用户的旧邮箱地址.
* @apiParam (POST) {String} new_email 用户的新邮箱地址.
* @apiParam (POST) {String} userid 如果要更换邮箱必须要有userid.
* @apiParam (POST) {String} pwd 用户当前的登录密码.
* @apiParam (POST) {String} vfcode 用户当前的验证码.

*
* @apiSuccess (ResponseJSON) {Integer} status 接口响应状态（0-失败,1-成功,2-需要弹出提示框,提示desc内容）.
* @apiSuccess (ResponseJSON) {Float} usetime 接口响应时间,调试用.
* @apiSuccess (ResponseJSON) {String} error 接口响应出错时的错误描述.
* @apiSuccess (ResponseJSON) {String} debug 接口响应出错时的过程描述,调试用.
* @apiSuccess (ResponseJSON) {String} desc status=2时需要弹窗提示此内容.

*
* @apiSuccessExample Success-Response[更换成功]:

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
* @apiError EmailOccupied 该<code>new_email</code>已被占用.
* @apiError PasswordInvalid 登录密码<code>pwd</code>错误.
* @apiError SameEmail 新旧邮箱是一样的一个邮箱，这个客户端可以直接判断.
* @apiError OldEmailError <code>old_email</code>地址错误（发的<code>old_email</code>地址不是该用户的邮箱地址）.
* @apiError UserNotFound 根本就没有这个<code>userid</code>的用户.

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

*     EmailOccupied:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "EmailOccupied",
*       "debug": "",
*       "desc": "该Email已被他人占用。"
*     }

*     PasswordInvalid:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "PasswordInvalid",
*       "debug": "",
*       "desc": "密码错误。"
*     }

*     SameEmail:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "SameEmail",
*       "debug": "",
*       "desc": "新旧邮箱不能相同啊魂淡"
*     }

*     OldEmailError:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "OldEmailError",
*       "debug": "",
*       "desc": "该用户邮箱地址不是:xxx@xx.com"
*     }

*     UserNotFound:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "UserNotFound",
*       "debug": "",
*       "desc": "没有这个用户。"
*     }

*     VerifyCodeInvalid:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "VerifyCodeInvalid",
*       "debug": "",
*       "desc": "验证码已失效请重新验证邮箱"
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
	$post_old_email = '';
	$post_new_email = '';
	$post_userid = '0';
	$post_pwd = '0';
	$post_vfcode = '';
	
	//获取Header
	//模拟请求采用的是request payload 发上来的参数
	$request_body = file_get_contents('php://input');
	$data = json_decode($request_body);
	if($data && count($data)>0 && (!isset($_POST) || count($_POST)==0) && isset($data->old_email) && strlen($data->old_email)>0 && isset($data->new_email) && strlen($data->new_email)>0 && isset($data->userid) && strlen($data->userid)>0 && isset($data->pwd) && strlen($data->pwd)>0 && isset($data->vfcode) && strlen($data->vfcode)>0){
		//curl提交的参数
		if(isset($data->pk)){
			$post_pk = htmlspecialchars(addslashes($data->pk));
		}
		
		if(isset($data->v)){
			$post_v = htmlspecialchars(addslashes($data->v));
		}
		
		$post_old_email = strtolower(htmlspecialchars(addslashes(trim($data->old_email))));
		$post_new_email = strtolower(htmlspecialchars(addslashes(trim($data->new_email))));
		$post_userid = userIdKeyDecode(htmlspecialchars(addslashes(trim($data->userid))));
		$post_pwd = strtolower(htmlspecialchars(addslashes(trim($data->pwd))));
		
		$post_vfcode = strtolower(htmlspecialchars(addslashes(trim($data->vfcode))));
		
	}else if(
		isset($_POST['old_email']) && strlen($_POST['old_email'])>0
		&&
		isset($_POST['new_email']) && strlen($_POST['new_email'])>0
		&&
		isset($_POST['userid']) && strlen($_POST['userid'])>0 && $_POST['userid']>0
		&&
		isset($_POST['pwd']) && strlen($_POST['pwd'])>0
		&&
		isset($_POST['vfcode']) && strlen($_POST['vfcode'])>0
		
		){
		//获取参数
		if(isset($_POST['pk']) && strlen($_POST['pk'])>0){$post_pk = htmlspecialchars(addslashes($_POST['pk']));}
		if(isset($_POST['v'])){$post_v = htmlspecialchars(addslashes($_POST['v']));}
		$post_old_email = strtolower(htmlspecialchars(addslashes(trim($_POST['old_email']))));
		$post_new_email = strtolower(htmlspecialchars(addslashes(trim($_POST['new_email']))));
		$post_userid = userIdKeyDecode(htmlspecialchars(addslashes($_POST['userid'])));
		$post_pwd = strtolower(htmlspecialchars(addslashes(trim($_POST['pwd']))));
		
		$post_vfcode = strtolower(htmlspecialchars(addslashes(trim($_POST['vfcode']))));
		
	}else{
		//缺少参数
		$json['status']= 0;
		$json['usetime'] = endtime($start_time);
		$json['error'] = 'PostError';
		$json_code = json_encode($json);
		echo $json_code;
		die();
	}
	
	//是否新旧邮箱一样的
	if($post_old_email==$post_new_email){
		$json['status']= 2;
		$json['usetime'] = endtime($start_time);
		$json['error'] = 'SameEmail';
		$json['desc'] = '新旧邮箱不能相同啊魂淡';
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
	
	
	//邮箱密码是否正确
	$query = 'SELECT * FROM `client_user` WHERE `id`='.$post_userid.';';
	$result = mysqli_query($connection,$query);
	if($result){
		if(mysqli_num_rows($result)>0){
			//找到了
			$record = mysqli_fetch_assoc($result);	
			if($record['email']!=$post_old_email){
				//发上来的email和记录中的不一致
				//关闭连接
				if($connection)mysqli_close($connection);
				$json['status']= 2;
				$json['usetime'] = endtime($start_time);
				$json['error'] = 'OldEmailError';
				$json['desc'] = "该用户邮箱地址不是:".$post_old_email;
				$json_code = json_encode($json);
				echo $json_code;
				die();
			}
			
			//记录一致
			//密码是否正确
			if($post_pwd!=$record['secure_pwd_md5']){
				//密码不对
				//关闭连接
				if($connection)mysqli_close($connection);
				$json['status']= 2;
				$json['usetime'] = endtime($start_time);
				$json['error'] = 'PasswordInvalid';
				$json['desc'] = "密码错误。";
				$json_code = json_encode($json);
				echo $json_code;
				die();
			}
			
			//OK通过验证
			
		}else{
			//没找到	
			if($connection)mysqli_close($connection);
			$json['status']= 2;
			$json['usetime'] = endtime($start_time);
			$json['error'] = 'UserNotFound';
			$json['desc'] = "没有这个用户。";
			$json_code = json_encode($json);
			echo $json_code;
			die();
		}
	}else{
		//SQL错误
		$json['status']= 2;
		$json['usetime'] = endtime($start_time);
		$json['error'] = 'ServerError';
		$json['desc'] = "额服务器开小差了,请稍后重试...";
		$json_code = json_encode($json);
		echo $json_code;
		die();
	}
	
	//是否已经被人占用
	$query = 'SELECT * FROM `client_user` WHERE `email`=\''.$post_new_email.'\';';
	$result = mysqli_query($connection,$query);
	if($result){
		if(mysqli_num_rows($result)>0){
			//被占用
			//关闭连接
			if($connection)mysqli_close($connection);
			
			$json['status']= 2;
			$json['usetime'] = endtime($start_time);
			$json['error'] = 'EmailOccupied';
			$json['desc'] = "该Email已被他人占用。";
			$json_code = json_encode($json);
			echo $json_code;
			die();
		}
	}
	
	//可用
	
	//检查验证码是否正确
	//是否有此验证码
	$now = 	date('Y-m-d H:i:s');
	$query = 'SELECT * FROM `pcmaker_email_verify` WHERE `email`=\''.$post_new_email.'\' AND `verify_code`=\''.$post_vfcode.'\' AND `over_time`>\''.$now.'\' AND `confirmed`=0 AND `type`=3;';
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
			
		}else{
			//验证码错误	
			$json['status']= 2;
			$json['usetime'] = endtime($start_time);
			$json['error'] = 'VerifyCodeInvalid';
			$json['desc'] = "验证码已失效请重新验证邮箱";
			$json_code = json_encode($json);
			echo $json_code;
			die();
		}
	}else{
		//验证码错误	
		$json['status']= 2;
		$json['usetime'] = endtime($start_time);
		$json['error'] = 'VerifyCodeInvalid';
		$json['desc'] = "验证码已失效请重新验证邮箱";
		$json_code = json_encode($json);
		echo $json_code;
		die();	
	}
	
	
	//修改Email
	//新邮箱地址本地客户端是通过验证码验证的邮箱地址 这里不再验证是否有效地址
	//需要修改三处地方
	//client_user,account,pcmaker_sign_state
	
	//client_user
	$query = 'UPDATE `client_user` SET '.
					'`email`='.'\''.$post_new_email.'\''.
					' WHERE `id`='	.$post_userid.
					';';
	mysqli_query($connection,$query);
	
	//account
	$json_info = array(
		"pwd"=>$post_pwd
	);
	$query = 'SELECT * FROM `account` WHERE `user_id`='.$post_userid.' AND `type`=1;';
	$result = mysqli_query($connection,$query); 
	if($result){ 
		if(mysqli_num_rows($result)>0){
			//找到了
			$record = mysqli_fetch_assoc($result);
			//UPDATE
			$query = 'UPDATE `account` SET '.
					'`account`='.'\''.$post_new_email.'\','.
					'`info`='.'\''.json_encode($json_info).'\''.
					' WHERE `id`='	.$record['id'].
					';';
		}else{
			//没找到 
			//INSERT
			$query = 'INSERT INTO account(user_id,account,type,info,add_time) VALUES ('.
						''.$post_userid.','.
						'\''.$post_new_email.'\','.
						'1,'.
						'\''.json_encode($json_info).'\','.
						'now()'.
			');';
			
		}
		$result = mysqli_query($connection,$query);	
		if(!$result){
			//插入失败 
			//这里有风险可能会account和client_user表中记录不一致
			if($connection)mysqli_close($connection);
			$json['status']= 2;
			$json['usetime'] = endtime($start_time);
			$json['error'] = 'ServerError';
			$json['desc'] = "额服务器开小差了,请稍后重试...";
			$json_code = json_encode($json);
			echo $json_code;
			die();
		}
	}
	
	//pcmaker_sign_state
	//前面接口QrcodeCkLogin EmailLogin SignNewUser 做了保证一定会存在
	$query = 'UPDATE `pcmaker_sign_state` SET '.
					'`email`='.'\''.$post_new_email.'\','.
					'`email_cked`=1'.
					' WHERE `user_id`='	.$post_userid.
					';';
	mysqli_query($connection,$query);
	
	
	//结束
	$json['status']= 1;
	$json['usetime'] = endtime($start_time);
	$json['error'] = '';
	$json_code = json_encode($json);
	echo $json_code;
	
	//关闭连接
	if($connection)mysqli_close($connection);
	
	die();		
?>