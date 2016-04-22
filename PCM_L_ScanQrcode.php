<?php
/**
* @api {post} /gmspanel/interface/zh-cn/3.1/PCM_L_ScanQrcode.php App扫描二维码上报
* @apiPermission pxseven
* @apiVersion 0.1.0
* @apiName ScanQrcode
* @apiGroup User
* @apiSampleRequest http://ser3.graphmovie.com/gmspanel/interface/zh-cn/3.1/PCM_L_ScanQrcode.php

* @apiDescription 用户在App中扫描了二维码时，上报此接口（测试期间可以模拟此操作）。

* @apiParam (POST) {String} userid App扫描用户的userid.
* @apiParam (POST) {String} qrcode App扫描出的二维码字符串(32位).
* @apiParam (POST) {String} seckey App扫描出的二维码，加密后的字串，用于验证该扫描是否合法有效.
*
* @apiSuccess (ResponseJSON) {Integer} status 接口响应状态（0-失败,1-成功,2-需要弹出提示框,提示desc内容）.
* @apiSuccess (ResponseJSON) {Float} usetime 接口响应时间,调试用.
* @apiSuccess (ResponseJSON) {String} error 接口响应出错时的错误描述.
* @apiSuccess (ResponseJSON) {String} debug 接口响应出错时的过程描述,调试用.
* @apiSuccess (ResponseJSON) {String} desc status=2时需要弹窗提示此内容.

*
* @apiSuccessExample Success-Response[记录成功]:

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
* @apiError QrcodeInvalid 错误的二维码，或者二维码已超时.
* @apiError UserNotFound 没有这个用户.
* @apiError IllegalScan 验证不通过，非法上报.

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

*     QrcodeInvalid:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "QrcodeInvalid",
*       "debug": "",
*       "desc": "二维码已失效。"
*     }

*     UserNotFound:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "UserNotFound",
*       "debug": "",
*       "desc": "没有这个用户。"
*     }

*     IllegalScan:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "IllegalScan",
*       "debug": "",
*       "desc": "非法数据上报。"
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
	$post_qrcode = '';
	$post_userid = '';
	$post_seckey = '';
	
	//获取Header
	//模拟请求采用的是request payload 发上来的参数
	$request_body = file_get_contents('php://input');
	$data = json_decode($request_body);
	if($data && count($data)>0 && (!isset($_POST) || count($_POST)==0) && isset($data->qrcode) && strlen($data->qrcode)>0 && isset($data->userid) && strlen($data->userid)>0 && $data->userid>0 && isset($data->seckey) && strlen($data->seckey)>0){
		//curl提交的参数
		$post_qrcode = strtolower(htmlspecialchars(addslashes(trim($data->qrcode))));
		$post_userid = userIdKeyDecode(htmlspecialchars(addslashes($data->userid)));
		$post_seckey = strtolower(htmlspecialchars(addslashes($data->seckey)));
	}else if(
		isset($_POST['qrcode']) && strlen($_POST['qrcode'])>0
		&&
		isset($_POST['userid']) && strlen($_POST['userid'])>0 && $_POST['userid']>0
		){
		//获取参数
		$post_qrcode = strtolower(htmlspecialchars(addslashes(trim($_POST['qrcode']))));
		$post_userid = userIdKeyDecode(htmlspecialchars(addslashes($_POST['userid'])));
		$post_seckey = strtolower(htmlspecialchars(addslashes(trim($_POST['seckey']))));
	}else{
		//缺少参数
		$json['status']= 0;
		$json['usetime'] = endtime($start_time);
		$json['error'] = 'PostError';
		$json_code = json_encode($json);
		echo $json_code;
		die();
	}
	
	//验证是否合法
	//验证的规则是:用户的二维码先做一个md5,然后追加字符串graphmovie,然后再做md5
	if(md5(md5($post_qrcode).'graphmovie')!=$post_seckey){
		$json['status']= 2;
		$json['usetime'] = endtime($start_time);
		$json['error'] = 'IllegalScan';
		$json['desc'] = "非法数据上报。";
		//$json['debug'] .= '['.$post_qrcode.']:['.md5($post_qrcode).']:['.md5($post_qrcode).'graphmovie'.']:['.md5(md5($post_qrcode).'graphmovie').']:['.$post_seckey.']';
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
	
	//查询是否有此合法的二维码
	$query = 'SELECT * FROM `pcmaker_qrcode_login` WHERE `user_id`=0 AND `qrkey`=\''.$post_qrcode.'\' AND `over_time`>now();';
	$result = mysqli_query($connection,$query);
	if($result){
		if(mysqli_num_rows($result)>0){
			//有
			$qr_record = mysqli_fetch_assoc($result);
				
			//当前这个用户是否存在
			$query = 'SELECT * FROM `client_user` WHERE `id`='.$post_userid.';';
			$result = mysqli_query($connection,$query);
			if($result){
				if(mysqli_num_rows($result)>0){
					//存在
					//OK 合法扫描 更新userid入表
					$query = 'UPDATE `pcmaker_qrcode_login` SET '.
									'`user_id`='.$post_userid.
									' WHERE `id`='	.$qr_record['id'].
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
					
				}else{
					//没有这个用户
					//file_put_contents('scan_qr_to_login_1.txt',$query.PHP_EOL,FILE_APPEND);
					//关闭连接
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
				//错误SQL	
			}
			
			
		}
	}
	
	//其余均是二维码错误
	if($connection)mysqli_close($connection);
	$json['status']= 2;
	$json['usetime'] = endtime($start_time);
	$json['error'] = 'QrcodeInvalid';
	$json['desc'] = "二维码已失效。";
	$json_code = json_encode($json);
	echo $json_code;
	die();	
	
		
?>