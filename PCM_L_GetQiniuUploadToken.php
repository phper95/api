<?php
/**
 * @api {post} /gmspanel/interface/zh-cn/3.1/PCM_L_GetQiniuUploadToken.php 获取七牛上传token
 * @apiPermission pxseven
 * @apiVersion 0.1.0
 * @apiName GetQiniuUploadToken
 * @apiGroup User
 * @apiSampleRequest http://ser3.graphmovie.com/gmspanel/interface/zh-cn/3.1/PCM_L_GetQiniuUploadToken.php

 * @apiDescription 用户请求此接口来生成七牛上传的token

 * @apiParam (POST) {String} [pk=""] 用户PC的机器码(MAC地址)
 * @apiParam (POST) {Integer} [v=0] 用户GraphMovieStudios的内部版本号
 * @apiParam (POST) {String} userid 用户的userid，必须为登录用户才可访问此接口
 * @apiParam (POST) {String} token 用户的登录时返回的token,用于验证用户是否合法

 *
 * @apiSuccess (ResponseJSON) {Integer} status 接口响应状态（0-失败,1-成功,2-需要弹出提示框,提示desc内容）.
 * @apiSuccess (ResponseJSON) {String} upload_token 七牛的上传token.
 * @apiSuccess (ResponseJSON) {Float} usetime 接口响应时间,调试用.
 * @apiSuccess (ResponseJSON) {String} error 接口响应出错时的错误描述.
 * @apiSuccess (ResponseJSON) {String} debug 接口响应出错时的过程描述,调试用.
 * @apiSuccess (ResponseJSON) {String} desc status=2时需要弹窗提示此内容.

 *
 * @apiSuccessExample Success-Response[提交成功]:

*	{
*		"status": 1,
*		"usetime": "0.00656",
*		"error": "",
*		"debug": "na",
*		"desc": "",
*		"query": "",
*		"upload_token": "QINIU_ACCESS_KEY:Pr7vaS6drzDlQs9BeiEfQzpu2xA=:eyJzY29wZSI6IlFJTklVX0JVQ0tFVCIsImRlYWRsaW5lIjoxNDYzNDA0MzkyfQ=="
*	}

*
 * @apiError PostError 请求的参数缺失或者参数格式错误.
 * @apiError ServerError 服务器状态异常.
 * @apiError TokenTimeOut 用户会话超时或非法.
 * @apiError ErrorDoubanURL 豆瓣链接错误.

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

 *     ErrorDoubanURL:

 *     {
 *       "status": 2,
 *       "usetime": 0.0024,
 *       "error": "GetTokenTimeOut",
 *       "debug": "",
 *       "desc": "获取token超时,请稍后再试"
 *     }

 */

//config
require_once(dirname(__FILE__).'/'.'inc/interface.json.inc.php');
require_once(dirname(__FILE__).'/'.'inc/methods.inc.php');
require_once(dirname(__FILE__).'/'.'inc/config.inc.php');
require_once(dirname(__FILE__).'/'.'inc/time.methods.inc.php');
require_once(dirname(__FILE__).'/'.'inc/qiniu/vendor/autoload.php');

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
$post_dburl = '';

//获取Header
//模拟请求采用的是request payload 发上来的参数
$request_body = file_get_contents('php://input');
$data = json_decode($request_body);
if($data && count($data)>0 && (!isset($_POST) || count($_POST)==0) && isset($data->userid) && strlen($data->userid)>0){
	//curl提交的参数
	if(isset($data->pk)){
		$post_pk = htmlspecialchars(addslashes($data->pk));
	}

	if(isset($data->v)){
		$post_v = htmlspecialchars(addslashes($data->v));
	}

		$post_userid = userIdKeyDecode(htmlspecialchars(addslashes($data->userid)));
		$post_token = htmlspecialchars(addslashes($data->token));

}else if(
	isset($_POST['userid']) && strlen($_POST['userid'])>0 &&
	isset($_POST['token']) && strlen($_POST['token'])>0
){
	//获取参数
	if(isset($_POST['pk']) && strlen($_POST['pk'])>0){$post_pk = htmlspecialchars(addslashes($_POST['pk']));}
	if(isset($_POST['v'])){$post_v = htmlspecialchars(addslashes($_POST['v']));}
	$post_userid = userIdKeyDecode(htmlspecialchars(addslashes($_POST['userid'])));
	$post_token = htmlspecialchars(addslashes($_POST['token']));

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

//查询用户token是否合法
//token 超时时间是3天
$okdate = date("Y-m-d H:i:s",strtotime("-3 day"));;
$query = 'SELECT * FROM `pcmaker_request_token` WHERE `token`=\''.$post_token.'\' AND `userid`='.$post_userid.' AND `add_time`>\''.$okdate.'\';';
$result = mysqli_query($connection,$query);
if($result){
	if(mysqli_num_rows($result)==0){
		//没找到 非法token
		//关闭连接
		if($connection)mysqli_close($connection);
		$json['status']= 2;
		$json['usetime'] = endtime($start_time);
		$json['error'] = 'TokenTimeOut';
		$json['desc'] = "会话超时,请重新登录";
		$json_code = json_encode($json);
		echo $json_code;
		die();
	}
}


//OK TOKEN 合法
use Qiniu\Auth;
$auth = new Auth(QINIU_ACCESS_KEY, QINIU_SECRET_KEY);
// 空间名  http://developer.qiniu.com/docs/v6/api/overview/concepts.html#bucket

//设置回调
$policy = array(
      'callbackUrl' =>  'http://ser3.graphmovie.com/boo/interface/api/qi/cb.php',
      'callbackBody' => 'bucket=$(bucket)&key=$(key)&fsize=$(fsize)&mimeType=$(mimeType)&exif=$(exif)&imageInfo=$(imageInfo)&imageAve=$(imageAve)&ext=$(ext)',
      'saveKey'=> md5 ( uniqid ( rand (), true ))
  );
  // 生成上传Token
$token = $auth->uploadToken(QINIU_BUCKET,null,7200,$policy);
  //var_dump($policy);
//var_dump($token);

//$json = '{"a":100,"b":200,"c":300,"d":400,"e":500}';



if(!$token){
	if($connection)mysqli_close($connection);
	$json['status']= 2;
	$json['usetime'] = endtime($start_time);
	$json['error'] = 'GetTokenTimeOut';
	$json['desc'] = "获取token超时,请稍后重试";
	$json_code = json_encode($json);
	echo $json_code;
	die();
}
//结束
$json['status']= 1;
$json['usetime'] = endtime($start_time);
$json['error'] = '';
$json['upload_token'] = $token;
$json_code = json_encode($json);
echo $json_code;

//关闭连接
if($connection)mysqli_close($connection);
die();
