<?php
/**
* @api {post} /gmspanel/interface/zh-cn/3.1/PCM_M_Feedback.php 用户反馈
* @apiPermission pxseven
* @apiVersion 0.1.0
* @apiName Feedback
* @apiGroup Msg
* @apiSampleRequest http://ser3.graphmovie.com/gmspanel/interface/zh-cn/3.1/PCM_M_Feedback.php

* @apiDescription 用户反馈信息提交上来，方便后台直接回复，用户将会在未读消息中看到回复

* @apiParam (POST) {String} [pk=""] 用户PC的机器码(MAC地址)
* @apiParam (POST) {Integer} [v=0] 用户GraphMovieStudios的内部版本号
* @apiParam (POST) {String} [userid="0"] 用户的userid，如果用户未登录发0即可.
* @apiParam (POST) {String} [contact=""] 用户在反馈面板中输入的联系方式.
* @apiParam (POST) {String} feedback 用户在反馈面板中输入的反馈内容.
* @apiParam (POST) {String} [sys=""] 用户的操作系统信息.

*
* @apiSuccess (ResponseJSON) {Integer} status 接口响应状态（0-失败,1-成功,2-需要弹出提示框,提示desc内容）.
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
	$post_userid = '0';
	$post_contact = '';
	$post_feedback = '';
	$post_sys = '';
	
	//获取Header
	//模拟请求采用的是request payload 发上来的参数
	$request_body = file_get_contents('php://input');
	$data = json_decode($request_body);
	if($data && count($data)>0 && (!isset($_POST) || count($_POST)==0) && isset($data->feedback) && strlen($data->feedback)>0){
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
		
		if(isset($data->contact)){
			$post_contact = htmlspecialchars(addslashes($data->contact));
		}
		
		$post_feedback = htmlspecialchars(addslashes(trim($data->feedback)));
		
		if(isset($data->sys)){
			$post_sys = htmlspecialchars(addslashes($data->sys));
		}
		
	}else if(
		isset($_POST['feedback']) && strlen($_POST['feedback'])>0
		){
		//获取参数
		if(isset($_POST['pk']) && strlen($_POST['pk'])>0){$post_pk = htmlspecialchars(addslashes($_POST['pk']));}
		if(isset($_POST['v'])){$post_v = htmlspecialchars(addslashes($_POST['v']));}
		if(isset($_POST['userid'])){$post_userid = userIdKeyDecode(htmlspecialchars(addslashes($_POST['userid'])));}
		if(isset($_POST['contact'])){$post_contact = htmlspecialchars(addslashes($_POST['contact']));}
		$post_feedback = htmlspecialchars(addslashes(trim($_POST['feedback'])));
		if(isset($_POST['sys'])){$post_sys = htmlspecialchars(addslashes($_POST['sys']));}
		
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
	
	//查询用户pcmid
	$pcm_id = 0;
	if(strlen($post_pk)>0){
		$query = 'SELECT * FROM `pcmaker_user` WHERE `pc_key`=\''.$post_pk.'\';';
		$result = mysqli_query($connection,$query);
		if($result){
			if(mysqli_num_rows($result)>0){
				$record = mysqli_fetch_assoc($result);	
				$pcm_id = $record['id'];
			}else{
				//没有 就记录入
				$query = 'INSERT INTO pcmaker_user(pc_key,sys,active_times,add_time,update_time) VALUES ('.
						 '\''.$post_pk.'\','.
						 '\''.$post_sys.'\','.
						 '1,'.
						 'now(),now()'.
				');';
				mysqli_query($connection, $query);	
				//在查询
				$query = 'SELECT * FROM `pcmaker_user` WHERE `pc_key`=\''.$post_pk.'\';';
				$result = mysqli_query($connection,$query);
				if($result){
					if(mysqli_num_rows($result)>0){
						$record = mysqli_fetch_assoc($result);	
						$pcm_id = $record['id'];
					}else{
						//还没有ERROR
						
					}
				}
			}
		}
	}
	
	$uip = getClientIP();
	
	
	//记录入数据库
	$query = 'INSERT INTO pcmaker_feedback(user_id,pcm_id,contact,feedback,sys,ver,ip,add_time) VALUES ('.
						 $post_userid.','.
						 $pcm_id.','.
						 '\''.$post_contact.'\','.
						 '\''.$post_feedback.'\','.
						 '\''.$post_sys.'\','.
						 ''.$post_v.','.
						 '\''.$uip.'\','.
						 'now()'.
	');';
	mysqli_query($connection, $query);
	
	//$json['debug'] = $query;
	
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