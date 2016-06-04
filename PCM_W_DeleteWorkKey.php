<?php
/**
* @api {post} /gmspanel/interface/zh-cn/3.1/PCM_W_DeleteWorkKey.php 删除未完成的作品
* @apiPermission yongge
* @apiVersion 0.1.0
* @apiName DeleteWorkKey
* @apiGroup Work
* @apiSampleRequest http://ser3.graphmovie.com/gmspanel/interface/zh-cn/3.1/PCM_W_DeleteWorkKey.php

* @apiDescription 用户放弃还未上传图片的作品

* @apiParam (POST) {String} [pk=""] 用户PC的机器码(MAC地址)
* @apiParam (POST) {Integer} [v=0] 用户GraphMovieStudios的内部版本号
* @apiParam (POST) {String} userid 用户的userid，必须为登录用户才可访问此接口
* @apiParam (POST) {String} token 用户的登录时返回的token，用于验证用户是否合法
* @apiParam (POST) {String} workid 需要删除所有图片文件的作品的workid

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
* @apiError TokenTimeOut 用户会话超时或非法.
* @apiError ErrorWorkId 不存在该作品.
* @apiError WorkIdWrongUser 该用户和该WorkId是不匹配的
* @apiError WorkCantDel 该作品已上线无法删除
* @apiError ErrorRequest 非法请求

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

*     ErrorWorkId:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "ErrorWorkId",
*       "debug": "",
*       "desc": "作品校验失败"
*     }

*     WorkIdWrongUser:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "WorkIdWrongUser",
*       "debug": "",
*       "desc": "该作品为[XXX]注册作品，无法同步"
*     }

*     WorkCantDel:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "WorkCantDel",
*       "debug": "",
*       "desc": "作品已上线无法删除"
*     }

*     ErrorRequest:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "ErrorRequest",
*       "debug": "",
*       "desc": "非法请求"
*     }
 *
 *
 * DeleteFaild：

 *     {
 *       "status": 2,
 *       "usetime": 0.0024,
 *       "error": "deleteFaild",
 *       "debug": "",
 *       "desc": "作品删除失败"
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
	$post_workid = '';
	$post_uploadkey = 0;
	
	//获取Header
	//模拟请求采用的是request payload 发上来的参数
	$request_body = file_get_contents('php://input');
	$data = json_decode($request_body);
	if($data && count($data)>0 && (!isset($_POST) || count($_POST)==0) && isset($data->userid) && strlen($data->userid)>0 && isset($data->workid) && strlen($data->workid)>0){
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
		
		if(isset($data->workid)){
			$post_workid = htmlspecialchars(addslashes($data->workid));
		}
		
		//uploadkey
		
		if(isset($data->uploadkey)){
			$post_uploadkey = htmlspecialchars(addslashes($data->uploadkey));
		}
		
	}else if(
		isset($_POST['userid']) && strlen($_POST['userid'])>0 && 
		isset($_POST['workid']) && strlen($_POST['workid'])>0
		){
		//获取参数
		if(isset($_POST['pk']) && strlen($_POST['pk'])>0){$post_pk = htmlspecialchars(addslashes($_POST['pk']));}
		if(isset($_POST['v'])){$post_v = htmlspecialchars(addslashes($_POST['v']));}
		if(isset($_POST['userid'])){$post_userid = userIdKeyDecode(htmlspecialchars(addslashes($_POST['userid'])));}
		if(isset($_POST['token'])){$post_token = htmlspecialchars(addslashes($_POST['token']));}
		if(isset($_POST['workid'])){$post_workid = htmlspecialchars(addslashes($_POST['workid']));}
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
	//查询该userid和workid的配对是否存在
	//查询该workid是否存在
	$query = 'SELECT * FROM `pcmaker_work` WHERE `work_key`=\''.$post_workid.'\';';
	$result = mysqli_query($connection,$query);
	if($result){
		if(mysqli_num_rows($result)>0){
			//找到 
			$work = mysqli_fetch_assoc($result);
			
			//检查状态是否是已上线作品
			if($work['state']>=2){
				//作品已上线
				//关闭连接
				if($connection)mysqli_close($connection);
				$json['status']= 2;
				$json['usetime'] = endtime($start_time);
				$json['error'] = 'WorkCantModify';
				$json['desc'] = "作品已上线无法修改";
				$json_code = json_encode($json);
				echo $json_code;
				die();
			}
			
			//检查该作品是否是该用户名下的作品
			if($work['user_id']!=$post_userid){
				//作品并不是该用户的
				//那是谁的 查一下
				$query = 'SELECT * FROM `client_user` WHERE `id`='.$work['user_id'].';';
				$user_result = mysqli_query($connection,$query);
				if($user_result && mysqli_num_rows($user_result)>0){
					$user = mysqli_fetch_assoc($user_result);
					$json['desc'] = "该作品为[".$user['name']."]注册作品，无法删除";
				}else{
					$json['desc'] = "该作品不是当前账户注册作品，无法删除";
				}
				
				//关闭连接
				if($connection)mysqli_close($connection);
				$json['status']= 2;
				$json['usetime'] = endtime($start_time);
				$json['error'] = 'WorkIdWrongUser';
				$json_code = json_encode($json);
				echo $json_code;
				die();	
			}else{
				//是该用户的
					//可以开始删除了
				$query = 'DELETE FROM `pcmaker_work` WHERE `work_key`=\''.$post_workid.'\';';
				$result = mysqli_query($connection,$query);
				if(!$result){
					//关闭连接
					if($connection)mysqli_close($connection);
					$json['status']= 2;
					$json['usetime'] = endtime($start_time);
					$json['error'] = 'DeleteFaild';
					$json['desc'] = "作品删除失败";
					$json_code = json_encode($json);
					echo $json_code;
					die();
				}

			}
		}else{
			//没找到
			//关闭连接
			if($connection)mysqli_close($connection);
			$json['status']= 2;
			$json['usetime'] = endtime($start_time);
			$json['error'] = 'ErrorWorkId';
			$json['desc'] = "作品校验失败";
			$json_code = json_encode($json);
			echo $json_code;
			die();	
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