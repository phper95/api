<?php
/**
* @api {post} /gms_works/interface/PCM_W_ImageS4DelWorkIdUploadKey.php 【非客户端】删除给定uploadkey下所有图片
* @apiPermission pxseven
* @apiVersion 0.1.0
* @apiName ImageS4DelWorkIdUploadKey
* @apiGroup Work
* @apiSampleRequest http://imgs4.graphmovie.com/gms_works/interface/PCM_W_ImageS4DelWorkIdUploadKey.php

* @apiDescription 【非客户端调用接口】处理从ser3服务器发送过来的删除workid/uploadkey下所有图片的请求

* @apiParam (POST) {String} svk 加密的验证key，用于验证请求是否有效
* @apiParam (POST) {String} workid 需要删除作品ID
* @apiParam (POST) {String} uploadkey 需要删除作品的上传key，每部作品每次上传会生成唯一一个上传key

*
* @apiSuccess (ResponseJSON) {Integer} status 接口响应状态（0-失败,1-成功）.
* @apiSuccess (ResponseJSON) {Float} usetime 接口响应时间,调试用.
* @apiSuccess (ResponseJSON) {String} error 接口响应出错时的错误描述.
* @apiSuccess (ResponseJSON) {String} debug 接口响应出错时的过程描述,调试用.
* @apiSuccess (ResponseJSON) {String} desc status=2时需要弹窗提示此内容.

*
* @apiSuccessExample Success-Response[提交成功]:

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
* @apiError ErrorRequest 非法请求.

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
*       "status": 0,
*       "usetime": 0.0024,
*       "error": "ServerError",
*       "debug": "",
*       "desc": ""
*     }

*     ErrorRequest:

*     {
*       "status": 0,
*       "usetime": 0.0024,
*       "error": "ErrorRequest",
*       "debug": "",
*       "desc": ""
*     }

*/

	//config
	require_once('interface.json.inc.php');
	require_once('methods.inc.php');
	require_once('FileUtil.inc.php');
	
	//时间记录
	$start_time = starttime();
	
	//获取JSON基本模板
	$json = getBasicJsonModel();
	$json["desc"] = "";
	
	//初始化POST参数
	$post_svk = '';
	$post_workid = '';
	$post_uploadkey = '';
	
	//获取Header
	//模拟请求采用的是request payload 发上来的参数
	$request_body = file_get_contents('php://input');
	$data = json_decode($request_body);
	if($data && count($data)>0 && (!isset($_POST) || count($_POST)==0) && isset($data->svk) && strlen($data->svk)>0 && isset($data->workid) && strlen($data->workid)>0 && isset($data->uploadkey) && strlen($data->uploadkey)>0){
		//curl提交的参数
		if(isset($data->svk)){
			$post_svk = htmlspecialchars(addslashes($data->svk));
		}
		
		if(isset($data->uploadkey)){
			$post_uploadkey = htmlspecialchars(addslashes($data->uploadkey));
		}
		
		if(isset($data->workid)){
			$post_workid = htmlspecialchars(addslashes($data->workid));
		}
		
	}else if(
		isset($_POST['svk']) && strlen($_POST['svk'])>0 && 
		isset($_POST['workid']) && strlen($_POST['workid'])>0 &&
		isset($_POST['uploadkey']) && strlen($_POST['uploadkey'])>0
		){
		//获取参数
		if(isset($_POST['svk']) && strlen($_POST['svk'])>0){$post_svk = htmlspecialchars(addslashes($_POST['svk']));}
		if(isset($_POST['workid'])){$post_workid = htmlspecialchars(addslashes($_POST['workid']));}
		if(isset($_POST['uploadkey'])){$post_uploadkey = htmlspecialchars(addslashes($_POST['uploadkey']));}
		
	}else{
		//缺少参数
		$json['status']= 0;
		$json['usetime'] = endtime($start_time);
		$json['error'] = 'PostError';
		$json_code = json_encode($json);
		echo $json_code;
		die();
	}
	
	
	//验证svk是否合法
	$svk = md5($post_workid.'graph'.$post_uploadkey.'movie'.'from ser3.graphmovie.com');
	if($svk != $post_svk){
		//非法请求
		$json['status']= 0;
		$json['usetime'] = endtime($start_time);
		$json['error'] = 'ErrorRequest';
		$json['desc'] = "";
		$json_code = json_encode($json);
		echo $json_code;
		die();
	}
	
	//通过验证
	//删除
	$del_root = '../work_imgs/';
	$del_workkey_dir = $del_root.$post_workid;
	$del_uploadkey_dir = $del_workkey_dir.'/'.$post_uploadkey;
	if(is_dir($del_workkey_dir) && is_dir($del_uploadkey_dir)){
		//删除所有
		$fu = new FileUtil();
		$fu->unlinkDir($del_uploadkey_dir);
	}
	
	//结束
	$json['status']= 1;
	$json['usetime'] = endtime($start_time);
	$json['error'] = '';
	$json_code = json_encode($json);
	echo $json_code;
	
	die();
?>