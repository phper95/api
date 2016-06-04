<?php
/**
* @api {post} /gmspanel/interface/zh-cn/3.1/PCM_W_GetUpdateInfo.php 获取版本升级信息
* @apiPermission yongge
* @apiVersion 0.1.0
* @apiName GetUpdateInfo
* @apiGroup Work
* @apiSampleRequest http://ser3.graphmovie.com/gmspanel/interface/zh-cn/3.1/PCM_W_GetUpdateInfo.php

* @apiDescription 获取版本升级内部的版本号，app版本号，简介路径，下载路径和文件md5值

* @apiParam (POST) {String} [pk=""] 用户PC的机器码(MAC地址)
* @apiParam (POST) {Integer} [v=0] 用户GraphMovieStudios的内部版本号


*
* @apiSuccess (ResponseJSON) {Integer} status 接口响应状态（0-失败,1-成功,2-需要弹出提示框,提示desc内容）.
* @apiSuccess (ResponseJSON) {Float} usetime 接口响应时间,调试用.
* @apiSuccess (ResponseJSON) {String} error 接口响应出错时的错误描述.
* @apiSuccess (ResponseJSON) {String} debug 接口响应出错时的过程描述,调试用.
* @apiSuccess (ResponseJSON) {String} desc status=2时需要弹窗提示此内容.
* @apiSuccess (ResponseJSON) {Array} update_info 版本信息，详细信息如下.
* @apiSuccess (ResponseJSON) {String} update_info.InternalVersion 内部版本号.
* @apiSuccess (ResponseJSON) {String} update_info.AppVersion App版本号.
* @apiSuccess (ResponseJSON) {String} update_info.VersionIntro 版本更新简介.
* @apiSuccess (ResponseJSON) {String} update_info.DownloadUrl 下载地址.
* @apiSuccess (ResponseJSON) {String} update_info.MD5 文件的MD5值.


*
*@apiSuccessExample Success-Response[提交成功]:

*	{
*		"status": 1,
*		"usetime": "4.0E-5",
*		"error": "",
*		"debug": "na",
*		"desc": "",
*		"query": "",
*		"update_info": {
*		"InternalVersion": "0",
*		"AppVersion": "1.0",
*		"VersionIntro": "http://web.graphmovie.com/home/maker/Info/2.8.html",
*		"DownloadUrl": "http://web.graphmovie.com/home/maker/download/GraphEditorV2.8.exe",
*		"MD5": "0e046fa662aeaa2daf99194f41fd07ee"
*		}
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

*     TokenTimeOut:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "TokenTimeOut",
*       "debug": "",
*       "desc": "会话超时,请重新登录"
*     }

*/

	//config
	require_once(dirname(__FILE__).'/'.'inc/interface.json.inc.php');
	require_once(dirname(__FILE__).'/'.'inc/methods.inc.php');
	require_once(dirname(__FILE__).'/'.'inc/config.inc.php');
	require_once(dirname(__FILE__).'/'.'inc/time.methods.inc.php');
	require_once(dirname(__FILE__).'/'.'inc/post.methods.inc.php');
	
	//时间记录
	$start_time = starttime();
	
	//获取JSON基本模板
	$json = getBasicJsonModel();
	$json["desc"] = "";
	$json["query"] = "";

	//20160304 内测结束 
	/*------------------------------------------------------------------------------------------------------------------*/
	/*------------------------------------------------------------------------------------------------------------------*/
	/*------------------------------------------------------------------------------------------------------------------*/
	/*$json['status']= 2;
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
	$post_keywords = '';
	
	//获取Header
	//模拟请求采用的是request payload 发上来的参数
	$request_body = file_get_contents('php://input');
	$data = json_decode($request_body);
	if($data && count($data)>0 && (!isset($_POST) || count($_POST)==0)){
		//curl提交的参数
		if(isset($data->pk)){
			$post_pk = htmlspecialchars(addslashes($data->pk));
		}


		if(isset($data->v)){
			$post_v = htmlspecialchars(addslashes($data->v));
		}

	}else{
		//获取参数
		if(isset($_POST['pk']) && strlen($_POST['pk'])>0){$post_pk = htmlspecialchars(addslashes($_POST['pk']));}
		if(isset($_POST['v'])){$post_v = htmlspecialchars(addslashes($_POST['v']));}

	}

	$arr = array(
		'InternalVersion' => '0',//1.0的内部版本号为0，逐版本递增
		'AppVersion' => '1.0',
		'VersionIntro' => 'http://ser3.graphmovie.com/pcmaker/GMStudios/info/1.0.html',
		'DownloadUrl' => 'http://web.graphmovie.com/home/maker/download/GM Studio V1.0.exe',
		'MD5' => 'd2fdaa89635e60be953a35e6c3836b7c'//0e046fa662aeaa2daf99194f41fd07ee
	);

	$json['update_info'] = $arr;

	//结束
	$json['status']= 1;
	$json['usetime'] = endtime($start_time);
	$json['error'] = '';
	$json_code = json_encode($json);
	echo $json_code;

?>