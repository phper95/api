<?php
/**
* @api {post} /gmspanel/interface/zh-cn/3.1/PCM_OFC_DoubanMsg.php 抓取给定豆瓣ID的Subject信息
* @apiPermission pxseven
* @apiVersion 0.1.0
* @apiName DoubanMsg
* @apiGroup Ofc
* @apiSampleRequest http://ser3.graphmovie.com/gmspanel/interface/zh-cn/3.1/PCM_OFC_DoubanMsg.php

* @apiDescription 抓取给定豆瓣ID的Subject信息,结构化返回

* @apiParam (POST) {String} subid="" 豆瓣的SubjectID
* @apiParam (POST) {String} ck="" 识别码,识别请求是否合法（因为过频繁请求会导致豆瓣短时封停IP无法请求,安全起见需要验证）

*
* @apiSuccess (ResponseJSON) {Integer} status 接口响应状态（0-失败,1-成功,2-需要弹出提示框,提示desc内容）.
* @apiSuccess (ResponseJSON) {Float} usetime 接口响应时间,调试用.
* @apiSuccess (ResponseJSON) {String} error 接口响应出错时的错误描述.
* @apiSuccess (ResponseJSON) {String} debug 接口响应出错时的过程描述,调试用.
* @apiSuccess (ResponseJSON) {String} desc status=2时需要弹窗提示此内容.
* @apiSuccess (ResponseJSON) {Object} dbmsg 抓取到的豆瓣信息.
* @apiSuccess (ResponseJSON) {String} dbmsg.title 影片名称
* @apiSuccess (ResponseJSON) {String} dbmsg.cover 影片小幅海报URL.
* @apiSuccess (ResponseJSON) {Array} dbmsg.directors 导演.
* @apiSuccess (ResponseJSON) {Object} person 人员结构，导演编剧演员数组中存的皆是这种结构.
* @apiSuccess (ResponseJSON) {Object} person.celeid 人员的豆瓣ID，对应地址页 http://movie.douban.com/celebrity/1048026/.
* @apiSuccess (ResponseJSON) {Object} person.name 人员的姓名.
* @apiSuccess (ResponseJSON) {Array} dbmsg.writers 编剧.
* @apiSuccess (ResponseJSON) {Object} person 人员结构，导演编剧演员数组中存的皆是这种结构.
* @apiSuccess (ResponseJSON) {Object} person.celeid 人员的豆瓣ID，对应地址页 http://movie.douban.com/celebrity/1048026/.
* @apiSuccess (ResponseJSON) {Object} person.name 人员的姓名.
* @apiSuccess (ResponseJSON) {Array} dbmsg.actors 主演.
* @apiSuccess (ResponseJSON) {Object} person 人员结构，导演编剧演员数组中存的皆是这种结构.
* @apiSuccess (ResponseJSON) {Object} person.celeid 人员的豆瓣ID，对应地址页 http://movie.douban.com/celebrity/1048026/.
* @apiSuccess (ResponseJSON) {Object} person.name 人员的姓名.
* @apiSuccess (ResponseJSON) {Array} dbmsg.tags 类型,数组.
* @apiSuccess (ResponseJSON) {String} dbmsg.tags.i 每个类型的名称.
* @apiSuccess (ResponseJSON) {String} dbmsg.zone 制片国家/地区.
* @apiSuccess (ResponseJSON) {String} dbmsg.lang 语言.
* @apiSuccess (ResponseJSON) {Array} dbmsg.pubday 上映日期,数组.
* @apiSuccess (ResponseJSON) {String} dbmsg.pubday.i 每个上映日期的详情,地区和日期.
* @apiSuccess (ResponseJSON) {String} dbmsg.mins 片长.
* @apiSuccess (ResponseJSON) {String} dbmsg.othernames 又名.
* @apiSuccess (ResponseJSON) {String} dbmsg.imdb IMDB的ID.
* @apiSuccess (ResponseJSON) {String} dbmsg.rating 豆瓣评分.
* @apiSuccess (ResponseJSON) {String} dbmsg.intro 剧情简介,含br等HTML排版代码.

*
* @apiSuccessExample Success-Response[提交成功]:

*     {
*       "status": 1,
*       "usetime": 0.0024,
*       "error": "",
*       "debug": "",
*       "desc": "",
*       "dbmsg": {
*			"title": "美人鱼",
*			"cover": "http://img3.doubanio.com/view/movie_poster_cover/spst/public/p2316177058.jpg",
*			"directors": [
*				{
*					"celeid": "1048026",
*					"name": "周星驰",
*				}
*			],
*			"writers": [
*				{
*					"celeid": "1048026",
*					"name": "周星驰",
*				},
*				{
*					"celeid": "1048026",
*					"name": "李思臻",
*				},
*				{
*					"celeid": "1048026",
*					"name": "何妙祺",
*				}
*			],
*			"actors": [
*				{
*					"celeid": "1048026",
*					"name": "邓超",
*				},
*				{
*					"celeid": "1048026",
*					"name": "罗志祥",
*				},
*				{
*					"celeid": "1048026",
*					"name": "张雨绮",
*				}
*			],
*			"tags": [
*				"喜剧",
*				"爱情",
*				"奇幻"
*			],
*			"zone": "中国大陆 / 香港",
*			"lang": "汉语普通话 / 英语 / 日语",
*			"pubday": [
*				"2014-09-30(中国大陆)",
*				"2014-09-06(多伦多电影节)"
*			],
*			"mins": "93分钟",
*			"othernames": "Mermaid",
*			"imdb": "tt4701660",
*			"rating": "7.3",
*			"intro": "富豪轩（邓超 饰）的地产计划涉及填海工程，威胁靠海以为生的居民。背负家族秘密的美人鱼珊（林允 饰）被派遣前往阻止。二人在交手过程中互生情愫，虽然轩最终因为爱上珊而停止填海工作，但她因意外受伤而消失于大海。"
*		}
*     }

*
* @apiError PostError 请求的参数缺失或者参数格式错误.
* @apiError ServerError 服务器状态异常.
* @apiError CkError 请求的验证失败，非法请求.
* @apiError ErrorDoubanURL 该豆瓣ID的页面不存在.

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
*       "desc": "额服务器开小差了,请稍后重试..."
*     }

*     CkError:

*     {
*       "status": 0,
*       "usetime": 0.0024,
*       "error": "CkError",
*       "debug": "",
*       "desc": "非法请求"
*     }

*     ErrorDoubanURL:

*     {
*       "status": 0,
*       "usetime": 0.0024,
*       "error": "ErrorDoubanURL",
*       "debug": "",
*       "desc": "该豆瓣ID的页面不存在"
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
	
	//初始化POST参数
	$post_subid = '';
	$post_ck = '';
	
	//获取Header
	//模拟请求采用的是request payload 发上来的参数
	$request_body = file_get_contents('php://input');
	$data = json_decode($request_body);
	if($data && count($data)>0 && (!isset($_POST) || count($_POST)==0) && isset($data->subid) && strlen($data->subid)>0 && isset($data->ck) && strlen($data->ck)>0){
		//curl提交的参数
		if(isset($data->subid)){
			$post_subid = htmlspecialchars(addslashes($data->subid));
		}
		
		if(isset($data->ck)){
			$post_ck = htmlspecialchars(addslashes($data->ck));
		}
		
	}else if(
		isset($_POST['subid']) && strlen($_POST['subid'])>0 && 
		isset($_POST['ck']) && strlen($_POST['ck'])>0
		){
		//获取参数
		if(isset($_POST['ck'])){$post_ck = htmlspecialchars(addslashes($_POST['ck']));}
		if(isset($_POST['subid'])){$post_subid = htmlspecialchars(addslashes($_POST['subid']));}
		
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
	//验证方法: ck = md5(subid+'graphmoviestudiosapi')
	
	if(strtolower(md5($post_subid.'graphmoviestudiosapi')) != strtolower($post_ck)){
		//不合法的请求
		$json['status']= 0;
		$json['usetime'] = endtime($start_time);
		$json['error'] = 'CkError';
		$json_code = json_encode($json);
		echo $json_code;
		die();
	}
	
	//链接数据库 如果数据库有该ID的信息记录 直接返回
	$connection = mysqli_connect(HOST,USER,PSD,DB);
	if($connection){
		//如果连不上就算了
		mysqli_query($connection, "SET NAMES 'UTF8'");
		$query = 'SELECT * FROM `mdb_douban_mvmsg` WHERE `douban_id`='.$post_subid.';';
		$result = mysqli_query($connection,$query);
		if($result){
			if(mysqli_num_rows($result)>0){
				//直接返回
				$record = mysqli_fetch_assoc($result);
				$json['dbmsg'] = json_decode($record['json']);
				
				if($connection)mysqli_close($connection);
				$json['status']= 1;
				$json['usetime'] = endtime($start_time);
				$json_code = json_encode($json);
				echo $json_code;
				die();	
			}
		}
	}
	
	//请求豆瓣的HTML代码
	$dburl = 'http://movie.douban.com/subject/'.$post_subid.'/';
	//只支持电影..游戏图书均不行
	
	//请求是否可以请求通
	/*
	Array ( [0] => HTTP/1.1 404 Not Found [1] => Server: dae [2] => Date: Fri, 15 Jan 2016 09:42:49 GMT [3] => Content-Type: text/html; charset=utf-8 [4] => Content-Length: 33076 [5] => Connection: close [6] => Expires: -1 )
	Array ( [0] => HTTP/1.1 403 Forbidden [1] => Server: dae [2] => Date: Fri, 15 Jan 2016 09:48:47 GMT [3] => Content-Type: text/html [4] => Content-Length: 160 [5] => Connection: close )
	*/
	$db_html = file_get_contents($dburl);
	if(strrpos($http_response_header[0],'404') || strrpos($http_response_header[0],'403')){
		//错误的URL不能请求
		$json['status']= 0;
		$json['usetime'] = endtime($start_time);
		$json['error'] = 'ErrorDoubanURL';
		$json['desc'] = "该豆瓣ID的页面不存在";
		$json_code = json_encode($json);
		echo $json_code;
		die();
	};
	
	//OK 可以抓取出需要的信息了
	$json['dbmsg'] = array(
		"title" => "",
		"cover" => "",
		"directors" => array(),
		"writers" => array(),
		"actors" => array(),
		"tags" => array(),
		"zone" => "",
		"lang" => "",
		"pubday" => array(),
		"mins" => "",
		"othernames" => "",
		"imdb" => "",
		"rating" => "",
		"intro" => ""
	);
	
	//编码转换
	//$db_html = preg_replace("#\\\u([0-9a-f]+)#ie","iconv('UCS-2','UTF-8', pack('H4', '\\1'))",$db_html);
	
	//title
	//<span property="v:itemreviewed"> - </span>
	$split_a = explode('<span property="v:itemreviewed">',$db_html);
	$split_b = explode('</span>',$split_a[1]);
	$json['dbmsg']['title'] = trim($split_b[0]);
	
	//剧情简介
	//<span property="v:summary" class=""> -- </span>
	$split_a = explode('<span property="v:summary" class="">',$db_html);
	$split_b = explode('</span>',$split_a[1]);
	$json['dbmsg']['intro'] = trim($split_b[0]);
	
	//剩余的先清楚多余部分的html代码
	//<div class="subject clearfix">
	//中间部分需要处理
	//<div id="interest_sect_level" class="clearfix">
	$split_a = explode('<div class="subject clearfix">',$db_html);
	$split_b = explode('<div id="interest_sect_level" class="clearfix">',$split_a[1]);
	$db_html = $split_b[0];
	
	//cover
	//<img src=" - "
	$split_a = explode('<img src="',$db_html);
	$split_b = explode('"',$split_a[1]);
	$json['dbmsg']['cover'] = trim($split_b[0]);
	
	//导演
	/*<a href="/celebrity/1048026/" rel="v:directedBy">
                          周星驰
                        </a>
	*/
	//正则匹配
	 $rule  = '/\/[0-9]*\/" rel="v:directedBy">[^<]*</i';  
     preg_match_all($rule,$db_html,$result);
	 
	 //print_r($result);
	 //echo '<hr/>';
	 
	 for($i=0;$i<count($result[0]);$i++){
		 //   /1274265/" rel="v:directedBy">宁浩<
		 $person = array(
		 	"celeid"=>"",
			"name"=>""
		 );
		 //取ID
		 $split_a = explode('/',$result[0][$i]);
		 $person['celeid'] = trim($split_a[1]);
		 //取名字
		 $split_a = explode('rel="v:directedBy">',$result[0][$i]);
		 $split_b = explode('<',$split_a[1]);
		 $person['name'] = trim($split_b[0]);
		 
		 $json['dbmsg']['directors'][count($json['dbmsg']['directors'])] = $person;
	 }
	 
	 //编剧
	/* <a href="/celebrity/1048026/">
                          周星驰
                        </a>
	*/
	//正则匹配
	 $rule  = '/celebrity\/[0-9]*\/">[^<]*</i';  
     preg_match_all($rule,$db_html,$result);
	 
	 //print_r($result);
	 //echo '<hr/>';
	 
	 for($i=0;$i<count($result[0]);$i++){
		 //   /1274265/">宁浩<
		 $person = array(
		 	"celeid"=>"",
			"name"=>""
		 );
		 //取ID
		 $split_a = explode('/',$result[0][$i]);
		 $person['celeid'] = trim($split_a[1]);
		 //取名字
		 $split_a = explode('>',$result[0][$i]);
		 $split_b = explode('<',$split_a[1]);
		 $person['name'] = trim($split_b[0]);
		 
		 $json['dbmsg']['writers'][count($json['dbmsg']['writers'])] = $person;
	 }
	 
	 //主演
	/* <a href="/celebrity/1274235/" rel="v:starring">
                          邓超
                        </a>
	*/
	//正则匹配
	 $rule  = '/\/[0-9]*\/" rel="v:starring">[^<]*</i';  
     preg_match_all($rule,$db_html,$result);
	 
	 //print_r($result);
	 //echo '<hr/>';
	 
	 for($i=0;$i<count($result[0]);$i++){
		 //   /1274265/" rel="v:starring">宁浩<
		 $person = array(
		 	"celeid"=>"",
			"name"=>""
		 );
		 //取ID
		 $split_a = explode('/',$result[0][$i]);
		 $person['celeid'] = trim($split_a[1]);
		 //取名字
		 $split_a = explode('rel="v:starring">',$result[0][$i]);
		 $split_b = explode('<',$split_a[1]);
		 $person['name'] = trim($split_b[0]);
		 
		 $json['dbmsg']['actors'][count($json['dbmsg']['actors'])] = $person;
	 }
	 
	 //类型
	/* <span property="v:genre">
                      喜剧
                    </span>
	*/
	//正则匹配
	 $rule  = '/property="v:genre">[^<]*</i';  
     preg_match_all($rule,$db_html,$result);
	 
	 //print_r($result);
	 //echo '<hr/>';
	 
	 for($i=0;$i<count($result[0]);$i++){
		 //   property="v:genre">喜剧<
		 //取名字
		 $split_a = explode('>',$result[0][$i]);
		 $split_b = explode('<',$split_a[1]);
		 $json['dbmsg']['tags'][count($json['dbmsg']['tags'])] = trim($split_b[0]);
	 }
	 
	 //制片国家/地区:
	 $split_a = explode('制片国家/地区:',$db_html);
	 $split_b = explode('>',$split_a[1]);
	 $split_a = explode('<',$split_b[1]);
	 $json['dbmsg']['zone'] = trim($split_a[0]);
	 
	 //语言:
	 $split_a = explode('语言:',$db_html);
	 $split_b = explode('>',$split_a[1]);
	 $split_a = explode('<',$split_b[1]);
	 $json['dbmsg']['lang'] = trim($split_a[0]);
	 
	 
	 //上映日期
	/* <span property="v:initialReleaseDate" content="2016-02-08(中国大陆/香港)">
                      2016-02-08(中国大陆/香港)
                    </span>
	*/
	//正则匹配
	 $rule  = '/property="v:initialReleaseDate" content="[^"]*">/i';  
     preg_match_all($rule,$db_html,$result);
	 
	 //print_r($result);
	 //echo '<hr/>';
	 
	 for($i=0;$i<count($result[0]);$i++){
		 //  property="v:initialReleaseDate" content="2016-02-08(中国大陆/香港)">
		 //取
		 $split_a = explode('content="',$result[0][$i]);
		 $split_b = explode('">',$split_a[1]);
		 $json['dbmsg']['pubday'][count($json['dbmsg']['pubday'])] = trim($split_b[0]);
	 }
	 
	 //
	 //片长
	/* <span property="v:runtime" content="137"> 
	*/
	//正则匹配
	 $rule  = '/property="v:runtime" content="[0-9]*">/i';  
     preg_match_all($rule,$db_html,$result);
	 
	 //print_r($result);
	 //echo '<hr/>';
	 
	 for($i=0;$i<count($result[0]);$i++){
		 //  property="v:runtime" content="137">
		 //取
		 $split_a = explode('content="',$result[0][$i]);
		 $split_b = explode('">',$split_a[1]);
		 $json['dbmsg']['mins'] = trim($split_b[0]);
	 }
	 
	 //语言:
	 $split_a = explode('又名:',$db_html);
	 $split_b = explode('>',$split_a[1]);
	 $split_a = explode('<',$split_b[1]);
	 $json['dbmsg']['othernames'] = trim($split_a[0]);
	 
	 //IMDb链接
	/* href="http://www.imdb.com/title/tt4701660"
	*/
	//正则匹配
	 $rule  = '/href="http:\/\/www.imdb.com\/title\/[^"]*"/i';  
     preg_match_all($rule,$db_html,$result);
	 
	 //print_r($result);
	 //echo '<hr/>';
	 
	 for($i=0;$i<count($result[0]);$i++){
		 //  property="v:runtime" content="137">
		 //取
		 $split_a = explode('http://www.imdb.com/title/',$result[0][$i]);
		 $split_b = explode('"',$split_a[1]);
		 $json['dbmsg']['imdb'] = trim($split_b[0]);
	 }
	 
	 //豆瓣评分
	/*  <strong class="ll rating_num" property="v:average">
                        7.3
                      </strong>
	*/
	//正则匹配
	 $rule  = '/property="v:average">[^<]*</i';  
     preg_match_all($rule,$db_html,$result);
	 
	 //print_r($result);
	 //echo '<hr/>';
	 
	 for($i=0;$i<count($result[0]);$i++){
		 //  property="v:runtime" content="137">
		 //取
		 $split_a = explode('property="v:average">',$result[0][$i]);
		 $split_b = explode('<',$split_a[1]);
		 $json['dbmsg']['rating'] = trim($split_b[0]);
	 }
	 
	
	//链接数据库 如果该影片的豆瓣信息没有存储过 就存入
	
	if($connection){ 
		//没连接上就不记录了
		$data = json_encode($json['dbmsg']);

		// 过滤
		$data = addslashes($data);

		mysqli_query($connection, "SET NAMES 'UTF8'");
		$query = 'SELECT * FROM `mdb_douban_mvmsg` WHERE `douban_id`='.$post_subid.';';
		$result = mysqli_query($connection,$query);
		if($result){
			if(mysqli_num_rows($result)==0){
				//没找到 INSERT
				$query = 'INSERT INTO mdb_douban_mvmsg(
										douban_id,
										json
										) VALUES ('.
							
							 $post_subid.','.
							 '\''.$data.'\''.
				');';
				mysqli_query($connection, $query);
			}
		}
	}
	
	
	//关闭连接
	if($connection)mysqli_close($connection);
	
	$json['status']= 1;
	$json['usetime'] = endtime($start_time);
	$json['error'] = '';
	$json_code = json_encode($json);
	echo $json_code;
	
	die();		
?>