<?php
/**
* @api {post} /gmspanel/interface/zh-cn/3.1/PCM_W_MyWorksBySort.php 根据不同条件获取作品分类
* @apiPermission pxseven
* @apiVersion 0.1.0
* @apiName MyWorksBySort
* @apiGroup Work
* @apiSampleRequest http://ser3.graphmovie.com/gmspanel/interface/zh-cn/3.1/PCM_W_MyWorksBySort.php

* @apiDescription 客户端作品管理面板请求此接口来获取该作者的作品信息,支持分页

* @apiParam (POST) {String} [pk=""] 用户PC的机器码(MAC地址)
* @apiParam (POST) {Integer} [v=0] 用户GraphMovieStudios的内部版本号
* @apiParam (POST) {String} userid 用户的userid，必须为登录用户才可访问此接口
* @apiParam (POST) {String} token 用户的登录时返回的token，用于验证用户是否合法
* @apiParam (POST) {String} state 请求的作品状态类型:1-创作中 2-已上线 3-已收录 4-被下线（含用户自主下线以及官方删除和退稿）
* @apiParam (POST) {Integer} [page=0]  请求的分页页码
* @apiParam (POST) {Integer} [limit=9] 请求的分页单页数目，即page=0&limit=9时，返回第0-8部作品，page=1&limit=9时，返回第9-17部作品
* @apiParam (POST) {Integer} [tv=0] 已上线/已收录/被下线三个页面，顶部分类的剧别，0-全部 1-电影 2-电视剧
* @apiParam (POST) {String} [tag="0"] 已上线/已收录/被下线三个页面，顶部分类的类型，"0"-全部 其余皆是类型名，如"剧情"是指需要剧情类的作品
* @apiParam (POST) {Integer} [pcklv=0] 已上线页面时,顶部分类的状态：0-全部，1-待审，2-S级，3-A级，4-B级，5-C级
* @apiParam (POST) {Integer} [weeklv=0] 已收录页面时,顶部分类的评级：0-全部，1-无评级，2-略屌，3-震精，4-神作
* @apiParam (POST) {Integer} [offtype=0] 被下线页面时,顶部分类的状态：0-全部，1-手动下线，2-评审失败下线
*
* @apiSuccess (ResponseJSON) {Integer} status 接口响应状态（0-失败,1-成功,2-需要弹出提示框,提示desc内容）.
* @apiSuccess (ResponseJSON) {Float} usetime 接口响应时间,调试用.
* @apiSuccess (ResponseJSON) {String} error 接口响应出错时的错误描述.
* @apiSuccess (ResponseJSON) {String} debug 接口响应出错时的过程描述,调试用.
* @apiSuccess (ResponseJSON) {String} desc status=2时需要弹窗提示此内容.
* @apiSuccess (ResponseJSON) {Integer} work_total 总作品数,一共有多少作品.
* @apiSuccess (ResponseJSON) {Integer} page_total 总页码数,一共有多少页.
* @apiSuccess (ResponseJSON) {Integer} page_now 当前页码数.
* @apiSuccess (ResponseJSON) {Integer} page_limit 当前每页展示多少部作品.
* @apiSuccess (ResponseJSON) {Array[]} works 按照给定的page和limit等参数查出的作品数据集合.
* @apiSuccess (ResponseJSON) {Object} work 信息的结构体，在JSON中是不存在此key的，这里只是为了说明，具体可参见下面Success-Response中的示例.
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
* @apiSuccess (ResponseJSON) {String} work.bpic_url 作品的大封面url.
* @apiSuccess (ResponseJSON) {String} work.spic_id 作品的小封面id.
* @apiSuccess (ResponseJSON) {String} work.spic_md5 作品的小封面md5.
* @apiSuccess (ResponseJSON) {String} work.spic_url 作品的小封面url.
* @apiSuccess (ResponseJSON) {String} work.firstpage_id 作品的第一页解说图片id.
* @apiSuccess (ResponseJSON) {String} work.firstpage_md5 作品的第一页解说图片md5.
* @apiSuccess (ResponseJSON) {String} work.firstpage_url 作品的第一页解说图片url.
* @apiSuccess (ResponseJSON) {String} work.tags 作品的类型，以|链接，例如：剧情|恐怖|惊悚.
* @apiSuccess (ResponseJSON) {String} work.tags_text 作品的自定义添加标签组，以|链接.
* @apiSuccess (ResponseJSON) {String} work.season_id 作品的剧集id，为图解后台剧集id.
* @apiSuccess (ResponseJSON) {String} work.act_id 作品参加的征稿活动id.
* @apiSuccess (ResponseJSON) {String} work.act_name 作品参加的征稿活动名称.
* @apiSuccess (ResponseJSON) {Integer} work.page_count 作品的页数.
* @apiSuccess (ResponseJSON) {Float} work.size 作品的尺寸.
* @apiSuccess (ResponseJSON) {Integer} work.state 作品的状态:0-缺省 1-创作中 2-已上线 3-已收录 4-被下线 -1-用户放弃 -2-回收站(测试作品官方丢弃) -3-退稿(-2和-3在被下线中应该显示手型图标)
* @apiSuccess (ResponseJSON) {Integer} work.movie_id 作品上线后的图解id.
* @apiSuccess (ResponseJSON) {String} work.movie_played 作品上线后的图解播放数.
* @apiSuccess (ResponseJSON) {String} work.movie_poptxt 作品上线后的图解弹幕数.
* @apiSuccess (ResponseJSON) {String} work.movie_like 作品上线后的图解点赞数.
* @apiSuccess (ResponseJSON) {String} work.movie_gold 作品上线后的图解打赏数.
* @apiSuccess (ResponseJSON) {String} work.movie_comment 作品上线后的图解评论数.
* @apiSuccess (ResponseJSON) {String} work.movie_keep 作品上线后的图解收藏数.
* @apiSuccess (ResponseJSON) {String} work.movie_share 作品上线后的图解分享数.
* @apiSuccess (ResponseJSON) {Float} work.movie_score 作品上线后的图解评分，10分制.
* @apiSuccess (ResponseJSON) {Integer} work.movie_lv 作品上线后的评级，0-无评级，1-略屌，2-震精，3-神作.
* @apiSuccess (ResponseJSON) {String} work.movie_url 作品上线后的图解分享url.
* @apiSuccess (ResponseJSON) {Integer} work.progress 作品的创作进度0-100.
* @apiSuccess (ResponseJSON) {String} work.db_url 作品关联的豆瓣url.
* @apiSuccess (ResponseJSON) {String} work.db_id 作品关联的豆瓣id.
* @apiSuccess (ResponseJSON) {String} work.db_name 作品关联的豆瓣电影名称.
* @apiSuccess (ResponseJSON) {Integer} work.film_id 作品关联的电影id.
* @apiSuccess (ResponseJSON) {String} work.film_name 作品关联的电影名称.
* @apiSuccess (ResponseJSON) {String} work.creat_time 作品的创建时间.
* @apiSuccess (ResponseJSON) {String} work.submit_time 作品的完成提交时间.
* @apiSuccess (ResponseJSON) {String} work.update_time 作品的最后编辑时间.
* @apiSuccess (ResponseJSON) {String} work.takeon_time 作品如已被收录，为最后收录的时间.
* @apiSuccess (ResponseJSON) {String} work.offline_time 作品如已被下线，下线时间.
* @apiSuccess (ResponseJSON) {String} work.ck_feedback 被下线作品的编辑审核反馈.
* @apiSuccess (ResponseJSON) {String} work.fb_repay_id 回复编辑审核反馈时上报的id.

*
* @apiSuccessExample Success-Response[提交成功]:

*     {
*       "status": 1,
*       "usetime": 0.0024,
*       "error": "",
*       "debug": "",
*       "desc": "",
*       "page_total": 19,
*       "page_now": 0,
*       "page_limit": 9,
*       "works": [
*			{
*				"workid":"d04e4007e9d4c2d6cc6a7ca3d386ff4f",
*				"title":"双姝怨",
*				"sub_title":"道德社会型恐怖片",
*				"editor_note":"前半部分昏昏欲睡，几乎要放弃这部片，看完之后却难过万分。",
*				"tv_type":0,
*				"tv_snum":0,
*				"tv_enum":0,
*				"we_score":0,
*				"author":"威廉·惠勒",
*				"actor":"奥黛丽·赫本，雪莉·麦克雷恩，詹姆斯·加纳",
*				"intro":"凯伦（奥黛丽·赫本 Audrey Hepburn 饰）和玛莎（雪莉·麦克雷恩 Shirley MacLaine 饰）共同管理着一间私立学校，尽管身为女流之辈，但她们特立独行英明果断的作风还是赢得了许多学生和老师的喜爱，两人之间的友谊也十分坚固。玛丽（Karen Balkin 饰）从小就过着娇生惯养的生活，成长于溺爱之中的她逐渐养成了乖僻的扭曲个性。
　　在一次犯错之后，玛丽遭到了凯伦与玛莎的惩罚，可这种惩罚在玛丽看来简直是奇耻大辱，仇恨的种子在她幼小的心灵里生根发芽。玛丽告诉祖母，她无意之中看到了凯伦和玛莎接吻的场面，愤怒的祖母将这子虚乌有的诽谤公之于众。面对来势汹汹的欲加之罪，凯伦和玛莎先是奋起反抗，但很快她们便发现，她们手中唯一的筹码——真诚与坦荡竟然是这样的无足轻重。",
*				"showtime":"1961",
*				"zone":"美国",
*				"score":"7",
*				"bpic_id":"10362",
*				"bpic_md5":"de7fd1a41e4c858f8accf58f0ee34749",
*				"bpic_url":"http://imgs4.graphmovie.com/gms_works/work_imgs/832e3e55fae2c99926149c0232edc898/47b5aeac1b5141b125da330fe4a07e98/de7fd1a41e4c858f8accf58f0ee34749.png",
*				"spic_id":"10363",
*				"spic_md5":"0aff9546b9c3340739bf82e8ad525519",
*				"spic_url":"http://imgs4.graphmovie.com/gms_works/work_imgs/832e3e55fae2c99926149c0232edc898/47b5aeac1b5141b125da330fe4a07e98/0aff9546b9c3340739bf82e8ad525519.png",
*				"firstpage_id":"10364",
*				"firstpage_md5":"08ae8449a6ee5824f7a0a4189efd0ac7",
*				"firstpage_url":"http://imgs4.graphmovie.com/gms_works/work_imgs/832e3e55fae2c99926149c0232edc898/47b5aeac1b5141b125da330fe4a07e98/08ae8449a6ee5824f7a0a4189efd0ac7.png",
*				"tags":"同性|剧情",
*				"tags_text":"",
*				"season_id":"",
*				"act_id":"152",
*				"act_name":"【图解】征稿 Vol .17 | 伊万·麦克格雷格影视作品征集",
*				"page_count":550,
*				"size":0,
*				"state":2,
*				"movie_id":8622,
*				"movie_played":"15.6万",
*				"movie_poptxt":"364",
*				"movie_like":"1.2万",
*				"movie_gold":"3695",
*				"movie_comment":"95",
*				"movie_keep":"365",
*				"movie_share":"425",
*				"movie_score":6.4,
*				"movie_lv":2,
*				"movie_url":"http://ser3.graphmovie.com/gmspanel/olr/detail.php?k=89S9HUK0",
*				"progress":100,
*				"db_url":"https://movie.douban.com/subject/25662327/",
*				"db_id":"25662327",
*				"db_name":"双姝怨",
*				"film_id":"121",
*				"film_name":"双姝怨",
*				"creat_time":"2016-03-02 11:15",
*				"submit_time":"2016-03-03 18:22",
*				"update_time":"2016-03-02 11:17",
*				"takeon_time":"",
*				"offline_time":"",
*				"ck_feedback":"第13,14,15页有露点照片，请注意哦亲！",
*				"fb_repay_id":"201"
*			},
*			...
*		],
*     }

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
	
	$json['page_total']= 0;
	$json['page_now']= 0;
	$json['page_limit']= 9;
	$json['works']= array();
	$json['work_total'] = 0;

	//初始化POST参数
	$post_pk = 'na';
	$post_v = 0;
	$post_userid = '0';
	$post_token = '';
	
	$post_state = 0;
	
	$post_page = 0;
	$post_limit = 9;
	$post_tv = 0;
	$post_tag = '0';
	$post_pcklv = 0;
	$post_weeklv = 0;
	$post_offtype = 0;
	
	//获取Header
	//模拟请求采用的是request payload 发上来的参数
	$request_body = file_get_contents('php://input');
	$data = json_decode($request_body);
	if($data && count($data)>0 && (!isset($_POST) || count($_POST)==0) && isset($data->userid) && strlen($data->userid)>0 && isset($data->token) && strlen($data->token)>0 && isset($data->state) && strlen($data->state)>0){
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
		
		if(isset($data->state)){
			$post_state = htmlspecialchars(addslashes($data->state));
		}
		
		if(isset($data->page)){
			$post_page = htmlspecialchars(addslashes($data->page));
		}
		
		if(isset($data->limit)){
			$post_limit = htmlspecialchars(addslashes($data->limit));
		}
		
		if(isset($data->tv)){
			$post_tv = htmlspecialchars(addslashes($data->tv));
		}
		
		if(isset($data->tag)){
			$post_tag = htmlspecialchars(addslashes($data->tag));
		}
		
		if(isset($data->pcklv)){
			$post_pcklv = htmlspecialchars(addslashes($data->pcklv));
		}
		
		if(isset($data->weeklv)){
			$post_weeklv = htmlspecialchars(addslashes($data->weeklv));
		}
		
		if(isset($data->offtype)){
			$post_offtype = htmlspecialchars(addslashes($data->offtype));
		}
		
		
	}else if(
		isset($_POST['userid']) && strlen($_POST['userid'])>0 && 
		isset($_POST['token']) && strlen($_POST['token'])>0 &&
		isset($_POST['state']) && strlen($_POST['state'])>0 
		){
		//获取参数
		if(isset($_POST['pk']) && strlen($_POST['pk'])>0){$post_pk = htmlspecialchars(addslashes($_POST['pk']));}
		if(isset($_POST['v'])){$post_v = htmlspecialchars(addslashes($_POST['v']));}
		if(isset($_POST['userid'])){$post_userid = userIdKeyDecode(htmlspecialchars(addslashes($_POST['userid'])));}
		if(isset($_POST['token'])){$post_token = htmlspecialchars(addslashes($_POST['token']));}
		
		if(isset($_POST['state'])){$post_state = htmlspecialchars(addslashes($_POST['state']));}
		
		if(isset($_POST['page'])){$post_page = htmlspecialchars(addslashes($_POST['page']));}
		if(isset($_POST['limit'])){$post_limit = htmlspecialchars(addslashes($_POST['limit']));}
		
		if(isset($_POST['tv'])){$post_tv = htmlspecialchars(addslashes($_POST['tv']));}
		if(isset($_POST['tag'])){$post_tag = htmlspecialchars(addslashes($_POST['tag']));}
		
		if(isset($_POST['pcklv'])){$post_pcklv = htmlspecialchars(addslashes($_POST['pcklv']));}
		if(isset($_POST['weeklv'])){$post_weeklv = htmlspecialchars(addslashes($_POST['weeklv']));}
		if(isset($_POST['offtype'])){$post_offtype = htmlspecialchars(addslashes($_POST['offtype']));}
		//echo userIdKeyDecode(htmlspecialchars(addslashes($_POST['userid'])));
		//echo '<hr />';
		//echo userIdKeyDecode(addslashes($_POST['userid']));
	}else{
		//缺少参数
		$json['status']= 0;
		$json['usetime'] = endtime($start_time);
		$json['error'] = 'PostError';
		$json_code = json_encode($json);
		echo $json_code;
		die();
	}
	
	//当前请求的页码和limit
	$json['page_now']= $post_page;
	$json['page_limit']= $post_limit;
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



	//按照要求查询作品分页
	//limt是否合法
	if($post_page<0 || $post_limit<=0 || $post_limit>30){
		if($connection)mysqli_close($connection);
		$json['status']= 2;
		$json['usetime'] = endtime($start_time);
		$json['error'] = 'EmptyPageLimit';
		$json['desc'] = "错误的分页请求参数";
		$json_code = json_encode($json);
		echo $json_code;
		die();
	}
	
	//1-创作中 2-已上线 3-已收录 4-被下线（含用户自主下线以及官方删除和退稿）
	$start_index = $post_page*$post_limit;
	if($post_state==1){
		
		//查一查总页数
		$query = 'SELECT * FROM `pcmaker_work` WHERE `user_id`='.$post_userid.' AND  `state`=1;';
		$result = mysqli_query($connection,$query);
		if($result){
			if(mysqli_num_rows($result)>0){
				$json['page_total'] = ceil(mysqli_num_rows($result)/$post_limit);
				$json['work_total'] = mysqli_num_rows($result);
			}
		}
		
		$query = 'SELECT * FROM `pcmaker_work` WHERE `user_id`='.$post_userid.' AND  `state`=1 ORDER BY `update_time` DESC,`creat_time` DESC LIMIT '.$start_index.','.$post_limit.';';
		$json['debug'] = $query;
	}else if($post_state==2){

		//查一查总页数
		$query = 'SELECT * FROM `pcmaker_work` WHERE `user_id`='.$post_userid.' AND  `state`=2';
		//剧种，顶级分类剧别 0-全部 1-电影 2-电视剧
		if(isset($post_tv)){
			if($post_tv==1){
				$query .=' AND `tv_type`=0';
			}elseif($post_tv==2){
				$query .= ' AND `tv_type` <>0';
			}
		}

		//类型，二级分类，喜剧|恐怖|爱情......
		if(isset($post_tag)){
			if($post_tag){
				$query .= ' AND `tags` LIKE \'%'.$post_tag.'%\'';
			}

		}

		//状态，评级sbac
		if(isset($post_pcklv)){
			if($post_pcklv ==1){
				//待审
			}elseif($post_pcklv == '2'){
				$query .= ' AND `we_score`=8';
			}elseif($post_pcklv == '3'){
				$query .= ' AND `we_score`=7';
			}elseif($post_pcklv == '4'){
				$query .= ' AND `we_score`=6';
			}elseif($post_pcklv == '5'){
				$query .= ' AND `we_score`=5';
			}
		}


		$result = mysqli_query($connection,$query);
		if($result){
			if(mysqli_num_rows($result)>0){
				$json['page_total'] = ceil(mysqli_num_rows($result)/$post_limit);
				$json['work_total'] = mysqli_num_rows($result);
			}
		}
		
		//$query = 'SELECT * FROM `pcmaker_work` WHERE `user_id`='.$post_userid.' AND  `state`=2 ORDER BY `submit_time` DESC,`update_time` DESC LIMIT '.$start_index.','.$post_limit.';';
		$query .=' ORDER BY `submit_time` DESC,`update_time` DESC LIMIT '.$start_index.','.$post_limit.';';
		$json['debug'] = $query;
	}else if($post_state==3){
		//已收录作品 还有 该作者之前上线作品的目录 因此只查movie表即可
		
		//查一查总页数
		$query = 'SELECT * FROM `movie` WHERE `grapher`=\''.$post_userid.'\' AND `ding`>0 AND `open`=1';

		// movie表剧集为vol_count，1-电影；剧集大于1 -电视剧
		if(isset($post_tv)){
			if($post_tv==1){
				$query .=' AND `vol_count`=1';
			}elseif($post_tv==2){
				$query .= ' AND `vol_count`>1';
			}
		}
		//类型，二级分类，喜剧|恐怖|爱情......
		if(isset($post_tag)){
			if($post_tag){
				$query .= ' AND `tags` LIKE \'%'.$post_tag.'%\'';
			}

		}
		//状态，评级，震惊，神作，略叼。。。
		if(isset($post_weeklv)){
			if($post_weeklv ==1){
				$query .=' AND `cellcover` = 0';
			}elseif($post_weeklv ==2){
				$query .=' AND `cellcover` = 1';
			}elseif($post_weeklv ==3){
				$query .=' AND `cellcover` = 2';
			}elseif($post_weeklv ==4){
				$query .=' AND `cellcover` = 3';
			}
		}



		$result = mysqli_query($connection,$query);
		if($result){
			if(mysqli_num_rows($result)>0){
				$json['page_total'] = ceil(mysqli_num_rows($result)/$post_limit);
				$json['work_total'] = mysqli_num_rows($result);
			}
		}

		//处理方式与其他不同 这里不需要设置
		
	}else if($post_state==4){
		
		//查一查总页数
		$query = 'SELECT * FROM `pcmaker_work` WHERE `user_id`='.$post_userid.' AND ( `state`=4 OR `state`=-1 OR `state`=-3 ) ';

		//根据请求条件组装查询语句
		//剧种，，顶级分类剧别 0-全部 1-电影 2-电视剧
		if(isset($post_tv)){
			if($post_tv==1){
				$query .=' AND `tv_type`=0';
			}elseif($post_tv==2){
				$query .= ' AND `tv_type` <>0';
			}
		}


		//类型，二级分类，喜剧|恐怖|爱情......
		if(isset($post_tag)){
			if($post_tag){
				$query .= ' AND `tags` LIKE \'%'.$post_tag.'%\'';
			}

		}

		//评审状态，手动下线，评审失败下线
		if(isset($post_offtype)){
			if($post_offtype ==1){
				$query .=' AND `offline_type`=1';
			}elseif($post_offtype==2){
				$query .=' AND `offline_type`=2';
			}
		}





		$result = mysqli_query($connection,$query);
		if($result){
			if(mysqli_num_rows($result)>0){
				$json['page_total'] = ceil(mysqli_num_rows($result)/$post_limit);
				$json['work_total'] = mysqli_num_rows($result);
			}
		}
		
		//$query = 'SELECT * FROM `pcmaker_work` WHERE `user_id`='.$post_userid.' AND ( `state`=4 OR `state`=-1 OR `state`=-3 ) ORDER BY `offline_time` DESC,`update_time` DESC LIMIT '.$start_index.','.$post_limit.';';
		$query .= ' ORDER BY `offline_time` DESC,`update_time` DESC LIMIT '.$start_index.','.$post_limit.';';

		$json['debug'] = $query;

	}else{
		//0- 非法的请求状态
		if($connection)mysqli_close($connection);
		$json['status']= 2;
		$json['usetime'] = endtime($start_time);
		$json['error'] = 'ErrorWorkState';
		$json['desc'] = "错误的作品状态，检索失败";
		$json_code = json_encode($json);
		echo $json_code;
		die();	
	}
	
	
	if($post_state==1 || $post_state==2 || $post_state==4){
		
		$result = mysqli_query($connection,$query);
		if($result && mysqli_num_rows($result)>0){
			$i=0;
			while($i<mysqli_num_rows($result)){
				
				//找到 
				$work = mysqli_fetch_assoc($result);

				//填充信息
				$json_work = array();
				$json_work['workid'] = is_null($work['work_key'])?'':$work['work_key'];
				$json_work['title'] = is_null($work['title'])?'':$work['title'];
				$json_work['sub_title'] = is_null($work['sub_title'])?'':$work['sub_title'];
				$json_work['editor_note'] = is_null($work['editor_note'])?'':$work['editor_note'];
				$json_work['tv_type'] = is_null($work['tv_type'])?'':$work['tv_type'];
				$json_work['tv_snum'] = is_null($work['tv_s_num'])?'':$work['tv_s_num'];
				$json_work['tv_enum'] = is_null($work['tv_e_num'])?'':$work['tv_e_num'];
				$json_work['we_score'] = is_null($work['we_score'])?'':$work['we_score'];
				$json_work['author'] = is_null($work['author'])?'':$work['author'];
				$json_work['actor'] = is_null($work['actor'])?'':$work['actor'];
				$json_work['intro'] = is_null($work['intro'])?'':$work['intro'];
				$json_work['showtime'] = is_null($work['showtime'])?'':$work['showtime'];
				$json_work['zone'] = is_null($work['zone'])?'':$work['zone'];
				$json_work['score'] = is_null($work['score'])?'':$work['score'];
				$json_work['bpic_id'] = is_null($work['bpic_id'])?'':$work['bpic_id'];
				$json_work['bpic_md5'] = is_null($work['bpic_md5'])?'':$work['bpic_md5'];
				$json_work['bpic_url'] = '';
				$json_work['movie_comment']='';
				$json_work['bpic_id']='';
				$json_work['bpic_md5']='';
				$json_work['spic_id']='';
				$json_work['spic_md5']='';
				$json_work['firstpage_id']='';
				$json_work['firstpage_md5']='';
				$json_work['firstpage_url']='';
				$json_work['tags']='';
				$json_work['act_id']='';
				$json_work['act_name']='';
				$json_work['page_count']=0;
				$json_work['size']='';
				$json_work['state']='';
				$json_work['movie_id']='';
				$json_work['movie_played']='';
				$json_work['movie_poptxt']='';
				$json_work['movie_like']='';
				$json_work['movie_gold']='';
				$json_work['movie_keep']='';
				$json_work['movie_share']='';
				$json_work['movie_score']='';
				$json_work['movie_lv']='';
				$json_work['movie_url']='http://www.graphmovie.com';
				$json_work['progress']=0;
				$json_work['db_name']='';
				$json_work['film_id']='';
				$json_work['film_name']='';
				$json_work['creat_time']='';
				$json_work['submit_time']='';
				$json_work['update_time']='';
				$json_work['takeon_time']='';
				$json_work['offline_time']='';
				$json_work['ck_feedback']='';
				$json_work['fb_repay_id']='';

				//查询出封面存储地址
				if(strlen($work['bpic_id'])>0 && $work['bpic_id']>0){
					$query = 'SELECT * FROM `pcmaker_work_img` WHERE `id`='.$work['bpic_id'].';';
					$pic_result = mysqli_query($connection,$query);
					if($pic_result && mysqli_num_rows($pic_result)>0){
						$pic_record = mysqli_fetch_assoc($pic_result);	
						$json_work['bpic_url'] = 'http://imgs4.graphmovie.com/gms_works'.$pic_record['url'];
					}
				}
				
				$json_work['spic_id'] = is_null($work['spic_id'])?'':$work['spic_id'];
				$json_work['spic_md5'] = is_null($work['spic_md5'])?'':$work['spic_md5'];
				$json_work['spic_url'] = '';
				//查询出封面存储地址
				if(strlen($work['spic_id'])>0 && $work['spic_id']>0){
					$query = 'SELECT * FROM `pcmaker_work_img` WHERE `id`='.$work['spic_id'].';';
					$pic_result = mysqli_query($connection,$query);
					if($pic_result && mysqli_num_rows($pic_result)>0){
						$pic_record = mysqli_fetch_assoc($pic_result);	
						$json_work['spic_url'] = 'http://imgs4.graphmovie.com/gms_works'.$pic_record['url'];
					}
				}
				
				$json_work['firstpage_id'] = is_null($work['firstpage_id'])?'':$work['firstpage_id'];
				$json_work['firstpage_md5'] = is_null($work['firstpage_md5'])?'':$work['firstpage_md5'];
				$json_work['firstpage_url'] = '';

				//查询出封面存储地址
				if(strlen($work['firstpage_id'])>0 && $work['firstpage_id']>0){
					$query = 'SELECT * FROM `pcmaker_work_img` WHERE `id`='.$work['firstpage_id'].';';
					$pic_result = mysqli_query($connection,$query);
					if($pic_result && mysqli_num_rows($pic_result)>0){
						$pic_record = mysqli_fetch_assoc($pic_result);	
						$json_work['firstpage_url'] = 'http://imgs4.graphmovie.com/gms_works'.$pic_record['url'];
					}
				}
				
				$json_work['tags'] = is_null($work['tags'])?'':$work['tags'];
				$json_work['tags_text'] = is_null($work['tags_text'])?'':$work['tags_text'];
				$json_work['season_id'] = is_null($work['season_id'])?'':$work['season_id'];
				$json_work['act_id'] = is_null($work['act_id'])?'':$work['act_id'];
				
				//查询活动
				$json_work['act_name'] = '';
				if(strlen($work['act_id'])>0 && $work['act_id']>0){
					$query = 'SELECT * FROM `activ` WHERE `id`='.$work['act_id'].';';
					$act_result = mysqli_query($connection,$query);
					if($act_result && mysqli_num_rows($act_result)>0){
						$act_record = mysqli_fetch_assoc($act_result);	
						$json_work['act_name'] = is_null($act_record['name'])?'':$act_record['name'];
					}
				}
				
				$json_work['page_count'] = is_null($work['page_count'])?'':$work['page_count'];
				$json_work['size'] = is_null($work['size'])?'':$work['size'];
				$json_work['state'] = is_null($work['state'])?'':$work['state'];
				
				$json_work['movie_id'] = is_null($work['movie_id'])?'':$work['movie_id'];
				$json_work['movie_played'] = '0';
				$json_work['movie_poptxt'] = '0';
				$json_work['movie_like'] = '0';
				$json_work['movie_gold'] = '0';
				$json_work['movie_score'] = '0';
				$json_work['movie_lv'] = '0';
				$json_work['movie_url'] = 'http://www.graphmovie.com';
				//查询movie
				if(strlen($work['movie_id'])>0 && $work['movie_id']>0){

					$query = 'SELECT * FROM `movie` WHERE `id`='.$work['movie_id'].';';
					$mv_result = mysqli_query($connection,$query);

					if($mv_result && mysqli_num_rows($mv_result)>0){
						$mv_record = mysqli_fetch_assoc($mv_result);	
						$json_work['movie_played'] = (string)number_2_numberFont($mv_record['played']);
						$json_work['movie_poptxt'] = (string)number_2_numberFont($mv_record['poptxt_count']);
						$json_work['movie_like'] = (string)number_2_numberFont($mv_record['ding']);
						$json_work['movie_comment'] = (string)number_2_numberFont($mv_record['comment_count']);
						$json_work['movie_keep'] = (string)number_2_numberFont($mv_record['keep']);
						$json_work['movie_share'] = (string)number_2_numberFont($mv_record['share']);
						$json_work['movie_lv'] = is_null($mv_record['cellcover'])?'':$mv_record['cellcover'];
						$json_work['movie_url'] = 'http://www.graphmovie.com/ereader/r.php?k='.movieIdOnlineKeyEncode($mv_result['id']);
					}

					//打赏的金币
					$query = 'SELECT * FROM `reward_map` '.
								' WHERE '. 
									'`data_type`=1 AND '.
									'`data_id`='.$work['movie_id'].' '.
								';';
					$result_reward = mysqli_query($connection,$query);
					if($result_reward && mysqli_num_rows($result_reward)>0){
						$reward_record = mysqli_fetch_assoc($result_reward);
						$json_work['movie_gold'] = is_null($reward_record['total_gold'])?'':$reward_record['total_gold'];
					}

					//图解的评分
					$json_work['movie_score'] = '0';
					$query = 'SELECT * FROM `movie_v_gmscore` WHERE `movie_id`='.$work['movie_id'].' AND `score_type`=1;';
					$score_result = mysqli_query($connection,$query);
					if($score_result && mysqli_num_rows($score_result)>0){
						$score_record = mysqli_fetch_assoc($score_result);	
						$json_work['movie_score'] = is_null($score_record['score_value'])?'':$score_record['score_value'];
					}


					//图解分享的地址
					$json_work['movie_url'] = 'http://www.graphmovie.com/ereader/r.php?k='.movieIdOnlineKeyEncode($work['movie_id']);
					
				}

				$json_work['progress'] = is_null($work['progress'])?'':$work['progress'];
				
				$json_work['db_url'] = is_null($work['db_url'])?'':$work['db_url'];
				
				$json_work['db_id'] = is_null($work['db_id'])?'':$work['db_id'];
				//豆瓣名称
				if(strlen($work['db_id'])>0 && $work['db_id']>0){
					$json_work['db_name'] = '';
					//mdb_douban_mvmsg
					$query = 'SELECT * FROM `mdb_douban_mvmsg` WHERE `douban_id`='.$work['db_id'].';';
					$db_result = mysqli_query($connection,$query);

					if($db_result && mysqli_num_rows($db_result)>0){
						$db_record = mysqli_fetch_assoc($db_result);
						$db_msg = @json_decode($db_record['json']);
						if(count($db_msg)>0){
							$json_work['db_name'] = is_null($db_msg->title) ? '' : $db_msg->title;

						}	
					}
				}

				$json_work['film_id'] = is_null($work['film_id'])?'':$work['film_id'];
				//film名称

				if(strlen($work['film_id'])>0 && $work['film_id']>0){
					$json_work['film_name'] = '';
					//mdb_film
					$query = 'SELECT * FROM `mdb_film` WHERE `id`='.$work['film_id'].';';
					$db_result = mysqli_query($connection,$query);
					if($db_result && mysqli_num_rows($db_result)>0){
						$db_record = mysqli_fetch_assoc($db_result);
						$json_work['film_name'] = is_null($db_record['name'])?'':$db_record['name'];
					}
				}

				
				//时间
				$json_work['creat_time'] = date('Y-m-d H:i',$work['creat_time']);		//作品创建时间
				$json_work['submit_time'] = date('Y-m-d H:i',$work['submit_time']);	//作品提交时间
				$json_work['update_time'] = date('Y-m-d H:i',$work['update_time']);	//作品变更时间
				$json_work['takeon_time'] = date('Y-m-d H:i',$work['takeon_time']);	//作品收录时间
				$json_work['offline_time'] = date('Y-m-d H:i',$work['offline_time']);//作品下线时间

				//评审意见
				$json_work['ck_feedback'] = '';
				$json_work['fb_repay_id'] = '';
				//只有自主下线或者评审下线才有评审意见
				if($post_state==4){
					$query = 'SELECT * FROM `pcmaker_cker_msg` WHERE `work_id`='.$work['id'].' ORDER BY `id` DESC LIMIT 1;';
					$ck_result = mysqli_query($connection,$query);
					if($ck_result && mysqli_num_rows($ck_result)>0){
						$ck_record = mysqli_fetch_assoc($ck_result);
						$json_work['ck_feedback'] = $ck_record['content'];
						$json_work['fb_repay_id'] = $ck_record['id'];
					}
				}
				
				//存储json_work
				$json['works'][count($json['works'])] = is_null($json_work)?'':$json_work;
				
				$i++;
				
			}// while
			
		}else{
			//没没有作品了
			if($connection)mysqli_close($connection);
			$json['status']= 1;
			$json['usetime'] = endtime($start_time);
			$json['error'] = 'NoMoreWorks';
			$json['desc'] = "没有更多作品了";
			$json_code = json_encode($json);
			echo $json_code;
			die();
		}
	
	}else{
		//state == 3 查该作者已收录作品
		//赞数大于0认为是已被官方收录上线作品
		$start_index = $post_page*$post_limit;
		//$query = 'SELECT * FROM `movie` WHERE `grapher`=\''.$post_userid.'\' AND `open`=1 AND `ding`>0 ORDER BY `id` DESC LIMIT '.$start_index.','.$post_limit.';';
		$query = 'SELECT * FROM `movie` WHERE `grapher`=\''.$post_userid.'\' AND `open`=1 AND `ding`>0';

		// movie表剧集为vol_count，1-电影；>1-电视剧
		if(isset($post_tv)){
			if($post_tv==1){
				$query .=' AND `vol_count`=1';
			}elseif($post_tv==2){
				$query .= ' AND `vol_count`>1';
			}
		}

		//类型，二级分类，喜剧|恐怖|爱情......
		if(isset($post_tag)){
			if($post_tag){
				$query .= ' AND `tags` LIKE \'%'.$post_tag.'%\'';
			}

		}

		//状态，评级，震惊，神作，略叼。。。
		if(isset($post_weeklv)){
			if($post_weeklv ==1){
				$query .=' AND `cellcover` = 0';
			}elseif($post_weeklv ==2){
				$query .=' AND `cellcover` = 1';
			}elseif($post_weeklv ==3){
				$query .=' AND `cellcover` = 2';
			}elseif($post_weeklv ==4){
				$query .=' AND `cellcover` = 3';
			}
		}

		$query .=' ORDER BY `id` DESC LIMIT '.$start_index.','.$post_limit;


		$json['debug'] = $query;
		$result = mysqli_query($connection,$query);
		
		if($result && mysqli_num_rows($result)>0){
			$i=0;
			while($i<mysqli_num_rows($result)){
				//找到 
				$movie = mysqli_fetch_assoc($result);
				
				//填充信息
				$json_work = array();
				$json_work['movie_comment']='';
				$json_work['bpic_id']='';
				$json_work['bpic_md5']='';
				$json_work['bpic_url']='';
				$json_work['spic_id']='';
				$json_work['spic_md5']='';
				$json_work['firstpage_id']='';
				$json_work['firstpage_md5']='';
				$json_work['firstpage_url']='';
				$json_work['tags']='';
				$json_work['act_id']='';
				$json_work['act_name']='';
				$json_work['page_count']=0;
				$json_work['size']='';
				$json_work['state']=3;
				$json_work['movie_id']='';
				$json_work['movie_played']='';
				$json_work['movie_poptxt']='';
				$json_work['movie_like']='';
				$json_work['movie_gold']='';
				$json_work['movie_keep']='';
				$json_work['movie_share']='';
				$json_work['movie_score']='';
				$json_work['movie_lv']='';
				$json_work['movie_url']='http://www.graphmovie.com';
				$json_work['progress'] = 100;
				$json_work['film_id']='';
				$json_work['film_name']='';
				$json_work['creat_time']='';
				$json_work['submit_time']='';
				$json_work['update_time']='';
				$json_work['takeon_time']='';
				$json_work['ck_feedback']='';
				$json_work['fb_repay_id']='';

				$json_work['workid'] = md5('movieid:'.$movie['id']);
				$json_work['title'] = is_null($movie['name'])?'':$movie['name'];
				$json_work['sub_title'] = is_null($movie['sub_title'])?'':$movie['sub_title'];
				$json_work['editor_note'] = is_null($movie['editor_note'])?'':$movie['editor_note'];
				
				$json_work['tv_type'] = $movie['vol_count']>1?2:0;
				$json_work['tv_snum'] = 0;
				$json_work['tv_enum'] = 0;
				
				if($movie['we_score']==8){
					$json_work['we_score'] = 1;
				}else if($movie['we_score']==7){
					$json_work['we_score'] = 2;
				}else if($movie['we_score']==6){
					$json_work['we_score'] = 3;
				}else if($movie['we_score']==5){
					$json_work['we_score'] = 4;
				}else{
					$json_work['we_score'] = 0;	
				}
				
				$json_work['author'] = is_null($movie['author'])?'':$movie['author'];
				$json_work['actor'] = is_null($movie['actor'])?'':$movie['actor'];
				$json_work['intro'] = is_null($movie['intro'])?'':$movie['intro'];
				$json_work['showtime'] = is_null($movie['showtime'])?'':$movie['showtime'];
				$json_work['zone'] = is_null($movie['zone'])?'':$movie['zone'];
				$json_work['score'] = is_null($movie['score'])?'':$movie['score'];
				
				$json_work['bpic_id'] = '';
				$json_work['bpic_md5'] = '';
				$json_work['bpic_url'] = 'http://avatar.graphmovie.com/boo/'.$movie['bpic'];
				
				$json_work['spic_url'] = 'http://avatar.graphmovie.com/boo/'.$movie['spic'];
				

				$json_work['tags'] = $movie['tags'];
				$json_work['tags_text'] = is_null($movie['tags_text'])?'':$movie['tags_text'];
				$json_work['season_id'] = is_null($movie['season_id'])?'':$movie['season_id'];
				

				//查询活动

				$json_work['page_count'] = $movie['total_page'];
				$json_work['size'] = $movie['total_size'];

				$json_work['movie_id'] = $movie['id'];
				$json_work['movie_played'] = (string)number_2_numberFont($movie['played']);
				$json_work['movie_poptxt'] = (string)number_2_numberFont($movie['poptxt_count']);
				$json_work['movie_like'] = (string)number_2_numberFont($movie['ding']);
				$json_work['movie_comment'] = (string)number_2_numberFont($movie['comment_count']);
				$json_work['movie_keep'] = (string)number_2_numberFont($movie['keep']);
				$json_work['movie_share'] = (string)number_2_numberFont($movie['share']);
				$json_work['movie_lv'] = is_null($movie['cellcover'])?'':$movie['cellcover'];



				
				$query = 'SELECT * FROM `reward_map` '.
							' WHERE '. 
								'`data_type`=1 AND '.
								'`data_id`='.$movie['id'].' '.
							';';
				$result_reward = mysqli_query($connection,$query);
				if($result_reward && mysqli_num_rows($result_reward)>0){
					$reward_record = mysqli_fetch_assoc($result_reward);
					$json_work['movie_gold'] = is_null($reward_record['total_gold'])?'':$reward_record['total_gold'];
				}
				
				//图解的评分
				$json_work['movie_score'] = '0';
				$query = 'SELECT * FROM `movie_v_gmscore` WHERE `movie_id`='.$movie['id'].' AND `score_type`=1;';
				$score_result = mysqli_query($connection,$query);
				if($score_result && mysqli_num_rows($score_result)>0){
					$score_record = mysqli_fetch_assoc($score_result);	
					$json_work['movie_score'] = is_null($score_record['score_value'])?'':$score_record['score_value'];
				}
				
				
				//图解分享的地址
				$json_work['movie_url'] = 'http://www.graphmovie.com/ereader/r.php?k='.movieIdOnlineKeyEncode($movie['id']);
				
				

				
				
				$json_work['db_url'] = '';
				//查询是否关联了豆瓣链接
				$query = 'SELECT * FROM `mdb_movie_v_link` WHERE `movie_id`='.$movie['id'].' AND `link_type`=1;';
				$db_result = mysqli_query($connection,$query);
				if($db_result && mysqli_num_rows($db_result)>0){
					$db_record = mysqli_fetch_assoc($db_result);
					$json_work['db_url'] = is_null($db_record['link_url'])?'':$db_record['link_url'];
				}
				
				$json_work['db_id'] = '';
				$cut_a = explode('subject/',$json_work['db_url']);
				if(count($cut_a)>1){
					$cut_b = explode('/',$cut_a[1]);
					$json_work['db_id'] = is_null($cut_b[0])?'':$cut_b[0];
				}
				
				//豆瓣名称
				$json_work['db_name'] = '';
				if(strlen($json_work['db_id'])>0 && $json_work['db_id']>0){
					//mdb_douban_mvmsg
					$query = 'SELECT * FROM `mdb_douban_mvmsg` WHERE `douban_id`='.$work['db_id'].';';
					$db_result = mysqli_query($connection,$query);
					if($db_result && mysqli_num_rows($db_result)>0){
						$db_record = mysqli_fetch_assoc($db_result);
						$db_msg = @json_decode($db_record['json']);
						if(count($db_msg)>0){
							$json_work['db_name'] = is_null($db_msg->title)?'':$db_msg->title;
						}	
					}
				}
				
				//再查询出film的记录
				$json_work['film_id'] = '';
				$json_work['film_name'] = '';
				
				//时间
				$json_work['creat_time'] = '';		//作品创建时间
				$json_work['submit_time'] = '';	//作品提交时间
				$json_work['update_time'] = '';	//作品变更时间
				$json_work['takeon_time'] = date('Y-m-d H:i',$movie['add_time']);	//作品收录时间
				$json_work['offline_time'] = '';	//作品下线时间
				
				//评审意见
				$json_work['ck_feedback'] = '';
				$json_work['fb_repay_id'] = '';
				
				//存储json_work
				$json['works'][count($json['works'])] = is_null($json_work)?'':$json_work;
				
				$i++;
				
				
			}
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