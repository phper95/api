<?php
/**
* @api {post} /gmspanel/interface/zh-cn/3.1/PCM_L_EmailLogin.php 邮箱密码登录
* @apiPermission pxseven
* @apiVersion 0.1.0
* @apiName EmailLogin
* @apiGroup User
* @apiSampleRequest http://ser3.graphmovie.com/gmspanel/interface/zh-cn/3.1/PCM_L_EmailLogin.php

* @apiDescription 用户在登录窗口输入Email地址和密码登录，用户状态共分为5情况：
*	<br/>1. 用户是App用户，第一次登录制作器：需要验证用户邮箱合法性
*	<br/>2. 用户之前登录过制作器，但是验证邮箱时放弃：此时同样需要验证邮箱合法性
*	<br/>3. 用户之前登录过制作器，并且完成了邮箱验证，此时《投稿守则》的协议有新版本：需要重新签署协议
*	<br/>4. 用户之前登录过制作器，邮箱和协议都OK，用户也不符合签约标准：此时直接登入主面板
*	<br/>5. 用户之前登录过制作器，邮箱和协议都OK，符合了签约的标准：弹出窗提示是否签约，同时进入主面板

* @apiParam (POST) {String} [pk=""] 用户PC的机器码(MAC地址)
* @apiParam (POST) {Integer} [v=0] 用户GraphMovieStudios的内部版本号
* @apiParam (POST) {String} email 用户邮箱地址.
* @apiParam (POST) {String} pwd 用户输入密码的MD5值(32位),大小写不敏感.

*
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
* @apiSuccess (ResponseJSON) {String} user.pwd  用户密码的不可逆转加密字串.
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
* @apiSuccessExample Success-Response:

*     直接登录入主面板(opt.name="main"):

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

*     需要验证邮箱(opt.name="ckemail"):

*     {
*       "status": 1,
*       "usetime": 0.0024,
*       "error": "",
*       "debug": "",
*       "desc": "",
*		"token": "df60d89def855874",
*       "opt": {
*			"name":"ckemail",
*			"data":{
*				"title":"",
*				"url":"",
*				"msg":""
*			}
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

*     协议有更新,需要签署投稿守则(opt.name="signap"):

*     {
*       "status": 1,
*       "usetime": 0.0024,
*       "error": "",
*       "debug": "",
*       "desc": "",
*		"token": "df60d89def855874",
*		"opt": {
*			"name":"signap",
*			"data":{
*				"title":"图解电影投稿守则",
*				"url":"http://graphmovie.com",
*				"msg":""
*			}
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

*     符合签约标准提示签约并进入主窗口(opt.name="signvip"):

*     {
*       "status": 1,
*       "usetime": 0.0024,
*       "error": "",
*       "debug": "",
*       "desc": "",
*		"token": "df60d89def855874",
*		"opt": {
*			"name":"signap",
*			"data":{
*				"title":"",
*				"url":"http://graphmovie.com",
*				"msg":"经过编辑部的一致认同，您已符合签约资格！是否立刻加入我们呢？"
*			}
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
* @apiError UserNotFound 没有找到给定<code>email</code>的用户账户.
* @apiError PasswordInvalid 密码<code>pwd</code>错误.
* @apiError UserInvalid 该用户账户已被冻结.
* @apiError ServerError 服务器状态异常.

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

*     UserNotFound:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "UserNotFound",
*       "debug": "",
*       "desc": "没有这个用户哎"
*     }

*     PasswordInvalid:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "PasswordInvalid",
*       "debug": "",
*       "desc": "密码错了..."
*     }

*     UserInvalid:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "UserInvalid",
*       "debug": "",
*       "desc": "该账户被冻结,自个儿说说你干了什么坏事吧"
*     }

*     ServerError:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "ServerError",
*       "debug": "",
*       "desc": "额服务器开小差了,请稍后重试..."
*     }

*/


	//config
	require_once(dirname(__FILE__).'/'.'inc/interface.json.inc.php');
	require_once(dirname(__FILE__).'/'.'inc/methods.inc.php');
	require_once(dirname(__FILE__).'/'.'inc/config.inc.php');
	require_once(dirname(__FILE__).'/'.'inc/rolelv.config.inc.php');
	
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
	$post_email = '';
	$post_pwd = '';
	
	//获取Header
	//模拟请求采用的是request payload 发上来的参数
	$request_body = file_get_contents('php://input');
	$data = json_decode($request_body);
	if($data && count($data)>0 && (!isset($_POST) || count($_POST)==0) && isset($data->email) && strlen($data->email)>0 && isset($data->pwd) && strlen($data->pwd)>0){
		//curl提交的参数
		if(isset($data->pk)){
			$post_pk = htmlspecialchars(addslashes($data->pk));
		}
		
		if(isset($data->v)){
			$post_v = htmlspecialchars(addslashes($data->v));
		}
		
		$post_email = strtolower(htmlspecialchars(addslashes($data->email)));
		$post_pwd = strtolower(htmlspecialchars(addslashes($data->pwd)));
		
	}else if(
		isset($_POST['email']) &&
		isset($_POST['pwd'])
		){
		//获取参数
		$post_pk = 'na';
		$post_v = 0;
		if(isset($_POST['pk']) && strlen($_POST['pk'])>0){$post_pk = htmlspecialchars(addslashes($_POST['pk']));}
		if(isset($_POST['v'])){$post_v = htmlspecialchars(addslashes($_POST['v']));}
		
		$post_email = strtolower(htmlspecialchars(addslashes($_POST['email'])));
		$post_pwd = strtolower(htmlspecialchars(addslashes($_POST['pwd'])));
	}else{
		//缺少参数
		$json['status']= 0;
		$json['usetime'] = endtime($start_time);
		$json['error'] = 'PostError';
		$json_code = json_encode($json);
		echo $json_code;
		die();
	}
	
	
	//初始化USER
	$user = new User();
	
	//建立数据库链接
	$user->creat_DataBase_Connection();
	
	//数据库链接失败
	if(!$user->connect_database){
		//服务器问题
		$json['status']= 2;
		$json['usetime'] = endtime($start_time);
		$json['error'] = 'ServerError';
		$json['desc'] = "额服务器开小差了,请稍后重试...";
		$json_code = json_encode($json);
		echo $json_code;
		
		//关闭连接
		$user->closeAllConnection();
		die();	
	}
			
	//检查邮箱存在不 
	if($user->check_user_email_exist($post_email)){
		//存在
		//密码对不
		if($user->check_user_email_pwd_match($post_email,$post_pwd)){
			//对
			
			//检查是否存在这个email在pcmaker_sign_state表中
			$query = 'SELECT * FROM `pcmaker_sign_state` '.
						' WHERE '. 
							'`email`=\''.$post_email.'\''.
						';';
			$result = mysqli_query($user->connect_database,$query);
			if($result){
				if(mysqli_num_rows($result)==0){
					//没有记录
					//记录入pcmaker_sign_state中
					$query = 'INSERT INTO pcmaker_sign_state(user_id,email,allow_sign_author,allow_sign_vip,pwd_set,add_time) VALUES ('.
										 $user->id.','.
										 '\''.$post_email.'\','.
										 '1,0,1,'.
										 'now()'.
					');';
					mysqli_query($user->connect_database, $query);
					
					//$json['debug'] .= $query;
					
					//同时返回信息
					//新用户需要验证邮箱
					//验证邮箱包含协议
					$json['opt'] = array(
						"name"=>"ckemail",
						"data"=>array(
							"url"=>"",
							"title"=>"补全信息：验证下邮箱吧！",
							"msg"=>""
						)
					);
			
				}else{
					//找到了
					$record = mysqli_fetch_assoc($result);	
					
					//先判断是否已经验证邮箱
					if($record['email_cked']==1){
						//是否有协议需要更新
						if($record['allow_sign_author']==1){
							//查询协议的最新版本
							$query = 'SELECT * FROM `pcmaker_sign_author_paper` ORDER BY `ver` DESC LIMIT 1;';
							$result = mysqli_query($user->connect_database,$query);
							if($result && mysqli_num_rows($result)>0){
								$paper = mysqli_fetch_assoc($result);
								if($paper['ver']>$record['sign_author_ver']){
									//需要更新协议
									$json['opt'] = array(
										"name"=>"signap",
										"data"=>array(
											"url"=>$paper['page_url'],
											"title"=>"协议更新：《图解电影投稿守则》",
											"msg"=>""
										)
									);	
								}else{
									//是否合格签约标准 
									//暂无
									$json['opt'] = array(
										"name"=>"main",
										"data"=>array(
											"url"=>"",
											"title"=>"",
											"msg"=>""
										)
									);	
								}
							}else{
								//直接登录
								$json['opt'] = array(
									"name"=>"main",
									"data"=>array(
										"url"=>"",
										"title"=>"",
										"msg"=>""
									)
								);	
							}
							
						}else{
							//直接登录
							$json['opt'] = array(
								"name"=>"main",
								"data"=>array(
									"url"=>"",
									"title"=>"",
									"msg"=>""
								)
							);		
						}
						
					}else{
						//需要验证邮箱
						$json['opt'] = array(
							"name"=>"ckemail",
							"data"=>array(
									"url"=>"",
									"title"=>"补全信息：验证下邮箱吧！",
									"msg"=>""
							)
						);	
					}
						
				}
			}
			
			 
			//不采用createResponse_me方法 太多无用查询
			
			//用户金币数
			$gold = 0;
			$query = 'SELECT * FROM `user_v_gold` '.
					' WHERE '. 
						' `user_id`='.$user->id .
					';';
					
			$result = mysqli_query($user->connect_database,$query);
			if($result && mysqli_num_rows($result)>0){
				$record = mysqli_fetch_assoc($result);
				$gold = (string)$record['gold'];
			}
			
			//经验
			$lvexp = 0;
			$currexp = 0;
			$nextexp = 1;
			$query = 'SELECT * FROM `user_v_exp` WHERE `user_id`='.$user->id.';';
			$result = mysqli_query($user->connect_database,$query);
			if($result && mysqli_num_rows($result)>0){
				$record = mysqli_fetch_assoc($result);
				$currexp = $record['exp'];
				
				//查当前级别的经验值原点
				$query = 'SELECT * FROM `exp_v_level` WHERE `exp`<'.$currexp.' ORDER BY `level` DESC LIMIT 1;';
				$result = mysqli_query($user->connect_database, $query);
				if($result && mysqli_num_rows($result)>0){
					$record = mysqli_fetch_assoc($result);
					$lvexp = $record['exp'];
				}else{
					//没查到
					//1级新用户
				}
				
				//再査下一级
				$query = 'SELECT * FROM `exp_v_level` WHERE `exp`>'.$currexp.' ORDER BY `level` ASC LIMIT 1;';
				$result = mysqli_query($user->connect_database, $query);
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
						' `user_id`='.$user->id.
					';';
			
			$result = mysqli_query($user->connect_database,$query);
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
						' `follow_user_id`='.$user->id .' AND `open`=1 '.
					';';
					
			$result = mysqli_query($user->connect_database,$query);
			if($result && mysqli_num_rows($result)>0){
				$record = mysqli_fetch_assoc($result);
				$fans = (string)number_2_numberFont($record['sumcount']);
			}
			
			//关注
			$follow = 0;
			$query = 'SELECT COUNT(*) AS sumcount FROM `user_v_follow_user` '.
					' WHERE '. 
						' `user_id`='.$user->id .' AND `open`=1 '.
					';';
					
			$result = mysqli_query($user->connect_database,$query);
			if($result && mysqli_num_rows($result)>0){
				$record = mysqli_fetch_assoc($result);
				$follow = (string)number_2_numberFont($record['sumcount']);
			}
			
			//此人的角色和等级
			$role = "";
			$level = "";
			$usermsg = array(
				'id'=>$user->id,
				'stat_work'=>$work_cked,
				'email'=>$user->email,
				'level'=>$user->level
			);
			$level 		= check_user_level_string($usermsg,$user->connect_memcache,$user->connect_database);
			$role 		= check_user_role_string($usermsg,$user->connect_memcache,$user->connect_database);
			
			//未读消息
			$unread = 0;
			$query = 'SELECT COUNT(*) AS sumcount FROM `pcmaker_msg` '.
					' WHERE '. 
						' `to_user_id`='.$user->id .' AND `readed`=0 '.
					';';
					
			$result = mysqli_query($user->connect_database,$query);
			if($result && mysqli_num_rows($result)>0){
				$record = mysqli_fetch_assoc($result);
				$unread = $record['sumcount'];
			}
			
		
			//user信息
			$json['user'] = array(
				"userid"=> (string)userIdKeyEncode($user->id),
				"nickname"=> $user->name?$user->name:randomDefaultName(),	
				"feeling" => strlen($user->feeling)>0?(string)$user->feeling:USERDEFAULT_FEELING,
				"avatar"=> $user->avatar?$user->avatar:'http://imgs4.graphmovie.com/appimage/app_default_avatar.jpg',	
				"role"=> $role,	
				"vip"=> 0,	
				"level"=> $level,
				"lvexp"=> $lvexp,	
				"currexp"=> $currexp,	
				"nextexp"=> $nextexp,	
				"email"=> $user->email,
				"pwd"=> strlen($user->secure_pwd_md5)>0?'areyoukiddingme':'',
				"gold"=> $gold,	
				"beplayed"=> (string)number_2_numberFont($user->stat_beplayed),
				"belike"=> (string)number_2_numberFont($user->stat_belike),
				"fans"=> $fans,
				"follow"=> $follow,
				"work_ing"=> $work_ing,
				"work_online"=> $work_online,
				"work_cked"=> $work_cked,
				"work_offline"=> $work_offline,
				"newmsg_count"=> $unread
			);
			
			//生成一个通信token
			//email + mac + timestamp + rand
			$tokenkey = $post_email.$post_pk.time().rand(10000, 100000);
			
			//$json['debug'] .= '['.$tokenkey.']';
			
			$token = md5($tokenkey);
			
			//$json['debug'] .= '['.$token.']';
			
			//记录入数据库
			$query = 'INSERT INTO pcmaker_request_token(user_id,pc_key,token,add_time) VALUES ('.
								 $user->id.','.
								 '\''.$post_pk.'\','.
								 '\''.$token.'\','.
								 'now()'.
			');';
			mysqli_query($user->connect_database, $query);
			
			//$json['debug'] .= '['.$query.']';
			
			$json['token'] = $token;
			
			//结束
			$json['status']= 1;
			$json['usetime'] = endtime($start_time);
			$json['error'] = '';
			$json_code = json_encode($json);
			echo $json_code;
			
			//关闭连接
			$user->closeAllConnection();
			die();		
			
			
		}else{
			//错
			$json['status']= 2;
			$json['usetime'] = endtime($start_time);
			$json['error'] = 'PasswordInvalid';
			$json['desc'] = "密码错了...";
			$json_code = json_encode($json);
			echo $json_code;
			
			//关闭连接
			$user->closeAllConnection();
			die();		
		}
		
	}else{
		$json['status']= 2;
		$json['usetime'] = endtime($start_time);
		$json['error'] = 'UserNotFound';
		$json['desc'] = "没有这个用户哎";
		$json_code = json_encode($json);
		echo $json_code;
		
		//关闭连接
		$user->closeAllConnection();
		die();	
	}

?>