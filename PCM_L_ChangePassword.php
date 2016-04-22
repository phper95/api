<?php
/**
* @api {post} /gmspanel/interface/zh-cn/3.1/PCM_L_ChangePassword.php 修改密码
* @apiPermission pxseven
* @apiVersion 0.1.0
* @apiName ChangePassword
* @apiGroup User
* @apiSampleRequest http://ser3.graphmovie.com/gmspanel/interface/zh-cn/3.1/PCM_L_ChangePassword.php

* @apiDescription 用户修改密码时会发送一封验证邮件（请求发送验证邮件接口<code>SendVerifyEmail</code>），邮件中有验证码，用户通过验证后（请求验证接口<code>VerifyEmail</code>）才可以请求此接口重置密码，此接口将会对邮箱验证的结果进行复核，如果一小时内，没有合法的验证记录，将会拒绝修改（也就是用户输入验证码后，必须1小时内点击提交按钮）。

* @apiParam (POST) {String} [pk=""] 用户PC的机器码(MAC地址)
* @apiParam (POST) {Integer} [v=0] 用户GraphMovieStudios的内部版本号
* @apiParam (POST) {String} email 用户完成验证的Email地址.
* @apiParam (POST) {String} vfcode 验证码.
* @apiParam (POST) {String} pwd 用户新的的登录密码MD5(32位大小写不限).

*
* @apiSuccess (ResponseJSON) {Integer} status 接口响应状态（0-失败,1-成功,2-需要弹出提示框,提示desc内容）.
* @apiSuccess (ResponseJSON) {Float} usetime 接口响应时间,调试用.
* @apiSuccess (ResponseJSON) {String} error 接口响应出错时的错误描述.
* @apiSuccess (ResponseJSON) {String} debug 接口响应出错时的过程描述,调试用.
* @apiSuccess (ResponseJSON) {String} desc status=2时需要弹窗提示此内容.

*
* @apiSuccessExample Success-Response[修改成功]:

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
* @apiError TimeOut 操作超时，应在一小时内完成修改.
* @apiError PasswordInvalid 新旧密码<code>pwd</code>不能一样.
* @apiError VerifyCodeInvalid 验证码非法(超时、邮箱地址更改、验证码输错、码已被用等情况)
* @apiError UserNotFound 根本就没有这个<code>email</code>的用户.

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

*     TimeOut:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "TimeOut",
*       "debug": "",
*       "desc": "操作超时，请在1小时内完成提交。"
*     }

*     PasswordInvalid:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "PasswordInvalid",
*       "debug": "",
*       "desc": "新密码不能同旧密码相同哇"
*     }

*     VerifyCodeInvalid:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "VerifyCodeInvalid",
*       "debug": "",
*       "desc": "验证码错误(tips:Ctrl+C,Ctrl+V)"
*     }

*     UserNotFound:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "UserNotFound",
*       "debug": "",
*       "desc": "没有这个用户。"
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
	$post_email = '';
	$post_vfcode = '0';
	$post_pwd = '';
	
	//获取Header
	//模拟请求采用的是request payload 发上来的参数
	$request_body = file_get_contents('php://input');
	$data = json_decode($request_body);
	if($data && count($data)>0 && (!isset($_POST) || count($_POST)==0) && isset($data->email) && strlen($data->email)>0  && isset($data->vfcode) && strlen($data->vfcode)>0 && isset($data->pwd) && strlen($data->pwd)>0){
		//curl提交的参数
		if(isset($data->pk)){
			$post_pk = htmlspecialchars(addslashes($data->pk));
		}
		
		if(isset($data->v)){
			$post_v = htmlspecialchars(addslashes($data->v));
		}
		
		$post_email = strtolower(htmlspecialchars(addslashes(trim($data->email))));
		$post_vfcode = strtolower(htmlspecialchars(addslashes(trim($data->vfcode))));
		$post_pwd = strtolower(htmlspecialchars(addslashes(trim($data->pwd))));
		
	}else if(
		isset($_POST['email']) && strlen($_POST['email'])>0
		&&
		isset($_POST['vfcode']) && strlen($_POST['vfcode'])>0
		&&
		isset($_POST['pwd']) && strlen($_POST['pwd'])>0
		){
		//获取参数
		if(isset($_POST['pk']) && strlen($_POST['pk'])>0){$post_pk = htmlspecialchars(addslashes($_POST['pk']));}
		if(isset($_POST['v'])){$post_v = htmlspecialchars(addslashes($_POST['v']));}
		$post_email = strtolower(htmlspecialchars(addslashes(trim($_POST['email']))));
		$post_vfcode = strtolower(htmlspecialchars(addslashes(trim($_POST['vfcode']))));
		$post_pwd = strtolower(htmlspecialchars(addslashes(trim($_POST['pwd']))));
		
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
	
	
	//先检查是否有该邮箱的验证记录
	$query = 'SELECT * FROM `pcmaker_email_verify` WHERE `email`=\''.$post_email.'\' AND `verify_code`=\''.$post_vfcode.'\' AND `type`=2 ORDER BY `id` DESC LIMIT 1;';
	$result = mysqli_query($connection,$query);
	if($result){
		if(mysqli_num_rows($result)>0){
			$record = mysqli_fetch_assoc($result);	
			//判断是否超时
			if(strtotime($record['over_time'])<time()){
				//超时
				//关闭连接
				if($connection)mysqli_close($connection);
				$json['status']= 2;
				$json['usetime'] = endtime($start_time);
				$json['error'] = 'TimeOut';
				$json['desc'] = "操作超时，请在1小时内完成提交。";
				$json_code = json_encode($json);
				echo $json_code;
				die();	
			}else{
				//OK存在合法记录
				//如果没有confirmed =1 就设置为1
				if($record['confirmed']==0){
					//UPDATE
					$query = 'UPDATE `pcmaker_email_verify` SET '.
						'`confirmed`=1 '.
						' WHERE `id`='	.$record['id'].
						';';
					mysqli_query($connection,$query);
				}
			}
			
		}else{
			//非法
			//关闭连接
			if($connection)mysqli_close($connection);
			$json['status']= 2;
			$json['usetime'] = endtime($start_time);
			$json['error'] = 'VerifyCodeInvalid';
			$json['desc'] = "验证码错误(tips:Ctrl+C,Ctrl+V)";
			$json_code = json_encode($json);
			echo $json_code;
			die();	
		}
	}else{
		//错误的SQL
		//关闭连接
		if($connection)mysqli_close($connection);
		$json['status']= 2;
		$json['usetime'] = endtime($start_time);
		$json['error'] = 'ServerError';
		$json['desc'] = "额服务器开小差了,请稍后重试...";
		$json_code = json_encode($json);
		echo $json_code;
		die();
	}
	
	
	//找到这个用户
	$query = 'SELECT * FROM `client_user` WHERE `email`=\''.$post_email.'\';';
	$result = mysqli_query($connection,$query);
	if($result){
		if(mysqli_num_rows($result)>0){
			//找到了
			$record = mysqli_fetch_assoc($result);
				
			if($record['secure_pwd_md5']==$post_pwd){
				//密码相同
				//关闭连接
				if($connection)mysqli_close($connection);
				$json['status']= 2;
				$json['usetime'] = endtime($start_time);
				$json['error'] = 'PasswordInvalid';
				$json['desc'] = "新密码不能同旧密码相同哇";
				$json_code = json_encode($json);
				echo $json_code;
				die();
			}
			
			//OK通过验证
			//修改密码
			//修改Password
			//需要修改2处地方
			//client_user,account
			
			//client_user
			$query = 'UPDATE `client_user` SET '.
							'`secure_pwd_md5`='.'\''.$post_pwd.'\''.
							' WHERE `id`='	.$record['id'].
							';';
			mysqli_query($connection,$query);
			
			//account
			$json_info = array(
				"pwd"=>$post_pwd
			);
			$query = 'SELECT * FROM `account` WHERE `user_id`='.$record['id'].' AND `type`=1;';
			$result = mysqli_query($connection,$query); 
			if($result){ 
				if(mysqli_num_rows($result)>0){
					//找到了
					$account_record = mysqli_fetch_assoc($result);
					//UPDATE
					$query = 'UPDATE `account` SET '.
							'`info`='.'\''.json_encode($json_info).'\' '.
							' WHERE `id`='	.$account_record['id'].
							';';
				}else{
					//没找到 
					//INSERT
					$query = 'INSERT INTO account(user_id,account,type,info,add_time) VALUES ('.
								''.$record['id'].','.
								'\''.$record['email'].'\','.
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