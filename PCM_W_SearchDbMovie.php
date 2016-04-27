<?php
/**
* @api {post} /gmspanel/interface/zh-cn/3.1/PCM_W_SearchDbMovie.php 搜索豆瓣电影
* @apiPermission pxseven
* @apiVersion 0.1.0
* @apiName SearchDbMovie
* @apiGroup Work
* @apiSampleRequest http://ser3.graphmovie.com/gmspanel/interface/zh-cn/3.1/PCM_W_SearchDbMovie.php

* @apiDescription 用户输入一个豆瓣的电影名返回豆瓣电影的列表

* @apiParam (POST) {String} [pk=""] 用户PC的机器码(MAC地址)
* @apiParam (POST) {Integer} [v=0] 用户GraphMovieStudios的内部版本号
* @apiParam (POST) {String} userid 用户的userid，必须为登录用户才可访问此接口
* @apiParam (POST) {String} token 用户的登录时返回的token,用于验证用户是否合法
* @apiParam (POST) {String} keywords 用户在搜索框中输入的电影名
* @apiParam (POST) {Integer} [page=0]  请求的分页页码
* @apiParam (POST) {Integer} [limit=9] 请求的分页单页数目，即page=0&limit=9时，返回第0-8部作品，page=1&limit=9时，返回第9-17部作品

*
* @apiSuccess (ResponseJSON) {Integer} status 接口响应状态（0-失败,1-成功,2-需要弹出提示框,提示desc内容）.
* @apiSuccess (ResponseJSON) {Float} usetime 接口响应时间,调试用.
* @apiSuccess (ResponseJSON) {String} error 接口响应出错时的错误描述.
* @apiSuccess (ResponseJSON) {String} debug 接口响应出错时的过程描述,调试用.
* @apiSuccess (ResponseJSON) {String} desc status=2时需要弹窗提示此内容.
* @apiSuccess (ResponseJSON) {Integer} searchcount 搜索到的结果数.
* @apiSuccess (ResponseJSON) {Integer} page_total 总页码数,一共有多少页.
* @apiSuccess (ResponseJSON) {Integer} page_now 当前页码数.
* @apiSuccess (ResponseJSON) {Integer} page_limit 当前每页展示多少部作品.
* @apiSuccess (ResponseJSON) {Array[]} dbmsg 按照给定的page和limit等参数查出的作品数据集合

*
* @apiSuccessExample Success-Response[提交成功]:

*	{
*	"status": 1,
*	"usetime": "2.03422",
*	"error": "",
*	"debug": "na",
*	"desc": "",
*	"dbmsg": {
*		"name": "谁的青春不迷茫",
*		"rating": "6.5",
*		"directors": [
*		{
*		"celeid": "1324487",
*		"name": "姚婷婷"
*		}
*		],
*		"actors": [
*		{
*		"celeid": "1342133",
*		"name": "白敬亭"
*		},
*		{
*		"celeid": "1355324",
*		"name": "郭姝彤"
*		},更多....
*		],
*		"tags": [
*		"爱情"
*		],
*		"region": "中国大陆",
*		"pubday": [
*		"2016-04-22(中国大陆)"
*		]
*		},
*		"query": "",
*		"ingcount": 1,
*		"okcount": 1,
*		"weicount": 0,
*	"ingmsg": [
*	*	{
*	*	"ingid": "93d48ab9ab3a6a55276c0a84db887466",
*	*	"userid": "9999999O",
*	*	"nickname": "图解电影-鞭基部",
*	*	"avatar": "http://imgs4.graphmovie.com/appimage/bj_1.jpg",
*	*	"update_time": "9小时前",
*	*	"percent": "0"
*	*	}
*		],
*	"okmsg": [
		{
		"okid": "f540eda2cd172baf51a4b5e80ad7162e",
		"userid": "9993Z39W",
		"nickname": "撇撇酱",
		"avatar": "http://ser3.graphmovie.com/gmspanel/appimages/avatars/875782/20160108231835.jpg",
		"workname": "撸片室の《谁的青春不迷茫》",
		"worksubname": "我们还年轻，年轻就可以失败。",
		"workscore": "0",
		"workscorenum": "1"
		}
*	],
*	"first_finished_reword": 500
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
	$post_keywords = '';
	
	//获取Header
	//模拟请求采用的是request payload 发上来的参数
	$request_body = file_get_contents('php://input');
	$data = json_decode($request_body);
	if($data && count($data)>0 && (!isset($_POST) || count($_POST)==0) && isset($data->keywords) && strlen($data->keywords)>0 && isset($data->userid) && strlen($data->userid)>0){
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
		
		$post_keywords = htmlspecialchars(addslashes(trim($data->keywords)));
		$page_now = isset($data->page_now) ? htmlspecialchars(addslashes($data->page_now)) : 0;
		$page_limit = isset($data->page_limit) ? htmlspecialchars(addslashes($data->page_limit)) : 9;
		
	}else if(
		isset($_POST['keywords']) && strlen($_POST['keywords'])>0 &&
		isset($_POST['userid']) && strlen($_POST['userid'])>0&&
		isset($_POST['token']) && strlen($_POST['token'])>0
		){
		//获取参数
		if(isset($_POST['pk']) && strlen($_POST['pk'])>0){$post_pk = htmlspecialchars(addslashes($_POST['pk']));}
		if(isset($_POST['v'])){$post_v = htmlspecialchars(addslashes($_POST['v']));}
		$post_userid = userIdKeyDecode(htmlspecialchars(addslashes($_POST['userid'])));
		$post_token = htmlspecialchars(addslashes($_POST['token']));
		$post_keywords = htmlspecialchars(addslashes(trim($_POST['keywords'])));
		$page_now = isset($_POST['page_now']) ? htmlspecialchars(addslashes($_POST['page_now'])) : 0;
		$page_limit = isset($_POST['page_limit']) ? htmlspecialchars(addslashes($_POST['page_limit'])) : 9;
		
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
$json['searchcount']=0;
$json['page_total']= 0;
$json['page_now']= $page_now;
$json['page_limit']= $page_limit;






	//OK TOKEN 合法
	//搜索mdb_douban_movie表，按标题搜索

	$query = 'SELECT * FROM `mdb_douban_movie` WHERE `title` LIKE \'%'.$post_keywords.'%\';';

	//分页查询
	$start_index = $page_now*$post_limit;
	$query1 = 'SELECT * FROM `mdb_douban_movie` WHERE `title` LIKE \'%'.$post_keywords.'%\' LIMIT '.$start_index.','.$post_limit.';';

	$result = mysqli_query($connection,$query);
	//$json['query'] = $query1;
	if($result){
		$json['searchcount'] = mysqli_num_rows($result);
		$json['page_total'] = ceil(mysqli_num_rows($result)/$post_limit);
	}

	$result1 = mysqli_query($connection,$query1);

	if($result1){
		if(mysqli_num_rows($result1)>0){
			$i=0;
			$dbmsg = array();
			while($i<mysqli_num_rows($result1)){
				$record = mysqli_fetch_assoc($result1);

					$msg = array(
						"name" => is_null($record['title']) ? '' : $record['title'],
						"tag" => is_null($record['genres_str']) ? '' : $record['genres_str'],
						"imgurl" => is_null($record['face_65x100']) ? '' : str_replace('/img\d/','img3',str_replace('ipst','spst',$record['face_65x100'])),
						"dburl" => is_null($record['db_url'])? '' :$record['db_url']
					);
					$dbmsg[] = $msg;

				$i++;
				
			}
		}
	}

	$json['dbmsg'] = $dbmsg;

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