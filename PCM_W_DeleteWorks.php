<?php
/**
 * @api {post} /gmspanel/interface/zh-cn/3.1/PCM_W_DeleteWorks.php [非客户端]删除测试数据
 * @apiPermission yongge
 * @apiVersion 0.1.0
 * @apiName DeleteWorks
 * @apiGroup Work
 * @apiSampleRequest http://ser3.graphmovie.com/gmspanel/interface/zh-cn/3.1/PCM_W_DeleteWorks.php

 * @apiDescription [非客户端]删除测试数据

 * @apiParam (POST) {String} userid 用户的userid，必须为登录用户才可访问此接口
 * @apiParam (POST) {String} token 用户的登录时返回的token，用于验证用户是否合法

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

	exit('no premission');
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
	if($data && count($data)>0 && (!isset($_POST) || count($_POST)==0) && isset($data->userid) && strlen($data->userid)>0 && isset($data->token) && strlen($data->token)>0){

			$post_userid = userIdKeyDecode(htmlspecialchars(addslashes($data->userid)));
			$post_token = htmlspecialchars(addslashes($data->token));

	}else if(
		isset($_POST['userid']) && strlen($_POST['userid'])>0 &&
		isset($_POST['token']) && strlen($_POST['token'])>0

		){
		//获取参数

		$post_userid = htmlspecialchars(addslashes(userIdKeyDecode($_POST['userid'])));
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


//$query='UPDATE `pcmaker_work` SET state=-2 WHERE id>=322;';
//$result = mysqli_query($connection,$query);

//exit('no premission');
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




				//统计操作个数
				$pcmaker_upload_map_succe_count=0;
				$pcmaker_upload_map_error_count=0;
				$pcmaker_work_img_succe=0;
				$pcmaker_work_img_error=0;
				//查询是否有此uploadkey
				$query = 'SELECT pcmaker_upload_map.work_key,pcmaker_upload_map.upload_key FROM `pcmaker_work`,`pcmaker_upload_map` WHERE pcmaker_work.user_id ='.$post_userid.'  and pcmaker_work.work_key=pcmaker_upload_map.work_key AND pcmaker_work.state <>3;';
				$result = mysqli_query($connection,$query);
				if($result && mysqli_num_rows($result)>0){
					//查找到了
					//可以开始删除了
					//post imgs4 接口 PCM_W_ImageS4DelWorkIdUploadKey
					/*
					svk
					workid
					uploadkey
					*/


					$i=0;
					while($i<mysqli_num_rows($result)){

						//找到
						$work = mysqli_fetch_assoc($result);
						//var_dump($work);exit;
						$svk = md5($work["work_key"].'graph'.$work["upload_key"].'movie'.'from ser3.graphmovie.com');
						$post_string = 'svk='.$svk.'&workid='.$work["work_key"].'&uploadkey='.$work["upload_key"];
						//$response_json = request_by_curl('http://imgs4.graphmovie.com/gms_works/interface/PCM_W_ImageS4DelWorkIdUploadKey.php',$post_string);

						//$json_struct = @json_decode($response_json);
						//$json_struct && isset($json_struct->status)
						if(1){
							//$json_struct->status==1
							if(1){
								//删除成功
								//更新pcmaker_upload_map
								$query = 'DELETE FROM `pcmaker_upload_map` WHERE `work_key`=\''.$work["work_key"].'\' AND `upload_key`=\''.$work["upload_key"].'\''.';';

								$result1 = mysqli_query($connection,$query);
								if(!$result1){
									$pcmaker_upload_map_error_count++;
								}else{
									$pcmaker_upload_map_succe_count++;
								}
								//删除所有图片中的图片记录
								$query = 'DELETE FROM `pcmaker_work_img` WHERE `work_key`=\''.$work["work_key"].'\' AND `upload_key`=\''.$work["upload_key"].'\'  AND `user_id`='.$post_userid.';';
								$result2 = mysqli_query($connection,$query);
								if($result2){
									$pcmaker_work_img_succe++;
								}else{
									$pcmaker_work_img_error++;
								}

							}else{
								//删除失败
								if(1){//$json_struct->error=='ErrorRequest'
									//非法请求
									if($connection)mysqli_close($connection);
									$json['status']= 2;
									$json['usetime'] = endtime($start_time);
									$json['error'] = 'ErrorRequest';
									$json['desc'] = "非法请求";
									$json_code = json_encode($json);
									echo $json_code;
									//die();
								}
							}
							//$response_json = json_encode($json_struct);
						}else{
							//作品上线不能被修改
							/*if($connection)mysqli_close($connection);
							$json['status']= 2;
							$json['usetime'] = endtime($start_time);
							$json['error'] = 'WorkCantModify';
							$json['desc'] = "作品已上线无法修改";
							$json_code = json_encode($json);
							echo $json_code;
							die();*/
						}

						$i++;
					}


				}

			//删除作品数据
			$query = 'DELETE FROM `pcmaker_work` WHERE `user_id`='.$post_userid.' AND state<>3;';
			$result3 = mysqli_query($connection,$query);
			if($result3){
				$json['pcmaker_work_count']=mysqli_affected_rows($connection);
			}



$json['pcmaker_upload_map_succe_count']=$pcmaker_upload_map_succe_count;
$json['pcmaker_upload_map_error_count']=$pcmaker_upload_map_error_count;
$json['pcmaker_work_img_succe']=$pcmaker_work_img_succe;
$json['pcmaker_work_img_error']=$pcmaker_work_img_error;


	
	
	
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