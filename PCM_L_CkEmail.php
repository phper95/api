<?php
/**
* @api {post} /gmspanel/interface/zh-cn/3.1/PCM_L_CkEmail.php 检查邮箱是否可用
* @apiPermission pxseven
* @apiVersion 0.2.0
* @apiName CkEmail
* @apiGroup User
* @apiSampleRequest http://ser3.graphmovie.com/gmspanel/interface/zh-cn/3.1/PCM_L_CkEmail.php

* @apiDescription 用户在填写注册信息时，可以实时来检查邮箱是否可用。不过该检查并没有占用该邮箱，如果用户检查后耽搁了太长的时间没有完成注册，该邮箱可能被他人占用，在提交注册信息接口还会再次检查邮箱可用性。

* @apiParam (POST) {String} [pk=""] 用户PC的机器码(MAC地址)
* @apiParam (POST) {Integer} [v=0] 用户GraphMovieStudios的内部版本号
* @apiParam (POST) {String} email 用户输入的邮箱.
* @apiParam (POST) {String} [userid="0"] 如果是二维码扫描登录的用户，这里会有用户ID，检查邮箱是否可用时会排出本人.
*
* @apiSuccess (ResponseJSON) {Integer} status 接口响应状态（0-失败,1-成功,2-需要弹出提示框,提示desc内容）.
* @apiSuccess (ResponseJSON) {Float} usetime 接口响应时间,调试用.
* @apiSuccess (ResponseJSON) {String} error 接口响应出错时的错误描述.
* @apiSuccess (ResponseJSON) {String} debug 接口响应出错时的过程描述,调试用.
* @apiSuccess (ResponseJSON) {String} desc status=2时需要弹窗提示此内容.

*
* @apiSuccessExample Success-Response[邮箱可用]:

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
* @apiError EmailOccupied 该Email已被占用.

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
	$post_userid = '0';
	
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
		
		if(isset($data->userid) && $data->userid>0){
			$post_userid = htmlspecialchars(addslashes($data->userid));
		}
		
	}else if(
		isset($_POST['email']) && strlen($_POST['email'])>0
		){
		//获取参数
		$post_pk = 'na';
		$post_v = 0;
		if(isset($_POST['pk']) && strlen($_POST['pk'])>0){$post_pk = htmlspecialchars(addslashes($_POST['pk']));}
		if(isset($_POST['v'])){$post_v = htmlspecialchars(addslashes($_POST['v']));}
		$post_email = strtolower(htmlspecialchars(addslashes(trim($_POST['email']))));
		
		if(isset($_POST['userid']) && $_POST['userid']>0){$post_userid = htmlspecialchars(addslashes($_POST['userid']));}
		
	}else{
		//缺少参数
		$json['status']= 0;
		$json['usetime'] = endtime($start_time);
		$json['error'] = 'PostError';
		$json_code = json_encode($json);
		echo $json_code;
		die();
	}
	
	//是否已经被人占用
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
	
	if($post_userid>0){
		$query = 'SELECT * FROM `client_user` WHERE `email`=\''.$post_email.'\' AND `id`<>'.$post_userid.';';
	}else{
		$query = 'SELECT * FROM `client_user` WHERE `email`=\''.$post_email.'\';';
	}
	
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