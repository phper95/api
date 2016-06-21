<?php
/**
* @api {post} /gmspanel/interface/zh-cn/3.1/PCM_W_GetUrl.php 获取制作器所需url
* @apiPermission yongge
* @apiVersion 0.1.0
* @apiName GetUrl
* @apiGroup Work
* @apiSampleRequest http://ser3.graphmovie.com/gmspanel/interface/zh-cn/3.1/PCM_W_GetUrl.php

* @apiDescription 客户端通过此接口获取url

* @apiParam (POST) {String} [pk=""] 用户PC的机器码(MAC地址)
* @apiParam (POST) {Integer} [v=0] 用户GraphMovieStudios的内部版本号
* @apiParam (POST) {String} userid 用户的userid，必须为登录用户才可访问此接口
* @apiParam (POST) {Integer} urlNum 所需url的序号，1代表首页url

* @apiSuccess (ResponseJSON) {Integer} status 接口响应状态（0-失败,1-成功,2-需要弹出提示框,提示desc内容）.
* @apiSuccess (ResponseJSON) {Float} usetime 接口响应时间,调试用.
* @apiSuccess (ResponseJSON) {String} error 接口响应出错时的错误描述.
* @apiSuccess (ResponseJSON) {String} debug 接口响应出错时的过程描述,调试用.
* @apiSuccess (ResponseJSON) {String} desc status=2时需要弹窗提示此内容.
* @apiSuccess (ResponseJSON) {String} workid 操作成功返回该作品workid.

*
* @apiSuccessExample Success-Response[提交成功]:

*	{
*		"status": 1,
*		"usetime": "7.0E-5",
*		"error": "",
*		"debug": "na",
*		"desc": "",
*		"url": "http://ser3.graphmovie.com/pcmaker/GMStudios/home/index.php"
*	}

*
* @apiError PostError 请求的参数缺失或者参数格式错误.
* @apiError ServerError 服务器状态异常.
* @apiError TokenTimeOut 用户会话超时或非法.

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

*    TokenTimeOut:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "TokenTimeOut",
*       "debug": "",
*       "desc": "会话超时,请重新登录"
*     }
*
*
*

*   GetError:

* 	{
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "GetError",
*       "debug": "",
*       "desc": "获取失败,请稍后重试..."
*  	}
*
*
*/


	//config
	require_once(dirname(__FILE__).'/'.'inc/interface.json.inc.php');
	require_once(dirname(__FILE__).'/'.'inc/methods.inc.php');
	require_once(dirname(__FILE__).'/'.'inc/config.inc.php');
	require_once(dirname(__FILE__).'/'.'inc/time.methods.inc.php');
	
	//时间记录
	$start_time = starttime();
	
	//获取JSON基本模板
	$json = getBasicJsonModel();
	$json["desc"] = "";
	
	//20160304 内测结束 
	/*------------------------------------------------------------------------------------------------------------------*/
	/*------------------------------------------------------------------------------------------------------------------*/
	/*------------------------------------------------------------------------------------------------------------------*/
/*	$json['status']= 2;
	$json['usetime'] = endtime($start_time);
	$json['error'] = 'TESTEND';
	$json['desc'] = '本轮内测结束，谢谢大家的踊跃参与！未完成的作品仍然可以继续编辑和投稿，但不能再创建新的作品啦！';
	$json_code = json_encode($json);
	echo $json_code;
	die();*/
	/*------------------------------------------------------------------------------------------------------------------*/
	/*------------------------------------------------------------------------------------------------------------------*/
	/*------------------------------------------------------------------------------------------------------------------*/
	
	//初始化POST参数
	$post_pk = 'na';
	$post_v = 0;
	$post_userid = '0';
	$post_token = '';
	
	//获取Header
	//模拟请求采用的是request payload 发上来的参数
	$request_body = file_get_contents('php://input');
	$data = json_decode($request_body);
	if($data && count($data)>0 && (!isset($_POST) || count($_POST)==0) && isset($data->userid) && strlen($data->userid)>0 && isset($data->urlNum) && strlen($data->urlNum)>0){
		//curl提交的参数
		if(isset($data->pk)){
			$post_pk = htmlspecialchars(addslashes($data->pk));
		}
		
		if(isset($data->v)){
			$post_v = htmlspecialchars(addslashes($data->v));
		}
			$post_userid = htmlspecialchars(userIdKeyDecode(addslashes($data->userid)));
			$post_urlNum = htmlspecialchars(addslashes($data->urlNum));
	}else if(
		isset($_POST['userid']) && strlen($_POST['userid'])>0 &&
		isset($_POST['urlNum']) && strlen($_POST['urlNum'])>0
		){
		//获取参数
		if(isset($_POST['pk']) && strlen($_POST['pk'])>0){$post_pk = htmlspecialchars(addslashes($_POST['pk']));}
		if(isset($_POST['v'])){$post_v = htmlspecialchars(addslashes($_POST['v']));}
		$post_userid = htmlspecialchars(userIdKeyDecode(addslashes($_POST['userid'])));
		$post_token = htmlspecialchars(addslashes($_POST['token']));
		$post_urlNum = htmlspecialchars(addslashes($_POST['urlNum']));

	}else{
		//缺少参数
		$json['status']= 0;
		$json['usetime'] = endtime($start_time);
		$json['error'] = 'PostError';
		$json_code = json_encode($json);
		echo $json_code;
		die();
	}

	//适配本地调试
	if($post_userid=='3631408'){
		switch ($post_urlNum){
			case 1:$url='http://192.168.0.19/GMStudios/home/index.php';
		}
	}else{
		switch ($post_urlNum){
			case 1:$url='http://ser3.graphmovie.com/pcmaker/GMStudios/home/index.php';
		}
	}
	

	if(isset($url)){
		$json['url']= $url;
	}else{
		$json['status']= 2;
		$json['usetime'] = endtime($start_time);
		$json['error'] = 'GetError';
		$json['desc'] = '获取失败,请稍后重试...';
		die();
	}
	
	//结束
	$json['status']= 1;
	$json['usetime'] = endtime($start_time);
	$json['error']= '';
	$json_code = json_encode($json);
	echo $json_code;
	
	//关闭连接
	die();		
?>