<?php
/**
* @api {post} /gmspanel/interface/zh-cn/3.1/PCM_L_SignNewUser.php 注册新用户
* @apiPermission pxseven
* @apiVersion 0.1.1
* @apiName SignNewUser
* @apiGroup User
* @apiSampleRequest http://ser3.graphmovie.com/gmspanel/interface/zh-cn/3.1/PCM_L_SignNewUser.php

* @apiDescription 注册新用户接口，该接口的请求分为3种情况：
* <br/>1. 匿名君用户扫描二维码进入，需要补充邮件地址（并验证），登录密码，同时支持修改昵称和头像；
* <br/>2. 新注册用户，完全全新的头像、昵称、邮箱地址、登录密码；
* <br/>3. App中已使用QQ或Sina微博登录的用户，扫描二维码进入，处理方式同1；

* @apiParam (POST) {String} [pk=""] 用户PC的机器码(MAC地址)
* @apiParam (POST) {Integer} [v=0] 用户GraphMovieStudios的内部版本号
* @apiParam (POST) {String} userid 用户本地的ID，如果为注册新用户userid=0，如果是二维码扫描登录，userid为<code>QrcodeCkLogin</code>接口返回的id.
* @apiParam (POST) {String} nickname 用户输入的昵称.
* @apiParam (POST) {String} email 用户输入的Email.
* @apiParam (POST) {String} [pwd=""] 用户输入的登录密码的MD5值(32位)，如果是二维码扫描登录，用户之前设定过邮箱和密码，二维码登录接口<code>QrcodeCkLogin</code>返回老密码的MD5，这里直接上报老密码MD5的MD5即可.
* @apiParam (POST) {Integer} imgid 用户选择的系统提供的头像ID，如果有imgdata数据，该字段将会被忽略（用户上传头像优先）.
* @apiParam (POST) {FILE} imgdata 用户需要上传的头像，本地需要压缩在30Kb以下，300x300px以下.

*
* @apiDescription 返回结果同<code>EmailLogin</code>接口是一样的，只是只有一种opt操作:main-登入主面板，另外错误状态多了几个。

* @apiSuccess (ResponseJSON) {Integer} status 接口响应状态（0-失败,1-成功,2-需要弹出提示框,提示desc内容）.
* @apiSuccess (ResponseJSON) {Float} usetime 接口响应时间,调试用.
* @apiSuccess (ResponseJSON) {String} error 接口响应出错时的错误描述.
* @apiSuccess (ResponseJSON) {String} debug 接口响应出错时的过程描述,调试用.
* @apiSuccess (ResponseJSON) {String} desc status=2时需要弹窗提示此内容.
* @apiSuccess (ResponseJSON) {String} token 服务器返回的token通信凭证,登录后请求其他接口必须带上token加密后的字串来通过验证.
* @apiSuccess (ResponseJSON) {Object} opt  根据用户情况,下一步的操作.
* @apiSuccess (ResponseJSON) {String} opt.name  进入主面板:main/验证邮箱:ckemail/签署作者协议:signap/提示签约并进入主面板:signvip
* @apiSuccess (ResponseJSON) {String} opt.data  操作数据
* @apiSuccess (ResponseJSON) {String} opt.data.title  当操作为signap时,该title为显示在窗口内的标题内容
* @apiSuccess (ResponseJSON) {String} opt.data.url  当操作为signap或signvip时,该url新协议的URL地址
* @apiSuccess (ResponseJSON) {String} opt.data.msg  当操作为signvip时,该msg为弹出的提示窗中显示的信息
* @apiSuccess (ResponseJSON) {Object} user  用户信息(仅当opt不为"ckemail"时返回).
* @apiSuccess (ResponseJSON) {String} user.userid  用户的userid,经过加密.
* @apiSuccess (ResponseJSON) {String} user.nickname  昵称.
* @apiSuccess (ResponseJSON) {String} user.feeling  心情签名.
* @apiSuccess (ResponseJSON) {String} user.avatar  头像.
* @apiSuccess (ResponseJSON) {String} user.role  身份角色.
* @apiSuccess (ResponseJSON) {String} user.vip  是否签约大V(0-没有,1-是).
* @apiSuccess (ResponseJSON) {String} user.level  等级.
* @apiSuccess (ResponseJSON) {Integer} user.lvexp  当前级的经验原点.
* @apiSuccess (ResponseJSON) {Integer} user.currexp  当前经验.
* @apiSuccess (ResponseJSON) {Integer} user.nextexp  下一级经验(本级经验进度=(nextexp-currexp)/(nextexp-lvexp)).
* @apiSuccess (ResponseJSON) {String} user.email  用户邮箱地址.
* @apiSuccess (ResponseJSON) {Integer} user.gold  用户金币数目.
* @apiSuccess (ResponseJSON) {String} user.beplayed  用户作品被阅读数目.
* @apiSuccess (ResponseJSON) {String} user.belike  用户作品获赞数目.
* @apiSuccess (ResponseJSON) {String} user.fans  用户的粉丝数目.
* @apiSuccess (ResponseJSON) {String} user.follow  用户的关注数目.
* @apiSuccess (ResponseJSON) {Integer} user.work_ing  用户创作中作品数目(云端).
* @apiSuccess (ResponseJSON) {Integer} user.work_online  用户已上线未审核收录作品数目.
* @apiSuccess (ResponseJSON) {Integer} user.work_cked  用户已审核收录作品数目.
* @apiSuccess (ResponseJSON) {Integer} user.work_offline  用户已下线作品数目.
* @apiSuccess (ResponseJSON) {Integer} user.newmsg_count  用户未读消息计数.

*
* @apiSuccessExample Success-Response[注册成功返回账户信息]:

*     {
*       "status": 1,
*       "usetime": 0.0024,
*       "error": "",
*       "debug": "",
*       "desc": "",
*		"token": "df60d89def855874",
*       "opt": {
*			"name":"main",
*			"data":{}
*		},
*       "user": {
*			"userid": "9999999H",
*       	"nickname": "佳怡生活",	
*			"feeling": "最近心情不错",	
*       	"avatar": "http://tp1.sinaimg.cn/1750477504/180/40036623679/1",	
*       	"role": "菜鸟",	
*       	"vip": 1,	
*       	"level": "Lv25",
*       	"lvexp": 1000,	
*       	"currexp": 1500,	
*       	"nextexp": 2000,	
*       	"email": "12345678@qq.com",
*       	"gold": 2800,	
*       	"beplayed": "39.4万",
*       	"belike": "2.9万",
*       	"fans": "1.1万",
*       	"follow": "60",
*       	"work_ing": 4,
*       	"work_online": 4,
*       	"work_cked": 4,
*       	"work_offline": 4,
*       	"newmsg_count": 4
*		}
*     }


*
* @apiError PostError 请求的参数缺失或者参数格式错误.
* @apiError ServerError 服务器状态异常.
* @apiError NicknameOccupied 该昵称已被他人占用(二维码登录用户自己占用不算).
* @apiError NicknameInvalid 该昵称含有敏感字不可用.
* @apiError NicknameTooLong 该昵称太长,超过了15个字的限制(理论上客户端会限制,这里再复查一遍).
* @apiError NicknameTooShort 该昵称太短,少于2个字(理论上客户端会限制,这里再复查一遍).
* @apiError NicknameLimit 该昵称中有官方限制用词(如'图解电影').
* @apiError EmailOccupied 该Email已被占用(二维码登录用户自己占用不算).
* @apiError NeedPassword 新注册用户的密码不能为空.
* @apiError ImgUploadFailed 头像文件上传失败
* @apiError EmailUsed 该Email已经关联了其它账户(client_user中存在email冲突的情况).
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

*     EmailOccupied:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "EmailOccupied",
*       "debug": "",
*       "desc": "该Email已被他人占用。"
*     }

*     NeedPassword:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "NeedPassword",
*       "debug": "",
*       "desc": "登录密码不能为空。"
*     }

*     ImgUploadFailed:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "ImgUploadFailed",
*       "debug": "",
*       "desc": "头像上传失败，请稍后再试试吧！"
*     }

*     EmailUsed:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "EmailUsed",
*       "debug": "",
*       "desc": "[000@qq.com]已在[2014-12-11 12:00:08]关联了账户[小炒肉]，无法再次关联~"
*     }

*/


	//config
	require_once(dirname(__FILE__).'/'.'inc/interface.json.inc.php');
	require_once(dirname(__FILE__).'/'.'inc/methods.inc.php');
	require_once(dirname(__FILE__).'/'.'inc/config.inc.php');
	require_once(dirname(__FILE__).'/'.'inc/badword.src.php');
	
	//user
	require_once(dirname(__FILE__).'/'.'class/User.class.php');
	
	//时间记录
	$start_time = starttime();
	
	//获取JSON基本模板
	$json = getBasicJsonModel();
	$json["desc"] = "";
	
	//初始化POST参数
	$post_pk = 'na';
	$post_v = 0;
	$post_userid = '0';
	$post_nickname = '';
	$post_email = '';
	$post_pwd = '';
	$post_imgid = '0';
	$post_imgdata = NULL;
	
	//获取Header
	//模拟请求采用的是request payload 发上来的参数
	$request_body = file_get_contents('php://input');
	$data = json_decode($request_body);
	if($data && count($data)>0 && (!isset($_POST) || count($_POST)==0) && isset($data->userid) && strlen($data->userid)>0 && isset($data->nickname) && strlen($data->nickname)>0 && isset($data->email) && strlen($data->email)>0 ){
		
		//curl提交的参数
		if(isset($data->pk)){
			$post_pk = htmlspecialchars(addslashes($data->pk));
		}
		if(isset($data->v)){
			$post_v = htmlspecialchars(addslashes($data->v));
		}
		
		$post_userid = userIdKeyDecode(htmlspecialchars(addslashes(trim($data->userid))));
		$post_nickname = htmlspecialchars(addslashes(trim($data->nickname)));
		$post_email = strtolower(htmlspecialchars(addslashes(trim($data->email))));
		
		if(isset($data->pwd) && strlen($data->pwd)>0){
			$post_pwd = strtolower(htmlspecialchars(addslashes($data->pwd)));
		}
		
		if(isset($data->imgid) && strlen($data->imgid)>0){
			$post_imgid = htmlspecialchars(addslashes($data->imgid));
		}
		
	}else if(
		isset($_POST['userid']) && strlen($_POST['userid'])>0
		&&
		isset($_POST['nickname']) && strlen($_POST['nickname'])>0
		&&
		isset($_POST['email']) && strlen($_POST['email'])>0
		){
		//获取参数
		if(isset($_POST['pk']) && strlen($_POST['pk'])>0){$post_pk = htmlspecialchars(addslashes($_POST['pk']));}
		if(isset($_POST['v'])){$post_v = htmlspecialchars(addslashes($_POST['v']));}
		
		$post_userid = userIdKeyDecode(htmlspecialchars(addslashes(trim($_POST['userid']))));
		$post_nickname = htmlspecialchars(addslashes(trim($_POST['nickname'])));
		$post_email = strtolower(htmlspecialchars(addslashes(trim($_POST['email']))));
		
		if(isset($_POST['pwd'])){$post_pwd = strtolower(htmlspecialchars(addslashes($_POST['pwd'])));}
		if(isset($_POST['imgid'])){$post_imgid = htmlspecialchars(addslashes($_POST['imgid']));}
		
		////file_put_contents('OCMLSignNewUserPost.txt',json_encode($_POST).PHP_EOL,FILE_APPEND);
		
	}else{
		//缺少参数
		$json['status']= 0;
		$json['usetime'] = endtime($start_time);
		$json['error'] = 'PostError';
		$json_code = json_encode($json);
		echo $json_code;
		die();
	}
	
	//验证昵称是否合法
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
	
	//如果给定的有userid 则要排除掉自己的id
	//有重名的情况 如果当前用户名没变 就不验证
	
	$query = 'SELECT * FROM `client_user` WHERE `name`=\''.$post_nickname.'\' AND `id`='.$post_userid.';';
	////file_put_contents('OCMLSignNewUserPost.txt',$query.PHP_EOL,FILE_APPEND);
	$result = mysqli_query($connection,$query);
	if($result){
		if(mysqli_num_rows($result)>0){
			//当前就是这个名字就不管了 有重名的情况 例如 逆光
		}else{
			if($post_userid>0){
				$query = 'SELECT * FROM `client_user` WHERE `name`=\''.$post_nickname.'\' AND `id`<>'.$post_userid.';';
			}else{
				$query = 'SELECT * FROM `client_user` WHERE `name`=\''.$post_nickname.'\';';	
			}
			////file_put_contents('OCMLSignNewUserPost.txt',$query.PHP_EOL,FILE_APPEND);
			$result = mysqli_query($connection,$query);
			if($result){
				if(mysqli_num_rows($result)>0){
					//被占用
					////file_put_contents('OCMLSignNewUserPost.txt',$query.PHP_EOL,FILE_APPEND);
					
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
		}
	}
		
	//判断Email是否可用
	//Email也有重叠的情况 例如825307326@qq.com
	$query = 'SELECT * FROM `client_user` WHERE `email`=\''.$post_email.'\' AND `id`='.$post_userid.';';
	$result = mysqli_query($connection,$query);
	if($result){
		if(mysqli_num_rows($result)>0){
			//当前就是这个email就不管了
		}else{
			if($post_userid>0){
				$query = 'SELECT * FROM `client_user` WHERE `email`=\''.$post_email.'\' AND `id`<>'.$post_userid.';';
			}else{
				$query = 'SELECT * FROM `client_user` WHERE `email`=\''.$post_email.'\';';	
			}
			$result = mysqli_query($connection,$query);
			if($result){
				if(mysqli_num_rows($result)>0){
					//被占用
					//关闭连接
					if($connection)mysqli_close($connection);
					
					$json['status']= 2;
					$json['usetime'] = endtime($start_time);
					$json['error'] = 'EmailOccupied';
					$json['desc'] = "该Email已被他人占用。";
					$json_code = json_encode($json);
					echo $json_code;
					die();
				}
			}
		}
	}
	
	//根据是否有userid来做处理
	//1. userid>0:UPDATE信息
	//2. userid=0：INSERT信息
	$user = NULL;
	if($post_userid<=0){
		//INSERT信息
		//必须有pwd 如果没有就错误
		if(strlen($post_pwd)==0){
			//Error
			if($connection)mysqli_close($connection);
			
			$json['status']= 2;
			$json['usetime'] = endtime($start_time);
			$json['error'] = 'NeedPassword';
			$json['desc'] = "登录密码不能为空。";
			$json_code = json_encode($json);
			echo $json_code;
			die();
		}
		//然后INSERT
		$query = 'INSERT INTO client_user(name,avatar,imei,email,secure_pwd_md5,phone_type,pub_platform,pub_channel,ver,add_time,update_time,phone_id) VALUES ('.
					'\''.$post_nickname.'\','.
					'\'http://imgs4.graphmovie.com/appimage/app_default_avatar.jpg\','.
					'\'\','.
					'\''.$post_email.'\','.
					'\''.$post_pwd.'\','.
					'\'\','.
					'\'\','.
					'\'\','.
					'\'\','.
					'now()'.','.
					'now()'.','.
					'\'\''.
		');';
		$result = mysqli_query($connection,$query);
		if(!$result){
			//插入失败 
			//file_put_contents('PCMLSignNewUserQuery.txt',$query.PHP_EOL,FILE_APPEND);
			
			if($connection)mysqli_close($connection);
			$json['status']= 2;
			$json['usetime'] = endtime($start_time);
			$json['error'] = 'ServerError';
			$json['desc'] = "额服务器开小差了,请稍后重试...";
			$json_code = json_encode($json);
			echo $json_code;
			die();
		}
		//成功 再查询出来
		$query = 'SELECT * FROM `client_user` WHERE `email`=\''.$post_email.'\' ORDER BY `id` DESC LIMIT 1;';
		$result = mysqli_query($connection,$query); 
		if($result){ 
			if(mysqli_num_rows($result)>0){
				//找到了
				$user = mysqli_fetch_assoc($result);
				
			}else{
				//Error
				//file_put_contents('PCMLSignNewUserQuery.txt',$query.PHP_EOL,FILE_APPEND);
				if($connection)mysqli_close($connection);
				$json['status']= 2;
				$json['usetime'] = endtime($start_time);
				$json['error'] = 'ServerError';
				$json['desc'] = "额服务器开小差了,请稍后重试...";
				$json_code = json_encode($json);
				echo $json_code;
				die();
			}
		}else{
			//file_put_contents('PCMLSignNewUserQuery.txt',$query.PHP_EOL,FILE_APPEND);
			if($connection)mysqli_close($connection);
			$json['status']= 2;
			$json['usetime'] = endtime($start_time);
			$json['error'] = 'ServerError';
			$json['desc'] = "额服务器开小差了,请稍后重试...";
			$json_code = json_encode($json);
			echo $json_code;
			die();	
		}
		
	}else{
		//UPDATE信息
		//需要检查密码 如果表中存储的密码MD5后同上报的密码MD5相同 则不更新密码
		
		$need_set_pwd = false;
		
		$query = 'SELECT * FROM `client_user` WHERE `id`='.$post_userid.';';
		$result = mysqli_query($connection,$query); 
		if($result){ 
			if(mysqli_num_rows($result)>0){
				//找到了
				$user = mysqli_fetch_assoc($result);
				
				if(strtolower($post_pwd)==strtolower(md5('gmschkemail'))){
					//未修改密码
					
				}else{
					//修改密码了
					$need_set_pwd = true;	
				}
				
			}else{
				//没有这个用户 
				//file_put_contents('PCMLSignNewUserQuery.txt',$query.PHP_EOL,FILE_APPEND);
				if($connection)mysqli_close($connection);
				$json['status']= 2;
				$json['usetime'] = endtime($start_time);
				$json['error'] = 'ServerError';
				$json['desc'] = "额服务器开小差了,请稍后重试...";
				$json_code = json_encode($json);
				echo $json_code;
				die();
			}
		}else{
			//没有这个用户 
			//file_put_contents('PCMLSignNewUserQuery.txt',$query.PHP_EOL,FILE_APPEND);
			if($connection)mysqli_close($connection);
			$json['status']= 2;
			$json['usetime'] = endtime($start_time);
			$json['error'] = 'ServerError';
			$json['desc'] = "额服务器开小差了,请稍后重试...";
			$json_code = json_encode($json);
			echo $json_code;
			die();
		}
		
		
		if(strlen($post_pwd)>0 && $need_set_pwd){
			$query = 'UPDATE `client_user` SET '.
					'`name`='.'\''.$post_nickname.'\','.
					'`email`='.'\''.$post_email.'\','.
					'`secure_pwd_md5`='.'\''.$post_pwd.'\''.
					' WHERE `id`='	.$post_userid.
					';';
		}else{
			$query = 'UPDATE `client_user` SET '.
					'`name`='.'\''.$post_nickname.'\','.
					'`email`='.'\''.$post_email.'\''.
					' WHERE `id`='	.$post_userid.
					';';	
		}
		
		$result = mysqli_query($connection,$query);
		
		if(!$result){
			//更新失败 
			//file_put_contents('PCMLSignNewUserQuery.txt',$query.PHP_EOL,FILE_APPEND);
			if($connection)mysqli_close($connection);
			$json['status']= 2;
			$json['usetime'] = endtime($start_time);
			$json['error'] = 'ServerError';
			$json['desc'] = "额服务器开小差了,请稍后重试...";
			$json_code = json_encode($json);
			echo $json_code;
			die();
		}
		
		//成功 再查询出来
		$query = 'SELECT * FROM `client_user` WHERE `id`='.$post_userid.';';
		$result = mysqli_query($connection,$query); 
		if($result){ 
			if(mysqli_num_rows($result)>0){
				//找到了
				$user = mysqli_fetch_assoc($result);
			}else{
				//Error
				//file_put_contents('PCMLSignNewUserQuery.txt',$query.PHP_EOL,FILE_APPEND);
				if($connection)mysqli_close($connection);
				$json['status']= 2;
				$json['usetime'] = endtime($start_time);
				$json['error'] = 'ServerError';
				$json['desc'] = "额服务器开小差了,请稍后重试...";
				$json_code = json_encode($json);
				echo $json_code;
				die();
			}
		}else{
			//file_put_contents('PCMLSignNewUserQuery.txt',$query.PHP_EOL,FILE_APPEND);
			if($connection)mysqli_close($connection);
			$json['status']= 2;
			$json['usetime'] = endtime($start_time);
			$json['error'] = 'ServerError';
			$json['desc'] = "额服务器开小差了,请稍后重试...";
			$json_code = json_encode($json);
			echo $json_code;
			die();	
		}
			
	}
	
	//如果没有user信息 错误
	if($user==NULL){
		//file_put_contents('PCMLSignNewUserQuery.txt','NO USER'.PHP_EOL,FILE_APPEND);
		if($connection)mysqli_close($connection);
		$json['status']= 2;
		$json['usetime'] = endtime($start_time);
		$json['error'] = 'ServerError';
		$json['desc'] = "额服务器开小差了,请稍后重试...";
		$json_code = json_encode($json);
		echo $json_code;
		die();
	}
	
	$p_userid = $user['id'];
	
	//记录入新增的账户表account
	//先查询有没有
	$json_info = array(
		"pwd"=>$user['secure_pwd_md5']
	);
	$query = 'SELECT * FROM `account` WHERE `user_id`='.$p_userid.' AND `type`=1;';
	$result = mysqli_query($connection,$query); 
	
	//$json['debug'] .= '['.$query.']';
	
	if($result){ 
		if(mysqli_num_rows($result)>0){
			//找到了
			$record = mysqli_fetch_assoc($result);
			//UPDATE
			$query = 'UPDATE `account` SET '.
					'`account`='.'\''.$post_email.'\','.
					'`info`='.'\''.json_encode($json_info).'\''.
					' WHERE `id`='	.$record['id'].
					';';
					
			//$json['debug'] .= '['.$query.']';
			
		}else{
			//没找到 
			//INSERT
			$query = 'INSERT INTO account(user_id,account,type,info,add_time) VALUES ('.
						''.$p_userid.','.
						'\''.$post_email.'\','.
						'1,'.
						'\''.json_encode($json_info).'\','.
						'now()'.
			');';
			
			//$json['debug'] .= '['.$query.']';
			
		}
		$result = mysqli_query($connection,$query);	
		if(!$result){
			//file_put_contents('PCMLSignNewUserQuery.txt',$query.PHP_EOL,FILE_APPEND);
			//插入失败 
			//有可能该账户已存在同一个邮箱的account
			//检查是否有这种情况
			$query = 'SELECT * FROM `account` WHERE `account`=\''.$post_email.'\' AND `type`=1 AND `user_id`<>'.$p_userid.';';
			$result = mysqli_query($connection,$query); 
			if($result && mysqli_num_rows($result)>0){
				//查查看关联了谁
				$record = mysqli_fetch_assoc($result);
				
				$return_date = $record['add_time'];
				$return_nickname = '';
				
				$query = 'SELECT * FROM `client_user` WHERE `id`='.$record['user_id'].';';
				$result = mysqli_query($connection,$query);
				if($result && mysqli_num_rows($result)>0){
					$record = mysqli_fetch_assoc($result);
					$return_nickname = $record['name'];
				}
				
				if($connection)mysqli_close($connection);
				$json['status']= 2;
				$json['usetime'] = endtime($start_time);
				$json['error'] = 'EmailUsed';
				$json['desc'] = "[".$post_email."]已在[".$return_date."]关联了账户[".$return_nickname."]，无法再次关联，请直接使用邮箱登录~";
				$json_code = json_encode($json);
				echo $json_code;
				die();
			}else{
				if($connection)mysqli_close($connection);
				$json['status']= 2;
				$json['usetime'] = endtime($start_time);
				$json['error'] = 'ServerError';
				$json['desc'] = "额服务器开小差了,请稍后重试...";
				$json_code = json_encode($json);
				echo $json_code;
				die();
			}
			
			
		}
	}
	
	//记录入pcmaker_sign_state表
	//先查出最新版本的协议版本号为多少
	$paper_maxver = 1;
	$query = 'SELECT * FROM `pcmaker_sign_author_paper` ORDER BY `ver` DESC LIMIT 1;';
	$result = mysqli_query($connection,$query);
	
	//$json['debug'] .= '['.$query.']';
	
	if($result){
		if(mysqli_num_rows($result)>0){
			$record = mysqli_fetch_assoc($result);
			$paper_maxver = $record['ver'];
		}
	}
	//是否已经存在记录		
	$query = 'SELECT * FROM `pcmaker_sign_state` '.
				' WHERE '. 
					'`user_id`='.$p_userid.''.
				';';
	
	//$json['debug'] .= '['.$query.']';			
	
	$result = mysqli_query($connection,$query);
	if($result){
		if(mysqli_num_rows($result)==0){
			//没有记录
			//记录入pcmaker_sign_state中
			$query = 'INSERT INTO pcmaker_sign_state(user_id,email,email_cked,allow_sign_author,sign_author_ver,allow_sign_vip,pwd_set,add_time) VALUES ('.
								 $p_userid.','.
								 '\''.$post_email.'\','.
								 '1,1,'.$paper_maxver.',0,1,'.
								 'now()'.
			');';
			mysqli_query($connection, $query);
			
			//$json['debug'] .= '['.$query.']';
			
		}else{
			//更新记录
			$record = mysqli_fetch_assoc($result);
			$query = 'UPDATE `pcmaker_sign_state` SET '.
					'`email`='.'\''.$post_email.'\','.
					'`email_cked`=1,'.
					'`allow_sign_author`=1,'.
					'`sign_author_ver`='.$paper_maxver.','.
					'`pwd_set`=1'.
					' WHERE `id`='	.$record['id'].
					';';	
			mysqli_query($connection,$query);
			
			//$json['debug'] .= '['.$query.']';
			
		}
	}
	
	
	//是否有上传头像
	
	$p_avatar = '';
	$upload_failed = FALSE; //头像是否上传失败 失败了也继续完成注册流程 因为已经INSERT了
	if(isset($_FILES['imgdata'])){
		if($_FILES['imgdata']['error'] == UPLOAD_ERR_OK ){
			//上传成功
			$filepath= dirname(__FILE__).'/../../../'."appimages/avatars/";            //定义路径
			//判断是否存在userid目录
			if(!is_dir($filepath.$p_userid)){
				mkdir($filepath.$p_userid);
			}
			
			$image_name = date('YmdHis').'.jpg';
			$filename = $filepath.$p_userid.'/'.$image_name;         //新的路径及文件名
			
			if(copy($_FILES['imgdata']['tmp_name'],$filename))           //复制文件的目标路径
			{
			   unlink($_FILES['imgdata']['tmp_name']);            //删除原有文件
			   $p_avatar = "http://".$_SERVER['SERVER_NAME'].'/gmspanel/appimages/avatars/'.$p_userid.'/'.$image_name;
			}
			else
			{
			  	$upload_failed = TRUE;
			}
		}	
	}
	
	//如果头像有更新就记录
	if(!$upload_failed && strlen($p_avatar)>0){
		//有上传头像
		//UPDATE
		$query = 'UPDATE `client_user` SET '.
			'`avatar`='.'\''.$p_avatar.'\''.
			' WHERE `id`='	.$p_userid.
			';';	
		$result = mysqli_query($connection,$query); 
		
		//$json['debug'] .= '['.$query.']';
		
	}else if($post_imgid>0){
		//有配置的系统默认头像
		//存储的路径为 http://imgs4.graphmovie.com/appimage/gms_default/x.jpg
		$ava_path = 'http://imgs4.graphmovie.com/appimage/gms_default/'.$post_imgid.'.jpg';
		$query = 'UPDATE `client_user` SET '.
			'`avatar`='.'\''.$ava_path.'\''.
			' WHERE `id`='	.$p_userid.
			';';
		$result = mysqli_query($connection,$query); 	
		
		//$json['debug'] .= '['.$query.']';
		
	}
	
	//查询用户信息返回
	//初始化USER 因为角色和等级的方法中需要user的实例
	$userObj = new User();
	//不必再二次链接数据库了
	$userObj->connect_database = $connection;
	//但是还是需要二次查询
	if(!$userObj->load_user($p_userid)){
		//加载失败 可能没有这个ID的用户
		//file_put_contents('PCMLSignNewUserQuery.txt','$userObj->load_user('.$p_userid.')'.PHP_EOL,FILE_APPEND);
		
		$json['status']= 2;
		$json['usetime'] = endtime($start_time);
		$json['error'] = 'ServerError';
		$json['desc'] = "额服务器开小差了,请稍后重试...";
		$json_code = json_encode($json);
		echo $json_code;
		//关闭连接
		$userObj->closeAllConnection();
		die();
	}
	
	//用户金币数
	$gold = 0;
	$query = 'SELECT * FROM `user_v_gold` '.
			' WHERE '. 
				' `user_id`='.$p_userid .
			';';
			
	$result = mysqli_query($connection,$query);
	if($result && mysqli_num_rows($result)>0){
		$record = mysqli_fetch_assoc($result);
		$gold = (string)$record['gold'];
	}
	
	//经验
	$lvexp = 0;
	$currexp = 0;
	$nextexp = 1;
	$query = 'SELECT * FROM `user_v_exp` WHERE `user_id`='.$p_userid.';';
	$result = mysqli_query($connection,$query);
	if($result && mysqli_num_rows($result)>0){
		$record = mysqli_fetch_assoc($result);
		$currexp = $record['exp'];
		
		//查当前级别的经验值原点
		$query = 'SELECT * FROM `exp_v_level` WHERE `exp`<'.$currexp.' ORDER BY `level` DESC LIMIT 1;';
		$result = mysqli_query($connection, $query);
		if($result && mysqli_num_rows($result)>0){
			$record = mysqli_fetch_assoc($result);
			$lvexp = $record['exp'];
		}else{
			//没查到
			//1级新用户
		}
		
		//再査下一级
		$query = 'SELECT * FROM `exp_v_level` WHERE `exp`>'.$currexp.' ORDER BY `level` ASC LIMIT 1;';
		$result = mysqli_query($connection, $query);
		if($result && mysqli_num_rows($result)>0){
			$record = mysqli_fetch_assoc($result);
			$nextexp = $record['exp'];
		}else{
			//没查到
			//可能满级
		}
	}
	
	//作品数目
	//表stat_user_create
	$work_ing = 0;
	$work_online = 0;
	$work_cked = 0;
	$work_offline = 0;
	
	$query = 'SELECT * FROM `stat_user_create` '.
			' WHERE '. 
				' `user_id`='.$p_userid.
			';';
	
	$result = mysqli_query($connection,$query);
	if($result && mysqli_num_rows($result)>0){
		$record = mysqli_fetch_assoc($result);
		$work_ing = $record['pcm_work_undone'];
		$work_online = $record['pcm_work_online'];
		$work_cked = $record['pcm_work_checked'];
		$work_offline = $record['pcm_work_offline'];
	}
	
	//粉丝
	$fans = 0;
	$query = 'SELECT COUNT(*) AS sumcount FROM `user_v_follow_user` '.
			' WHERE '. 
				' `follow_user_id`='.$p_userid .' AND `open`=1 '.
			';';
			
	$result = mysqli_query($connection,$query);
	if($result && mysqli_num_rows($result)>0){
		$record = mysqli_fetch_assoc($result);
		$fans = (string)number_2_numberFont($record['sumcount']);
	}
	
	//关注
	$follow = 0;
	$query = 'SELECT COUNT(*) AS sumcount FROM `user_v_follow_user` '.
			' WHERE '. 
				' `user_id`='.$p_userid .' AND `open`=1 '.
			';';
			
	$result = mysqli_query($connection,$query);
	if($result && mysqli_num_rows($result)>0){
		$record = mysqli_fetch_assoc($result);
		$follow = (string)number_2_numberFont($record['sumcount']);
	}
	
	//此人的角色和等级
	$role = "";
	$level = "";
	$userMsg = array(
		'id'=>$p_userid,
		'stat_work'=>$work_cked,
		'email'=>$userObj->email,
		'level'=>$userObj->level
	);
	$role 		= check_user_level_string($userMsg,$userObj->connect_memcache,$userObj->connect_database);
	$level 		= check_user_role_string($userMsg,$userObj->connect_memcache,$userObj->connect_database);
	
	//未读消息
	$unread = 0;
	$query = 'SELECT COUNT(*) AS sumcount FROM `pcmaker_msg` '.
			' WHERE '. 
				' `to_user_id`='.$p_userid .' AND `readed`=0 '.
			';';
			
	$result = mysqli_query($connection,$query);
	if($result && mysqli_num_rows($result)>0){
		$record = mysqli_fetch_assoc($result);
		$unread = $record['sumcount'];
	}
	

	//user信息
	$json['user'] = array(
		"userid"=> (string)userIdKeyEncode($p_userid),
		"nickname"=> $userObj->name?$userObj->name:randomDefaultName(),	
		"feeling" => strlen($userObj->feeling)>0?(string)$userObj->feeling:USERDEFAULT_FEELING,
		"avatar"=> $userObj->avatar?$userObj->avatar:'http://imgs4.graphmovie.com/appimage/app_default_avatar.jpg',	
		"role"=> $role,	
		"vip"=> 0,	
		"level"=> $level,
		"lvexp"=> $lvexp,	
		"currexp"=> $currexp,	
		"nextexp"=> $nextexp,	
		"email"=> $post_email,
		"gold"=> $gold,	
		"beplayed"=> (string)number_2_numberFont($userObj->stat_beplayed),
		"belike"=> (string)number_2_numberFont($userObj->stat_belike),
		"fans"=> $fans,
		"follow"=> $follow,
		"work_ing"=> $work_ing,
		"work_online"=> $work_online,
		"work_cked"=> $work_cked,
		"work_offline"=> $work_offline,
		"newmsg_count"=> $unread
	);
	
	//生成一个通信token
	//qrcode + mac + timestamp + rand
	$tokenkey = $post_qrcode.$post_pk.time().rand(10000, 100000);
	$token = md5($tokenkey);
	//记录入数据库
	$query = 'INSERT INTO pcmaker_sign_state(user_id,pc_key,token,add_time) VALUES ('.
						 $p_userid.','.
						 '\''.$post_pk.'\','.
						 '\''.$token.'\','.
						 'now()'.
	');';
	mysqli_query($connection, $query);
	
	$json['token'] = $token;
	
	//可用
	//结束
	$json['status']= 1;
	$json['usetime'] = endtime($start_time);
	//如果头像上传失败了
	if($upload_failed){
		$json['error'] = 'ImgUploadFailed';
		$json['desc'] = '头像上传失败，请稍后再试试吧！';
	}else{
		$json['error'] = '';	
	}
	$json_code = json_encode($json);
	echo $json_code;
	
	//关闭连接
	if($connection)mysqli_close($connection);
	
	die();		
?>