<?php
	//负责从缓存或者数据库中 读写数据
	require_once(dirname(__FILE__).'/'.'DaoBase.dao.php');
	class user_v_sns_sinawb_dao extends DaoBase{
		public $id;	//id 该表id可自增处理
		public $user_id;
		public $sns_id;
		public $sns_avatar;
		public $sns_data;
		public $sns_sex;
		public $sns_name;
		public $open;
		public $add_time;
		
		public $sns_data_struct;
		
		//初始化
		function __construct(){
			parent::__construct(); 
			$this->id			=	0;
			$this->user_id		=	0;
			$this->sns_id		=	'';
			$this->sns_avatar	=	'';
			$this->sns_data		=	'';
			$this->sns_sex		=	0;
			$this->sns_name		=	'';
			$this->open			=	0;
			$this->add_time		=	'';
			$this->sns_data_struct = '';
		}
		
		//加载数据,无论是MemCache还是Database
		
		function loadFrom_UserId($connect_memcache,$connect_database,$userid){
			//先尝试缓存加载
			if(!$connect_memcache){
				//没有给定缓存连接
				//自己创建连接
				$this->creat_MemCache_Connection();
			}else{
				$this->set_MemCache_Connection($connect_memcache);	
			}
			
			if($this->loadFrom_MemCache_UserId($userid)){
				return true;
			}
			
			//不行再数据库加载
			if(!$connect_database){
				//没有给定数据库连接
				//自己创建连接
				$this->creat_DataBase_Connection();
			}else{
				$this->set_DataBase_Connection($connect_database);
			}
			
			if($this->loadFrom_DataBase_UserId($userid)){
				//数据库加载完就写入Memcache
				$this->saveTo_MemCache();
				return true;
			}
			
			//数据库也失败,那就真失败了
			return false;
		}
			
		//从缓存加载数据
		function loadFrom_MemCache_UserId($userid){
			$this->user_id = $userid;
			if(!$this->connect_memcache){
				//若连接非法
				//自己创建连接
				$this->creat_MemCache_Connection();
			}
			
			//检查user_id_{userid}_sns_sinawb
			$key = 'user_id_'.$this->user_id.'_sns_sinawb';
			if(($value = $this->connect_memcache ->get($key)) != FALSE) { 
				$this->id			= 	$value['id'];
				$this->sns_id		=	$value['sns_id'];
				$this->sns_avatar	=	$value['sns_avatar'];
				$this->sns_data		=	$value['sns_data'];
				$this->sns_sex		=	$value['sns_sex'];
				$this->sns_name		=	$value['sns_name'];
				$this->open			=	$value['open'];
				$this->add_time		=	$value['add_time'];
				return true;
			}else{
				//没找到
				return false;
			}
		}
		
		//把数据写入到缓存
		function saveTo_MemCache(){
			if(!$this->connect_memcache){
				//若连接非法
				//自己创建连接
				$this->creat_MemCache_Connection();
			}	
			
			$json = $this->getJsonStruct();
			
			//记录Memcache
			$key = 'user_id_'.$this->user_id.'_sns_sinawb';
			if(($value = $this->connect_memcache ->get($key)) != FALSE) { 
				$this->connect_memcache->replace($key, $json, 0, 86400);
			}else{
				$this->connect_memcache->set($key, $json, 0, 86400);	
			}
			if(strlen($this->sns_id)>0){
				$key = 'user_sns_sinawb_id_'.$this->sns_id;
				if(($value = $this->connect_memcache ->get($key)) != FALSE) { 
					$this->connect_memcache->replace($key, $this->user_id, 0, 86400);
				}else{
					$this->connect_memcache->set($key, $this->user_id, 0, 86400);	
				}
			}
			
			return true;
		}
		
		//从数据库加载数据
		function loadFrom_DataBase_UserId($userid){
			$this->user_id = $userid;
			if(!$this->connect_database){
				//若连接非法
				//自己创建连接
				$this->creat_DataBase_Connection();
			}
			
			$query = 'SELECT * FROM `user_v_sns_sinawb` WHERE `user_id`='.$this->user_id .';';
			$result = mysqli_query($this->connect_database,$query); 
			if($result){ 
				if(mysqli_num_rows($result)>0){
					//找到了
					$row = mysqli_fetch_assoc($result);
					$this->id			= 	$row['id'];
					$this->sns_id		=	$row['sns_id'];
					$this->sns_avatar	=	$row['sns_avatar'];
					$this->sns_data		=	$row['sns_data'];
					$this->sns_sex		=	$row['sns_sex'];
					$this->sns_name		=	$row['sns_name'];
					$this->open			=	$row['open'];
					$this->add_time		=	$row['add_time'];
					
					return true;
					
				}else{
					//没找到	
					return false;
				}
			}
			return false;
		}
		
		//存储到数据库
		function saveTo_DataBase(){
			if(!$this->connect_database){
				//若连接非法
				//自己创建连接
				$this->creat_DataBase_Connection();
			}
			$query = 'SELECT * FROM `user_v_sns_sinawb` WHERE `user_id`='.$this->user_id .';';
			$result = mysqli_query($this->connect_database,$query); 
			if($result){ 
				if(mysqli_num_rows($result)>0){
					//有 Update
					$query = 'UPDATE `user_v_sns_sinawb` SET '.
						'`user_id`='			.$this->user_id.
						',`sns_id`=\''			.$this->sns_id.
						'\',`sns_avatar`=\''	.$this->sns_avatar.
						'\',`sns_data`=\''		.$this->sns_data.
						'\',`sns_sex`='			.$this->sns_sex.
						',`sns_name`=\''		.$this->sns_name.
						'\',`open`='			.$this->open.
						' WHERE `user_id`='		.$this->user_id.
						';';
				}else{
					//没有 Insert
					$query = 'INSERT INTO user_v_sns_sinawb(
								user_id,
								sns_id,
								sns_avatar,
								sns_data,
								sns_sex,
								sns_name,
								open,
								add_time
								) VALUES ('.
								$this->user_id.','.
								'\''.$this->sns_id.'\','.
								'\''.$this->sns_avatar.'\','.
								'\''.$this->sns_data.'\','.
								''.$this->sns_sex.','.
								'\''.$this->sns_name.'\','.
								''.$this->open.','.
								''.'now()'.''.
					');';
				}
				
				$result = mysqli_query($this->connect_database,$query);
				if(!$result){ 
					return false;
				}
				
				return true;
			}
			return false;
		}
		
		//获取JSON的数组表达
		function getJsonStruct(){
			$json = array(
				'id' 			=> 0,
				'user_id'		=> 0,
				'sns_id' 		=> '',
				'sns_avatar' 	=> '',
				'sns_data'		=> '',
				'sns_sex' 		=> 0,
				'sns_name'		=> '',
				'open' 			=> 0,
				'add_time' 		=> '',
			);
			$json['id']					=	$this->id;
			$json['user_id']			=	$this->user_id;
			$json['sns_id']				=	$this->sns_id;
			$json['sns_avatar']			=	$this->sns_avatar;
			$json['sns_data']			=	$this->sns_data;
			$json['sns_sex']			=	$this->sns_sex;
			$json['sns_name']			=	$this->sns_name;
			$json['open']				=	$this->open;
			$json['add_time']			=	$this->add_time;
			return $json;	
		}
		
		//是否已经绑定
		function isBinded(){
			if(strlen($this->sns_id)>0){
				return true;
			}else{
				return false;	
			}
		}
		
		//绑定
		function bindData($messages){
			if(isset($messages['sns_id'])){$this->sns_id = $messages['sns_id'];}
			if(isset($messages['sns_avatar'])){$this->sns_avatar = $messages['sns_avatar'];}
			if(isset($messages['sns_data'])){$this->sns_data = $messages['sns_data'];}
			
			if(isset($messages['sns_sex'])){
				switch($messages['sns_sex']){
					case '男':
						$this->sns_sex = 1;
						break;
					case '女':
						$this->sns_sex = 2;
						break;
					default:
						$this->sns_sex = 0;
						break;	
				}
			}
			if(isset($messages['sns_name'])){$this->sns_name = $messages['sns_name'];}
					
			$this->saveTo_MemCache();
		}
		
		//按照给定的messages加载信息
		function loadMessages($userid,$messages,$new){
			$this->user_id= $userid;
			if(isset($messages['sns_id'])){$this->sns_id = $messages['sns_id'];}
			if(isset($messages['sns_avatar'])){$this->sns_avatar = $messages['sns_avatar'];}
			if(isset($messages['sns_data'])){$this->sns_chanelId = $messages['sns_data'];}
			if(isset($messages['sns_sex'])){$this->sns_sex = $messages['sns_sex'];}
			if(isset($messages['sns_name'])){$this->sns_name = $messages['sns_name'];}
			if($new){
				
			}
		}
		
		//获取接口返回结构{sns_bind}
		/*
		sns_bind{
			sinawb_bind
			}
		*/
		function createResponse_sns_bind($json){
			if(!$json){$json = array();}
			if($this->isBinded()){
				$json['sinawb'] =  (string)$this->sns_id;
			}else{
				$json['sinawb'] = '0';
			}
			return $json;
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
		function readSnsData($string,$snsid,$save){
			if(strlen($snsid)){
				$this->sns_id = $snsid;
			}
			if($this->sns_data_struct = @json_decode($string)){
				if(!$this->loadSnsDataStruct()){ return false;}
				$this->sns_data = $string;
				if($save){$this->saveTo_MemCache();}
				return true;
			}else{
				return false;	
			}
		}
		
		//按照DataStruct加载信息
		function loadSnsDataStruct(){
			if(!$this->sns_data_struct){ return false;}
			if(isset($this->sns_data_struct->idstr)){
			$this->sns_id = $this->sns_data_struct->idstr;}
			
			if(isset($this->sns_data_struct->screen_name)){
			$this->sns_name = $this->sns_data_struct->screen_name;}
			
			if(isset($this->sns_data_struct->avatar_large)){
			$this->sns_avatar = $this->sns_data_struct->avatar_large;}
			
			if(isset($this->sns_data_struct->gender)){
				if($this->sns_data_struct->gender=='m'){
					$this->sns_sex = 1;	
				}elseif($this->sns_data_struct->gender=='f'){
					$this->sns_sex = 2;	
				}else{
					$this->sns_sex = 0;	
				}
			}
			return true;
		}
		
		//给定的sns_id加载,检查是否存在绑定用户
		function checkBindUser_sns_id($snsid){
			if(!$this->connect_memcache){
				//若连接非法
				//自己创建连接
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
				//若连接非法
				//自己创建连接
				$this->creat_DataBase_Connection();
			}
			$query = 'SELECT * FROM `user_v_sns_sinawb` WHERE `sns_id`='.$snsid .';';
			$result = mysqli_query($this->connect_database,$query); 
			if($result){ 
				if(mysqli_num_rows($result)>0){
					$row = mysqli_fetch_assoc($result);
					return $row['user_id'];		
				}
			}
			
			return false;
		}
		
	}
?>