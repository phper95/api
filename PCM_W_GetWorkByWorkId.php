<?php
/**
* @api {post} /gmspanel/interface/zh-cn/3.1/PCM_W_GetWorkByWorkId.php 通过workid获取作品信息
* @apiPermission yongge
* @apiVersion 0.1.0
* @apiName GetWorkByWorkId
* @apiGroup Work
* @apiSampleRequest http://ser3.graphmovie.com/gmspanel/interface/zh-cn/3.1/PCM_W_GetWorkByWorkId.php

* @apiDescription 客户端作品管理面板请求此接口来获取该作者指定作品的作品信息，用于作品修改填充表单

* @apiParam (POST) {String} [pk=""] 用户PC的机器码(MAC地址)
* @apiParam (POST) {Integer} [v=0] 用户GraphMovieStudios的内部版本号
* @apiParam (POST) {String} userid 用户的userid，必须为登录用户才可访问此接口
* @apiParam (POST) {String} token 用户的登录时返回的token，用于验证用户是否合法
* @apiParam (POST) {String} workid 用户的作品ID


* @apiSuccess (ResponseJSON) {Integer} status 接口响应状态（0-失败,1-成功,2-需要弹出提示框,提示desc内容）.
* @apiSuccess (ResponseJSON) {Float} usetime 接口响应时间,调试用.
* @apiSuccess (ResponseJSON) {String} error 接口响应出错时的错误描述.
* @apiSuccess (ResponseJSON) {String} debug 接口响应出错时的过程描述,调试用.
* @apiSuccess (ResponseJSON) {String} desc status=2时需要弹窗提示此内容.
* @apiSuccess (ResponseJSON) {Object} work  按照给定的page和limit等参数查出的作品数据集合,信息的结构体，在JSON中是不存在此key的，这里只是为了说明，具体可参见下面Success-Response中的示例.
* @apiSuccess (ResponseJSON) {String} work.workid 作品的ID，为32位MD5加密串.
* @apiSuccess (ResponseJSON) {String} work.title 作品的名称.
* @apiSuccess (ResponseJSON) {String} work.sub_title 作品的副标题.
* @apiSuccess (ResponseJSON) {String} work.editor_note 作品的编者按.
* @apiSuccess (ResponseJSON) {Integer} work.tv_type 作品的类型，0-电影 1-单季剧 2-多季剧.
* @apiSuccess (ResponseJSON) {Integer} work.tv_snum 作品的季数.
* @apiSuccess (ResponseJSON) {Integer} work.tv_enum 作品的集数.
* @apiSuccess (ResponseJSON) {Integer} work.we_score 作品的小编评分，0-无评分，1-S级，2-A级，3-B级，4-C级.
* @apiSuccess (ResponseJSON) {String} work.author 作品的导演.
* @apiSuccess (ResponseJSON) {String} work.actor 作品的主要演员.
* @apiSuccess (ResponseJSON) {String} work.intro 作品的剧情简介.
* @apiSuccess (ResponseJSON) {String} work.showtime 作品的上映时间.
* @apiSuccess (ResponseJSON) {String} work.zone 作品的上映地区.
* @apiSuccess (ResponseJSON) {String} work.score 作品的豆瓣评分.
* @apiSuccess (ResponseJSON) {String} work.bpic_id 作品的大封面id.
* @apiSuccess (ResponseJSON) {String} work.bpic_md5 作品的大封面md5.
* @apiSuccess (ResponseJSON) {String} work.spic_id 作品的小封面id.
* @apiSuccess (ResponseJSON) {String} work.spic_md5 作品的小封面md5.
* @apiSuccess (ResponseJSON) {String} work.firstpage_id 作品的第一页解说图片id.
* @apiSuccess (ResponseJSON) {String} work.firstpage_md5 作品的第一页解说图片md5.
* @apiSuccess (ResponseJSON) {String} work.tags 作品的类型，以|链接，例如：剧情|恐怖|惊悚.
* @apiSuccess (ResponseJSON) {String} work.tags_text 作品的自定义添加标签组，以|链接.
* @apiSuccess (ResponseJSON) {String} work.season_id 作品的剧集id，为图解后台剧集id.
* @apiSuccess (ResponseJSON) {Integer} work.state 作品的状态:0-缺省 1-创作中 2-已上线 3-已收录 4-被下线 -1-用户放弃 -2-回收站(测试作品官方丢弃) -3-退稿(-2和-3在被下线中应该显示手型图标)
* @apiSuccess (ResponseJSON) {Integer} work.movie_id 作品上线后的图解id.
 * @apiSuccess (ResponseJSON) {Integer} work.progress 作品的创作进度0-100.
* @apiSuccess (ResponseJSON) {String} work.db_url 作品关联的豆瓣url.
* @apiSuccess (ResponseJSON) {String} work.db_id 作品关联的豆瓣id.
* @apiSuccess (ResponseJSON) {Integer} work.film_id 作品关联的电影id.

*
* @apiSuccessExample Success-Response[提交成功]:

*     {
*		"status": 1,
*		"usetime": "0.00562",
*		"error": "",
*		"debug": "na",
*		"desc": "",
*		"work": {
*		"workid": "6f5b457c778c6528275fc5639ce4a833",
*		"title": "lee测试作品",
*		"sub_title": "lee测试作品",
*		"editor_note": "水电费水电费",
*		"author": "lee",
*		"actor": "发的说法",
*		"intro": "是的发顺丰",
*		"showtime": "2018",
*		"zone": "深圳",
*		"score": "7.0",
*		"bpic_id": "24299",
*		"spic_id": "24298",
*		"firstpage_id": "24300",
*		"tags": "传记|恐怖|剧情",
*		"tags_text": "",
*		"season_id": "0",
*		"page_count": "4",
*		"state": "2",
*		"movie_id": "0",
*		"progress": "100",
*		"db_url": "",
*		"db_id": "",
*		"film_id": "0",
*		"bpic_md5": "435b7a6028bc9ca42f2c32072900309d",
*		"spic_md5": "0799975c7daf448795879ec065f6100b",
*		"firstpage_md5": "defd26cdf46dda735591823a9ccd884f",
*		"tv_type": "0",
*		"tv_s_num": "0",
*		"tv_e_num": "0"
*			}
*	}

*
* @apiError PostError 请求的参数缺失或者参数格式错误.
* @apiError ServerError 服务器状态异常.
* @apiError TokenTimeOut 用户会话超时或非法.
* @apiError ErrorWorkState 发送的state参数不能识别(1-4)
* @apiError EmptyPageLimit 错误的分页请求参数

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

*     ErrorWorkState:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "ErrorWorkState",
*       "debug": "",
*       "desc": "错误的作品状态，检索失败"
*     }

*     EmptyPageLimit:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "EmptyPageLimit",
*       "debug": "",
*       "desc": "错误的分页请求参数"
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
	
	//获取Header
	//模拟请求采用的是request payload 发上来的参数
	$request_body = file_get_contents('php://input');
	$data = json_decode($request_body);
	if($data && count($data)>0 && (!isset($_POST) || count($_POST)==0) && isset($data->userid) && strlen($data->userid)>0 && isset($data->token) && strlen($data->token)>0 && isset($data->workid) && strlen($data->workid)>0){
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
	}else if(
		isset($_POST['userid']) && strlen($_POST['userid'])>0 && 
		isset($_POST['token']) && strlen($_POST['token'])>0 &&
		isset($_POST['workid']) && strlen($_POST['workid'])>0
		){
		//获取参数
		if(isset($_POST['pk']) && strlen($_POST['pk'])>0){$post_pk = htmlspecialchars(addslashes($_POST['pk']));}
		if(isset($_POST['v'])){$post_v = htmlspecialchars(addslashes($_POST['v']));}
		$post_userid = userIdKeyDecode(htmlspecialchars(addslashes($_POST['userid'])));
		$post_token = htmlspecialchars(addslashes($_POST['token']));
		$post_workid = htmlspecialchars(addslashes($_POST['workid']));
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
//查询该userid和workid的配对是否存在
//查询该workid是否存在
$query = 'SELECT * FROM `pcmaker_work` WHERE `work_key`=\''.$post_workid.'\' LIMIT 1;';
$result = mysqli_query($connection,$query);
if($result){
	if(mysqli_num_rows($result)>0){
		//找到
		$work = mysqli_fetch_assoc($result);

		//检查状态是否是已上线作品
		/*if($work['state']==3){
			//作品已上线
			//关闭连接
			if($connection)mysqli_close($connection);
			$json['status']= 2;
			$json['usetime'] = endtime($start_time);
			$json['error'] = 'WorkCantModify';
			$json['desc'] = "作品已收录无法修改";
			$json_code = json_encode($json);
			echo $json_code;
			die();
		}*/

		//检查该作品是否是该用户名下的作品
		if($work['user_id']!=$post_userid){
			//作品并不是该用户的
			$json['desc'] = "该作品不是当前账户注册作品，无法获取作品信息";

			//关闭连接
			if($connection)mysqli_close($connection);
			$json['status']= 2;
			$json['usetime'] = endtime($start_time);
			$json['error'] = 'WorkIdWrongUser';
			$json_code = json_encode($json);
			echo $json_code;
			die();
		}else{
			//是该用户的
			//可以开始获取作品信息了
			$json_work = array();
			$json_work["workid"] = strlen($work['work_key'])==0?'':$work['work_key'];
			$json_work["title"]= strlen($work['title'])==0?'':$work['title'];
			$json_work["sub_title"] = strlen($work['sub_title'])==0?'':$work['sub_title'];
			$json_work["editor_note"] = strlen($work["editor_note"])==0?'':$work["editor_note"];
			$json_work["author"] = strlen($work["author"])==0?'':$work["author"];
			$json_work["actor"] = strlen($work["actor"])==0?'':$work["actor"];
			$json_work["intro"] = strlen($work["intro"])==0?'':$work["intro"];
			$json_work["showtime"] = strlen($work["showtime"])==0?'':$work["showtime"];
			$json_work["zone"] = strlen($work["zone"])==0?'':$work["zone"];
			$json_work["score"] = $work["score"]==0?'':$work["score"];
			$json_work["we_score"] = $work["we_score"]==0?'':$work["we_score"];
			$json_work["bpic_id"] = is_null($work["bpic_id"]) ? 0:$work["bpic_id"];
			$json_work["spic_id"] = $work["spic_id"]==0?'':$work["spic_id"];
			$json_work["firstpage_id"] = $work["firstpage_id"]==0?'':$work["firstpage_id"];
			$json_work["tags"] = strlen($work["tags"])==0?'':$work["tags"];
			$json_work["tags_text"] = strlen($work["tags_text"])==0 ? '':$work["tags_text"];
			$json_work["season_id"] = $work["season_id"]==0?'':$work["season_id"];
			$json_work["page_count"] = $work["page_count"];
			$json_work["state"] = $work["state"];
			$json_work["movie_id"] = strlen($work["movie_id"])==0?'':$work["movie_id"];
			$json_work["progress"] = $work["progress"];
			$json_work["db_url"] = strlen($work["db_url"])==0?'':$work["db_url"];
			$json_work["db_id"] = strlen($work["db_id"])==0?'':$work["db_id"];
			$json_work["film_id"] = $work["film_id"]==0?'':$work["film_id"];
			$json_work["bpic_md5"] = $work["bpic_md5"];
			$json_work["spic_md5"] = strlen($work["spic_md5"])==0?'':$work["spic_md5"];
			$json_work["firstpage_md5"] = strlen($work["firstpage_md5"])==0?'':$work["firstpage_md5"];
			$json_work["tv_type"] = $work["tv_type"];
			$json_work["tv_snum"] = $work["tv_s_num"]==0?'':$work["tv_s_num"];
			$json_work["tv_enum"] = $work["tv_e_num"]==0?'':$work["tv_e_num"];
			$json['work'] = $json_work;

		}
	}else{
		//没找到
		//关闭连接
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