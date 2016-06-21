<?php
/**
* @api {post} /gmspanel/interface/zh-cn/3.1/PCM_OFC_CreatScriptJson.php 获取给定作品的全部脚本信息,以json格式返回
* @apiPermission yongge
* @apiVersion 0.1.0
* @apiName CreatScriptJson
* @apiGroup Ofc
* @apiSampleRequest http://ser3.graphmovie.com/gmspanel/interface/zh-cn/3.1/PCM_OFC_CreatScriptJson.php

* @apiDescription 获取给定作品Key的全部脚本信息,即<code>scriptJson</code>

* @apiParam (POST) {String} [workkey=""] 需要获取脚本的作品key
* @apiParam (POST) {String} ck="" 识别码,识别请求是否合法
*
* @apiSuccess (ResponseJSON) {Integer} status 接口响应状态（0-失败,1-成功,2-需要弹出提示框,提示desc内容）.
* @apiSuccess (ResponseJSON) {Float} usetime 接口响应时间,调试用.
* @apiSuccess (ResponseJSON) {String} error 接口响应出错时的错误描述.
* @apiSuccess (ResponseJSON) {String} debug 接口响应出错时的过程描述,调试用.
* @apiSuccess (ResponseJSON) {String} desc status=2时需要弹窗提示此内容.
* @apiSuccess (ResponseJSON) {Object} scriptJson 生成的全部json数据.


*
* @apiSuccessExample Success-Response[提交成功]:

*     {
		"status": 1,
		"usetime": "0.07139",
		"error": "",
		"debug": "na",
		"desc": "",
		"scriptJson": {
		"scriptItem": [
		{
		"image": "http://imgs4.graphmovie.com/gms_works/work_imgs/f6ee0baeb3ea3b34e689616242c43105/d562484665cc1eeac0b4c69aab3f8a7c/76a4c2ec36362d91393af00c7402eb7f.jpg",
		"intro": "大家好TAT这是发春做的第二遍……因为第一遍快做完的时候电脑重装，什么都没有了。感谢大家的不离不弃。"
		},
		{
		"image": "http://imgs4.graphmovie.com/gms_works/work_imgs/f6ee0baeb3ea3b34e689616242c43105/d562484665cc1eeac0b4c69aab3f8a7c/49b580885c6f6f08d4ba3bea8fae3dd9.jpg",
		"intro": "上一集说到，月牙收留了个声称无家可归的小妹，无心却不待见她。待月牙出门买菜时，无心才揭穿了小妹的身份——她正是井下新娘，岳绮罗。"
		},
		{
		"image": "http://imgs4.graphmovie.com/gms_works/work_imgs/f6ee0baeb3ea3b34e689616242c43105/d562484665cc1eeac0b4c69aab3f8a7c/2127e8471930b1ad5d906e727d338a4e.jpg",
		"intro": "岳绮罗被无心揭穿身份,并不恼怒,笑嘻嘻地说道:你认得我?"
		},
		......
		......
		{
		"image": "http://imgs4.graphmovie.com/gms_works/work_imgs/f6ee0baeb3ea3b34e689616242c43105/d562484665cc1eeac0b4c69aab3f8a7c/1d8a35ef78c1f774575f31dc63da9dea.jpg",
		"intro": "第四集就在这欢声笑语中结束了"
		},
		{
		"image": "http://imgs4.graphmovie.com/gms_works/work_imgs/f6ee0baeb3ea3b34e689616242c43105/d562484665cc1eeac0b4c69aab3f8a7c/3087a12d97bc0c899ffcaf2c24287688.jpg",
		"intro": "谢谢大家的不离不弃，你们的支持点赞是发春继续图解的动力，本学期课少，速度会提高的！（如有建议意见，欢迎留言~）"
		}
		],
		"LastEditPage": 516,
		"workInfo": {
			"id": "75",
			"work_key": "f6ee0baeb3ea3b34e689616242c43105",
			"user_id": "2577356",
			"title": "无心法师",
			"sub_title": "打打闹闹捉妖怪，甜甜蜜蜜谈恋爱。",
			"editor_note": "画面精致，特效还行，演员颜高，且演技自然，台词逻辑性没有硬伤，没有莫名其妙的情节，令人反感的植入，这在天雷纵横，五毛特效弥漫，演技浮夸，台词坑爹，剧情狗血的国产剧里是多么难得！",
			"author": "林玉芬；高林豹",
			"actor": "韩东君，金晨，陈瑶",
			"intro": "《无心法师》是搜狐视频和唐人影视联合出品，改编自作者尼罗同名作品的民国玄幻季播网络剧，由韩东君、金晨、张若昀等联袂主演。该剧讲述了拥有不老不死之身的无心带领除邪团队，一路与恶人奸邪斗智斗勇的故事。",
			"showtime": "2015",
			"zone": "中国",
			"score": "7.0",
			"bpic_id": "10881",
			"spic_id": "10882",
			"firstpage_id": "10883",
			"jian": "0",
			"tags": "奇幻|悬疑|剧情",
			"tags_text": "",
			"season_id": "0",
			"act_id": "0",
			"page_count": "519",
			"size": "0.00",
			"state": "2",
			"movie_id": "0",
			"progress": "100",
			"db_url": "http://movie.douban.com/subject/26298756/",
			"db_id": "26298756",
			"film_id": "0",
			"add_time": "2016-02-18 22:19:40",
			"update_time": "2016-03-09 19:56:25",
			"save_path": "f6ee0baeb3ea3b34e689616242c43105/d562484665cc1eeac0b4c69aab3f8a7c",
			"bpic_md5": "a9e9fc4e9b7f356fcfd53809791d7e69",
			"spic_md5": "55fc6ca837eb68dd2655d25423c1ae50",
			"firstpage_md5": "3fe84eea3a2e27d043bf1706d290c198",
			"tv_type": "2",
			"tv_s_num": "0",
			"tv_e_num": "0",
			"creat_time": "2016-02-18 22:19:40",
			"submit_time": "2016-03-09 19:56:25",
			"we_score": "8",
			"takeon_time": null,
			"offline_time": null,
			"offline_type": "0"
		},
		"userInfo": {
			"id": "2577356",
			"name": "该好友最近没有发春",
			"intro": "",
			"email": "360342406@qq.com",
			"phone_number": "",
			"sex": "0",
			"age": "0",
			"avatar": "http://ser3.graphmovie.com/gmspanel/appimages/avatars/2577356/20160326164801.jpg",
			"open": "1",
			"add_time": "2015-04-03 21:29:27",
			"update_time": "2016-06-17 17:00:41",
			"secure_pwd_md5": "4dbfcfc131465396526085ae63f963b8",
			"level": "1",
			"role": "",
			"feeling": "粉丝里有母上，评论手下留情…",
			"avatar_bg": "http://imgs4.graphmovie.com/appimage/avatar_cover/system/user_background08.jpg",
			"imei": "865931021005046",
			"phone_type": "MI4LTE",
			"pub_channel": "me",
			"pub_platform": "android",
			"sns_qq_id": "0",
			"sns_qq_avatar": "",
			"sns_qq_data": "",
			"sns_qq_name": "",
			"sns_qq_sex": "",
			"sns_sinawb_id": "1799830700",
			"sns_sinawb_avatar": "http://tva2.sinaimg.cn/crop.0.0.179.179.180/6b473cacjw1e7mgubpk3ej205005074c.jpg",
			"sns_sinawb_data": "{\"followers_count\":\"1002\",\"profile_image_url\":\"http:\\/\\/tva2.sinaimg.cn\\/crop.0.0.179.179.180\\/6b473cacjw1e7mgubpk3ej205005074c.jpg\",\"description\":\"最近迷上了刘昊然董子健||不知道怎么给自己打标签\",\"screen_name\":\"该好友最近没有发春\",\"location\":\"福建 福州\",\"access_token\":\"2.007LunxBz5FSQD23895c8c97n9TSyB\",\"verified\":\"false\",\"gender\":\"0\",\"uid\":\"1799830700\",\"favourites_count\":\"88\",\"statuses_count\":\"7241\",\"friends_count\":\"287\",\"idstr\":\"1799830700\",\"avatar_large\":\"http:\\/\\/tva2.sinaimg.cn\\/crop.0.0.179.179.180\\/6b473cacjw1e7mgubpk3ej205005074c.jpg\"}",
			"sns_sinawb_name": "该好友最近没有发春",
			"sns_sinawb_sex": "0",
			"sns_bdyts_appid": "1185357",
			"sns_bdyts_userId": "820974229536475925",
			"sns_bdyts_channelId": "4379381018260436707",
			"sns_bdyts_requestId": "1277803016",
			"sns_bdyts_data": "{\"errorCode\":0,\"appid\":\"1185357\",\"requestId\":\"1277803016\",\"channelId\":\"4379381018260436707\",\"userId\":\"820974229536475925\"}",
			"stat_follow": "0",
			"stat_befollow": "0",
			"stat_belike": "25942",
			"stat_new": "0",
			"stat_work": "22",
			"stat_guess_pass": "0",
			"stat_becai": "0",
			"stat_beshare": "295",
			"stat_bekeep": "1726",
			"stat_beplayed": "2462077",
			"stat_user_commnet": "31",
			"stat_user_new_unread": "1",
			"ver": "64",
			"limit_grapherlist": "0",
			"ip": "120.35.62.141",
			"like_tag_name": "剧情",
			"phone_id": "07d42d7a80e724a8273e427a3af3fa76"
				}
		}
}

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
	
	$json['scriptJson'] = array();
	
	/*$jsonscript['JsonScript.data'] = array(
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
	);*/
	
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

			
			
			//用户信息
			$query = 'SELECT * FROM `client_user` WHERE `id`='.$work['user_id'].';';
			$result = mysqli_query($connection,$query);
			if($result && mysqli_num_rows($result)>0){
				//找到 
				$user = mysqli_fetch_assoc($result);
				
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
					$i = 0;
					while($i<mysqli_num_rows($result)){
						$page = mysqli_fetch_assoc($result);
						$json['scriptJson']['scriptItem'][] = array(
							"image" => 'http://imgs4.graphmovie.com/gms_works'.$page['url'],
							"intro" => $page['intro']
						);
						
						$i++;
						
					}
					
					$json['scriptJson']['lastEditPage'] = mysqli_num_rows($result);
					$json['scriptJson']['workInfo'] = $work;
					$json['scriptJson']['userInfo'] = $user;
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