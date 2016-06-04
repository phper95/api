<?php
/**
* @api {post} /gmspanel/interface/zh-cn/3.1/PCM_W_Grade.php 为作品评分
* @apiPermission yongge
* @apiVersion 0.1.0
* @apiName Grade
* @apiGroup Work
* @apiSampleRequest http://ser3.graphmovie.com/gmspanel/interface/zh-cn/3.1/PCM_W_Grade.php

* @apiDescription 通过workid对作品评分

* @apiParam (POST) {String} [pk=""] 用户PC的机器码(MAC地址)
* @apiParam (POST) {Integer} [v=0] 用户GraphMovieStudios的内部版本号
* @apiParam (POST) {String} userid 用户的userid，必须为登录用户才可访问此接口
* @apiParam (POST) {String} token 用户的登录时返回的token,用于验证用户是否合法
* @apiParam (POST) {String} workid 用户正在创作作品的作品ID，为<code>CreatWithDb</code>和<code>CreatWithNew</code>接口返回的workid字段，32位MD5值
* @apiParam (POST) {Integer} grade 该作品的分数
* @apiParam (POST) {Integer} sort 该作品的状态，目前可针对已上线作品和官方收录作品进行评分，已上线作品为2，官方收录为3
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
*       "desc": "",
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

*     TokenTimeOut:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "TokenTimeOut",
*       "debug": "",
*       "desc": "会话超时,请重新登录"
*     }
*
*     {
*       "status": 3,
*       "usetime": 0.0024,
*       "error": "InsertError",
*       "debug": "",
*       "desc": "评分失败，请稍后再试"
*     }
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
	if($data && count($data)>0 && (!isset($_POST) || count($_POST)==0) && isset($data->userid) && strlen($data->userid)>0 && isset($data->token) && strlen($data->token)>0 && isset($data->workid) && strlen($data->workid)>0 && isset($data->grade) && strlen($data->grade)>0 && isset($data->sort) && strlen($data->sort)>0){
		//curl提交的参数
		if(isset($data->pk)){
			$post_pk = htmlspecialchars(addslashes($data->pk));
		}
		
		if(isset($data->v)){
			$post_v = htmlspecialchars(addslashes($data->v));
		}
		

			$post_userid = userIdKeyDecode(htmlspecialchars(addslashes($data->userid)));
			$post_token = htmlspecialchars(addslashes($data->token));
			$post_workid = htmlspecialchars(addslashes($data->workid));
			$post_sort = htmlspecialchars(addslashes($data->sort));
	}else if(
		isset($_POST['userid']) && strlen($_POST['userid'])>0 &&
		isset($_POST['token']) && strlen($_POST['token'])>0 &&
		isset($_POST['workid']) && strlen($_POST['workid'])>0 &&
		isset($_POST['grade']) && strlen($_POST['grade'])>0 &&
		isset($_POST['sort']) && strlen($_POST['sort'])>0
		){
		//获取参数
		if(isset($_POST['pk']) && strlen($_POST['pk'])>0){$post_pk = htmlspecialchars(addslashes($_POST['pk']));}
		if(isset($_POST['v'])){$post_v = htmlspecialchars(addslashes($_POST['v']));}
		$post_userid = userIdKeyDecode(htmlspecialchars(addslashes($_POST['userid'])));
		$post_token = htmlspecialchars(addslashes($_POST['token']));
		$post_workid = htmlspecialchars(addslashes($_POST['workid']));
		$post_grade = htmlspecialchars(addslashes($_POST['grade']));
		$post_sort = htmlspecialchars(addslashes($_POST['sort']));

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


	//已上线
	if($post_sort==2){
		//$query ='UPDATE　`pcmaker_work` SET `score`='.$post_grade.'WHERE `work_key`='.$post_workid;
		$result = mysqli_query($connection,$query);

	}

	//官方收录
	if($post_sort==3){
		$cquery = 'SELECT `id` FROM `movie` WHERE `work_key`='.$post_workid;
		$result = mysqli_query($connection,$query);
		if($result){
			if(mysqli_num_rows($result)==0){
				//没找到
				//关闭连接
				if($connection)mysqli_close($connection);
				$json['status']= 2;
				$json['usetime'] = endtime($start_time);
				$json['error'] = 'NoThisWork';
				$json['desc'] = "无此图解";
				$json_code = json_encode($json);
				echo $json_code;
				die();
			}
		}else{
			//关闭连接
			if($connection)mysqli_close($connection);
			$json['status']= 2;
			$json['usetime'] = endtime($start_time);
			$json['error'] = 'NoThisWork';
			$json['desc'] = "无此图解";
			$json_code = json_encode($json);
			echo $json_code;
			die();
		}
		$movie = mysqli_fetch_assoc($result,$connection);
		//图解评分类别：1-图解的总得分；2-图的综合得分；3-解的综合得分；4-图片的清晰度；5-截图的筛选和排序；6-图解的选题，原创作品的创新，情节的安排等；7-作者解说的文字功底；8-作者对影片的解读深度
		$score_type=1;
		$query ='INSERT INTO　`movie_v_gmscore_record`(`user_id`,`movie_id`,`score_type`,`score_value`,`add_time`) VALUES('.$post_userid.','.$movie['id'].','.$score_type.','.$post_grade.',now())';
		$result = mysqli_query($connection,$query);
		if(!$result){
			$json['status']=3;
			$json['usetime'] = endtime($start_time);
			$json['error'] = 'InsertError';
			$json['desc'] = '评分失败，请稍后再试';
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