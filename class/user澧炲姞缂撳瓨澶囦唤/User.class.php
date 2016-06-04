<?php
	//RootClass
	require_once(dirname(__FILE__).'/'.'MemDBConnector.class.php');
	require_once(dirname(__FILE__).'/'.'../inc/methods.inc.php');
	require_once(dirname(__FILE__).'/'.'../inc/time.methods.inc.php');
	class User extends MemDBConnector{
		//basic
		public $id;
		public $name;
		public $intro;
		public $email;
		public $phone_number;
		public $sex;
		public $age;
		public $avatar;
		public $open;
		public $add_time;
		public $update_time;
		//secure
		public $secure_pwd_md5;
		public $secure_question_1;
		public $secure_answer_1;
		public $secure_question_2;
		public $secure_answer_2;
		public $secure_question_3;
		public $secure_answer_3;
		//phone
		public $imei;
		public $phone_type;
		public $pub_channel;
		public $pub_platform;
		public $ver;
		//sns
		public $sns_qq_id;
		public $sns_qq_avatar;
		public $sns_qq_data;
		public $sns_qq_name;
		public $sns_qq_sex;
		public $sns_sinawb_id;
		public $sns_sinawb_avatar;
		public $sns_sinawb_data;
		public $sns_sinawb_name;
		public $sns_sinawb_sex;
		public $sns_bdyts_appid;
		public $sns_bdyts_userId;
		public $sns_bdyts_channelId;
		public $sns_bdyts_requestId;
		public $sns_bdyts_data;
		
		public $sns_qq_data_struct;
		public $sns_sinawb_data_struct;
		public $sns_bdyts_data_struct;
		
		//stat
		public $stat_follow;
		public $stat_befollow;
		public $stat_belike;
		public $stat_new;
		public $stat_work;
		public $stat_guess_pass;
		public $stat_beplayed;
		public $stat_user_new_unread;
		
		//被更名
		public $_bechange_name;
		
		//初始化
		function __construct(){
			parent::__construct(); 
			$this->reset_user_msg();
		}
		
		//重置用户信息
		function reset_user_msg(){
			$this->id = 0;
			$this->name = '';
			$this->intro = '';
			$this->email = '';
			$this->phone_number = '';
			$this->sex = 0;
			$this->age = 0;
			$this->avatar = '';
			$this->open = 1;
			$this->add_time = '';
			$this->update_time = '';
			$this->secure_pwd_md5 = '';
			$this->secure_question_1 = '';
			$this->secure_answer_1 = '';
			$this->secure_question_2 = '';
			$this->secure_answer_2 = '';
			$this->secure_question_3 = '';
			$this->secure_answer_3 = '';
			$this->imei = '';
			$this->phone_type = '';
			$this->pub_channel = '';
			$this->pub_platform = '';
			$this->ver = '0';
			$this->sns_qq_id = '0';
			$this->sns_qq_avatar = '';
			$this->sns_qq_data = '';
			$this->sns_qq_name = '';
			$this->sns_qq_sex = '';
			$this->sns_sinawb_id = '0';
			$this->sns_sinawb_avatar = '';
			$this->sns_sinawb_data = '';
			$this->sns_sinawb_name = '';
			$this->sns_sinawb_sex = '';
			$this->sns_bdyts_appid = '0';
			$this->sns_bdyts_userId = '0';
			$this->sns_bdyts_channelId = '';
			$this->sns_bdyts_requestId = '';
			$this->sns_bdyts_data = '';	
			$this->stat_follow = 0;
			$this->stat_befollow = 0;
			$this->stat_belike = 0;
			$this->stat_new = 0;
			$this->stat_work = 0;
			$this->stat_guess_pass = 0;
			
			$this->stat_beplayed = 0;
			$this->stat_user_new_unread = 0;
			
			$this->_bechange_name = false;
		}
		
		//加载用户基本信息
		function load_user($userid){
			$this->reset_user_msg();
			$this->id = $userid;
			
			//优先缓存加载
			if(!$this->connect_memcache){
				$this->creat_MemCache_Connection();
			}
			
			//检查缓存是否存在
			//检查user_id_{userid}
			$key = 'user_id_'.$this->id;
			if(($value = $this->connect_memcache-> get($key)) != FALSE) { 
				
				$this->name 				= $value['name'];
				$this->intro 				= $value['intro'];
				$this->email 				= $value['email'];
				$this->phone_number 		= $value['phone_number'];
				$this->sex 					= $value['sex'];
				$this->age 					= $value['age'];
				$this->avatar 				= $value['avatar'];
				$this->open 				= $value['open'];
				$this->add_time 			= $value['add_time'];
				$this->update_time 			= $value['update_time'];
				$this->secure_pwd_md5 		= $value['secure_pwd_md5'];
				$this->secure_question_1 	= $value['secure_question_1'];
				$this->secure_answer_1 		= $value['secure_answer_1'];
				$this->secure_question_2 	= $value['secure_question_2'];
				$this->secure_answer_2 		= $value['secure_answer_2'];
				$this->secure_question_3 	= $value['secure_question_3'];
				$this->secure_answer_3 		= $value['secure_answer_3'];
				$this->imei 				= $value['imei'];
				$this->phone_type 			= $value['phone_type'];
				$this->pub_channel 			= $value['pub_channel'];
				$this->pub_platform 		= $value['pub_platform'];
				$this->ver 					= $value['ver'];
				$this->sns_qq_id 			= $value['sns_qq_id'];
				$this->sns_qq_avatar 		= $value['sns_qq_avatar'];
				$this->sns_qq_data 			= $value['sns_qq_data'];
				$this->sns_qq_name 			= $value['sns_qq_name'];
				$this->sns_qq_sex 			= $value['sns_qq_sex'];
				$this->sns_sinawb_id 		= $value['sns_sinawb_id'];
				$this->sns_sinawb_avatar 	= $value['sns_sinawb_avatar'];
				$this->sns_sinawb_data 		= $value['sns_sinawb_data'];
				$this->sns_sinawb_name 		= $value['sns_sinawb_name'];
				$this->sns_sinawb_sex 		= $value['sns_sinawb_sex'];
				$this->sns_bdyts_appid 		= $value['sns_bdyts_appid'];
				$this->sns_bdyts_userId 	= $value['sns_bdyts_userId'];
				$this->sns_bdyts_channelId 	= $value['sns_bdyts_channelId'];
				$this->sns_bdyts_requestId 	= $value['sns_bdyts_requestId'];
				$this->sns_bdyts_data 		= $value['sns_bdyts_data'];
				$this->stat_follow 			= $value['stat_follow'];
				$this->stat_befollow 		= $value['stat_befollow'];
				$this->stat_belike 			= $value['stat_belike'];
				$this->stat_new 			= $value['stat_new'];
				$this->stat_work 			= $value['stat_work'];
				$this->stat_guess_pass 		= $value['stat_guess_pass'];
				
				$this->stat_beplayed 		= $value['stat_beplayed'];
				$this->stat_user_new_unread 		= $value['stat_user_new_unread'];
				return true;
			}
			
			//没找到 DB加载
			if(!$this->connect_database){
				$this->creat_DataBase_Connection();
			}
			
			$query = 'SELECT * FROM `client_user` WHERE `id`='.$this->id .';';
			$result = mysqli_query($this->connect_database,$query); 
			if($result){ 
				if(mysqli_num_rows($result)>0){
					//找到了
					$value = mysqli_fetch_assoc($result);
				
					$this->name 				= $value['name'];
					$this->intro 				= $value['intro'];
					$this->email 				= $value['email'];
					$this->phone_number 		= $value['phone_number'];
					$this->sex 					= $value['sex'];
					$this->age 					= $value['age'];
					$this->avatar 				= $value['avatar'];
					$this->open 				= $value['open'];
					$this->add_time 			= $value['add_time'];
					$this->update_time 			= $value['update_time'];
					$this->secure_pwd_md5 		= $value['secure_pwd_md5'];
					$this->secure_question_1 	= $value['secure_question_1'];
					$this->secure_answer_1 		= $value['secure_answer_1'];
					$this->secure_question_2 	= $value['secure_question_2'];
					$this->secure_answer_2 		= $value['secure_answer_2'];
					$this->secure_question_3 	= $value['secure_question_3'];
					$this->secure_answer_3 		= $value['secure_answer_3'];
					$this->imei 				= $value['imei'];
					$this->phone_type 			= $value['phone_type'];
					$this->pub_channel 			= $value['pub_channel'];
					$this->pub_platform 		= $value['pub_platform'];
					$this->ver 					= $value['ver'];
					$this->sns_qq_id 			= $value['sns_qq_id'];
					$this->sns_qq_avatar 		= $value['sns_qq_avatar'];
					$this->sns_qq_data 			= $value['sns_qq_data'];
					$this->sns_qq_name 			= $value['sns_qq_name'];
					$this->sns_qq_sex 			= $value['sns_qq_sex'];
					$this->sns_sinawb_id 		= $value['sns_sinawb_id'];
					$this->sns_sinawb_avatar 	= $value['sns_sinawb_avatar'];
					$this->sns_sinawb_data 		= $value['sns_sinawb_data'];
					$this->sns_sinawb_name 		= $value['sns_sinawb_name'];
					$this->sns_sinawb_sex 		= $value['sns_sinawb_sex'];
					$this->sns_bdyts_appid 		= $value['sns_bdyts_appid'];
					$this->sns_bdyts_userId 	= $value['sns_bdyts_userId'];
					$this->sns_bdyts_channelId 	= $value['sns_bdyts_channelId'];
					$this->sns_bdyts_requestId 	= $value['sns_bdyts_requestId'];
					$this->sns_bdyts_data 		= $value['sns_bdyts_data'];
					$this->stat_follow 			= $value['stat_follow'];
					$this->stat_befollow 		= $value['stat_befollow'];
					$this->stat_belike 			= $value['stat_belike'];
					$this->stat_new 			= $value['stat_new'];
					$this->stat_work 			= $value['stat_work'];
					$this->stat_guess_pass 		= $value['stat_guess_pass'];
					
					$this->stat_beplayed 		= $value['stat_beplayed'];
					$this->stat_user_new_unread 		= $value['stat_user_new_unread'];
					/*
					//这里写缓存
					*/
					//用户
					//不再写缓存
					//$this->save_to_memcache();
					//这里可以统计每小时活跃用户
					
					return true;
				}
			}
			$this->id = 0;
			//Mem和DB都没加载成功
			//Error
			return false;	
		}
		
		//写缓存
		function save_to_memcache(){
			$cache_json = array(
				'id' => $this->id,
				'name' => $this->name,
				'intro' => $this->intro,
				'email' => $this->email,
				'phone_number' => $this->phone_number,
				'sex' => $this->sex,
				'age' => $this->age,
				'avatar' => $this->avatar,
				'open' => $this->open,
				'add_time' => $this->add_time,
				'update_time' => $this->update_time,
				'secure_pwd_md5'=> $this->secure_pwd_md5,
				'secure_question_1' => $this->secure_question_1,
				'secure_answer_1' => $this->secure_answer_1,
				'secure_question_2' => $this->secure_question_2,
				'secure_answer_2' => $this->secure_answer_2,
				'secure_question_3' => $this->secure_question_3,
				'secure_answer_3' => $this->secure_answer_3,
				'imei' => $this->imei,
				'phone_type' => $this->phone_type,
				'pub_channel' => $this->pub_channel,
				'pub_platform' => $this->pub_platform,
				'ver' => $this->ver,
				'sns_qq_id' => $this->sns_qq_id,
				'sns_qq_avatar' => $this->sns_qq_avatar,
				'sns_qq_data' => $this->sns_qq_data,
				'sns_qq_name' => $this->sns_qq_name,
				'sns_qq_sex' => $this->sns_qq_sex,
				'sns_sinawb_id' => $this->sns_sinawb_id,
				'sns_sinawb_avatar' => $this->sns_sinawb_avatar,
				'sns_sinawb_data' => $this->sns_sinawb_data,
				'sns_sinawb_name' => $this->sns_sinawb_name,
				'sns_sinawb_sex' => $this->sns_sinawb_sex,
				'sns_bdyts_appid' => $this->sns_bdyts_appid,
				'sns_bdyts_userId' => $this->sns_bdyts_userId,
				'sns_bdyts_channelId' => $this->sns_bdyts_channelId,
				'sns_bdyts_requestId' => $this->sns_bdyts_requestId,
				'sns_bdyts_data' => $this->sns_bdyts_data,
				'stat_follow' => $this->stat_follow,
				'stat_befollow' => $this->stat_befollow,
				'stat_belike' => $this->stat_belike,
				'stat_new' => $this->stat_new,
				'stat_work' => $this->stat_work,
				'stat_guess_pass' => $this->stat_guess_pass,
				'stat_beplayed' => $thid->stat_beplayed,
				'stat_user_new_unread' => $this->stat_user_new_unread
			);
			
			$key = 'user_id_'.$this->id;
			if(($value = $this->connect_memcache-> get($key)) != FALSE) { 
				//用户
				//不再写缓存
				//$this->connect_memcache->replace($key, $cache_json, 0, 60*60);
			}else{
				//用户
				//不再写缓存
				//$this->connect_memcache->set($key, $cache_json, 0, 60*60);	
			}
		}
		
		//写数据库
		function save_to_database($save_basic,$save_secure,$save_phone,$save_sns,$save_stat){
			if(!($save_basic || $save_secure || $save_phone || $save_sns || $save_stat)){return true;}
			if(!$this->connect_database){
				$this->creat_DataBase_Connection();
			}
			
			$query = 'SELECT * FROM `client_user` WHERE `id`='.$this->id .';';
			$result = mysqli_query($this->connect_database,$query); 
			if($result){ 
				if(mysqli_num_rows($result)>0){
					//有 Update
					$link_str = '';
					$update_str = '';
					//拼凑更新字串
					if($save_basic){
						$update_str .= $link_str.
							'`name`='	.'\''.$this->name.'\''.','.
							'`intro`='	.'\''.$this->intro.'\''.','.
							'`email`='	.'\''.$this->email.'\''.','.
							'`phone_number`='	.'\''.$this->phone_number.'\''.','.
							'`sex`='	.$this->sex.','.
							'`age`='	.$this->age.','.
							'`avatar`='	.'\''.$this->avatar.'\''.','.
							'`open`='	.$this->open.','.
							'`update_time`='	.'now()';
						$link_str = ',';
					}
					
					if($save_secure){
						$update_str .= $link_str.
							'`secure_pwd_md5`='	.'\''.$this->secure_pwd_md5.'\''.','.
							'`secure_question_1`='	.'\''.$this->secure_question_1.'\''.','.
							'`secure_answer_1`='	.'\''.$this->secure_answer_1.'\''.','.
							'`secure_question_2`='	.'\''.$this->secure_question_2.'\''.','.
							'`secure_answer_2`='	.'\''.$this->secure_answer_2.'\''.','.
							'`secure_question_3`='	.'\''.$this->secure_question_3.'\''.','.
							'`secure_answer_3`='	.'\''.$this->secure_answer_3.'\'';
						$link_str = ',';
					}
					
					if($save_phone){
						$update_str .= $link_str.
							'`imei`='	.'\''.$this->imei.'\''.','.
							'`phone_type`='	.'\''.$this->phone_type.'\''.','.
							'`ver`='	.'\''.$this->ver.'\''.','.
							'`pub_channel`='	.'\''.$this->pub_channel.'\''.','.
							'`pub_platform`='	.'\''.$this->pub_platform.'\'';
						$link_str = ',';
					}
					
					if($save_sns){
						$update_str .= $link_str.
							'`sns_qq_id`='	.'\''.$this->sns_qq_id.'\''.','.
							'`sns_qq_avatar`='	.'\''.$this->sns_qq_avatar.'\''.','.
							'`sns_qq_data`='	.'\''.$this->sns_qq_data.'\''.','.
							'`sns_qq_name`='	.'\''.$this->sns_qq_name.'\''.','.
							'`sns_qq_sex`='	.'\''.$this->sns_qq_sex.'\''.','.
							'`sns_sinawb_id`='	.'\''.$this->sns_sinawb_id.'\''.','.
							'`sns_sinawb_avatar`='	.'\''.$this->sns_sinawb_avatar.'\''.','.
							'`sns_sinawb_data`='	.'\''.$this->sns_sinawb_data.'\''.','.
							'`sns_sinawb_name`='	.'\''.$this->sns_sinawb_name.'\''.','.
							'`sns_sinawb_sex`='	.'\''.$this->sns_sinawb_sex.'\''.','.
							'`sns_bdyts_appid`='	.'\''.$this->sns_bdyts_appid.'\''.','.
							'`sns_bdyts_userId`='	.'\''.$this->sns_bdyts_userId.'\''.','.
							'`sns_bdyts_channelId`='	.'\''.$this->sns_bdyts_channelId.'\''.','.
							'`sns_bdyts_requestId`='	.'\''.$this->sns_bdyts_requestId.'\''.','.
							'`sns_bdyts_data`='	.'\''.$this->sns_bdyts_data.'\'';
						$link_str = ',';
					}
					
					if($save_stat){
						$update_str .= $link_str.
							'`stat_follow`='		.$this->stat_follow.','.
							'`stat_befollow`='		.$this->stat_befollow.','.
							'`stat_belike`='		.$this->stat_belike.','.
							'`stat_new`='			.$this->stat_new.','.
							'`stat_work`='			.$this->stat_work.','.
							'`stat_guess_pass`='	.$this->stat_guess_pass;
						$link_str = ',';
					}
					
					$query = 'UPDATE `client_user` SET '.
						$update_str.
						' WHERE `id`='		.$this->id.
						';';
					$result = mysqli_query($this->connect_database,$query);
					if(!$result){ 
						return false;
					}
				}else{
					//没有 不操作
				}
				
				return true;
			}
			return false;	
		}
		
		//更新用户update_time
		function log_update_time(){
			if(!$this->connect_database){
				$this->creat_DataBase_Connection();
			}
			
			$query = 'UPDATE `client_user` SET '.
						'`update_time`='.'now()'.
						' WHERE `id`='	.$this->id.
						';';
			$result = mysqli_query($this->connect_database,$query);
		}
		
		//检查给定email是否已注册
		function check_user_email_exist($email){
			
			//直接数据库加载
			if(!$this->connect_database){
				$this->creat_DataBase_Connection();
			}
			
			//检查email
			$query = 'SELECT * FROM `client_user` WHERE `email`=\''.$email .'\';';
			$result = mysqli_query($this->connect_database,$query); 
			if($result){ 
				if(mysqli_num_rows($result)>0){
					//找到了
					//加载数据
					$value = mysqli_fetch_assoc($result);
					$this->reset_user_msg();
					$this->name 				= $value['name'];
					$this->intro 				= $value['intro'];
					$this->email 				= $value['email'];
					$this->phone_number 		= $value['phone_number'];
					$this->sex 					= $value['sex'];
					$this->age 					= $value['age'];
					$this->avatar 				= $value['avatar'];
					$this->open 				= $value['open'];
					$this->add_time 			= $value['add_time'];
					$this->update_time 			= $value['update_time'];
					$this->secure_pwd_md5 		= $value['secure_pwd_md5'];
					$this->secure_question_1 	= $value['secure_question_1'];
					$this->secure_answer_1 		= $value['secure_answer_1'];
					$this->secure_question_2 	= $value['secure_question_2'];
					$this->secure_answer_2 		= $value['secure_answer_2'];
					$this->secure_question_3 	= $value['secure_question_3'];
					$this->secure_answer_3 		= $value['secure_answer_3'];
					$this->imei 				= $value['imei'];
					$this->phone_type 			= $value['phone_type'];
					$this->pub_channel 			= $value['pub_channel'];
					$this->pub_platform 		= $value['pub_platform'];
					$this->ver 					= $value['ver'];
					$this->sns_qq_id 			= $value['sns_qq_id'];
					$this->sns_qq_avatar 		= $value['sns_qq_avatar'];
					$this->sns_qq_data 			= $value['sns_qq_data'];
					$this->sns_qq_name 			= $value['sns_qq_name'];
					$this->sns_qq_sex 			= $value['sns_qq_sex'];
					$this->sns_sinawb_id 		= $value['sns_sinawb_id'];
					$this->sns_sinawb_avatar 	= $value['sns_sinawb_avatar'];
					$this->sns_sinawb_data 		= $value['sns_sinawb_data'];
					$this->sns_sinawb_name 		= $value['sns_sinawb_name'];
					$this->sns_sinawb_sex 		= $value['sns_sinawb_sex'];
					$this->sns_bdyts_appid 		= $value['sns_bdyts_appid'];
					$this->sns_bdyts_userId 	= $value['sns_bdyts_userId'];
					$this->sns_bdyts_channelId 	= $value['sns_bdyts_channelId'];
					$this->sns_bdyts_requestId 	= $value['sns_bdyts_requestId'];
					$this->sns_bdyts_data 		= $value['sns_bdyts_data'];
					$this->stat_follow 			= $value['stat_follow'];
					$this->stat_befollow 		= $value['stat_befollow'];
					$this->stat_belike 			= $value['stat_belike'];
					$this->stat_new 			= $value['stat_new'];
					$this->stat_work 			= $value['stat_work'];
					$this->stat_guess_pass 		= $value['stat_guess_pass'];
					$this->stat_beplayed 		= $value['stat_beplayed'];
					$this->stat_user_new_unread 		= $value['stat_user_new_unread'];
					
					return true;
				}
			}
			return false;
		}
		
		//检查给定的email和pwd是否正确
		function check_user_email_pwd_match($email,$pwd){
			if($this->open==1 && $email == $this->email && $pwd == $this->pwd_md5){
				return true;
			}else{
				return false;	
			}
		}
		
		//创建新用户
		function creat_new_user($messages){
			/*
			$messages = array(
				'imei' => $p_imei,
				'phone_type' => $p_phone,
				'pub_platform' => $p_plat,
				'pub_channel' => $p_channel,
				'ver' => $p_ver
			);
			*/
			
			//清空用户信息
			$this->reset_user_msg();
			
			if(isset($messages['imei'])) $this->imei = $messages['imei'];
			if(isset($messages['phone_type'])) $this->phone_type = $messages['phone_type'];
			if(isset($messages['pub_platform'])) $this->pub_platform = $messages['pub_platform'];
			if(isset($messages['pub_channel'])) $this->pub_channel = $messages['pub_channel'];
			if(isset($messages['ver'])) $this->ver = $messages['ver'];
			
			//直接写数据库
			if(!$this->connect_database){
				$this->creat_DataBase_Connection();
			}
			
			//20140301更改新用户逻辑
			//同一个IMEI最多只存在唯一一个游客(匿名君)账户
			$query = 'SELECT * FROM `client_user` WHERE `imei`=\''.$this->imei .'\' AND `sns_qq_id` = \'0\' AND `sns_sinawb_id` = \'0\' AND `email`=\'\' ORDER BY `id` DESC;';
			$result = mysqli_query($this->connect_database,$query); 
			if($result){ 
				if(mysqli_num_rows($result)>0){
					//找到了
					//就不必再生成游客账户了
					$row = mysqli_fetch_assoc($result);
					$this->id = $row['id'];
					$this->name = $row['name'];
					return $row['id'];
				}
			}
			
			$random_name = randomDefaultName();
			
			
			//Insert
			$query = 'INSERT INTO client_user(name,avatar,imei,phone_type,pub_platform,pub_channel,ver,add_time,update_time) VALUES ('.
						'\''.$random_name.'\','.
						'\'http://imgs4.graphmovie.com/appimage/app_default_avatar.jpg\','.
						'\''.$this->imei.'\','.
						'\''.$this->phone_type.'\','.
						'\''.$this->pub_platform.'\','.
						'\''.$this->pub_channel.'\','.
						'\''.$this->ver.'\','.
						'now(),'.
						'now()'.
			');';
			
			$result = mysqli_query($this->connect_database,$query);
			if(!$result){
				//插入失败 
				return false;
			}
			
			//再查找出来新插入的id
			$query = 'SELECT * FROM `client_user` WHERE `imei`=\''.$this->imei .'\' ORDER BY `id` DESC LIMIT 1;';
			$result = mysqli_query($this->connect_database,$query); 
			if($result){ 
				if(mysqli_num_rows($result)>0){
					//找到了
					$row = mysqli_fetch_assoc($result);
					$this->id = $row['id'];
					$this->name = $row['name'];
					return $row['id'];
				}
			}
			
			return false;
		}
		
		//检查用户是否是匿名君状态
		function isUserVisitorStatus(){
			//SNS或者Email有一个绑定即不为匿名君
			if($this->isUserBindEmail() || $this->isUserBindSNS()){
				return false;	
			}
			
			return true;
		}
		
		//检查用户是否绑定了SNS
		function isUserBindSNS(){
			//Baiduyts不算
			
			if(strlen($this->sns_qq_id)>0 && $this->sns_qq_id != '0'){
				return true;
			}
			if(strlen($this->sns_sinawb_id)>0 && $this->sns_sinawb_id != '0'){
				return true;
			}
			
			return false;
		}
		
		//检查用户是否绑定了EMail
		function isUserBindEmail(){
			//Email
			if(strlen($this->email)>0 && $this->email != '0'){
				return true;
			}
			return false;
		}
		
		//解析SNSDATA
		//解析第三方返回JSON Data
		/*
		{
			"is_yellow_year_vip": "0",
			"ret": 0,
			"figureurl_qq_1": "http://q.qlogo.cn/qqapp/1101115824/E23412C936E1AFF0AE1FBB930B13D404/40",
			"figureurl_qq_2": "http://q.qlogo.cn/qqapp/1101115824/E23412C936E1AFF0AE1FBB930B13D404/100",
			"nickname": "无名",
			"yellow_vip_level": "0",
			"is_lost": 0,
			"msg": "",
			"figureurl_1": "http://qzapp.qlogo.cn/qzapp/1101115824/E23412C936E1AFF0AE1FBB930B13D404/50",
			"vip": "0",
			"level": "0",
			"figureurl_2": "http://qzapp.qlogo.cn/qzapp/1101115824/E23412C936E1AFF0AE1FBB930B13D404/100",
			"is_yellow_vip": "0",
			"gender": "男",
			"figureurl": "http://qzapp.qlogo.cn/qzapp/1101115824/E23412C936E1AFF0AE1FBB930B13D404/30"
		}
		*/
		function read_sns_qq_data($string,$snsid){
			if(strlen($snsid)){
				$this->sns_qq_id = $snsid;
			}
			if($this->sns_qq_data_struct = @json_decode($string)){
				if(!$this->load_sns_qq_dataStruct()){ return false;}
				$this->sns_qq_data = $string;
				return true;
			}else{
				return false;	
			}
		}
		//按照DataStruct加载信息
		function load_sns_qq_dataStruct(){
			if(!$this->sns_qq_data_struct){ return false;}
			
			if(isset($this->sns_qq_data_struct->nickname)){
			$this->sns_qq_name = $this->sns_qq_data_struct->nickname;}
			
			if(isset($this->sns_qq_data_struct->figureurl_qq_2)){
				$this->sns_qq_avatar = $this->sns_qq_data_struct->figureurl_qq_2;
			}elseif(isset($this->sns_qq_data_struct->figureurl_2)){
				$this->sns_qq_avatar = $this->sns_qq_data_struct->figureurl_2;
			}elseif(isset($this->sns_qq_data_struct->figureurl_1)){
				$this->sns_qq_avatar = $this->sns_qq_data_struct->figureurl_1;
			}elseif(isset($this->sns_qq_data_struct->figureurl_qq_1)){
				$this->sns_qq_avatar = $this->sns_qq_data_struct->figureurl_qq_1;
			}
			
			if(isset($this->sns_qq_data_struct->avatar_large)){
			$this->sns_qq_avatar = $this->sns_qq_data_struct->avatar_large;}
			
			if(isset($this->sns_qq_data_struct->gender)){
				if($this->sns_qq_data_struct->gender=='男'){
					$this->sns_qq_sex = 1;	
				}elseif($this->sns_qq_data_struct->gender=='女'){
					$this->sns_qq_sex = 2;	
				}else{
					$this->sns_qq_sex = 0;	
				}
			}
			return true;
		}
		
		//按照SNSID加载USERID
		function load_sns_qq_id($snsid){
			
			if(!$this->connect_memcache){
				$this->creat_MemCache_Connection();
			}
			//检查user_sns_qq_id_{snsid}
			$key = 'user_sns_qq_id_'.$snsid;
			if(($value = $this->connect_memcache-> get($key)) != FALSE) { 
				if(strlen($value)>0){
					return $value;	
				}
			}
			
			//再检查数据库
			if(!$this->connect_database){
				$this->creat_DataBase_Connection();
			}
			
			$query = 'SELECT * FROM `client_user` WHERE `sns_qq_id`=\''.$snsid .'\';';
			$result = mysqli_query($this->connect_database,$query); 
			if($result){ 
				if(mysqli_num_rows($result)>0){
					$value = mysqli_fetch_assoc($result);
					$this->reset_user_msg();
					$this->id 					= $value['id'];
					$this->name 				= $value['name'];
					$this->intro 				= $value['intro'];
					$this->email 				= $value['email'];
					$this->phone_number 		= $value['phone_number'];
					$this->sex 					= $value['sex'];
					$this->age 					= $value['age'];
					$this->avatar 				= $value['avatar'];
					$this->open 				= $value['open'];
					$this->add_time 			= $value['add_time'];
					$this->update_time 			= $value['update_time'];
					$this->secure_pwd_md5 		= $value['secure_pwd_md5'];
					$this->secure_question_1 	= $value['secure_question_1'];
					$this->secure_answer_1 		= $value['secure_answer_1'];
					$this->secure_question_2 	= $value['secure_question_2'];
					$this->secure_answer_2 		= $value['secure_answer_2'];
					$this->secure_question_3 	= $value['secure_question_3'];
					$this->secure_answer_3 		= $value['secure_answer_3'];
					$this->imei 				= $value['imei'];
					$this->phone_type 			= $value['phone_type'];
					$this->pub_channel 			= $value['pub_channel'];
					$this->pub_platform 		= $value['pub_platform'];
					$this->ver 					= $value['ver'];
					$this->sns_qq_id 			= $value['sns_qq_id'];
					$this->sns_qq_avatar 		= $value['sns_qq_avatar'];
					$this->sns_qq_data 			= $value['sns_qq_data'];
					$this->sns_qq_name 			= $value['sns_qq_name'];
					$this->sns_qq_sex 			= $value['sns_qq_sex'];
					$this->sns_sinawb_id 		= $value['sns_sinawb_id'];
					$this->sns_sinawb_avatar 	= $value['sns_sinawb_avatar'];
					$this->sns_sinawb_data 		= $value['sns_sinawb_data'];
					$this->sns_sinawb_name 		= $value['sns_sinawb_name'];
					$this->sns_sinawb_sex 		= $value['sns_sinawb_sex'];
					$this->sns_bdyts_appid 		= $value['sns_bdyts_appid'];
					$this->sns_bdyts_userId 	= $value['sns_bdyts_userId'];
					$this->sns_bdyts_channelId 	= $value['sns_bdyts_channelId'];
					$this->sns_bdyts_requestId 	= $value['sns_bdyts_requestId'];
					$this->sns_bdyts_data 		= $value['sns_bdyts_data'];
					$this->stat_follow 			= $value['stat_follow'];
					$this->stat_befollow 		= $value['stat_befollow'];
					$this->stat_belike 			= $value['stat_belike'];
					$this->stat_new 			= $value['stat_new'];
					$this->stat_work 			= $value['stat_work'];
					$this->stat_guess_pass 		= $value['stat_guess_pass'];
					$this->stat_beplayed 		= $value['stat_beplayed'];
					$this->stat_user_new_unread 		= $value['stat_user_new_unread'];
					return $value['id'];		
				}
			}
			
			return false;	
		}
		
		//解析第三方返回JSON Data
		/*
		{
			"id": 3652494872,
			"idstr": "3652494872",
			"class": 1,
			"screen_name": "图解电影GM",
			"name": "图解电影GM",
			"province": "44",
			"city": "3",
			"location": "广东 深圳",
			"description": "图解电影，最专业的在线电影图解APP！十分钟品味精彩影视！一夜变身电影达人！",
			"url": "http://www.graphmovie.com",
			"profile_image_url": "http://tp1.sinaimg.cn/3652494872/50/5671013770/0",
			"profile_url": "graphmovie",
			"domain": "graphmovie",
			"weihao": "",
			"gender": "f",
			"followers_count": 11817,
			"friends_count": 57,
			"statuses_count": 198,
			"favourites_count": 1,
			"created_at": "Tue Jul 23 15:49:06 +0800 2013",
			"following": false,
			"allow_all_act_msg": false,
			"geo_enabled": true,
			"verified": true,
			"verified_type": 6,
			"remark": "",
			"status": {
				"created_at": "Thu Jan 02 00:54:21 +0800 2014",
				"id": 3662096308826022,
				"mid": "3662096308826022",
				"idstr": "3662096308826022",
				"text": "2014年，一切将由你而新生。努力孕育中的图解电影3.0，将带着你、我、Ta的需求和想象，强势出发。猛击链接：http://t.cn/8kgW63L，参与问卷反馈！一起来告诉我们，你心目中的图解电影，应该是什么样子？一切的新生，正来自于你们的心声！2014让我们继续，一路相伴，与你同行！",
				"source": "<a href=\"http://weibo.com/\" rel=\"nofollow\">新浪微博</a>",
				"favorited": false,
				"truncated": false,
				"in_reply_to_status_id": "",
				"in_reply_to_user_id": "",
				"in_reply_to_screen_name": "",
				"pic_urls": [
					{
						"thumbnail_pic": "http://ww3.sinaimg.cn/thumbnail/d9b4a618gw1ec4j1hnj9fj20hs0csjsj.jpg"
					}
				],
				"thumbnail_pic": "http://ww3.sinaimg.cn/thumbnail/d9b4a618gw1ec4j1hnj9fj20hs0csjsj.jpg",
				"bmiddle_pic": "http://ww3.sinaimg.cn/bmiddle/d9b4a618gw1ec4j1hnj9fj20hs0csjsj.jpg",
				"original_pic": "http://ww3.sinaimg.cn/large/d9b4a618gw1ec4j1hnj9fj20hs0csjsj.jpg",
				"geo": null,
				"reposts_count": 0,
				"comments_count": 0,
				"attitudes_count": 0,
				"mlevel": 0,
				"visible": {
					"type": 0,
					"list_id": 0
				}
			},
			"ptype": 0,
			"allow_all_comment": true,
			"avatar_large": "http://tp1.sinaimg.cn/3652494872/180/5671013770/0",
			"avatar_hd": "http://ww3.sinaimg.cn/crop.0.0.1024.1024.1024/d9b4a618jw1e7gm73bngjj20sg0sg0vj.jpg",
			"verified_reason": "图解电影APP官方微博",
			"follow_me": false,
			"online_status": 1,
			"bi_followers_count": 24,
			"lang": "zh-cn",
			"star": 0,
			"mbtype": 2,
			"mbrank": 2,
			"block_word": 0
		}
		*/
		function read_sns_sinawb_data($string,$snsid){
			if(strlen($snsid)){
				$this->sns_sinawb_id = $snsid;
			}
			if($this->sns_sinawb_data_struct = @json_decode($string)){
				if(!$this->load_sns_sinawb_dataStruct()){ return false;}
				$this->sns_sinawb_data = $string;
				return true;
			}else{
				return false;	
			}
		}
		
		//按照DataStruct加载信息
		function load_sns_sinawb_dataStruct(){
			if(!$this->sns_sinawb_data_struct){ return false;}
			if(isset($this->sns_sinawb_data_struct->idstr)){
			$this->sns_sinawb_id = $this->sns_sinawb_data_struct->idstr;}
			
			if(isset($this->sns_sinawb_data_struct->screen_name)){
			$this->sns_sinawb_name = $this->sns_sinawb_data_struct->screen_name;}
			
			if(isset($this->sns_sinawb_data_struct->avatar_large)){
			$this->sns_sinawb_avatar = $this->sns_sinawb_data_struct->avatar_large;}
			
			if(isset($this->sns_sinawb_data_struct->gender)){
				if($this->sns_sinawb_data_struct->gender=='m'){
					$this->sns_sinawb_sex = 1;	
				}elseif($this->sns_sinawb_data_struct->gender=='f'){
					$this->sns_sinawb_sex = 2;	
				}else{
					$this->sns_sinawb_sex = 0;	
				}
			}
			return true;
		}
		
		//按照SNSID加载USERID
		function load_sns_sinawb_id($snsid){
			
			if(!$this->connect_memcache){
				$this->creat_MemCache_Connection();
			}
			//检查user_sns_sinawb_id_{snsid}
			$key = 'user_sns_sinawb_id_'.$snsid;
			if(($value = $this->connect_memcache-> get($key)) != FALSE) { 
				if(strlen($value)>0){
					return $value;	
				}
			}
			
			//再检查数据库
			if(!$this->connect_database){
				$this->creat_DataBase_Connection();
			}
			
			$query = 'SELECT * FROM `client_user` WHERE `sns_sinawb_id`=\''.$snsid .'\';';
			$result = mysqli_query($this->connect_database,$query); 
			if($result){ 
				if(mysqli_num_rows($result)>0){
					$value = mysqli_fetch_assoc($result);
					$this->reset_user_msg();
					$this->id 					= $value['id'];
					$this->name 				= $value['name'];
					$this->intro 				= $value['intro'];
					$this->email 				= $value['email'];
					$this->phone_number 		= $value['phone_number'];
					$this->sex 					= $value['sex'];
					$this->age 					= $value['age'];
					$this->avatar 				= $value['avatar'];
					$this->open 				= $value['open'];
					$this->add_time 			= $value['add_time'];
					$this->update_time 			= $value['update_time'];
					$this->secure_pwd_md5 		= $value['secure_pwd_md5'];
					$this->secure_question_1 	= $value['secure_question_1'];
					$this->secure_answer_1 		= $value['secure_answer_1'];
					$this->secure_question_2 	= $value['secure_question_2'];
					$this->secure_answer_2 		= $value['secure_answer_2'];
					$this->secure_question_3 	= $value['secure_question_3'];
					$this->secure_answer_3 		= $value['secure_answer_3'];
					$this->imei 				= $value['imei'];
					$this->phone_type 			= $value['phone_type'];
					$this->pub_channel 			= $value['pub_channel'];
					$this->pub_platform 		= $value['pub_platform'];
					$this->ver 					= $value['ver'];
					$this->sns_qq_id 			= $value['sns_qq_id'];
					$this->sns_qq_avatar 		= $value['sns_qq_avatar'];
					$this->sns_qq_data 			= $value['sns_qq_data'];
					$this->sns_qq_name 			= $value['sns_qq_name'];
					$this->sns_qq_sex 			= $value['sns_qq_sex'];
					$this->sns_sinawb_id 		= $value['sns_sinawb_id'];
					$this->sns_sinawb_avatar 	= $value['sns_sinawb_avatar'];
					$this->sns_sinawb_data 		= $value['sns_sinawb_data'];
					$this->sns_sinawb_name 		= $value['sns_sinawb_name'];
					$this->sns_sinawb_sex 		= $value['sns_sinawb_sex'];
					$this->sns_bdyts_appid 		= $value['sns_bdyts_appid'];
					$this->sns_bdyts_userId 	= $value['sns_bdyts_userId'];
					$this->sns_bdyts_channelId 	= $value['sns_bdyts_channelId'];
					$this->sns_bdyts_requestId 	= $value['sns_bdyts_requestId'];
					$this->sns_bdyts_data 		= $value['sns_bdyts_data'];
					$this->stat_follow 			= $value['stat_follow'];
					$this->stat_befollow 		= $value['stat_befollow'];
					$this->stat_belike 			= $value['stat_belike'];
					$this->stat_new 			= $value['stat_new'];
					$this->stat_work 			= $value['stat_work'];
					$this->stat_guess_pass 		= $value['stat_guess_pass'];
					$this->stat_beplayed 		= $value['stat_beplayed'];
					$this->stat_user_new_unread 		= $value['stat_user_new_unread'];
					return $value['id'];		
				}
			}
			
			return false;	
		}
		
		//百度云推送
		//解析第三方返回JSON Data
		/*
		onBind:onBind errorCode=0 appid=1185357 userId=692361033542731277 channelId=3750997923735370515 requestId=4269075777
		*/
		function read_sns_bdyts_data($string,$userId){
			if(strlen($userId)){
				$this->sns_bdyts_userId = $userId;
			}
			if($this->sns_bdyts_data_struct = @json_decode($string)){
				$this->sns_bdyts_userId = $this->sns_bdyts_data_struct->userId;
				$this->sns_bdyts_appid = $this->sns_bdyts_data_struct->appid;
				$this->sns_bdyts_chanelId = $this->sns_bdyts_data_struct->channelId;
				$this->sns_bdyts_requestId = $this->sns_bdyts_data_struct->requestId;
				$this->sns_bdyts_data = $string;

				return true;
			}else{
				return false;	
			}
		}
		
		//按照SNSID加载USERID
		function load_sns_bdyts_id($snsid){
			
			if(!$this->connect_memcache){
				$this->creat_MemCache_Connection();
			}
			//检查user_sns_bdyts_id_{snsid}
			$key = 'user_sns_bdyts_id_'.$snsid;
			if(($value = $this->connect_memcache-> get($key)) != FALSE) { 
				if(strlen($value)>0){
					return $value;	
				}
			}
			
			//再检查数据库
			if(!$this->connect_database){
				$this->creat_DataBase_Connection();
			}
			
			$query = 'SELECT * FROM `client_user` WHERE `sns_bdyts_userId`=\''.$snsid .'\';';
			$result = mysqli_query($this->connect_database,$query); 
			if($result){ 
				if(mysqli_num_rows($result)>0){
					$value = mysqli_fetch_assoc($result);
					$this->reset_user_msg();
					$this->id 					= $value['id'];
					$this->name 				= $value['name'];
					$this->intro 				= $value['intro'];
					$this->email 				= $value['email'];
					$this->phone_number 		= $value['phone_number'];
					$this->sex 					= $value['sex'];
					$this->age 					= $value['age'];
					$this->avatar 				= $value['avatar'];
					$this->open 				= $value['open'];
					$this->add_time 			= $value['add_time'];
					$this->update_time 			= $value['update_time'];
					$this->secure_pwd_md5 		= $value['secure_pwd_md5'];
					$this->secure_question_1 	= $value['secure_question_1'];
					$this->secure_answer_1 		= $value['secure_answer_1'];
					$this->secure_question_2 	= $value['secure_question_2'];
					$this->secure_answer_2 		= $value['secure_answer_2'];
					$this->secure_question_3 	= $value['secure_question_3'];
					$this->secure_answer_3 		= $value['secure_answer_3'];
					$this->imei 				= $value['imei'];
					$this->phone_type 			= $value['phone_type'];
					$this->pub_channel 			= $value['pub_channel'];
					$this->pub_platform 		= $value['pub_platform'];
					$this->ver 					= $value['ver'];
					$this->sns_qq_id 			= $value['sns_qq_id'];
					$this->sns_qq_avatar 		= $value['sns_qq_avatar'];
					$this->sns_qq_data 			= $value['sns_qq_data'];
					$this->sns_qq_name 			= $value['sns_qq_name'];
					$this->sns_qq_sex 			= $value['sns_qq_sex'];
					$this->sns_sinawb_id 		= $value['sns_sinawb_id'];
					$this->sns_sinawb_avatar 	= $value['sns_sinawb_avatar'];
					$this->sns_sinawb_data 		= $value['sns_sinawb_data'];
					$this->sns_sinawb_name 		= $value['sns_sinawb_name'];
					$this->sns_sinawb_sex 		= $value['sns_sinawb_sex'];
					$this->sns_bdyts_appid 		= $value['sns_bdyts_appid'];
					$this->sns_bdyts_userId 	= $value['sns_bdyts_userId'];
					$this->sns_bdyts_channelId 	= $value['sns_bdyts_channelId'];
					$this->sns_bdyts_requestId 	= $value['sns_bdyts_requestId'];
					$this->sns_bdyts_data 		= $value['sns_bdyts_data'];
					$this->stat_follow 			= $value['stat_follow'];
					$this->stat_befollow 		= $value['stat_befollow'];
					$this->stat_belike 			= $value['stat_belike'];
					$this->stat_new 			= $value['stat_new'];
					$this->stat_work 			= $value['stat_work'];
					$this->stat_guess_pass 		= $value['stat_guess_pass'];
					$this->stat_beplayed 		= $value['stat_beplayed'];
					$this->stat_user_new_unread 		= $value['stat_user_new_unread'];
					return $value['id'];		
				}
			}
			
			return false;	
		}
		
		//解绑百度云推送
		//百度云推送始终绑定在客户端手机最后一次成功登录的账户上
		function debind_bdyts(){
			if($this->id>0 && strlen($this->sns_bdyts_userId)>0){
				if(!$this->connect_database){
					$this->creat_DataBase_Connection();
				}
				
				$query = 'UPDATE `client_user` SET '.
							'`sns_bdyts_appid`='.'\'0\','.
							'`sns_bdyts_userId`='.'\'0\','.
							'`sns_bdyts_channelId`='.'\'0\','.
							'`sns_bdyts_requestId`='.'\'0\','.
							'`sns_bdyts_data`='.'\'\','.
							' WHERE `id`='	.$this->id.
							';';
				$result = mysqli_query($this->connect_database,$query);
			}
		}
		
		//生成用户基本信息
		//用于登录注册验证等返回
		function createResponse_me($json){
			//Response {me}
			/*
			me{
				{msg}
				{sns_bind}
				{stat}
				{menu}
				}
			*/
			if(!$json){$json = array();}
			
			$json['me'] = array();
			
			//{msg}
			/*
				msg{
					user_id
					name
					intro
					sex
					avatar
					email
					age
				}
			*/
			$json['me']['msg'] 	= array();
			$json['me']['msg']['user_id'] 	= (string)userIdKeyEncode($this->id);
			$json['me']['msg']['name'] 		= $this->name?$this->name:randomDefaultName();
			$json['me']['msg']['intro'] 	= $this->intro;
			
			//当前没有intro
			//显示固定内容
			//如果是匿名君就显示:↑登录就能改名儿啦
			//如果不是匿名君未绑定邮箱:快绑定邮箱吧!
			//如果绑定邮箱:邮箱地址
			
			//没有绑定SNS
			if(strlen($this->email)>0){
				if($this->_bechange_name){
					$json['me']['msg']['intro'] = '↑昵称已存在,被更名';	
				}else{
					$json['me']['msg']['intro'] = $this->email;	
				}
			}else{
				if($this->_bechange_name){
					$json['me']['msg']['intro'] = '↑昵称已存在,被更名';	
				}else{
					if(strlen($this->sns_qq_id)<=2 && strlen($this->sns_sinawb_id)<=2){
						$json['me']['msg']['intro'] = '↑登录就能改名儿啦';	
					}else{
						$json['me']['msg']['intro'] = '快绑定邮箱吧!';
					}
				}
			}	
			
			$json['me']['msg']['sex'] 		= (string)$this->sex;
			$json['me']['msg']['avatar'] 	= $this->avatar?$this->avatar:'http://imgs4.graphmovie.com/appimage/app_default_avatar.jpg';
			$json['me']['msg']['email'] 	= $this->email;
			$json['me']['msg']['age'] 		= (string)$this->age;
			
			//{sns_bind}
			/*
				qq
				sinawb
				baiduyts
			*/
			$json['me']['sns_bind'] = array();
			$json['me']['sns_bind']['qq'] = (string)$this->sns_qq_id;
			$json['me']['sns_bind']['sinawb'] = (string)$this->sns_sinawb_id;
			$json['me']['sns_bind']['baiduyts'] = (string)$this->sns_bdyts_userId;
			
			//{stat}
			$json['me']['stat'] = array();
			$json['me']['stat']['avr'] = $this->stat_new>99?'99+':(string)$this->stat_new;
			$json['me']['stat']['works'] = (string)number_2_numberFont($this->stat_work);
			$json['me']['stat']['follow'] = (string)number_2_numberFont($this->stat_follow);
			$json['me']['stat']['befollow'] = (string)number_2_numberFont($this->stat_befollow);
			$json['me']['stat']['news'] = (string)$this->stat_user_new_unread;
			$json['me']['stat']['belike'] = (string)number_2_numberFont($this->stat_belike);
			$json['me']['stat']['beplayed'] = (string)number_2_numberFont($this->stat_beplayed);
			
			
			
			//{menu}
			if(!$this->connect_database){
				$this->creat_DataBase_Connection();
			}
			//这里要写缓存,避免每个用户都查询肥皂榜和排行榜
			if(!$this->connect_memcache){
				$this->creat_MemCache_Connection();
			}
			
			$ranking_str = '榜主空缺哟,速来~';
			$topic_str = '貌似木有专题上线.';
			$home_str = '咦!为毛什么都没有发现!';
			$guess_str = '掐指一算！你搞基！';
			$picksoap_str = '谁捡谁知道,嗯哼～';
			
			//最新一部电影
			//检查缓存中是否存在
			/*
			user_class_ck_lastmv
			_{ver}
			_{pub_platform}
			_{pub_channel}
			*/
			$cache_key = 'user_class_ck_lastmv'
					.'_'.$this->ver
					.'_'.$this->pub_platform
					.'_'.$this->pub_channel;
			if(($value = $this->connect_memcache-> get($cache_key)) != FALSE) {  
				//有
				$home_str = $value;
			}else{
				$query = 'SELECT * FROM `movie_online_map` '.
						' WHERE '. 
							' `pub_platform`=\''.$this->pub_platform .'\' AND '.
							' `pub_channel`=\''.$this->pub_channel .'\''.
						';';
						
				$result = mysqli_query($this->connect_database,$query);
				if($result && mysqli_num_rows($result)>0){
					$movie_online_map = mysqli_fetch_assoc($result);
					$all_movie = explode(',',$movie_online_map['online_movie']);
					if(is_array($all_movie) && count($all_movie)>0){
						
						foreach($all_movie as $all_movie_id){
							//取
							$query = 'SELECT * FROM `movie` '.
									' WHERE '. 
										'`id`='.$all_movie_id.' '.
									';';
							$result = mysqli_query($this->connect_database,$query);
							if($result && mysqli_num_rows($result)>0){
								$movie = mysqli_fetch_assoc($result);
								if($movie['limit_listshow']==0){
									//if($movie['jian']==0){
										$home_str = '最近上线：《'.$movie['name'].'》';
										break;
									//}
								}else{
									$home_str = '就不告诉你,就不告诉你';
								}
							}else{
								//$home_str = 'ERROR:'.$query;
							}
						}
					}else{
						//$home_str = 'MAP:'.$movie_online_map['online_movie'];
					}
				}else{
					//$home_str = 'MOM_ERROR:'.$query;	
				}
				
				$this->connect_memcache->set($cache_key, $home_str, 0, 300);
			}
			
			//最新一部专题
			//检查缓存中是否存在
			/*
			user_class_ck_lasttopic
			_{ver}
			_{pub_platform}
			_{pub_channel}
			*/
			$cache_key = 'user_class_ck_lasttopic'
					.'_'.$this->ver
					.'_'.$this->pub_platform
					.'_'.$this->pub_channel;
			if(($value = $this->connect_memcache-> get($cache_key)) != FALSE) {  
				//有
				$topic_str = $value;
			}else{
				$query = 'SELECT * FROM `topic_online_map` '.
						' WHERE '. 
							' `pub_platform`=\''.$this->pub_platform .'\' AND '.
							' `pub_channel`=\''.$this->pub_channel .'\''.
						';';
						
				$result = mysqli_query($this->connect_database,$query);
				if($result && mysqli_num_rows($result)>0){
					$topic_online_map = mysqli_fetch_assoc($result);
					$all_topic = explode(',',$topic_online_map['online_topic']);
					if(is_array($all_topic) && count($all_topic)>0){
						//取0
						$query = 'SELECT * FROM `topic` '.
								' WHERE '. 
									'`id`='.$all_topic[0].' '.
								';';
						$result = mysqli_query($this->connect_database,$query);
						if($result && mysqli_num_rows($result)>0){
							$topic = mysqli_fetch_assoc($result);
							$topic_str = '最近上线：《'.$topic['name'].'》';
						}else{
							//$topic_str = $query;	
						}
					}
				}
				$this->connect_memcache->set($cache_key, $topic_str, 0, 300);
			}
			
			//这里要查询排行榜第一名
			//检查缓存中是否存在
			/*
			user_class_ck_lastrank
			_{ver}
			_{pub_platform}
			_{pub_channel}
			*/
			$cache_key = 'user_class_ck_lastrank'
					.'_'.$this->ver
					.'_'.$this->pub_platform
					.'_'.$this->pub_channel;
			if(($value = $this->connect_memcache-> get($cache_key)) != FALSE) {  
				//有
				$ranking_str = $value;
			}else{
				$query = 'SELECT * FROM `client_user` '.
							' WHERE '. 
								'`open`=1 AND '.
								'`stat_work`>0 AND '.
								'`limit_grapherlist`=0 '.
							' ORDER BY `stat_belike` DESC '.
							' LIMIT 1'.
							';';
				$result = mysqli_query($this->connect_database,$query);
				if($result){ 
					if(mysqli_num_rows($result)>0){
						$value = mysqli_fetch_assoc($result);
						$ranking_str = '红人榜主：'.$value['name'].' (❤'.$value['stat_belike'].')';
					}
				}
				$this->connect_memcache->set($cache_key, $ranking_str, 0, 120);
			}
			
			//猜你喜欢
			//user_tag_map
			$query = 'SELECT `tag_id` FROM `user_tag_map` '.
						' WHERE '. 
							'`user_id`='.$this->id.' AND '.
							'`tag_id` > 1 '.
						' ORDER BY `pvalue` DESC '.
						' LIMIT 1'.
						';';
			$result = mysqli_query($this->connect_database,$query);
			if($result){ 
				if(mysqli_num_rows($result)>0){
					$value = mysqli_fetch_assoc($result);
					//找到了
					$like_tag_id = $value['tag_id'];
					//查找该tag的名字
					$query = 'SELECT `name` FROM `m_tag` '.
						' WHERE '. 
							'`id`='.$like_tag_id.
						';';
					$result = mysqli_query($this->connect_database,$query);
					if($result){
						if(mysqli_num_rows($result)>0){
							$value = mysqli_fetch_assoc($result);
							$guess_str = '看来你喜欢'.$value['name'].'类的影片哟!';
						}
					}
				}
			}
			
			//肥皂
			//检查缓存中是否存在
			//如果为31版本,3.0 不用检查肥皂，直接返回需要更新
			if($this->ver<=31){
				$picksoap_str = '⊙▽⊙点此更新【图解电影3.1版】！';
			}else{
			/*
			user_class_ck_lastsoap
			*/
			$cache_key = 'user_class_ck_lastsoap';
			if(($value = $this->connect_memcache-> get($cache_key)) != FALSE) {  
				//有
				$picksoap_str = $value;
			}else{
				$query = 'SELECT * FROM `user_pick_soap` '.
							' WHERE '. 
								'`is_pick_up`=1 '.
							' ORDER BY `add_time` DESC '.
							' LIMIT 1'.
							';';
				$result = mysqli_query($this->connect_database,$query);
				
				if($result && mysqli_num_rows($result)>0){
					$pick = mysqli_fetch_assoc($result);
					//找到了
					$pick_userid = $pick['user_id'];
					
					//查找该user的名字
					$query = 'SELECT `name` FROM `client_user` '.
						' WHERE '. 
							'`id`='.$pick_userid.
						';';
					$result = mysqli_query($this->connect_database,$query);
					if($result){
						if(mysqli_num_rows($result)>0){
							$value = mysqli_fetch_assoc($result);
							$picksoap_str = $value['name'].' '.getDateStyle(strtotime($pick['add_time'])).' 捡起了肥皂君！!';
						}
					}
				}else{
					//没有人捡到	
					$query = 'SELECT COUNT(*) AS \'sumpick\' FROM `user_pick_soap`;';
					$result = mysqli_query($this->connect_database,$query);
					if($result && mysqli_num_rows($result)>0){
						$value = mysqli_fetch_assoc($result);
						$picksoap_str = '共有 '.number_2_numberFont($value['sumpick']).' 人次调戏了肥皂君！!';	
					}
					
				}
				$this->connect_memcache->set($cache_key, $picksoap_str, 0, 300);
			}
			}
			
			//广播信息
			//radio_messages
			if($this->ver>32){
				$query = 'SELECT * FROM `radio_messages` '.
							' WHERE '. 
								'`pub_platform`=\''.$this->pub_platform.'\' AND '.
								'`pub_channel`=\''.$this->pub_channel.'\' AND '.
								'`ver`=\''.$this->ver.'\' AND '.
								'`open` = 1 '.
							' ORDER BY `add_time` DESC '.
							' LIMIT 1'.
							';';
				$result = mysqli_query($this->connect_database,$query);
				if($result){ 
					if(mysqli_num_rows($result)>0){
						$value = mysqli_fetch_assoc($result);
						//找到了
						$radio_id = $value['id'];
						
						if($value['repeat']==1){
							//可以重复领取的信息
							$json['alert'] = array(
								'content'=>$value['content'],
								'btn_title'=>$value['btn_title'],
								'btn_url'=>$value['btn_url']
							);
							//记录领取
							$query = 'INSERT INTO radio_take_record(user_id,radio_id,add_time) VALUES ('.
											 $this->id.','.
											 $radio_id.','.
											 'now()'.
							');';
							mysqli_query($this->connect_database, $query);
							
						}else{
							
							//查找该信息是否已被领取
							$query = 'SELECT * FROM `radio_take_record` '.
								' WHERE '. 
									'`user_id`='.$this->id.' AND '.
									'`radio_id`='.$radio_id.
								';';
							$result = mysqli_query($this->connect_database,$query);
							if($result && mysqli_num_rows($result)>0){
								//找到了
							}else{
								//没找到就领取
								$json['alert'] = array(
									'content'=>$value['content'],
									'btn_title'=>$value['btn_title'],
									'btn_url'=>$value['btn_url']
								);
								//记录领取
								$query = 'INSERT INTO radio_take_record(user_id,radio_id,add_time) VALUES ('.
												 $this->id.','.
												 $radio_id.','.
												 'now()'.
								');';
								mysqli_query($this->connect_database, $query);	
							}
								
						}
					}
				}
			}
			
			//UNDONE
			$json['me']['menu'] = array();
			
			$json['me']['menu']['home'] = array();
			$json['me']['menu']['home']['subtitle'] = $home_str;
			$json['me']['menu']['home']['num'] = '0';
			
			$json['me']['menu']['friend']['subtitle'] = '敬请期待!';
			$json['me']['menu']['friend']['num'] = '0';
			
			$json['me']['menu']['bbs']['subtitle'] = '敬请期待!';
			$json['me']['menu']['bbs']['num'] = '0';
			
			$json['me']['menu']['gift']['subtitle'] = '敬请期待!';
			$json['me']['menu']['gift']['num'] = '0';
			
			$json['me']['menu']['guess']['subtitle'] = '敬请期待!';
			$json['me']['menu']['guess']['num'] = '0';
			
			
			
			$json['me']['menu']['ranking']['subtitle'] = $ranking_str;
			$json['me']['menu']['ranking']['num'] = '0';
			
			$json['me']['menu']['topic']['subtitle'] = $topic_str;
			$json['me']['menu']['topic']['num'] = '0';
			
			$json['me']['menu']['guesslike']['subtitle'] = $guess_str;
			$json['me']['menu']['guesslike']['num'] = '0';
			
			$json['me']['menu']['appwall']['subtitle'] = $picksoap_str;
			$json['me']['menu']['appwall']['num'] = '0';
			
			
			
			
			
			return $json;
		}
		
		//关闭所有连接
		function closeAllConnection(){
			$this->close_Connection();
		}
	}
?>