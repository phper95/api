<?php
/**
* @api {post} /gmspanel/interface/zh-cn/3.1/PCM_W_MyWorksCount.php 获取用户作品总数
* @apiPermission yongge
* @apiVersion 0.1.0
* @apiName MyWorksCount
* @apiGroup Work
* @apiSampleRequest http://ser3.graphmovie.com/gmspanel/interface/zh-cn/3.1/PCM_W_MyWorksCount.php

* @apiDescription 客户端作品管理面板请求此接口来获取该作者的作品个数

* @apiParam (POST) {String} [pk=""] 用户PC的机器码(MAC地址)
* @apiParam (POST) {Integer} [v=0] 用户GraphMovieStudios的内部版本号
* @apiParam (POST) {String} userid 用户的userid，必须为登录用户才可访问此接口
* @apiParam (POST) {String} token 用户的登录时返回的token，用于验证用户是否合法

*
* @apiSuccess (ResponseJSON) {Integer} status 接口响应状态（0-失败,1-成功,2-需要弹出提示框,提示desc内容）.
* @apiSuccess (ResponseJSON) {Float} usetime 接口响应时间,调试用.
* @apiSuccess (ResponseJSON) {String} error 接口响应出错时的错误描述.
* @apiSuccess (ResponseJSON) {String} debug 接口响应出错时的过程描述,调试用.
* @apiSuccess (ResponseJSON) {String} desc status=2时需要弹窗提示此内容.
* @apiSuccess (ResponseJSON) {Integer} edit 未完成作品总数.
* @apiSuccess (ResponseJSON) {Integer} online 已上线作品总数.
* @apiSuccess (ResponseJSON) {Integer} official 已收录作品总数.
* @apiSuccess (ResponseJSON) {Integer} offline 被下线作品总数.

*
* @apiSuccessExample Success-Response[提交成功]:
*		{
*			"status": 1,
*			"usetime": "0.0457",
*			"error": "",
*			"debug": "na",
*			"desc": "",
*			"work_total1": 7,
*			"work_total2": 0,
*			"work_total3": 518,
*			"work_total4": 1
*		}
*
* @apiError PostError 请求的参数缺失或者参数格式错误.
* @apiError ServerError 服务器状态异常.
* @apiError TokenTimeOut 用户会话超时或非法.
* @apiError ErrorWorkState 发送的state参数不能识别(1-4)
* @apiError EmptyPageLimit 错误的分页请求参数

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

	//初始化POST参数
	$post_pk = 'na';
	$post_v = 0;
	$post_userid = '0';
	$post_token = '';
	
	$post_state = 0;
	
	$post_page = 0;
	$post_limit = 9;
	$post_tv = 0;
	$post_tag = '0';
	$post_pcklv = 0;
	$post_weeklv = 0;
	$post_offtype = 0;
	
	//获取Header
	//模拟请求采用的是request payload 发上来的参数
	$request_body = file_get_contents('php://input');
	$data = json_decode($request_body);
	if($data && count($data)>0 && (!isset($_POST) || count($_POST)==0) && isset($data->userid) && strlen($data->userid)>0 && isset($data->token) && strlen($data->token)>0){
		//curl提交的参数
		if(isset($data->pk)){
			$post_pk = htmlspecialchars(addslashes($data->pk));
		}
		
		if(isset($data->v)){
			$post_v = htmlspecialchars(addslashes($data->v));
		}
		
		if(isset($data->userid)){
			$post_userid = userIdKeyDecode(htmlspecialchars(addslashes($data->userid)));
		}
		
		if(isset($data->token)){
			$post_token = htmlspecialchars(addslashes($data->token));
		}
		
		if(isset($data->state)){
			$post_state = htmlspecialchars(addslashes($data->state));
		}
		
		if(isset($data->page)){
			$post_page = htmlspecialchars(addslashes($data->page));
		}
		
		if(isset($data->limit)){
			$post_limit = htmlspecialchars(addslashes($data->limit));
		}
		
		if(isset($data->tv)){
			$post_tv = htmlspecialchars(addslashes($data->tv));
		}
		
		if(isset($data->tag)){
			$post_tag = htmlspecialchars(addslashes($data->tag));
		}
		
		if(isset($data->pcklv)){
			$post_pcklv = htmlspecialchars(addslashes($data->pcklv));
		}
		
		if(isset($data->weeklv)){
			$post_weeklv = htmlspecialchars(addslashes($data->weeklv));
		}
		
		if(isset($data->offtype)){
			$post_offtype = htmlspecialchars(addslashes($data->offtype));
		}
		
		
	}else if(
		isset($_POST['userid']) && strlen($_POST['userid'])>0 && 
		isset($_POST['token']) && strlen($_POST['token'])>0
		){
		//获取参数
		if(isset($_POST['pk']) && strlen($_POST['pk'])>0){$post_pk = htmlspecialchars(addslashes($_POST['pk']));}
		if(isset($_POST['v'])){$post_v = htmlspecialchars(addslashes($_POST['v']));}
		if(isset($_POST['userid'])){$post_userid = userIdKeyDecode(htmlspecialchars(addslashes($_POST['userid'])));}
		if(isset($_POST['token'])){$post_token = htmlspecialchars(addslashes($_POST['token']));}
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


	$json['edit']=0;
	$json['online']=0;
	$json['official']=0;
	$json['offline']=0;


	//1-创作中 2-已上线 3-已收录 4-被下线（含用户自主下线以及官方删除和退稿）
		
		//查一查总页数
		$query = 'SELECT * FROM `pcmaker_work` WHERE `user_id`='.$post_userid.' AND  `state`=1;';
		$result = mysqli_query($connection,$query);
		if($result){
			if(mysqli_num_rows($result)>0){
				$json['edit'] = mysqli_num_rows($result);
			}
		}


		//查一查总页数
		$query = 'SELECT * FROM `pcmaker_work` WHERE `user_id`='.$post_userid.' AND  `state`=2';
		$result = mysqli_query($connection,$query);
		if($result){
			if(mysqli_num_rows($result)>0){
				$json['online'] = mysqli_num_rows($result);
			}
		}

		//已收录作品 还有 该作者之前上线作品的目录 因此只查movie表即可
		
		//查一查总页数
		$query = 'SELECT * FROM `movie` WHERE `grapher`=\''.$post_userid.'\' AND `ding`>0 AND `open`=1';


		$result = mysqli_query($connection,$query);
		if($result){
			if(mysqli_num_rows($result)>0){
				$json['official'] = mysqli_num_rows($result);
			}
		}

		
		//查一查总页数
		$query = 'SELECT * FROM `pcmaker_work` WHERE `user_id`='.$post_userid.' AND ( `state`=4 OR `state`=-1 OR `state`=-3 ) ';


		$result = mysqli_query($connection,$query);
		if($result){
			if(mysqli_num_rows($result)>0){
				$json['offline'] = mysqli_num_rows($result);
			}
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