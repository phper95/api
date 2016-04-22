<?php
/**
* @api {post} /gmspanel/interface/zh-cn/3.1/PCM_OFC_CreatScript.php 获取给定作品的全部脚本信息
* @apiPermission pxseven
* @apiVersion 0.1.0
* @apiName CreatScript
* @apiGroup Ofc
* @apiSampleRequest http://ser3.graphmovie.com/gmspanel/interface/zh-cn/3.1/PCM_OFC_CreatScript.php

* @apiDescription 获取给定作品Key的全部脚本信息，包括<code>GraphEditorFilmInfo</code>,<code>GraphEditorScript.ges</code>,<code>GraphEditorUserInfo</code>,<code>JsonScript.data</code>

* @apiParam (POST) {String} [workkey=""] 需要获取脚本的作品key
* @apiParam (POST) {String} ck="" 识别码,识别请求是否合法
*
* @apiSuccess (ResponseJSON) {Integer} status 接口响应状态（0-失败,1-成功,2-需要弹出提示框,提示desc内容）.
* @apiSuccess (ResponseJSON) {Float} usetime 接口响应时间,调试用.
* @apiSuccess (ResponseJSON) {String} error 接口响应出错时的错误描述.
* @apiSuccess (ResponseJSON) {String} debug 接口响应出错时的过程描述,调试用.
* @apiSuccess (ResponseJSON) {String} desc status=2时需要弹窗提示此内容.
* @apiSuccess (ResponseJSON) {Array} files 要生成的文件名称列表.
* @apiSuccess (ResponseJSON) {String} files.GraphEditorFilmInfo 脚本GraphEditorFilmInfo的内容字符串信息，该脚本是制作器使用的影片信息脚本，文件名"GraphEditorFilmInfo"，无格式后缀（本质是XML格式）.
* @apiSuccess (ResponseJSON) {String} files.GraphEditorScriptges <=键名是<code>GraphEditorScript.ges</code>前面打不出来，脚本GraphEditorScript.ges的内容字符串信息，该脚本是制作器使用的解说信息脚本，文件名"GraphEditorScript.ges"，后缀ges(本质是XML格式).
* @apiSuccess (ResponseJSON) {String} files.GraphEditorUserInfo 脚本GraphEditorUserInfo的内容字符串信息，该脚本是制作器使用的作者信息脚本，文件名"GraphEditorUserInfo"，无格式后缀（本质是XML格式）.
* @apiSuccess (ResponseJSON) {String} files.JsonScriptdata <=键名是<code>JsonScript.data</code>前面打不出来，脚本JsonScript.data的内容字符串信息，该脚本是后台上传使用的全信息脚本（含以上三项），文件名"JsonScript.data"，后缀data（本质是JSON格式）.


*
* @apiSuccessExample Success-Response[提交成功]:

*     {
*       "status": 1,
*       "usetime": 0.0024,
*       "error": "",
*       "debug": "",
*       "desc": "",
*		"files": [
*       	"GraphEditorFilmInfo": "<GraphFilmInfo><作品名>卡罗尔</作品名>...</GraphFilmInfo>",
*       	"GraphEditorScript.ges": "<GraphEditorScript><ScriptContent><ScriptItem>...</GraphEditorScript>",
*       	"GraphEditorUserInfo": "<GraphUserInfo><绑定邮箱>...</GraphUserInfo>",
*       	"JsonScript.data": "{\"story\":[{\"name\":\"gm_1.PNG\",\"intro\"...\"movie_fpic\":\"01.png\"}"
*		]
*     }

*
* @apiError PostError 请求的参数缺失或者参数格式错误.
* @apiError ServerError 服务器状态异常.
* @apiError ErrorWorkKey 不存在该作品.
* @apiError CkError ck验证不通过，请求非法 

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

*     ErrorWorkKey:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "ErrorWorkKey",
*       "debug": "",
*       "desc": "作品校验失败"
*     }

*     CkError:

*     {
*       "status": 0,
*       "usetime": 0.0024,
*       "error": "CkError",
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
	$post_workkey = '';
	$post_ck = 0;
	
	//获取Header
	//模拟请求采用的是request payload 发上来的参数
	$request_body = file_get_contents('php://input');
	$data = json_decode($request_body);
	if($data && count($data)>0 && (!isset($_POST) || count($_POST)==0) && isset($data->workkey) && strlen($data->workkey)>0 && isset($data->ck) && strlen($data->ck)>0){
		//curl提交的参数
		if(isset($data->workkey)){
			$post_workkey = htmlspecialchars(addslashes($data->workkey));
		}
		
		if(isset($data->ck)){
			$post_ck = htmlspecialchars(addslashes($data->ck));
		}
		
	}else if(
		isset($_POST['workkey']) && strlen($_POST['workkey'])>0 && 
		isset($_POST['ck']) && strlen($_POST['ck'])>0
		){
		//获取参数
		if(isset($_POST['workkey']) && strlen($_POST['workkey'])>0){$post_workkey = htmlspecialchars(addslashes($_POST['workkey']));}
		if(isset($_POST['ck'])){$post_ck = htmlspecialchars(addslashes($_POST['ck']));}
		
	}else{
		//缺少参数
		$json['status']= 0;
		$json['usetime'] = endtime($start_time);
		$json['error'] = 'PostError';
		$json_code = json_encode($json);
		echo $json_code;
		die();
	}
	
	//验证是否合法请求 
	//验证方法: ck = md5(workkey+'graphmoviestudiosapi')
	
	if(strtolower(md5($post_workkey.'graphmoviestudiosapi')) != strtolower($post_ck)){
		//不合法的请求
		$json['status']= 0;
		$json['usetime'] = endtime($start_time);
		$json['error'] = 'CkError';
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
	
	$json['files'] = array(
		"GraphEditorFilmInfo" => "",
		"GraphEditorUserInfo" => "",
		"GraphEditorScript.ges" => "",
		"JsonScript.data" => ""
	);
	
	$jsonscript['JsonScript.data'] = array(
		"story" => array(),
		"time" => "",
		"user_email" => "",
		"user_weiboType" => "",
		"app_username" => "",
		"place" => "",
		"lovetype" => "",
		"movie_name" => "",
		"movie_direct" => "",
		"movie_actor" => "",
		"movie_score" => "",
		"movie_time" => "",
		"movie_countory" => "",
		"subtitle" => "",
		"movie_bza" => "",
		"movie_intro" => "",
		"movie_type" => "",
		"movie_bpic" => "",
		"movie_spic" => "",
		"movie_fpic" => ""
	);
	
	/*
	{
		"story": [
			{
				"name": "gm_1.PNG",
				"intro": "2015年的百合片，《卡罗尔》"
			}
		],
		"time": "2016/2/22 星期一 15:09:37",
		"user_email": "874379869@qq.com",
		"user_weiboType": "新浪微博",
		"weibo": "已被海锅掰成了蚊香",
		"app_username": "小高桥凉介子",
		"place": "陕西省西安市",
		"lovetype": "爱情、剧情、悬疑",
		"movie_name": "卡罗尔",
		"movie_direct": "托德·海因斯",
		"movie_actor": "凯特·布兰切特、鲁妮·玛拉、莎拉·保罗森",
		"movie_score": "8.4",
		"movie_time": "2015",
		"movie_countory": "美国、英国",
		"subtitle": "超越了同性的限定，2015最美的爱情片",
		"movie_bza": "两人相爱，早已超越了一切，性别、年龄、世俗。",
		"movie_intro": "50年代的美国，年轻女子特芮丝（鲁妮·玛拉 饰）在纽约百货公司担任售货员，但心中向往的却是摄影师工作。某日，一位美丽优雅的金发贵妇卡罗尔（凯特·布兰切特 饰）来到百货公司购买圣诞节礼物，结果和特芮丝一见投缘。两人相识后特芮丝得知原来卡罗尔有一个女儿，而且正和丈夫哈吉（凯尔·钱德勒 饰）办理离婚手续。通过书信来往、约会相处以及公路旅行，特芮丝和卡罗尔发现彼此就是自己的真爱，然而在当时社会这是不被允许的。特芮丝的男友认为她只是一时迷惑，卡罗尔的丈夫则请私家侦探调查取证，希望在离婚诉讼让中她一无所有。考验两位女性的时刻终于到来了：在社会压力下她们能否坚守内心、不计代价的把感情路走到底？ \r\n　　《卡罗尔》是美国著名独立导演托德·海恩斯的新作，入围第68届戛纳电影节主竞赛单元，获得最佳女主角奖。电影根据派翠西亚·海史密斯在1952年匿名发表的中篇女同小说《盐的代价》改编，由于题材敏感，最初出版社还拒绝发行。之所以叫“盐的代价”，因为在17世纪“盐”还有另一个意思表示女性的情欲。而在本书中它隐喻了女主们的处境：没有爱情就像没有盐的肉；那么为了这份爱，你愿意付出多少代价?",
		"movie_type": "同性、爱情、剧情",
		"movie_bpic": "02.png",
		"movie_spic": "03.png",
		"movie_fpic": "01.png"
	}
	*/
	
	
	$query = 'SELECT * FROM `pcmaker_work` WHERE `work_key`=\''.$post_workkey.'\';';
	$result = mysqli_query($connection,$query);
	if($result){
		if(mysqli_num_rows($result)>0){
			//找到 
			$work = mysqli_fetch_assoc($result);
			
			$bpic_name = $work['bpic_id']>0?'02.png':'';
			$spic_name = $work['spic_id']>0?'03.png':'';
			$firstpage_name = $work['firstpage_id']>0?'01.png':'';
			$tv_name = $work['tv_type']==0?'Movie':($work['tv_type']==1?'SingleTV':'SeasonTV');
			
			//作品信息
			$json['files']['GraphEditorFilmInfo'] = '<GraphFilmInfo><作品名>'.$work['title'].'</作品名><作品类型>'.$tv_name.'</作品类型><季数></季数><集数></集数><导演>'.$work['author'].'</导演><主演>'.$work['actor'].'</主演><影片评分>'.$work['score'].'</影片评分><影片上映年份>'.$work['showtime'].'</影片上映年份><影片上映地区>'.$work['zone'].'</影片上映地区><副标题>'.$work['sub_title'].'</副标题><编者按>'.$work['editor_note'].'</编者按><影片简介>'.$work['intro'].'</影片简介><影片类型>'.str_replace('|','、',$work['tags']).'</影片类型><封面大图>'.$bpic_name.'</封面大图><封面小图>'.$spic_name.'</封面小图><首页海报>'.$firstpage_name.'</首页海报></GraphFilmInfo>';
			
			$jsonscript['JsonScript.data']['movie_name'] = $work['title'];
			$jsonscript['JsonScript.data']['movie_direct'] = $work['author'];
			$jsonscript['JsonScript.data']['movie_actor'] = $work['actor'];
			$jsonscript['JsonScript.data']['movie_score'] = $work['score'];
			$jsonscript['JsonScript.data']['movie_time'] = $work['showtime'];
			$jsonscript['JsonScript.data']['movie_countory'] = $work['zone'];
			$jsonscript['JsonScript.data']['subtitle'] = $work['sub_title'];
			$jsonscript['JsonScript.data']['movie_bza'] = $work['editor_note'];
			$jsonscript['JsonScript.data']['movie_intro'] = $work['intro'];
			$jsonscript['JsonScript.data']['movie_type'] = str_replace('|','、',$work['tags']);
			$jsonscript['JsonScript.data']['movie_bpic'] = $bpic_name;
			$jsonscript['JsonScript.data']['movie_spic'] = $spic_name;
			$jsonscript['JsonScript.data']['movie_fpic'] = $firstpage_name;
			
			$jsonscript['JsonScript.data']['time'] = $work['add_time'];
			
			
			//用户信息
			$query = 'SELECT * FROM `client_user` WHERE `id`='.$work['user_id'].';';
			$result = mysqli_query($connection,$query);
			if($result && mysqli_num_rows($result)>0){
				//找到 
				$user = mysqli_fetch_assoc($result);
				
				$json['files']['GraphEditorUserInfo'] = '<GraphUserInfo><绑定邮箱>'.$user['email'].'</绑定邮箱><微博类型></微博类型><微博昵称></微博昵称><用户昵称>'.$user['name'].'</用户昵称><所在地区></所在地区><喜欢类型>'.$user['like_tag_name'].'</喜欢类型></GraphUserInfo>';
				
				$jsonscript['JsonScript.data']['user_email'] = $user['email'];
				$jsonscript['JsonScript.data']['user_weiboType'] = '';
				$jsonscript['JsonScript.data']['app_username'] = $user['name'];
				$jsonscript['JsonScript.data']['place'] = '';
				$jsonscript['JsonScript.data']['lovetype'] = $user['like_tag_name'];
				
			}else{
				if($connection)mysqli_close($connection);
				$json['status']= 2;
				$json['usetime'] = endtime($start_time);
				$json['error'] = 'ErrorWorkKey';
				$json['desc'] = "作品校验失败[user]";
				$json_code = json_encode($json);
				echo $json_code;
				die();	
			}
			
			//脚本信息
			//先查出最后上传的uploadkey是哪个
			$query = 'SELECT * FROM `pcmaker_upload_map` WHERE `work_key`=\''.$post_workkey.'\' AND `state`=2 ORDER BY `id` DESC LIMIT 1;';
			$result = mysqli_query($connection,$query);
			if($result && mysqli_num_rows($result)>0){
				$upload = mysqli_fetch_assoc($result);
				
				//再查该子版本的全部脚本
				$query = 'SELECT * FROM `pcmaker_work_img` WHERE `work_key`=\''.$post_workkey.'\' AND `upload_key`=\''.$upload['upload_key'].'\' AND `page_index`>=0 ORDER BY `page_index` ASC;';
				$result = mysqli_query($connection,$query);
				if($result && mysqli_num_rows($result)>0){
					$json['files']['GraphEditorScript.ges'] = '<GraphEditorScript><ScriptContent>';
					$i = 0;
					while($i<mysqli_num_rows($result)){
						$page = mysqli_fetch_assoc($result);
						
						$json['files']['GraphEditorScript.ges'] .= '<ScriptItem><Image>gm_'.($i+1).'.'.$page['type'].'</Image><Content>'.$page['intro'].'</Content></ScriptItem>';
						
						$jsonscript['JsonScript.data']['story'][count($jsonscript['JsonScript.data']['story'])] = array(
							"name" => 'gm_'.($i+1).'.'.$page['type'],
							"intro" => $page['intro']
						);
						
						$i++;
						
					}
					
					$json['files']['GraphEditorScript.ges'] .= '<LastEditPage>'.mysqli_num_rows($result).'</LastEditPage></ScriptContent></GraphEditorScript>';
					
				}
				
				
				
			}else{
				if($connection)mysqli_close($connection);
				$json['status']= 2;
				$json['usetime'] = endtime($start_time);
				$json['error'] = 'ErrorWorkKey';
				$json['debug'] = $query;
				$json['desc'] = "作品校验失败[script]";
				$json_code = json_encode($json);
				echo $json_code;
				die();		
			}
			
			
			
		}else{
			//没找到
			//非法
			if($connection)mysqli_close($connection);
			$json['status']= 2;
			$json['usetime'] = endtime($start_time);
			$json['error'] = 'ErrorWorkKey';
			$json['desc'] = "作品校验失败[work]";
			$json_code = json_encode($json);
			echo $json_code;
			die();
		}
	}
	
	$json['files']['JsonScript.data'] = json_encode($jsonscript['JsonScript.data']);
	
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