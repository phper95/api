<?php
/**
* @api {post} /gmspanel/interface/zh-cn/3.1/PCM_W_SetUpUpload1.php 准备上传（避免与内测冲突）
* @apiPermission pxseven
* @apiVersion 0.1.0
* @apiName SetUpUpload1
* @apiGroup Work
* @apiSampleRequest http://ser3.graphmovie.com/gmspanel/interface/zh-cn/3.1/PCM_W_SetUpUpload1.php

* @apiDescription 获取上传标识uploadkey和身份签名cer准备上传,若本地客户端没有workid则补全workid

* @apiParam (POST) {String} [pk=""] 用户PC的机器码(MAC地址)
* @apiParam (POST) {Integer} [v=0] 用户GraphMovieStudios的内部版本号
* @apiParam (POST) {String} userid 用户的userid，必须为登录用户才可访问此接口
* @apiParam (POST) {String} token 用户的登录时返回的token，用于验证用户是否合法
* @apiParam (POST) {String} [workid=""] 用户正在创作作品的作品ID
* @apiParam (POST) {String} [dburl=""] 用户在关联中输入的豆瓣URL,接口会验证URL是否合法,原创作品传空字符串即可
* @apiParam (POST) {String} [uploadkey=""] 用户作品对应的版本标识，一个work可以对应多个版本，每个版本有唯一一个uploadkey；第一次上传时没有uploadkey，发送空字符串即可；续传时有uploadkey，则发送uploadkey上来。
* @apiParam (POST) {String} [movie_name=""] 影片信息-作品名称
* @apiParam (POST) {String} [movie_direct=""] 影片信息-导演名
* @apiParam (POST) {String} [movie_actor=""] 影片信息-主要演员名
* @apiParam (POST) {String} [movie_score=""] 影片信息-影片评分
* @apiParam (POST) {String} [movie_time=""] 影片信息-上映年份
* @apiParam (POST) {String} [movie_countory=""] 影片信息-上映地区
* @apiParam (POST) {String} [subtitle=""] 影片信息-副标题
* @apiParam (POST) {String} [movie_bza=""] 影片信息-编者按
* @apiParam (POST) {String} [movie_intro=""] 影片信息-剧情简介
* @apiParam (POST) {String} [movie_type=""] 影片信息-影片类型 恐怖惊悚等
* @apiParam (POST) {String} [movie_istv=""] 影片信息-剧别 0-电影 1-单季剧 2-多季剧
* @apiParam (POST) {String} [bpic_md5=""] 影片信息-640x460封面的图片的MD5值
* @apiParam (POST) {String} [spic_md5=""] 影片信息-640x230封面的图片的MD5值
* @apiParam (POST) {String} [fpic_md5=""] 影片信息-960x540封面的图片的MD5值
* @apiParam (POST) {Integer} [movie_imgcount=0] 影片的图解页数

*
* @apiSuccess (ResponseJSON) {Integer} status 接口响应状态（0-失败,1-成功,2-需要弹出提示框,提示desc内容）.
* @apiSuccess (ResponseJSON) {Float} usetime 接口响应时间,调试用.
* @apiSuccess (ResponseJSON) {String} error 接口响应出错时的错误描述.
* @apiSuccess (ResponseJSON) {String} debug 接口响应出错时的过程描述,调试用.
* @apiSuccess (ResponseJSON) {String} desc status=2时需要弹窗提示此内容.
* @apiSuccess (ResponseJSON) {String} workid 如果workid为""或者workid非法(workid与userid不对应),则会生成一个新的workid返回,客户端验证如果workid不为空字符串,就覆盖本地workid.
* @apiSuccess (ResponseJSON) {String} uploadkey 如果uploadkey为""或者uploadkey非法(workid与uploadkey不对应),则会生成一个新的uploadkey返回,客户端验证如果uploadkey不为空字符串,就覆盖本地uploadkey.
* @apiSuccess (ResponseJSON) {String} regcer 本次上传的通信签名，用于通过imgs4服务器的身份验证，本次记录在内存中，后续上传图片时带上即可.
* @apiSuccess (ResponseJSON) {String} error_uploadkey 如果请求参数uploadkey与服务器未完成的上传的版本uploadkey不一致（此情况出现在用户离线时选择编辑了未完成上传的作品），则返回<code>ErrorUploadkey</code>错误或<code>DnfUpload</code>，此时返回值中将含有此参数，用于请求删除未完成的版本。


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
* @apiError DnfUpload 存在未完成的上传需要清除(服务器记录有一个未完成上传的版本，可客户端发上来的uploadkey为空).
* @apiError ErrorUploadkey 版本错误需要重新上传(服务器记录有一个未完成上传的版本，可客户端发上来的uploadkey并不匹配).
* @apiError WorkCantModify 该作品已上线无法再次上传

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

*     DnfUpload:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "DnfUpload",
*       "debug": "",
*       "desc": "服务器上有一个 2016-01-18 18:53:02 上传的未完成版本，是否删除后重新上传？",
*       "error_uploadkey": "1698bc5fd560c286e3c45f5df44508bd"
*     }

*     ErrorUploadkey:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "ErrorUploadkey",
*       "debug": "",
*       "desc": "服务器上有一个 2016-01-18 18:53:02 上传的未完成版本，是否删除后重新上传？",
*       "error_uploadkey": "1698bc5fd560c286e3c45f5df44508bd"
*     }

*     WorkCantModify:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "WorkCantModify",
*       "debug": "",
*       "desc": "作品已上线无法再次上传"
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
	$post_dburl = '';
	$post_uploadkey = '';
	$post_movie_name = '';
	$post_movie_direct = '';
	$post_movie_actor = '';
	$post_movie_score = '';
	$post_movie_time = '';
	$post_movie_countory = '';
	$post_subtitle = '';
	$post_movie_bza = '';
	$post_movie_intro = '';
	$post_movie_type = '';
	$post_movie_istv = 0;
	$post_bpic_md5 = '';
	$post_spic_md5 = '';
	$post_fpic_md5 = '';
	$post_movie_imgcount = 0;
	
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
		
		if(isset($data->workid)){
			$post_workid = htmlspecialchars(addslashes($data->workid));
		}
		
		if(isset($data->dburl)){
			$post_dburl = htmlspecialchars(addslashes($data->dburl));
		}
		
		if(isset($data->uploadkey)){
			$post_uploadkey = htmlspecialchars(addslashes($data->uploadkey));
		}
		
		if(isset($data->movie_name)){
			$post_movie_name = htmlspecialchars(addslashes($data->movie_name));
		}
		
		if(isset($data->movie_direct)){
			$post_movie_direct = htmlspecialchars(addslashes($data->movie_direct));
		}
		
		if(isset($data->movie_actor)){
			$post_movie_actor = htmlspecialchars(addslashes($data->movie_actor));
		}
		
		if(isset($data->movie_score)){
			$post_movie_score = htmlspecialchars(addslashes($data->movie_score));
		}
		
		if(isset($data->movie_time)){
			$post_movie_time = htmlspecialchars(addslashes($data->movie_time));
		}
		
		if(isset($data->movie_countory)){
			$post_movie_countory = htmlspecialchars(addslashes($data->movie_countory));
		}
		
		if(isset($data->subtitle)){
			$post_subtitle = htmlspecialchars(addslashes($data->subtitle));
		}
		
		if(isset($data->movie_bza)){
			$post_movie_bza = htmlspecialchars(addslashes($data->movie_bza));
		}
		
		if(isset($data->movie_intro)){
			$post_movie_intro = htmlspecialchars(addslashes($data->movie_intro));
		}
		
		if(isset($data->movie_type)){
			$post_movie_type = htmlspecialchars(addslashes($data->movie_type));
		}
		
		if(isset($data->movie_istv)){
			$post_movie_istv = htmlspecialchars(addslashes($data->movie_istv));
		}
		
		if(isset($data->bpic_md5)){
			$post_bpic_md5 = htmlspecialchars(addslashes($data->bpic_md5));
		}
		
		if(isset($data->spic_md5)){
			$post_spic_md5 = htmlspecialchars(addslashes($data->spic_md5));
		}
		
		if(isset($data->fpic_md5)){
			$post_fpic_md5 = htmlspecialchars(addslashes($data->fpic_md5));
		}
		
		if(isset($data->movie_imgcount)){
			$post_movie_imgcount = htmlspecialchars(addslashes($data->movie_imgcount));
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
		if(isset($_POST['workid'])){$post_workid = htmlspecialchars(addslashes($_POST['workid']));}
		if(isset($_POST['dburl'])){$post_dburl = htmlspecialchars(addslashes($_POST['dburl']));}
		
		if(isset($_POST['uploadkey'])){$post_uploadkey = htmlspecialchars(addslashes($_POST['uploadkey']));}
		if(isset($_POST['movie_name'])){$post_movie_name = htmlspecialchars(addslashes($_POST['movie_name']));}
		if(isset($_POST['movie_direct'])){$post_movie_direct = htmlspecialchars(addslashes($_POST['movie_direct']));}
		if(isset($_POST['movie_actor'])){$post_movie_actor = htmlspecialchars(addslashes($_POST['movie_actor']));}
		if(isset($_POST['movie_score'])){$post_movie_score = htmlspecialchars(addslashes($_POST['movie_score']));}
		if(isset($_POST['movie_time'])){$post_movie_time = htmlspecialchars(addslashes($_POST['movie_time']));}
		if(isset($_POST['movie_countory'])){$post_movie_countory = htmlspecialchars(addslashes($_POST['movie_countory']));}
		if(isset($_POST['subtitle'])){$post_subtitle = htmlspecialchars(addslashes($_POST['subtitle']));}
		if(isset($_POST['movie_bza'])){$post_movie_bza = htmlspecialchars(addslashes($_POST['movie_bza']));}
		if(isset($_POST['movie_intro'])){$post_movie_intro = htmlspecialchars(addslashes($_POST['movie_intro']));}
		if(isset($_POST['movie_type'])){$post_movie_type = htmlspecialchars(addslashes($_POST['movie_type']));}
		if(isset($_POST['movie_istv'])){$post_movie_istv = htmlspecialchars(addslashes($_POST['movie_istv']));}
		if(isset($_POST['bpic_md5'])){$post_bpic_md5 = htmlspecialchars(addslashes($_POST['bpic_md5']));}
		if(isset($_POST['spic_md5'])){$post_spic_md5 = htmlspecialchars(addslashes($_POST['spic_md5']));}
		if(isset($_POST['fpic_md5'])){$post_fpic_md5 = htmlspecialchars(addslashes($_POST['fpic_md5']));}
		if(isset($_POST['movie_imgcount'])){$post_movie_imgcount = htmlspecialchars(addslashes($_POST['movie_imgcount']));}
		
		
	}else{
		//缺少参数
		$json['status']= 0;
		$json['usetime'] = endtime($start_time);
		$json['error'] = 'PostError';
		$json_code = json_encode($json);
		echo $json_code;
		die();
	}
	
	//初始化所有返回key
	$json['workid']= '';
	$json['uploadkey']= '';
	$json['regcer']= '';
	$json['error_uploadkey']= '';
	
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
	//只有作品已经上线了才不能上传
	$need_creat_new_workkey = false;
	
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
				$need_creat_new_workkey = true;
				//$need_creat_new_workkey = false;
			}else{
				//是该用户的
				$need_creat_new_workkey = false;	
			}
			
			
			
		}else{
			//没找到
			$need_creat_new_workkey = true;
		}
	}
	
	
	
	//var_dump($need_creat_new_workkey);
	//如果需要生成新的workid就post到接口PCM_W_CreatWithDb 或 PCM_W_CreatWithNew 生成新的id
	if($need_creat_new_workkey){
		$is_newwork = false; 
		if(strlen($post_dburl)){
			//post PCM_W_CreatWithDb
			/*
			pk
			v
			userid 
			token 
			dburl 
			*/
			$post_string = 'pk='.$post_pk.'&v='.$post_v.'&userid='.userIdKeyEncode($post_userid).'&token='.$post_token.'&dburl='.$post_dburl;
			$response_json = request_by_curl('http://ser3.graphmovie.com/gmspanel/interface/zh-cn/3.1/PCM_W_CreatWithDbForInput.php',$post_string);
			
			$json_struct = @json_decode($response_json);
			if($json_struct && isset($json_struct->status)){
				if($json_struct->status==1){
					$is_newwork = false;
					
					//有了新的workid
					$post_workid = $json_struct->workid;
						
				}else{
					//如果链接出错就是原创作品
					$is_newwork = true;		
				}
			}else{
				$is_newwork = true;		
			}
		}else{
			$is_newwork = true;	
		}
		
		if($is_newwork){
			//post PCM_W_CreatWithNew
			/*
			pk
			v
			userid 
			token 
			dburl 
			*/
			$post_string = 'pk='.$post_pk.'&v='.$post_v.'&userid='.userIdKeyEncode($post_userid).'&token='.$post_token;
			$response_json = request_by_curl('http://ser3.graphmovie.com/gmspanel/interface/zh-cn/3.1/PCM_W_CreatWithNew1.php',$post_string);
			
			$json_struct = @json_decode($response_json);
			if($json_struct && isset($json_struct->status)){
				if($json_struct->status==1){
					
					//有了新的workid
					$post_workid = $json_struct->workid;
						
				}else if($json_struct->error == 'TESTEND'){
					//20160304 内测结束 
					if($connection)mysqli_close($connection);
					/*------------------------------------------------------------------------------------------------------------------*/
					/*------------------------------------------------------------------------------------------------------------------*/
					/*------------------------------------------------------------------------------------------------------------------*/
					/*$json['status']= 2;
					$json['usetime'] = endtime($start_time);
					$json['error'] = 'TESTEND';
					$json['desc'] = '本轮内测结束，谢谢大家的踊跃参与！未完成的上传作品请导出，使用旧版本制作器导入打包后再邮箱投稿哈~';
					$json_code = json_encode($json);
					echo $json_code;
					die();*/
					/*------------------------------------------------------------------------------------------------------------------*/
					/*------------------------------------------------------------------------------------------------------------------*/
					/*------------------------------------------------------------------------------------------------------------------*/
				
				}else{
					//如果原创作品也生成不了workid
					//那就只能报错返回了
					if($connection)mysqli_close($connection);
					$json['status']= 2;
					$json['usetime'] = endtime($start_time);
					$json['error'] = 'ServerError';
					$json['desc'] = "额服务器开小差了,请稍后重试...";
					$json_code = json_encode($json);
					echo $json_code;
					die();
				}
			}else{
					//20160304 内测结束 
					if($connection)mysqli_close($connection);
					/*------------------------------------------------------------------------------------------------------------------*/
					/*------------------------------------------------------------------------------------------------------------------*/
					/*------------------------------------------------------------------------------------------------------------------*/
					$json['status']= 2;
					$json['usetime'] = endtime($start_time);
					$json['error'] = 'TESTEND';
					$json['desc'] = '本轮内测结束，谢谢大家的踊跃参与！未完成的上传作品请导出，使用旧版本制作器导入打包后再邮箱投稿哈~';
					$json_code = json_encode($json);
					echo $json_code;
					die();
					/*------------------------------------------------------------------------------------------------------------------*/
					/*------------------------------------------------------------------------------------------------------------------*/
					/*------------------------------------------------------------------------------------------------------------------*/
				
				}
				/*
				else{
				//如果原创作品也生成不了workid
				//那就只能报错返回了
				if($connection)mysqli_close($connection);
				$json['status']= 2;
				$json['usetime'] = endtime($start_time);
				$json['error'] = 'ServerError';
				$json['desc'] = "额服务器开小差了,请稍后重试...";
				$json_code = json_encode($json);
				echo $json_code;
				die();
			}
			*/
		}
		
		//生成了新id
		//不需要再验证uploadkey  一定无效
		//直接生成新的就好
		$post_uploadkey = '';
	} 
	
	//这里验证uoloadkey的合法性
	
	$need_creat_uploadkey = false;
	
	//退稿作品uploadKey状态改为3-删除
	if(isset($work['state'])&&$work['state']==-3){
		$query = 'UPDATE `pcmaker_upload_map` SET `state`=3 WHERE `work_key`=\''.$post_workid.'\';';
		$result = mysqli_query($connection,$query);
	}
	
	//验证uploadkey
	//$query = 'SELECT * FROM `pcmaker_upload_map` WHERE `work_key`=\''.$post_workid.'\' AND `state`<>3 ORDER BY `id` DESC LIMIT 1;';
	$query = 'SELECT * FROM `pcmaker_upload_map` WHERE `work_key`=\''.$post_workid.'\' AND `state`<>3 ORDER BY `id` DESC LIMIT 1;';
	$result = mysqli_query($connection,$query);
	if($result && mysqli_num_rows($result)>0){
		//找到了 验证是否是合法的uploadkey
		$record = mysqli_fetch_assoc($result);
		
		if(strlen($post_uploadkey)==0 && strlen($record['upload_key'])>0){
			//发上来的和workid的不匹配
			//非法的uploadkey
			//
			$json['error_uploadkey'] = $record['upload_key'];
			if($connection)mysqli_close($connection);
			$json['status']= 2;
			$json['usetime'] = endtime($start_time);
			$json['error'] = 'DnfUpload';
			$json['desc'] = "服务器上有一个 ".$record['add_time']." 上传的未完成版本，是否删除后重新上传？";
			$json_code = json_encode($json);
			echo $json_code;
			die();
			
		}else if(strlen($post_uploadkey)>0 && $record['upload_key'] == $post_uploadkey){
			//合法的uploadkey 可以继续传
			$need_creat_uploadkey = false;
			
		}else if(strlen($post_uploadkey)>0 && $record['upload_key'] != $post_uploadkey){
			//非法的uploadkey
			//
			$json['error_uploadkey'] = $record['upload_key'];
			if($connection)mysqli_close($connection);
			$json['status']= 2;
			$json['usetime'] = endtime($start_time);
			$json['error'] = 'ErrorUploadkey';
			$json['desc'] = "服务器上有一个 ".$record['add_time']." 上传的未完成版本，是否删除后重新上传？";
			$json_code = json_encode($json);
			echo $json_code;
			die();
		}else{
			//再来就是 record中upload_key为空了 这样就生成一个新的upload_key
			$need_creat_uploadkey = true;
		}
		
	}else{
		//没找到
		//合法 首次上传 可以上传
		$need_creat_uploadkey = true;
	}
	
	
	
	
	if($need_creat_uploadkey){
		//生成一个新的uploadkey
		//生成规则 userid+token+workid+time+rand
		$uploadkey = $post_userid.$post_token.$post_workid.time().rand(10000, 100000);
		$uploadkey = md5($uploadkey);
		
		//记录入表
		$query = 'INSERT INTO pcmaker_upload_map(work_key,upload_key,state,progress,add_time,update_time) VALUES ('.
					 '\''.$post_workid.'\','.
					 '\''.$uploadkey.'\','.
					 '0,0,'.
					 'now(),now()'.
		');';
		$result = mysqli_query($connection, $query);
		
		if($result){
			$post_uploadkey = $uploadkey;
			
		}else{
			//uploadkey记录失败
			//不能上传了
			//那就只能报错返回了
			if($connection)mysqli_close($connection);
			$json['status']= 2;
			$json['usetime'] = endtime($start_time);
			$json['error'] = 'ServerError';
			$json['desc'] = "额服务器开小差了,请稍后重试...";
			$json_code = json_encode($json);
			echo $json_code;
			die();	
		}
	}
	
	//到这里 workid 和 uploadkey 均已合法
	
	//可以补全work的信息了
	//只有新生成uploadkey时候才更新信息
	if($need_creat_uploadkey){
		//对tag进行替换
		$post_movie_type = str_replace('，','|',$post_movie_type);
		$post_movie_type = str_replace('、','|',$post_movie_type);
		$post_movie_type = str_replace(' ','|',$post_movie_type);
		$query = "UPDATE `pcmaker_work` SET `title`='".$post_movie_name."',`sub_title`='".$post_subtitle."',`editor_note`='".$post_movie_bza."',`author`='".$post_movie_direct."',`actor`='".$post_movie_actor."',`intro`='".$post_movie_intro."',`showtime`='".$post_movie_time."',`zone`='".$post_movie_countory."',`tags`='".$post_movie_type."',`tags_text`='',`save_path`='".$post_workid.'/'.$post_uploadkey."',`bpic_md5`='".$post_bpic_md5."',`spic_md5`='".$post_spic_md5."',`firstpage_md5`='".$post_fpic_md5."',`tv_type`=".$post_movie_istv.",`page_count`=".$post_movie_imgcount." WHERE `work_key`='".$post_workid."';";
		
		//这里只更新非空字符串
		$query = "UPDATE `pcmaker_work` SET `save_path`='".$post_workid.'/'.$post_uploadkey."',`tags_text`='' ";
		
		if(strlen($post_movie_name)>0){
			$query .= ",`title`='".$post_movie_name."' ";	
		}
		
		if(strlen($post_subtitle)>0){
			$query .= ",`sub_title`='".$post_subtitle."' ";	
		}
		
		if(strlen($post_movie_bza)>0){
			$query .= ",`editor_note`='".$post_movie_bza."' ";	
		}
		
		if(strlen($post_movie_direct)>0){
			$query .= ",`author`='".$post_movie_direct."' ";	
		}
		
		if(strlen($post_movie_actor)>0){
			$query .= ",`actor`='".$post_movie_actor."' ";	
		}
		
		if(strlen($post_movie_intro)>0){
			$query .= ",`intro`='".$post_movie_intro."' ";	
		}
		
		if(strlen($post_movie_time)>0){
			$query .= ",`showtime`='".$post_movie_time."' ";	
		}
		
		if(strlen($post_movie_countory)>0){
			$query .= ",`zone`='".$post_movie_countory."' ";	
		}
		
		if(strlen($post_movie_type)>0){
			$query .= ",`tags`='".$post_movie_type."' ";	
		}
		
		if(strlen($post_bpic_md5)>0){
			$query .= ",`bpic_md5`='".$post_bpic_md5."' ";	
		}
		
		if(strlen($post_spic_md5)>0){
			$query .= ",`spic_md5`='".$post_spic_md5."' ";	
		}
		
		if(strlen($post_fpic_md5)>0){
			$query .= ",`firstpage_md5`='".$post_fpic_md5."' ";	
		}
		
		if(strlen($post_movie_istv)>0){
			$query .= ",`tv_type`=".$post_movie_istv." ";	
		}
		
		if(strlen($post_movie_imgcount)>0 && $post_movie_imgcount>0){
			$query .= ",`page_count`=".$post_movie_imgcount." ";	
		}
		
		$query .= " WHERE `work_key`='".$post_workid."';";
		
		
		$result = mysqli_query($connection, $query);
	}
	
	//生成通信证书
	//生成规则 根据本次的userid token workid uploadkey 以及一个秘钥来md5
	$regcer = 'graphmoviestudios-'.$post_userid.'-image-'.$post_token.'-server-'.$post_workid.'-register-'.$post_uploadkey.'-certificate';
	$regcer = md5($regcer);
	
	$json['workid']= $post_workid;
	$json['uploadkey'] = $post_uploadkey;
	$json['regcer'] = $regcer;	 
	
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