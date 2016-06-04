<?php
/**
* @api {post} /gmspanel/interface/zh-cn/3.1/PCM_W_CreatWithDbForInput.php 通过豆瓣URL新建一部作品(仅供内部接口调用)
* @apiPermission pxseven
* @apiVersion 0.1.0
* @apiName CreatWithDbForInput
* @apiGroup Work
* @apiSampleRequest http://ser3.graphmovie.com/gmspanel/interface/zh-cn/3.1/PCM_W_CreatWithDbForInput.php

* @apiDescription 用户输入一个豆瓣电影URL点击关联后,请求此接口来生成作品ID

* @apiParam (POST) {String} [pk=""] 用户PC的机器码(MAC地址)
* @apiParam (POST) {Integer} [v=0] 用户GraphMovieStudios的内部版本号
* @apiParam (POST) {String} userid 用户的userid，必须为登录用户才可访问此接口
* @apiParam (POST) {String} token 用户的登录时返回的token,用于验证用户是否合法
* @apiParam (POST) {String} dburl 用户在关联中输入的豆瓣URL,接口会验证URL是否合法

*
* @apiSuccess (ResponseJSON) {Integer} status 接口响应状态（0-失败,1-成功,2-需要弹出提示框,提示desc内容）.
* @apiSuccess (ResponseJSON) {Float} usetime 接口响应时间,调试用.
* @apiSuccess (ResponseJSON) {String} error 接口响应出错时的错误描述.
* @apiSuccess (ResponseJSON) {String} debug 接口响应出错时的过程描述,调试用.
* @apiSuccess (ResponseJSON) {String} desc status=2时需要弹窗提示此内容.
* @apiSuccess (ResponseJSON) {String} workid 生成的workid加密串,为32位MD5.
* @apiSuccess (ResponseJSON) {Integer} ingcount 当前正在制作这部作品的其他作者有几个.
* @apiSuccess (ResponseJSON) {Integer} okcount 当前已经上线的这部影片的图解有几部.
* @apiSuccess (ResponseJSON) {String} poptitle 可以用作提示窗的标题,如:"已有2部该片的图解正在创作",为什么不是"2名图解师正在制作..."的原因是:剧集的缘故同一个用户可以占同一个豆瓣ID的电影多次.
* @apiSuccess (ResponseJSON) {String} popdesc 可以用作提示窗的正文.
* @apiSuccess (ResponseJSON) {Array[]} ingmsg 若不想采用<code>popdesc</code>来展示其他作者信息,可用该结构化数据来展示.
* @apiSuccess (ResponseJSON) {Object} msg_ing 信息的结构体，在JSON中是不存在此key的，这里只是为了说明，具体可参见下面Success-Response中的示例.
* @apiSuccess (ResponseJSON) {String} msg_ing.ingid 记录ID，保证各个msg结构此键值不同，客户端使用.
* @apiSuccess (ResponseJSON) {String} msg_ing.userid 作者信息中的用户ID.
* @apiSuccess (ResponseJSON) {String} msg_ing.nickname 作者信息中的昵称.
* @apiSuccess (ResponseJSON) {String} msg_ing.avatar 作者信息中的头像URL.
* @apiSuccess (ResponseJSON) {String} msg_ing.addtime 作者开始创作该部图解的时间,如"1月13日".
* @apiSuccess (ResponseJSON) {Integer} msg_ing.percent 作者已经完成的进度[0-100],如40,65,99.
* @apiSuccess (ResponseJSON) {Array[]} okmsg 若不想采用<code>popdesc</code>来展示其他作者信息,可用该结构化数据来展示.
* @apiSuccess (ResponseJSON) {Object} msg_ok 信息的结构体，在JSON中是不存在此key的，这里只是为了说明，具体可参见下面Success-Response中的示例.
* @apiSuccess (ResponseJSON) {String} msg_ok.okid 记录ID，保证各个msg结构此键值不同，客户端使用.
* @apiSuccess (ResponseJSON) {String} msg_ok.userid 作者信息中的用户ID.
* @apiSuccess (ResponseJSON) {String} msg_ok.nickname 作者信息中的昵称.
* @apiSuccess (ResponseJSON) {String} msg_ok.avatar 作者信息中的头像URL.
* @apiSuccess (ResponseJSON) {Integer} msg_ok.workname 作者已上线作品的名称.
* @apiSuccess (ResponseJSON) {String} msg_ok.played 作者已上线作品的播放数.
* @apiSuccess (ResponseJSON) {String} msg_ok.like 作者已上线作品的点赞数.

*
* @apiSuccessExample Success-Response[提交成功]:

*     {
*       "status": 1,
*       "usetime": 0.0024,
*       "error": "",
*       "debug": "",
*       "desc": "",
*       "workid": "d6a2e3c51e2434ed72ca2e8ecfe9a34c",
*		"ingcount": 2,
*		"poptitle": "该片图解[1部已上线/2部正在创作]:",
*		"popdesc": "可爱小炒肉自1月10日开始图解,目前进度7%;灵台无计自1月15日开始图解,目前进度2%;已上线的图解有1部:静默森林作品-《泰坦尼克号》,11.2万人阅读/6335人喜欢;",
*		"ingmsg": [
*					{
*						"ingid": "6a9bb2e2a33c690fee7142e46e445679",
*						"userid": "9999999H",
*						"nickname": "佳怡生活",
*						"avatar": "http://tp1.sinaimg.cn/1750477504/180/40036623679/1",
*						"addtime": "1月13日",
*						"percent": 65
*					}
*					...更多{}
*				]
*		"okmsg": [
*					{
*						"okid": "367e2d465aa31e2ab261c8cccb106d31",
*						"userid": "9999999H",
*						"nickname": "静默森林",
*						"avatar": "http://tp1.sinaimg.cn/1750477504/180/40036623679/1",
*						"workname": "泰坦尼克号",
*						"played": "11.2万",
*						"like": "6335",
*					}
*					...更多{}
*				]
*     }

*
* @apiError PostError 请求的参数缺失或者参数格式错误.
* @apiError ServerError 服务器状态异常.
* @apiError TokenTimeOut 用户会话超时或非法.
* @apiError ErrorDoubanURL 豆瓣链接错误.

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

*     ErrorDoubanURL:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "ErrorDoubanURL",
*       "debug": "",
*       "desc": "输入的豆瓣链接有误,请更正后再试"
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
	$json["query"] = "";

	//20160304 内测结束 
	/*------------------------------------------------------------------------------------------------------------------*/
	/*------------------------------------------------------------------------------------------------------------------*/
	/*------------------------------------------------------------------------------------------------------------------*/
	/*$json['status']= 2;
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
	$post_dburl = '';
	
	//获取Header
	//模拟请求采用的是request payload 发上来的参数
	$request_body = file_get_contents('php://input');
	$data = json_decode($request_body);
	if($data && count($data)>0 && (!isset($_POST) || count($_POST)==0) && isset($data->dburl) && strlen($data->dburl)>0 && isset($data->userid) && strlen($data->userid)>0){
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
		
		$post_dburl = htmlspecialchars(addslashes(trim($data->dburl)));
		
	}else if(
		isset($_POST['dburl']) && strlen($_POST['dburl'])>0 &&
		isset($_POST['userid']) && strlen($_POST['userid'])>0
		){
		//获取参数
		if(isset($_POST['pk']) && strlen($_POST['pk'])>0){$post_pk = htmlspecialchars(addslashes($_POST['pk']));}
		if(isset($_POST['v'])){$post_v = htmlspecialchars(addslashes($_POST['v']));}
		if(isset($_POST['userid'])){$post_userid = userIdKeyDecode(htmlspecialchars(addslashes($_POST['userid'])));}
		if(isset($_POST['token'])){$post_token = htmlspecialchars(addslashes($_POST['token']));}
		$post_dburl = htmlspecialchars(addslashes(trim($_POST['dburl'])));
		
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
	//检测豆瓣URL是否合法
	//是否是豆瓣subject链接
	//支持两种格式:
	//统一ID的格式:http://www.douban.com/subject/10734267/
	//电影ID的格式:http://movie.douban.com/subject/20326665/?from=showing 或//https://movie.douban.com/subject/25777636/?from=showing
	//书本ID的格式:http://book.douban.com/subject/10734267/
	//豆瓣各个实体的ID不重复
	//
	
	$subject_rest = '';
	$subject_id = '';
	$subject_preurl = 'http://www.douban.com/';
	//echo $post_dburl;exit;
	if(strrpos($post_dburl, 'http://www.douban.com/subject/')){
		$subect_cut = explode('http://www.douban.com/subject/',$post_dburl);
		$subject_rest = $subect_cut[1];
		$subject_preurl = 'http://www.douban.com/';
		
	}else if(strrpos($post_dburl, 'http://movie.douban.com/subject/')){
		$subect_cut = explode('http://movie.douban.com/subject/',$post_dburl);
		$subject_rest = $subect_cut[1];
		$subject_preurl = 'http://movie.douban.com/';
		
	}else if(strrpos($post_dburl, 'http://book.douban.com/subject/')){
		$subect_cut = explode('http://book.douban.com/subject/',$post_dburl);
		$subject_rest = $subect_cut[1];
		$subject_preurl = 'http://book.douban.com/';
		
	}else if(strrpos($post_dburl, 'book.douban.com/subject/')){
		$subect_cut = explode('book.douban.com/subject/',$post_dburl);
		$subject_rest = $subect_cut[1];
		$subject_preurl = 'http://book.douban.com/';
		
	}else if(strrpos($post_dburl, 'movie.douban.com/subject/')){
		$subect_cut = explode('movie.douban.com/subject/',$post_dburl);
		$subject_rest = $subect_cut[1];
		$subject_preurl = 'http://movie.douban.com/';
		
	}else if(strrpos($post_dburl, 'www.douban.com/subject/')){
		$subect_cut = explode('www.douban.com/subject/',$post_dburl);
		$subject_rest = $subect_cut[1];
		$subject_preurl = 'http://www.douban.com/';
		
	}else if(strrpos($post_dburl, 'https://www.douban.com/subject/')){
		$subect_cut = explode('https://www.douban.com/subject/',$post_dburl);
		$subject_rest = $subect_cut[1];
		$subject_preurl = 'http://www.douban.com/';
		
	}else if(strrpos($post_dburl, 'https://movie.douban.com/subject/')){
		$subect_cut = explode('https://movie.douban.com/subject/',$post_dburl);
		$subject_rest = $subect_cut[1];
		$subject_preurl = 'http://movie.douban.com/';
		
	}else if(strrpos($post_dburl, 'https://book.douban.com/subject/')){
		$subect_cut = explode('https://book.douban.com/subject/',$post_dburl);
		$subject_rest = $subect_cut[1];
		$subject_preurl = 'http://book.douban.com/';
		
	}else{
		//还不对就不会对了
		//关闭连接
		if($connection)mysqli_close($connection);
		$json['status']= 2;
		$json['usetime'] = endtime($start_time);
		$json['error'] = 'ErrorDoubanURL';
		$json['desc'] = "输入的豆瓣链接有误,请更正后再试";
		$json_code = json_encode($json);
		echo $json_code;
		die();
	}
	
	$subject_rest = explode('/',$subject_rest);
	$subject_id = $subject_rest[0];
	
	//是豆瓣的ID了 再来请求
	$dburl = $subject_preurl.'subject/'.$subject_id.'/';
	
	//请求是否可以请求通
	/*
	Array ( [0] => HTTP/1.1 404 Not Found [1] => Server: dae [2] => Date: Fri, 15 Jan 2016 09:42:49 GMT [3] => Content-Type: text/html; charset=utf-8 [4] => Content-Length: 33076 [5] => Connection: close [6] => Expires: -1 )
	Array ( [0] => HTTP/1.1 403 Forbidden [1] => Server: dae [2] => Date: Fri, 15 Jan 2016 09:48:47 GMT [3] => Content-Type: text/html [4] => Content-Length: 160 [5] => Connection: close )
	*/
	$db_html = file_get_contents($dburl);
	if(strrpos($http_response_header[0],'404') || strrpos($http_response_header[0],'403')){
		//错误的URL不能请求
		//关闭连接
		if($connection)mysqli_close($connection);
		$json['status']= 2;
		$json['usetime'] = endtime($start_time);
		$json['error'] = 'ErrorDoubanURL';
		$json['desc'] = "输入的豆瓣链接有误,请更正后再试]";
		$json_code = json_encode($json);
		echo $json_code;
		die();
	};
	
	//OK豆瓣验证通过
	
	//检测电影数据库是否存在此ID 没有就存入待抓取队列 补完电影数据库
	//目前mdb_film中没有一条记录有豆瓣的KEY 但是这里当做有来处理 
	//未来抓取回来的豆瓣信息同 上线前关联豆瓣时光的人工生成信息对比 如有找到 则补完在相关的时光key下 如未找到 则新插入mdb_film
	$query = 'SELECT * FROM `mdb_film` WHERE `douban_key`=\''.$subject_id.'\';';
	$result = mysqli_query($connection,$query);
	if($result){
		if(mysqli_num_rows($result)==0){
			//没找到 
			//查询是否已经有这条任务了
			$query = 'SELECT * FROM `mdb_task` WHERE `task_type`=1 AND `task_data` LIKE \'%"dbkey":"'.$subject_id.'"%\';';
			$result = mysqli_query($connection,$query);
			if($result){
				if(mysqli_num_rows($result)==0){
					$taskdata = array(
						"dbkey"=>$subject_id,
						"dburl"=>$dburl
					);
					//记录入抓取任务表
					$query = 'INSERT INTO mdb_task(task_type,task_data,status,add_time,take_time,exe_time) VALUES ('.
								 '1,'.
								 '\''.json_encode($taskdata).'\','.
								 '0,'.
								 'now(),'.
								 '\''.'\','.
								 '\''.'\''.
					');';
					mysqli_query($connection, $query);
				}
			}
		}
	}
	
	//查询这个作者是否已经注册过这个作品了
	//这里不要求一个作者只能占一个ID一次 因为有剧集
	
	//检查是否有人在做这个影片
	$res_ingcount = 0;
	$poptitle = '当前没有人在图解此影片哟！';
	$popdesc = '';
	$ingmsg = array();
	$query = 'SELECT * FROM `pcmaker_work` WHERE `db_id`=\''.$subject_id.'\' AND `state`=1;';
	$result = mysqli_query($connection,$query);
	if($result){
		if(mysqli_num_rows($result)>0){
			$i=0;
			while($i<mysqli_num_rows($result)){
				$record = mysqli_fetch_assoc($result);
				//查作者信息
				$query = 'SELECT * FROM `client_user` WHERE `id`='.$record['user_id'].';';
				$user_result = mysqli_query($connection,$query);
				if($user_result && mysqli_num_rows($user_result)>0){
					$user = mysqli_fetch_assoc($user_result);
					
					$msg = array(
						"ingid" => $record['work_key'],
						"userid" => (string)userIdKeyEncode($user['id']),
						"nickname" => (string)$user['name'],
						"avatar" => $user['avatar']?$user['avatar']:'http://imgs4.graphmovie.com/appimage/app_default_avatar.jpg',
						"addtime" => (string)getDateStyle(strtotime($record['add_time'])),
						"percent" => $record['progress']
					);
					
					$popdesc .= $user['name'].' 自'.(string)getDateStyle(strtotime($record['add_time'])).'开始图解,目前进度'.$record['progress'].'%;';
					$res_ingcount++;
					$ingmsg[count($ingmsg)] = $msg;
					
				}
				$i++;
				
			}
		}
	}
	
	if($res_ingcount==0){
		$poptitle = '当前没有人在图解此影片哟！';
		$popdesc = '数百万小伙伴翘首企盼，还在等什么(ง •̀_•́)ง';
		$ingmsg = array();
	}else{
		$poptitle = '已有'.$res_ingcount.'部该片的图解正在创作:';	
	}
	
	//检查此作品是否已经有人上线
	//mdb_movie_v_link
	$res_okcount = 0;
	$okdesc = '';
	$okmsg = array();
	$query = 'SELECT * FROM `mdb_movie_v_link` WHERE `link_type`=1 AND `link_url` LIKE \'%douban.com/subject/'.$subject_id.'/%\';';
	$result = mysqli_query($connection,$query);
	if($result){
		if(mysqli_num_rows($result)>0){
			$i=0;
			while($i<mysqli_num_rows($result)){
				$popdesc .= '已上线的图解有'.mysqli_num_rows($result).'部:';
				
				$record = mysqli_fetch_assoc($result);
				//查询movie信息
				$query = 'SELECT `name`,`played`,`ding`,`grapher` FROM `movie` WHERE `id`='.$record['movie_id'].' AND `open`=1;';
				$movie_result = mysqli_query($connection,$query);
				if($movie_result && mysqli_num_rows($movie_result)>0){
					$movie = mysqli_fetch_assoc($movie_result);
					$graphers = explode(',',$movie['grapher']);
					//再查user
					//查作者信息
					$query = 'SELECT * FROM `client_user` WHERE `id`='.$graphers[0].';';
					$user_result = mysqli_query($connection,$query);
					if($user_result && mysqli_num_rows($user_result)>0){
						$user = mysqli_fetch_assoc($user_result);
						
						$msg = array(
							"okid" => md5(userIdKeyEncode($record['movie_id'])),
							"userid" => (string)userIdKeyEncode($user['id']),
							"nickname" => (string)$user['name'],
							"avatar" => $user['avatar']?$user['avatar']:'http://imgs4.graphmovie.com/appimage/app_default_avatar.jpg',
							"workname" => $movie['name'],
							"played" => (string)number_2_numberFont($movie['played']),
							"like" => (string)number_2_numberFont($movie['ding'])
						);
						
						$popdesc .= $user['name'].'作品-《'.$movie['name'].'》,'.(string)number_2_numberFont($movie['played']).'人阅读/'.(string)number_2_numberFont($movie['ding']).'人喜欢;';
						$res_okcount++;
						$okmsg[count($okmsg)] = $msg;
						
					}
				}
				$i++;
				
			}
		}else{
			//没有已上线作品	
		}
	}
	
	$poptitle = '该片图解['.$res_okcount.'部已上线/'.$res_ingcount.'部正在创作]:';
	
	
	//检查该用户是否多次提交此作品
	//无需查询
	
	//INSERT 生成workid
	$workkey = $post_userid.$post_token.time().rand(10000, 100000);
	$workkey = md5($workkey);
	$query = 'INSERT INTO pcmaker_work(
							work_key,
							user_id,
							title,
							sub_title,
							editor_note,
							author,
							actor,
							intro,
							showtime,
							zone,
							score,
							bpic_id,
							spic_id,
							firstpage_id,
							jian,
							tags,
							tags_text,
							season_id,
							act_id,
							page_count,
							size,
							state,
							movie_id,
							progress,
							save_path,
							db_url,
							db_id,
							film_id,
							add_time,
							update_time,
							bpic_md5,
							spic_md5,
							firstpage_md5,
							tv_type,
							creat_time,
							submit_time
							) VALUES ('.
				'\''.$workkey.'\','.
				 $post_userid.','.
				 '\'\',\'\',\'\',\'\',\'\',\'\',\'\',\'\',7.0,0,0,0,0,\'\',\'\',0,0,0,0,1,0,0,\'\',\''.$dburl.'\',\''.$subject_id.'\',0,now(),now(),\'\',\'\',\'\',0,now(),now()'.
	');';
	$result = mysqli_query($connection, $query);
	
	if(!$result){
		$json['workid'] = '0';
	}else{
		$json['workid'] = $workkey;
	}
	
	$json['ingcount'] = $res_ingcount;
	$json['okcount'] = $res_okcount;
	$json['poptitle'] = $poptitle;
	$json['popdesc'] = $popdesc;
	$json['ingmsg'] = $ingmsg;
	$json['okmsg'] = $okmsg;
	
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