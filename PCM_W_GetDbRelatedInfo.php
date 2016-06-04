<?php
/**
* @api {post} /gmspanel/interface/zh-cn/3.1/PCM_W_GetDbRelatedInfo.php 通过豆瓣URL获取相关作品信息
* @apiPermission yongge
* @apiVersion 0.1.0
* @apiName GetDbRelatedInfo
* @apiGroup Work
* @apiSampleRequest http://ser3.graphmovie.com/gmspanel/interface/zh-cn/3.1/PCM_W_GetDbRelatedInfo.php

* @apiDescription 通过豆瓣电影URL获取相关作品的信息

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
* @apiSuccess (ResponseJSON) {Object} dbmsg 此豆瓣链接关联的电影信息.
* @apiSuccess (ResponseJSON) {Array[]} dbmsg 信息的结构体，具体可参见下面Success-Response中的示例.
 * @apiSuccess (ResponseJSON) {String} dbmsg.name 豆瓣电影名,
* @apiSuccess (ResponseJSON) {String} dbmsg.score 豆瓣电影评分,
* @apiSuccess (ResponseJSON) {String} dbmsg.author 豆瓣电影导演,
* @apiSuccess (ResponseJSON) {String} dbmsg.actors 豆瓣电影演员,
* @apiSuccess (ResponseJSON) {String} dbmsg.tags 豆瓣电影类型,
* @apiSuccess (ResponseJSON) {String} dbmsg.region 豆瓣电影上映地区,
* @apiSuccess (ResponseJSON) {String} dbmsg.pubday 豆瓣电影上映时间,
* @apiSuccess (ResponseJSON) {String} dbmsg.coverimg 豆瓣电影封面图,
* @apiSuccess (ResponseJSON) {Integer} ingcount 当前正在制作这部作品的其他作者有几个.
* @apiSuccess (ResponseJSON) {Integer} okcount 当前已经上线的这部影片的图解有几部.
* @apiSuccess (ResponseJSON) {Integer} weicount 当前已经上线的这部影片的微图解有几部（目前没有统计微图解的数量，暂时返回0）.
* @apiSuccess (ResponseJSON) {Integer} waitingcount 当前作品期待人数（目前没有此功能，返回10000-100000之间的随机数）.
* @apiSuccess (ResponseJSON) {Integer} contributecount 贡献图库的图解师人数（目前没有此功能，返回0）.
* @apiSuccess (ResponseJSON) {Array[]} ingmsg 信息的结构体，具体可参见下面Success-Response中的示例.
* @apiSuccess (ResponseJSON) {String} ingmsg.ingid 记录ID，保证各个msg结构此键值不同，客户端使用.
* @apiSuccess (ResponseJSON) {String} ingmsg.userid 作者信息中的用户ID.
* @apiSuccess (ResponseJSON) {String} ingmsg.nickname 作者信息中的昵称.
* @apiSuccess (ResponseJSON) {String} ingmsg.avatar 作者信息中的头像URL.
* @apiSuccess (ResponseJSON) {String} ingmsg.update_time 作者最后制作该图解的时间,如"9小时前".
* @apiSuccess (ResponseJSON) {Integer} ingmsg.percent 作者已经完成的进度[0-100],如40,65,99.
* @apiSuccess (ResponseJSON) {Array[]} okmsg 信息的结构体，具体可参见下面Success-Response中的示例.
* @apiSuccess (ResponseJSON) {String} okmsg.okid 记录ID，保证各个msg结构此键值不同，客户端使用.
* @apiSuccess (ResponseJSON) {String} okmsg.movie_id 记录ID，保证各个msg结构此键值不同，客户端使用.
* @apiSuccess (ResponseJSON) {String} okmsg.userid 作者信息中的用户ID.
* @apiSuccess (ResponseJSON) {String} okmsg.nickname 作者信息中的昵称.
* @apiSuccess (ResponseJSON) {String} okmsg.coverimg 作品封面图的URL.
* @apiSuccess (ResponseJSON) {Integer} okmsg.workname 作者已上线作品的名称.
* @apiSuccess (ResponseJSON) {Integer} okmsg.worksubname 作者已上线作品的副标题.
* @apiSuccess (ResponseJSON) {Integer} okmsg.workscore 作者已上线作品的分数.
* @apiSuccess (ResponseJSON) {Integer} okmsg.workscorenum 作者已上线作品参与评分的人数.
* @apiSuccess (ResponseJSON) {String} first_finished_reword 首个完成被官方收录的作品可获得的金币数.

*
* @apiSuccessExample Success-Response[提交成功]:

*	{
*	"status": 1,
*	"usetime": "2.03422",
*	"error": "",
*	"debug": "na",
*	"desc": "",
* 	"query": "",
*	"ingcount": 1,
*	"okcount": 1,
*	"weicount": 0,
*	"contributecount": 0,
*   "first_finished_reword": 500,
*	"dbmsg": {
*		"name": "魔宫魅影",
*		"score": "",
*		"author": "叶伟民",
*		"actors": "林心如/杨祐宁/任达华/黄幻/景岗山/林江国/孟瑶/张子枫/黄磊/李菁/何云伟",
*		"tags": "惊悚",
*		"region": "中国大陆 / 香港",
*		"pubday": "2016-04-29(中国大陆)",
*		"coverimg": "https://img3.doubanio.com/view/movie_poster_cover/spst/public/p2331558412.jpg"
*		},

*	"ingmsg": [
*		{
*		"ingid": "93d48ab9ab3a6a55276c0a84db887466",
*		"nickname": "图解电影-鞭基部",
*		"avatar": "http://imgs4.graphmovie.com/appimage/bj_1.jpg",
*		"update_time": "9小时前",
*		"percent": "0"
*		}
*		],
*	"okmsg": [
*		{
*		"okid": "f540eda2cd172baf51a4b5e80ad7162e",
*		"nickname": "撇撇酱",
*		"coverimg": "http://s1.dwstatic.com/group1/M00/C9/D3/c9d3b5829c6c401da8f20626b12f46768600.jpg",
*		"workname": "撸片室の《谁的青春不迷茫》",
*		"worksubname": "我们还年轻，年轻就可以失败。",
*		"workscore": "0",
*		"workscorenum": "1"
*		}
*	],
*	}


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
	require_once(dirname(__FILE__).'/'.'inc/post.methods.inc.php');
	
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


//初始化返回结果（c#不接受null）
$json['dbmsg']='';
$db_info = array();
$db_info['name'] = '';
$db_info['score'] ='';
$db_info['author'] ='';
$db_info['actors'] = '';
$db_info['tags'] = '';
$db_info['region'] = '';
$db_info['pubday'] = '';
$db_info['coverimg']='';

$json['ingcount']='';
$json['okcount']=0;
$json['weicount']=0;
$json['waitingcount']=0;
$json['contributecount']=0;

$ingmsg = array();
$json['ingmsg']='';



$json['okmsg']='';

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
	/*$db_html = file_get_contents($dburl);
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
	};*/
	
	//OK豆瓣验证通过
$dbquery = 'SELECT * FROM `mdb_douban_movie` WHERE `db_id` = \''.$subject_id.'\';';
$dbresult =  mysqli_query($connection,$dbquery);
if($dbresult){
	if(mysqli_num_rows($dbresult)==0){
		//本地没有就抓取
		//校验豆瓣链接
		$db_header = get_headers($dburl);
		if(!$db_header){
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
		}

		//调用抓取接口
		//适配本地调试处理
		if($_SERVER['HTTP_HOST']=='localhost'){
			$response_json = request_by_curl('http://localhost/gms/PCM_OFC_DoubanMsg.php',$post_string);
		}else{
			$response_json = request_by_curl('http://ser3.graphmovie.com/gmspanel/interface/zh-cn/3.1/PCM_OFC_DoubanMsg.php',$post_string);
		}

		$json_struct = @json_decode($response_json);

		if($json_struct && isset($json_struct->status)){
			if($json_struct->status==1){
				//豆瓣电影名
				$db_info['name'] = $json_struct->dbmsg->title;

				//评分
				$db_info['score'] =$json_struct->dbmsg->rating;

				//导演
				foreach($json_struct->dbmsg->directors as $v){
					$db_info['author'] .= $v->name.'/';
				}

				$db_info['author'] =rtrim($db_info['author'],'/');

				//主演
				foreach($json_struct->dbmsg->actors as $val){
					$db_info['actors'] .= $val->name.'/';
				}
				$db_info['actors'] = rtrim($db_info['actors'],'/');

				//类型
				foreach($json_struct->dbmsg->tags as $value){
					$db_info['tags'] .= $value.'|';
				}
				$db_info['tags'] = rtrim($db_info['tags'],'|');

				//区域
				$db_info['region'] = $json_struct->dbmsg->zone;

				//上映时间
				$db_info['pubday'] = $json_struct->dbmsg->pubday[0];

				//封面图
				$db_info['coverimg'] = $json_struct->dbmsg->cover;
				$json['dbmsg'] = $db_info;

			}
		}

	}else{
		$db_local = mysqli_fetch_assoc($dbresult);
		$db_info['name'] = is_null($db_local['title'])?'':$db_local['title'];
		$db_info['author'] = is_null($db_local['directors_str'])?'': str_replace(',','/',$db_local['directors_str']);
		$db_info['actors'] = is_null($db_local['actors_str'])?'': str_replace(',','/',$db_local['actors_str']);
		$db_info['score'] = is_null($db_local['score'])?'': $db_local['score'];
		$db_info['tags'] = is_null($db_local['genres_str'])?'': str_replace(',','|',$db_local['genres_str']);
		$db_info['region'] = is_null($db_local['zone'])?'':$db_local['zone'];
		$db_info['pubday'] = is_null($db_local['showtimes_str'])?'': substr($db_local['showtimes_str'],0,4);
		$db_info['coverimg'] = is_null($db_local['face_65x100'])?'':str_replace('img5','img3',str_replace('ipst','spst',$db_local['face_65x100']));
		$json['dbmsg'] = $db_info;

	}
}
//获取该豆瓣关联的电影信息
$post_string = 'ck='.strtolower(md5($subject_id.'graphmoviestudiosapi')).'&subid='.$subject_id;
//echo $post_string;


	
	//检测电影数据库是否存在此ID 没有就存入待抓取队列 补完电影数据库
	//目前mdb_film中没有一条记录有豆瓣的KEY 但是这里当做有来处理 
	//未来抓取回来的豆瓣信息同 上线前关联豆瓣时光的人工生成信息对比 如有找到 则补完在相关的时光key下 如未找到 则新插入mdb_film
	/*$query = 'SELECT * FROM `mdb_film` WHERE `douban_key`=\''.$subject_id.'\';';
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
	}*/
	
	//查询这个作者是否已经注册过这个作品了
	//这里不要求一个作者只能占一个ID一次 因为有剧集
	
	//检查是否有人在做这个影片
	$res_ingcount = 0;
	$poptitle = '当前没有人在图解此影片哟！';
	$popdesc = '';
	$query = 'SELECT *, count(`user_id`) FROM `pcmaker_work` WHERE `db_id`=\''.$subject_id.'\' AND `state`=1 GROUP BY `user_id`;';
	//只需取五条且user_id不同
	$query1 = 'SELECT *,count(`user_id`) FROM `pcmaker_work` WHERE `db_id`=\''.$subject_id.'\' AND `state`=1 GROUP BY `user_id` LIMIT 5;';

	$result = mysqli_query($connection,$query);
	//$json['query'] = $query1;
	//查正在图解的总人数
	$ingcount =0;
	if($result){
		$ingcount = mysqli_num_rows($result);
	}

	//查五条数据
	$result1 = mysqli_query($connection,$query1);

	if($result1){
		if(mysqli_num_rows($result1)>0){
			$i=0;

			while($i<mysqli_num_rows($result1)){
				$record = mysqli_fetch_assoc($result1);
				//查作者信息
				$query = 'SELECT * FROM `client_user` WHERE `id`='.$record['user_id'].';';
				$user_result = mysqli_query($connection,$query);
				if($user_result && mysqli_num_rows($user_result)>0){
					$user = mysqli_fetch_assoc($user_result);

					$msg = array(
						"ingid" => is_null($record['work_key']) ? '' : $record['work_key'],
						//"userid" => is_null((string)userIdKeyEncode($user['id'])) ? '' : (string)userIdKeyEncode($user['id']),
						"nickname" => is_null((string)$user['name']) ? '' : (string)$user['name'],
						"avatar" => $user['avatar']?$user['avatar']:'http://imgs4.graphmovie.com/appimage/app_default_avatar.jpg',
						"update_time" => (string)getDateStyle(strtotime($record['submit_time'])),
						"percent" => is_null($record['progress']) ? '' : $record['progress']
					);
					$ingmsg[] = $msg;
				}
				$i++;
				
			}
		}
	}

	//微图解作品数
	$weicount = 0;
	
	//检查此作品是否已经有人上线
	//mdb_movie_v_link
	$res_okcount = 0;
	$okdesc = '';
	$okmsg = array();
	$msg['okid']='';
	$msg['nickname']='';
	$msg['coverimg']='';
	$msg['worksubname']='';
	$msg['workscore']='';
	$msg['workscorenum']='';





	$query = 'SELECT * FROM `mdb_movie_v_link` WHERE `link_type`=1 AND `link_url` LIKE \'%douban.com/subject/'.$subject_id.'/%\';';
	//只查2条记录用于界面显示
	$query2 = 'SELECT * FROM `mdb_movie_v_link` WHERE `link_type`=1 AND `link_url` LIKE \'%douban.com/subject/'.$subject_id.'/%\' ORDER BY `add_time` LIMIT 2;';
	$result = mysqli_query($connection,$query);
	$result2 = mysqli_query($connection,$query2);
	//已上线作品数
	$okcount = mysqli_num_rows($result);
	if($result2){
		if(mysqli_num_rows($result2)>0){
			$i=0;
			while($i<mysqli_num_rows($result2)){

				$record = mysqli_fetch_assoc($result2);
				//查询movie信息
				$query = 'SELECT `name`,`sub_title`,`bpic`,`score`,`played`,`ding`,`grapher` FROM `movie` WHERE `id`='.$record['movie_id'].' AND `open`=1;';
				$movie_result = mysqli_query($connection,$query);
				if($movie_result && mysqli_num_rows($movie_result)>0){
					$movie = mysqli_fetch_assoc($movie_result);
					$graphers = explode(',',$movie['grapher']);
					//再查user
					//查作者信息
					$query = 'SELECT * FROM `client_user` WHERE `id`='.$graphers[0].';';
					$user_result = mysqli_query($connection,$query);

					//查评分人数（movie_v_gmscore_record）
					$score_query = 'SELECT *,avg(`score_value`) AS score,count(1) AS num FROM `movie_v_gmscore_record` WHERE `movie_id`='.$record['movie_id'].' AND `score_type`=1;';
					$score_result = mysqli_query($connection,$score_query);
					//$score_num = mysqli_num_rows($score_result);

					$score = mysqli_fetch_assoc($score_result);
					if($score['num']){
						$gmscore = is_null($score['score'])? '':round($score['score'],1);
					}else{
						$gmscore = 0;
					}

					if($user_result && mysqli_num_rows($user_result)>0){
						$user = mysqli_fetch_assoc($user_result);
						$msg = array(
							"okid" => md5(userIdKeyEncode($record['movie_id'])),
							'movie_id'=>$record['movie_id'],
							"userid" => is_null((string)userIdKeyEncode($user['id'])) ? '': (string)userIdKeyEncode($user['id']),
							"nickname" => (string)$user['name'],
							//"avatar" => $user['avatar']?$user['avatar']: 'http://imgs4.graphmovie.com/appimage/app_default_avatar.jpg',
							"coverimg" => is_null($movie['bpic'])? '':'http://avatar.graphmovie.com/boo/'.$movie['bpic'],
							"workname" => is_null($movie['name'])? '':$movie['name'],
							"worksubname" => is_null($movie['sub_title']) ? '':$movie['sub_title'],
							"workscore" => $gmscore,
							"workscorenum" => (string)number_2_numberFont($score['num']),
							//"played" => (string)number_2_numberFont($movie['played']),
							//"like" => (string)number_2_numberFont($movie['ding'])
						);

						$okmsg[] = $msg;
						
						//$popdesc .= $user['name'].'作品-《'.$movie['name'].'》,'.(string)number_2_numberFont($movie['played']).'人阅读/'.(string)number_2_numberFont($movie['ding']).'人喜欢;';
						//$res_okcount++;
						//$okmsg[count($okmsg)] = $msg;

					}
				}
				$i++;
				
			}
		}else{
			//没有已上线作品	
		}
	}
	
	//$poptitle = '该片图解['.$res_okcount.'部已上线/'.$res_ingcount.'部正在创作]:';

	$json['ingcount'] = $ingcount;
	$json['okcount'] = $okcount;
	$json['weicount'] = $weicount;
	$json['waitingcount'] = rand(10000,100000);
	//$json['poptitle'] = $poptitle;
	//$json['popdesc'] = $popdesc;
	$json['ingmsg'] = $ingmsg;
	$json['okmsg'] = $okmsg;
	$json['first_finished_reword'] = FIRST_FINISHED_WORK_REWORD;

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