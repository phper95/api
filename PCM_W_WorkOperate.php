<?php
/**
* @api {post} /gmspanel/interface/zh-cn/3.1/PCM_W_WorkOperate.php 作者对作品上下线操作
* @apiPermission pxseven
* @apiVersion 0.1.0
* @apiName WorkOperate
* @apiGroup Work
* @apiSampleRequest http://ser3.graphmovie.com/gmspanel/interface/zh-cn/3.1/PCM_W_WorkOperate.php

* @apiDescription 通过workid对作品进行上下线操作，必须为用户自己的作品

* @apiParam (POST) {String} [pk=""] 用户PC的机器码(MAC地址)
* @apiParam (POST) {Integer} [v=0] 用户GraphMovieStudios的内部版本号
* @apiParam (POST) {String} userid 用户的userid，必须为登录用户才可访问此接口
* @apiParam (POST) {String} token 用户的登录时返回的token,用于验证用户是否合法
* @apiParam (POST) {String} workid 用户正在创作作品的作品ID，为<code>CreatWithDb</code>和<code>CreatWithNew</code>接口返回的workid字段，32位MD5值
* @apiParam (POST) {Integer} operate 作品的操作码0-下线；1-上线

* @apiSuccess (ResponseJSON) {Integer} status 接口响应状态（0-失败,1-成功,2-需要弹出提示框,提示desc内容）.
* @apiSuccess (ResponseJSON) {Float} usetime 接口响应时间,调试用.
* @apiSuccess (ResponseJSON) {String} error 接口响应出错时的错误描述.
* @apiSuccess (ResponseJSON) {String} debug 接口响应出错时的过程描述,调试用.
* @apiSuccess (ResponseJSON) {String} desc status=2时需要弹窗提示此内容.
* @apiSuccess (ResponseJSON) {String} workid 操作成功返回该作品workid.

*
* @apiSuccessExample Success-Response[提交成功]:

*     {
*       "status": 1,
*       "usetime": 0.0024,
*       "error": "",
*       "debug": "",
*       "desc": "",
*       "workid": "f787307554daaab7f13ad7e17843117b",
*     }

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

*    WorkIdWrongUser:

*     {
*       "status": 3,
*       "usetime": 0.0024,
*       "error": "WorkIdWrongUser",
*       "debug": "",
*       "desc": "不能操作别人的作品哦"
*     }


*   ErrorWorkId:

*  	{
*       "status": 4,
*       "usetime": 0.0024,
*       "error": "ErrorWorkId",
*       "debug": "",
*       "desc": "作品校验失败"
*  	}
*
*

*   WorkCantModify:

* 	{
*       "status": 5,
*       "usetime": 0.0024,
*       "error": "WorkCantModify",
*       "debug": "",
*       "desc": "作品已收录，无法修改"
*  	}
*
*
*

*   UpdateError:

* 	{
*       "status": 6,
*       "usetime": 0.0024,
*       "error": "UpdateError",
*       "debug": "",
*       "desc": "操作失败,请稍后重试..."
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
	if($data && count($data)>0 && (!isset($_POST) || count($_POST)==0) && isset($data->userid) && strlen($data->userid)>0 && isset($data->token) && strlen($data->token)>0 && isset($data->workid) && strlen($data->workid)>0 && isset($data->operate) && strlen($data->operate)>0){
		//curl提交的参数
		if(isset($data->pk)){
			$post_pk = htmlspecialchars(addslashes($data->pk));
		}
		
		if(isset($data->v)){
			$post_v = htmlspecialchars(addslashes($data->v));
		}
		

			$post_userid = htmlspecialchars(userIdKeyDecode(addslashes($data->userid)));
			$post_token = htmlspecialchars(addslashes($data->token));
			$post_workid = htmlspecialchars(addslashes($data->workid));
			$post_operate = htmlspecialchars(addslashes($data->operate));
	}else if(
		isset($_POST['userid']) && strlen($_POST['userid'])>0 &&
		isset($_POST['token']) && strlen($_POST['token'])>0 &&
		isset($_POST['workid']) && strlen($_POST['workid'])>0 &&
		isset($_POST['operate']) && strlen($_POST['operate'])>0
		){
		//获取参数
		if(isset($_POST['pk']) && strlen($_POST['pk'])>0){$post_pk = htmlspecialchars(addslashes($_POST['pk']));}
		if(isset($_POST['v'])){$post_v = htmlspecialchars(addslashes($_POST['v']));}
		$post_userid = htmlspecialchars(userIdKeyDecode(addslashes($_POST['userid'])));
		$post_token = htmlspecialchars(addslashes($_POST['token']));
		$post_workid = htmlspecialchars(addslashes($_POST['workid']));
		$post_operate = htmlspecialchars(addslashes($_POST['operate']));

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
	$query = 'SELECT * FROM `pcmaker_request_token` WHERE `token`=\''.$post_token.'\' AND `userid`=\''.$post_userid.'\' AND `add_time`>\''.$okdate.'\';';
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
//$json['query'] =$query;
$result = mysqli_query($connection,$query);
if($result){
	if(mysqli_num_rows($result)>0){
		//找到
		$work = mysqli_fetch_assoc($result);

		//检查状态是否是已上线作品

		if($work['state'] != 3) {
			if ($work['user_id'] == $post_userid) {
				//校验通过可以修改上线状态了
				//0下线；1上线
				if($post_operate==1){
					$operate = 2;
				}else{
					$operate = -1;
				}
				if($work['state'] != $operate){
					if($operate === -1){
						//`offline_type`=1 用户手动下线
						$query = 'UPDATE `pcmaker_work` SET `state`='.$operate.',`offline_type`=1,`offline_time`=now() WHERE `work_key`=\''.$post_workid.'\';';
					}else{
						$query = 'UPDATE `pcmaker_work` SET `state`='.$operate.',`offline_type`=0,`submit_time`=now() WHERE `work_key`=\''.$post_workid.'\';';
					}
					$json['query'] = $query;
					$res = mysqli_query($connection,$query);
					if(!$res){
						//服务器问题
						$json['status']= 6;
						$json['usetime'] = endtime($start_time);
						$json['error'] = 'UpdateError';
						$json['desc'] = "操作失败,请稍后重试...";
						$json_code = json_encode($json);
						echo $json_code;
						die();
					}else{
						$json['workid'] = $post_workid;
					}
				}


			} else {
				//关闭连接
				if($connection)mysqli_close($connection);
				$json['status']= 3;
				$json['usetime'] = endtime($start_time);
				$json['error'] = 'WorkIdWrongUser';
				$json['desc'] = '不能操作别人的作品哦';
				$json_code = json_encode($json);
				echo $json_code;
				die();
			}
		}else{
			//关闭连接
			if($connection)mysqli_close($connection);
			$json['status']= 5;
			$json['usetime'] = endtime($start_time);
			$json['error'] = 'WorkCantModify';
			$json['desc'] = "作品已收录无法修改";
			$json_code = json_encode($json);
			echo $json_code;
			die();
		}
	}else{
		//没找到
		//关闭连接
		if($connection)mysqli_close($connection);
		$json['status']= 4;
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