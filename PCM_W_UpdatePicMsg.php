<?php
/**
* @api {post} /gmspanel/interface/zh-cn/3.1/PCM_W_UpdatePicMsg.php 【非客户端】图片上传成功更新信息
* @apiPermission pxseven
* @apiVersion 0.1.0
* @apiName UpdatePicMsg
* @apiGroup Work
* @apiSampleRequest http://ser3.graphmovie.com/gmspanel/interface/zh-cn/3.1/PCM_W_UpdatePicMsg.php

* @apiDescription 客户端请求接口PCM_W_UploadPic后，图片服务器在经过验证证书，验证图片，图片存储之后会转post到此接口来更新数据库记录

* @apiParam (POST) {String} [pk=""] 用户PC的机器码(MAC地址)
* @apiParam (POST) {Integer} [v=0] 用户GraphMovieStudios的内部版本号
* @apiParam (POST) {String} userid 用户的userid，必须为登录用户才可访问此接口
* @apiParam (POST) {String} token 用户的登录时返回的token，用于验证用户是否合法
* @apiParam (POST) {String} workid 用户正在创作作品的作品ID
* @apiParam (POST) {String} uploadkey 用户作品对应的版本标识
* @apiParam (POST) {String} regcer 之前SetUpUpload接口请求到的签名证书
* @apiParam (POST) {String} imgfile 该页的图片存储路径
* @apiParam (POST) {String} intro 该页的解说
* @apiParam (POST) {String} imgmd5 上传图片的MD5值，如果是封面将会用此MD5来更新work表中的封面id
* @apiParam (POST) {Integer} imgs 上传图片的像素宽度
* @apiParam (POST) {Integer} imgh 上传图片的像素高度
* @apiParam (POST) {Integer} imgs 上传图片的体积，单位是字节
* @apiParam (POST) {String} imgt 上传图片的类型，支持BMP,PNG,JPG,GIF
* @apiParam (POST) {String} svk 加密的验证key，用于验证请求是否有效
* @apiParam (POST) {Integer} pageindex 该图片的页序，注意由于封面图也需要上传，小封面图spic(图片名03.png)页序编号为-3，大封面图bpic(图片名02.png)页序编号为-2，图解第一页fpic(图片名01.png)页序编号为-1，其余正常解说图片从0开始编号（0为高斯模糊图），如果没有指定封面不发即可

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
* @apiError ErrorUploadKey 不存在该作品的这个版本.
* @apiError WorkIdWrongUser 该用户和该WorkId是不匹配的.
* @apiError WorkCantModify 该作品已上线无法再次上传
* @apiError ErrorRequest svk验证不通过，请求非法 

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

*     ErrorUploadKey:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "ErrorUploadKey",
*       "debug": "",
*       "desc": "该作品不存在这个版本"
*     }

*     WorkIdWrongUser:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "WorkIdWrongUser",
*       "debug": "",
*       "desc": "该作品不是你的作品"
*     }

*     WorkCantModify:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "WorkCantModify",
*       "debug": "",
*       "desc": "作品已上线无法再次上传"
*     }

*     ErrorRequest:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "ErrorRequest",
*       "debug": "",
*       "desc": "非法请求"
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
	$post_uploadkey = '';
	$post_regcer = '';
	$post_imgfile = '';
	$post_intro = '';
	$post_imgmd5 = '';
	$post_w = 0;
	$post_h = 0;
	$post_s = 0;
	$post_t = '';
	$post_svk = '';
	$post_pageindex = -9999;
	
	//获取Header
	//模拟请求采用的是request payload 发上来的参数
	$request_body = file_get_contents('php://input');
	$data = json_decode($request_body);
	if($data && count($data)>0 && (!isset($_POST) || count($_POST)==0) && isset($data->userid) && strlen($data->userid)>0 && isset($data->token) && strlen($data->token)>0 && isset($data->workid) && strlen($data->workid)>0 && isset($data->uploadkey) && strlen($data->uploadkey)>0 && isset($data->regcer) && strlen($data->regcer)>0 && isset($data->imgfile) && strlen($data->imgfile)>0 && isset($data->imgmd5) && strlen($data->imgmd5)>0 && isset($data->svk) && strlen($data->svk)>0){
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
		
		if(isset($data->uploadkey)){
			$post_uploadkey = htmlspecialchars(addslashes($data->uploadkey));
		}
		
		if(isset($data->regcer)){
			$post_regcer = htmlspecialchars(addslashes($data->regcer));
		}
		
		if(isset($data->pageindex)){
			$post_pageindex = htmlspecialchars(addslashes($data->pageindex));
		}
		
		if(isset($data->intro)){
			$post_intro = htmlspecialchars(addslashes($data->intro));
		}
		
		if(isset($data->imgfile)){
			$post_imgfile = htmlspecialchars(addslashes($data->imgfile));
		}
		
		if(isset($data->imgmd5)){
			$post_imgmd5 = htmlspecialchars(addslashes($data->imgmd5));
		}
		
		if(isset($data->imgw)){
			$post_imgw = htmlspecialchars(addslashes($data->imgw));
		}
		
		if(isset($data->imgh)){
			$post_imgh = htmlspecialchars(addslashes($data->imgh));
		}
		
		if(isset($data->imgs)){
			$post_imgs = htmlspecialchars(addslashes($data->imgs));
		}
		
		if(isset($data->imgt)){
			$post_imgt = htmlspecialchars(addslashes($data->imgt));
		}
		
		if(isset($data->svk)){
			$post_svk = htmlspecialchars(addslashes($data->svk));
		}
		
		
	}else if(
		isset($_POST['userid']) && strlen($_POST['userid'])>0 && 
		isset($_POST['token']) && strlen($_POST['token'])>0 &&
		isset($_POST['workid']) && strlen($_POST['workid'])>0 &&
		isset($_POST['uploadkey']) && strlen($_POST['uploadkey'])>0 &&
		isset($_POST['regcer']) && strlen($_POST['regcer'])>0 && 
		isset($_POST['imgfile']) && strlen($_POST['imgfile'])>0 && 
		isset($_POST['imgmd5']) && strlen($_POST['imgmd5'])>0 && 
		isset($_POST['svk']) && strlen($_POST['svk'])>0 
		){
		//获取参数
		if(isset($_POST['pk']) && strlen($_POST['pk'])>0){$post_pk = htmlspecialchars(addslashes($_POST['pk']));}
		if(isset($_POST['v'])){$post_v = htmlspecialchars(addslashes($_POST['v']));}
		if(isset($_POST['userid'])){$post_userid = userIdKeyDecode(htmlspecialchars(addslashes($_POST['userid'])));}
		if(isset($_POST['token'])){$post_token = htmlspecialchars(addslashes($_POST['token']));}
		if(isset($_POST['workid'])){$post_workid = htmlspecialchars(addslashes($_POST['workid']));}
		if(isset($_POST['uploadkey'])){$post_uploadkey = htmlspecialchars(addslashes($_POST['uploadkey']));}
		if(isset($_POST['regcer'])){$post_regcer = htmlspecialchars(addslashes($_POST['regcer']));}
		
		if(isset($_POST['pageindex'])){$post_pageindex = htmlspecialchars(addslashes($_POST['pageindex']));}
		if(isset($_POST['intro'])){$post_intro = htmlspecialchars(addslashes($_POST['intro']));}
		
		if(isset($_POST['imgfile'])){$post_imgfile = htmlspecialchars(addslashes($_POST['imgfile']));}
		if(isset($_POST['imgmd5'])){$post_imgmd5 = htmlspecialchars(addslashes($_POST['imgmd5']));}
		if(isset($_POST['svk'])){$post_svk = htmlspecialchars(addslashes($_POST['svk']));}
		
		if(isset($_POST['imgw'])){$post_imgw = htmlspecialchars(addslashes($_POST['imgw']));}
		if(isset($_POST['imgh'])){$post_imgh = htmlspecialchars(addslashes($_POST['imgh']));}
		if(isset($_POST['imgs'])){$post_imgs = htmlspecialchars(addslashes($_POST['imgs']));}
		if(isset($_POST['imgt'])){$post_imgt = htmlspecialchars(addslashes($_POST['imgt']));}
		
	}else{
		//缺少参数
		$json['status']= 0;
		$json['usetime'] = endtime($start_time);
		$json['error'] = 'PostError';
		$json_code = json_encode($json);
		echo $json_code;
		die();
	}
	
	//debug
	//file_put_contents('PCM_W_UpdatePicMsg_1.txt',''.PHP_EOL,FILE_APPEND);
	
	//验证svk参数是否合法
	$svk = md5($post_workid.'graph'.$post_uploadkey.'movie'.'from imgs.graphmovie.com');
	if($svk != $post_svk){
		//非法请求
		$json['status']= 0;
		$json['usetime'] = endtime($start_time);
		$json['error'] = 'ErrorRequest';
		$json['desc'] = "";
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
	
	//debug
	//file_put_contents('PCM_W_UpdatePicMsg_2.txt',''.PHP_EOL,FILE_APPEND);
	
	//OK TOKEN 合法
	//查询该userid和workid的配对是否存在
	//查询该workid是否存在
	
	$total_page_count = 0;
	
	$query = 'SELECT * FROM `pcmaker_work` WHERE `work_key`=\''.$post_workid.'\';';
	$result = mysqli_query($connection,$query);
	if($result){
		if(mysqli_num_rows($result)>0){
			//找到 
			$work = mysqli_fetch_assoc($result);
			$total_page_count = $work['page_count'];
			
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
				//非法
				if($connection)mysqli_close($connection);
				$json['status']= 2;
				$json['usetime'] = endtime($start_time);
				$json['error'] = 'WorkIdWrongUser';
				$json['desc'] = "该作品不是你的作品";
				$json_code = json_encode($json);
				echo $json_code;
				die();
			}else{
				//是该用户的 
				//合法
					
			}
			
		}else{
			//没找到
			//非法
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
	
	//作品id和用户id对应合法 验证uploadkey是否合法
	
	//debug
	//file_put_contents('PCM_W_UpdatePicMsg_3.txt',''.PHP_EOL,FILE_APPEND);
	
	//验证uploadkey
	$query = 'SELECT * FROM `pcmaker_upload_map` WHERE `work_key`=\''.$post_workid.'\' AND `state`<>3 ORDER BY `id` DESC LIMIT 1;';
	$result = mysqli_query($connection,$query);
	if($result && mysqli_num_rows($result)>0){
		//找到了 验证是否是合法的uploadkey
		$record = mysqli_fetch_assoc($result);
		if(strlen($post_uploadkey)>0 && $record['upload_key'] == $post_uploadkey){
			//合法的
			
		}else{
			//非法的uploadkey
			if($connection)mysqli_close($connection);
			$json['status']= 2;
			$json['usetime'] = endtime($start_time);
			$json['error'] = 'ErrorUploadKey';
			$json['desc'] = "该作品不存在这个版本";
			$json_code = json_encode($json);
			echo $json_code;
			die();
		}
		
	}else{
		//没找到
		//非法的
		if($connection)mysqli_close($connection);
		$json['status']= 2;
		$json['usetime'] = endtime($start_time);
		$json['error'] = 'ErrorUploadKey';
		$json['desc'] = "该作品不存在这个版本";
		$json_code = json_encode($json);
		echo $json_code;
		die();
	}
	
	//ok uploadkey也合法
	
	//debug
	//file_put_contents('PCM_W_UpdatePicMsg_4.txt',''.PHP_EOL,FILE_APPEND);
	
	//可以存储图片信息了
	//先查找这一页的图片和解说是不是已经有了
	$thispage_id = 0;
	
	$query = 'SELECT * FROM `pcmaker_work_img` WHERE `user_id`='.$post_userid.' AND `work_key`=\''.$post_workid.'\' AND `upload_key`=\''.$post_uploadkey.'\' AND `page_index`='.$post_pageindex.';';
	$result = mysqli_query($connection,$query);
	if($result && mysqli_num_rows($result)>0){
		//找到了 有这张图了
		//就update
		$record = mysqli_fetch_assoc($result);
		
		$query = 'UPDATE `pcmaker_work_img` SET '.
						'`url`=\''.$post_imgfile.'\','.
						'`md5`=\''.$post_imgmd5.'\','.
						'`w`='.$post_imgw.','.
						'`h`='.$post_imgh.','.
						'`size`='.$post_imgs.','.
						'`type`=\''.strtolower($post_imgt).'\','.
						'`intro`=\''.$post_intro.'\' '.
						' WHERE `id`='.$record['id'].
						';';
		$result = mysqli_query($connection,$query);
		
		$thispage_id = $record['id'];
	}else{
		//没有 就INSERT
		$query = 'INSERT INTO pcmaker_work_img(url,page_index,md5,w,h,type,size,user_id,work_key,upload_key,add_time,intro,open) VALUES ('.
					'\''.$post_imgfile.'\','.
					$post_pageindex.','.
					'\''.$post_imgmd5.'\','.
					$post_imgw.','.
					$post_imgh.','.
					'\''.$post_imgt.'\','.
					$post_imgs.','.
					$post_userid.','.
					'\''.$post_workid.'\','.
					'\''.$post_uploadkey.'\','.
					'now(),'.
					'\''.$post_intro.'\','.
					'1'.
		');';
		$result = mysqli_query($connection, $query);
		
		//再查出来最后INSERT的
		$query = 'SELECT last_insert_id() as \'lastinsert\' FROM `pcmaker_work_img` LIMIT 1;';
		$insert_result = mysqli_query($connection,$query);
		$record = mysqli_fetch_assoc($insert_result);	
		$thispage_id = $record['lastinsert'];
	}
	
	if(!$result || $thispage_id<=0){
		//记录失败
		//返回错误
		if($connection)mysqli_close($connection);
		$json['status']= 2;
		$json['usetime'] = endtime($start_time);
		$json['error'] = 'ServerError';
		$json['desc'] = "服务器存储异常错误";
		$json_code = json_encode($json);
		echo $json_code;
		die();
		
	}
	
	//debug
	//file_put_contents('PCM_W_UpdatePicMsg_5.txt',''.PHP_EOL,FILE_APPEND);
	
	//记录成功 查看是否是封面 是就更新
	if($post_pageindex == -3){
		//小封面
		$query = 'UPDATE `pcmaker_work` SET '.
						'`spic_id`='.$thispage_id.','.
						'`spic_md5`=\''.$post_imgmd5.'\''.
						' WHERE `work_key`=\''.$post_workid.'\''.
						';';
		mysqli_query($connection,$query);
	}else if($post_pageindex == -2){
		//大封面
		$query = 'UPDATE `pcmaker_work` SET '.
						'`bpic_id`='.$thispage_id.','.
						'`bpic_md5`=\''.$post_imgmd5.'\''.
						' WHERE `work_key`=\''.$post_workid.'\''.
						';';
		mysqli_query($connection,$query);
	}else if($post_pageindex == -1){
		//图解第一张
		$query = 'UPDATE `pcmaker_work` SET '.
						'`firstpage_id`='.$thispage_id.','.
						'`firstpage_md5`=\''.$post_imgmd5.'\''.
						' WHERE `work_key`=\''.$post_workid.'\''.
						';';
		mysqli_query($connection,$query);
	}
	
	//debug
	//file_put_contents('PCM_W_UpdatePicMsg_6.txt',''.PHP_EOL,FILE_APPEND);
	
	//更新上传进度
	$query = 'SELECT COUNT(*) as \'pagecount\' FROM `pcmaker_work_img` WHERE `work_key`=\''.$post_workid.'\' AND `upload_key`=\''.$post_uploadkey.'\';';
	
	//debug
	//file_put_contents('PCM_W_UpdatePicMsg_6_1.txt',$query.PHP_EOL,FILE_APPEND);
	//debug
	//file_put_contents('PCM_W_UpdatePicMsg_6_2.txt',$total_page_count.PHP_EOL,FILE_APPEND);
	
	$result = mysqli_query($connection,$query);
	if($result && mysqli_num_rows($result)>0 && $total_page_count>0){
		$record = mysqli_fetch_assoc($result);
		$uploadok_count = $record['pagecount'];
		$per = round(((float)$uploadok_count/(float)$total_page_count)*100);
		$state = 1;
		if($uploadok_count>=$total_page_count){
			$per = 100;
			$state = 2;
		}
		
		$query = 'UPDATE `pcmaker_upload_map` SET '.
						'`progress`='.$per.','.
						'`state`='.$state.
						' WHERE `work_key`=\''.$post_workid.'\' AND `upload_key`=\''.$post_uploadkey.'\''.
						';';
		mysqli_query($connection,$query);
		
		//debug
		//file_put_contents('PCM_W_UpdatePicMsg_6_3.txt',$query.PHP_EOL,FILE_APPEND);
		
		//20160224 上传成功就更新submit_time
		if($state==2){
			$query = 'UPDATE `pcmaker_work` SET '.
						'`progress`='.$per.','.
						'`submit_time`=now(),'.
						'`state`='.$state.
						' WHERE `work_key`=\''.$post_workid.'\' '.
						';';
			mysqli_query($connection,$query);
			
			//UNDONE 如果该上传的作品存在一个同豆瓣ID的退稿作品 更新创建时间?  暂时不处理
			
		}
		
		//更新update time
		$query = 'UPDATE `pcmaker_work` SET '.
						'`update_time`=now()'.
						' WHERE `work_key`=\''.$post_workid.'\' '.
						';';
		mysqli_query($connection,$query);
		
	}
	
	//debug
	//file_put_contents('PCM_W_UpdatePicMsg_7.txt',''.PHP_EOL,FILE_APPEND);
	
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