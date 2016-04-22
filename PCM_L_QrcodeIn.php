<?php
/**
* @api {post} /gmspanel/interface/zh-cn/3.1/PCM_L_QrcodeIn.php 上报登录二维码Key
* @apiPermission pxseven
* @apiVersion 0.1.0
* @apiName QrcodeIn
* @apiGroup User
* @apiSampleRequest http://ser3.graphmovie.com/gmspanel/interface/zh-cn/3.1/PCM_L_QrcodeIn.php

* @apiDescription 用户在登录窗口点击右下角二维码按钮，本地生成二维码串，然后上报到服务器监控扫描情况。

* @apiParam (POST) {String} [pk=""] 用户PC的机器码(MAC地址)
* @apiParam (POST) {Integer} [v=0] 用户GraphMovieStudios的内部版本号
* @apiParam (POST) {String} qrcode 客户端生成的二维码字符串(32位).
*
* @apiSuccess (ResponseJSON) {Integer} status 接口响应状态（0-失败,1-成功,2-需要弹出提示框,提示desc内容）.
* @apiSuccess (ResponseJSON) {Float} usetime 接口响应时间,调试用.
* @apiSuccess (ResponseJSON) {String} error 接口响应出错时的错误描述.
* @apiSuccess (ResponseJSON) {String} debug 接口响应出错时的过程描述,调试用.
* @apiSuccess (ResponseJSON) {String} desc status=2时需要弹窗提示此内容.
* @apiSuccess (ResponseJSON) {String} qrcode 接口返回的需要生成二维码的字符串,由于客户端w2c协议的设计,这里需要生成二维码的字符串中包含了一个url.

*
* @apiSuccessExample Success-Response:

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
	$post_qrcode = '';
	
	//获取Header
	//模拟请求采用的是request payload 发上来的参数
	$request_body = file_get_contents('php://input');
	$data = json_decode($request_body);
	if($data && count($data)>0 && (!isset($_POST) || count($_POST)==0) && isset($data->qrcode) && strlen($data->qrcode)>0){
		//curl提交的参数
		if(isset($data->pk)){
			$post_pk = htmlspecialchars(addslashes($data->pk));
		}
		
		if(isset($data->v)){
			$post_v = htmlspecialchars(addslashes($data->v));
		}
		
		$post_qrcode = strtolower(htmlspecialchars(addslashes($data->qrcode)));
		
	}else if(
		isset($_POST['qrcode']) && strlen($_POST['qrcode'])>0
		){
		//获取参数
		$post_pk = 'na';
		$post_v = 0;
		if(isset($_POST['pk']) && strlen($_POST['pk'])>0){$post_pk = htmlspecialchars(addslashes($_POST['pk']));}
		if(isset($_POST['v'])){$post_v = htmlspecialchars(addslashes($_POST['v']));}
		
		$post_qrcode = strtolower(htmlspecialchars(addslashes($_POST['qrcode'])));
		
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
	
	//然后直接记录即可
	//记录入数据库
	//默认超时时间1个小时
	$nowtime = time(); //获取当前的时间戳
	$now = date('Y-m-d H:i:s'); 
	$overtime =  date('Y-m-d H:i:s',$nowtime+3600);
	$query = 'INSERT INTO pcmaker_qrcode_login(user_id,qrkey,add_time,over_time) VALUES ('.
						 '0,'.
						 '\''.$post_qrcode.'\','.
						 '\''.$now.'\','.
						 '\''.$overtime.'\''.
	');';
	mysqli_query($connection, $query);
	
	//$json['debug'] = $query;
	
	$json['qrcode']= 'http://ser3.graphmovie.com/gmspanel/interface/zh-cn/3.1/scan_qr_to_login.php?lgk='.$post_qrcode;
	
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