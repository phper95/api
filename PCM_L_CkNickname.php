<?php
/**
* @api {post} /gmspanel/interface/zh-cn/3.1/PCM_L_CkNickname.php 检查昵称是否可用
* @apiPermission pxseven
* @apiVersion 0.2.0
* @apiName CkNickname
* @apiGroup User
* @apiSampleRequest http://ser3.graphmovie.com/gmspanel/interface/zh-cn/3.1/PCM_L_CkNickname.php

* @apiDescription 用户在填写注册信息时，可以实时来检查昵称是否可用。不过该检查并没有占用该昵称，如果用户检查后耽搁了太长的时间没有完成注册，该昵称可能被他人占用，在提交注册信息接口还会再次检查昵称可用性。

* @apiParam (POST) {String} [pk=""] 用户PC的机器码(MAC地址)
* @apiParam (POST) {Integer} [v=0] 用户GraphMovieStudios的内部版本号
* @apiParam (POST) {String} nickname 用户输入的昵称.
* @apiParam (POST) {String} [userid="0"] 如果是二维码扫描登录的用户，这里会有用户ID，检查昵称是否可用时会排出本人.
*
* @apiSuccess (ResponseJSON) {Integer} status 接口响应状态（0-失败,1-成功,2-需要弹出提示框,提示desc内容）.
* @apiSuccess (ResponseJSON) {Float} usetime 接口响应时间,调试用.
* @apiSuccess (ResponseJSON) {String} error 接口响应出错时的错误描述.
* @apiSuccess (ResponseJSON) {String} debug 接口响应出错时的过程描述,调试用.
* @apiSuccess (ResponseJSON) {String} desc status=2时需要弹窗提示此内容.

*
* @apiSuccessExample Success-Response[昵称可用]:

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
* @apiError NicknameOccupied 该昵称已被占用.
* @apiError NicknameInvalid 该昵称含有敏感字不可用.
* @apiError NicknameTooLong 该昵称太长,超过了15个字的限制(理论上客户端会限制,这里再复查一遍).
* @apiError NicknameTooShort 该昵称太短,少于2个字(理论上客户端会限制,这里再复查一遍).
* @apiError NicknameLimit 该昵称中有官方限制用词(如'图解电影').

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

*     NicknameOccupied:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "NicknameOccupied",
*       "debug": "",
*       "desc": "该昵称已被他人征服。"
*     }

*     NicknameInvalid:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "NicknameInvalid",
*       "debug": "",
*       "desc": "昵称中含有敏感字符。"
*     }

*     NicknameTooLong:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "NicknameTooLong",
*       "debug": "",
*       "desc": "太长了...我是指昵称太长了。"
*     }

*     NicknameTooShort:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "NicknameTooShort",
*       "debug": "",
*       "desc": "你太短了...我是指昵称。"
*     }

*     NicknameLimit:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "NicknameLimit",
*       "debug": "",
*       "desc": "昵称中不可以含有'XXXX'哟！"
*     }

*/


	//config
	require_once(dirname(__FILE__).'/'.'inc/interface.json.inc.php');
	require_once(dirname(__FILE__).'/'.'inc/methods.inc.php');
	require_once(dirname(__FILE__).'/'.'inc/config.inc.php');
	require_once(dirname(__FILE__).'/'.'inc/badword.src.php');
	
	//时间记录
	$start_time = starttime();
	
	//获取JSON基本模板
	$json = getBasicJsonModel();
	$json["desc"] = "";
	
	//初始化POST参数
	$post_pk = 'na';
	$post_v = 0;
	$post_nickname = '';
	$post_userid = '0';
	
	//获取Header
	//模拟请求采用的是request payload 发上来的参数
	$request_body = file_get_contents('php://input');
	$data = json_decode($request_body);
	if($data && count($data)>0 && (!isset($_POST) || count($_POST)==0) && isset($data->nickname) && strlen($data->nickname)>0){
		//curl提交的参数
		if(isset($data->pk)){
			$post_pk = htmlspecialchars(addslashes($data->pk));
		}
		
		if(isset($data->v)){
			$post_v = htmlspecialchars(addslashes($data->v));
		}
		
		$post_nickname = htmlspecialchars(addslashes(trim($data->nickname)));
		
		if(isset($data->userid) && $data->userid>0){
			$post_userid = htmlspecialchars(addslashes($data->userid));
		}
		
	}else if(
		isset($_POST['nickname']) && strlen($_POST['nickname'])>0
		){
		//获取参数
		$post_pk = 'na';
		$post_v = 0;
		if(isset($_POST['pk']) && strlen($_POST['pk'])>0){$post_pk = htmlspecialchars(addslashes($_POST['pk']));}
		if(isset($_POST['v'])){$post_v = htmlspecialchars(addslashes($_POST['v']));}
		$post_nickname = htmlspecialchars(addslashes(trim($_POST['nickname'])));
		if(isset($_POST['userid']) && $_POST['userid']>0){$post_userid = htmlspecialchars(addslashes($_POST['userid']));}
		
	}else{
		//缺少参数
		$json['status']= 0;
		$json['usetime'] = endtime($start_time);
		$json['error'] = 'PostError';
		$json_code = json_encode($json);
		echo $json_code;
		die();
	}
	
	//昵称长度是否合适
	if(mb_strlen($post_nickname,'utf8')>=15){
		$json['status']= 2;
		$json['usetime'] = endtime($start_time);
		$json['error'] = 'NicknameTooLong';
		$json['desc'] = '太长了...我是指昵称太长了。';
		$json_code = json_encode($json);
		echo $json_code;
		die();
	}
	
	if(mb_strlen($post_nickname,'utf8')<2){
		$json['status']= 2;
		$json['usetime'] = endtime($start_time);
		$json['error'] = 'NicknameTooShort';
		$json['desc'] = '你太短了...我是指昵称。';
		$json_code = json_encode($json);
		echo $json_code;
		die();
	}
	
	//判断有没有限制字符
	//图解电影,官方,鞭基部,编辑部,研发部,盐发部,创作团,鞭基,盐发
	$limit_names = array('图解电影','GraphMovie','graphmovie','Graphmovie','Graph Movie','graph movie','Graph movie','官方','鞭基部','编辑部','研发部','盐发部','创作团','鞭基','盐发','匿名君');
	foreach($limit_names as $name){
		if(mb_strpos($post_nickname,$name,0,'utf8')!==false){
			$json['status']= 0;
			$json['usetime'] = endtime($start_time);
			$json['error'] = 'NicknameLimit';
			$json['desc'] = '昵称中不可以含有'.$name.'哟！';
			$json_code = json_encode($json);
			echo $json_code;
			die();		
		}	
	}
		
	
	//检查昵称中是否有敏感字
	$p_content = $post_nickname;
	$badword1 = array_combine($badword,array_fill(0,count($badword),'*'));
	$p_content = strtr($p_content, $badword1);
	if($p_content != $post_nickname){
		//敏感字
		$json['status']= 2;
		$json['usetime'] = endtime($start_time);
		$json['error'] = 'NicknameInvalid';
		
		$json['desc'] = '昵称中含有敏感字符。';
		$json_code = json_encode($json);
		echo $json_code;
		die();
	}
	
	//OK合法
	//是否已经被人占用
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
	
	if($post_userid>0){
		$query = 'SELECT * FROM `client_user` WHERE `name`=\''.$post_nickname.'\' AND `id`<>'.$post_userid.';';
	}else{
		$query = 'SELECT * FROM `client_user` WHERE `name`=\''.$post_nickname.'\';';	
	}
	
	$result = mysqli_query($connection,$query);
	if($result){
		if(mysqli_num_rows($result)>0){
			//被占用
			//关闭连接
			if($connection)mysqli_close($connection);
			
			$json['status']= 2;
			$json['usetime'] = endtime($start_time);
			$json['error'] = 'NicknameOccupied';
			$json['desc'] = "该昵称已被他人征服。";
			$json_code = json_encode($json);
			echo $json_code;
			die();
		}
	}
	
	//可用
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