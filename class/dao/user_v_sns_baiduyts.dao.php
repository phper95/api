<?php
	//负责从缓存或者数据库中 读写数据
	require_once(dirname(__FILE__).'/'.'DaoBase.dao.php');

	class user_v_sns_baiduyts_dao extends DaoBase{
		public $id;	//id 该表id可自增处理
		public $user_id;
		public $sns_appid;
		public $sns_userId;
		public $sns_chanelId;
		public $sns_requestId;
		public $sns_data;
		public $open;
		public $add_time;
		
		public $sns_data_struct;
		
		//初始化
		function __construct(){
			
			parent::__construct(); 
			
			$this->id				=	0;
			$this->user_id			=	0;
			$this->sns_appid		=	'';
			$this->sns_userId		=	'';
			$this->sns_chanelId		=	'';
			$this->sns_requestId	=	'';
			$this->sns_data			=	'';
			$this->open				=	0;
			$this->add_time			=	'';
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
			
			
			//检查user_id_{userid}_sns_baiduyts
			$key = 'user_id_'.$this->user_id.'_sns_baiduyts';
			if(($value = $this->connect_memcache ->get($key)) != FALSE) { 
				$this->id				= 	$value['id'];
				$this->sns_appid		=	$value['sns_appid'];
				$this->sns_userId		=	$value['sns_userId'];
				$this->sns_chanelId		=	$value['sns_chanelId'];
				$this->sns_requestId	=	$value['sns_requestId'];
				$this->sns_data			=	$value['sns_data'];
				$this->open				=	$value['open'];
				$this->add_time			=	$value['add_time'];
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
			$key = 'user_id_'.$this->user_id.'_sns_baiduyts';
			if(($value = $this->connect_memcache ->get($key)) != FALSE) { 
				$this->connect_memcache->replace($key, $json, 0, 86400);
			}else{
				$this->connect_memcache->set($key, $json, 0, 86400);	
			}
			
			if(strlen($this->sns_userId)>0){
				$key = 'user_sns_baiduyts_id_'.$this->sns_userId;
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
			$this->user_id= $userid;
			if(!$this->connect_database){
				//若连接非法
				//自己创建连接
				$this->creat_DataBase_Connection();
			}
			
			$query = 'SELECT * FROM `user_v_sns_baiduyts` WHERE `user_id`='.$this->user_id .';';
			$result = mysqli_query($this->connect_database,$query); 
			if($result){ 
				if(mysqli_num_rows($result)>0){
					//找到了
					$row = mysqli_fetch_assoc($result);
					$this->id				= 	$row['id'];
					$this->sns_appid		=	$row['sns_appid'];
					$this->sns_userId		=	$row['sns_userId'];
					$this->sns_chanelId		=	$row['sns_chanelId'];
					$this->sns_requestId	=	$row['sns_requestId'];
					$this->sns_data			=	$row['sns_data'];
					$this->open				=	$row['open'];
					$this->add_time			=	$row['add_time'];
					
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
			$query = 'SELECT * FROM `user_v_sns_baiduyts` WHERE `user_id`='.$this->user_id .';';
			$result = mysqli_query($this->connect_database,$query); 
			if($result){ 
				if(mysqli_num_rows($result)>0){
					//有 Update
					$query = 'UPDATE `user_v_sns_baiduyts` SET '.
						'`user_id`='			.$this->user_id.
						',`sns_appid`=\''		.$this->sns_appid.
						'\',`sns_userId`=\''	.$this->sns_userId.
						'\',`sns_chanelId`=\''	.$this->sns_chanelId.
						'\',`sns_requestId`=\''	.$this->sns_requestId.
						'\',`sns_data`=\''		.$this->sns_data.
						'\',`open`='			.$this->open.
						' WHERE `user_id`='		.$this->user_id.
						';';
				}else{
					//没有 Insert
					$query = 'INSERT INTO user_v_sns_baiduyts(
								user_id,
								sns_appid,
								sns_userId,
								sns_chanelId,
								sns_requestId,
								sns_data,
								open,
								add_time
								) VALUES ('.
								$this->user_id.','.
								'\''.$this->sns_appid.'\','.
								'\''.$this->sns_userId.'\','.
								'\''.$this->sns_chanelId.'\','.
								'\''.$this->sns_requestId.'\','.
								'\''.$this->sns_data.'\','.
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
				'sns_appid' 	=> '',
				'sns_userId' 	=> '',
				'sns_chanelId'	=> '',
				'sns_requestId' => '',
				'sns_data'		=> '',
				'open' 			=> 0,
				'add_time' 		=> '',
			);
			$json['id']					=	$this->id;
			$json['user_id']			=	$this->user_id;
			$json['sns_appid']			=	$this->sns_appid;
			$json['sns_userId']			=	$this->sns_userId;
			$json['sns_chanelId']		=	$this->sns_chanelId;
			$json['sns_requestId']		=	$this->sns_requestId;
			$json['sns_data']			=	$this->sns_data;
			$json['open']				=	$this->open;
			$json['add_time']			=	$this->add_time;
			return $json;	
		}
		
		//是否已经绑定
		function isBinded(){
			if(strlen($this->sns_userId)>0){
				return true;
			}else{
				return false;	
			}
		}
		
		//绑定
		function bindData($messages){
			
			if(isset($messages['sns_appid'])){$this->sns_appid = $messages['sns_appid'];}
			if(isset($messages['sns_userId'])){$this->sns_userId = $messages['sns_userId'];}
			if(isset($messages['sns_chanelId'])){$this->sns_chanelId = $messages['sns_chanelId'];}
			if(isset($messages['sns_requestId'])){$this->sns_requestId = $messages['sns_requestId'];}
			if(isset($messages['sns_data'])){$this->sns_data = $messages['sns_data'];}
			
			$this->saveTo_MemCache();
		}
		
		//按照给定的messages加载信息
		function loadMessages($userid,$messages,$new){
			$this->user_id= $userid;
			if(isset($messages['sns_appid'])){$this->sns_appid = $messages['sns_appid'];}
			if(isset($messages['sns_userId'])){$this->sns_userId = $messages['sns_userId'];}
			if(isset($messages['sns_chanelId'])){$this->sns_chanelId = $messages['sns_chanelId'];}
			if(isset($messages['sns_requestId'])){$this->sns_requestId = $messages['sns_requestId'];}
			if(isset($messages['sns_data'])){$this->sns_data = $messages['sns_data'];}
			if($new){
				
			}
		}
		
		//获取接口返回结构{sns_bind}
		/*
		sns_bind{
			baiduyts
			}
		*/
		function createResponse_sns_bind($json){
			if(!$json){$json = array();}
			if($this->isBinded()){
				$json['baiduyts'] =  (string)$this->sns_userId;
			}else{
				$json['baiduyts'] = '0';
			}
			return $json;
		}
		
		//解析第三方返回JSON Data
		/*
		onBind:onBind errorCode=0 appid=1185357 userId=692361033542731277 channelId=3750997923735370515 requestId=4269075777
		*/
		function readSnsData($string,$userId,$save){
			if(strlen($snsid)){
				$this->sns_userId = $userId;
			}
			if($this->sns_data_struct = @json_decode($string)){
				$this->sns_userId = $this->sns_data_struct->userId;
				$this->sns_appid = $this->sns_data_struct->appid;
				$this->sns_chanelId = $this->sns_data_struct->channelId;
				$this->sns_requestId = $this->sns_data_struct->requestId;
				$this->sns_data = $string;
				if($save){$this->saveTo_MemCache();}
				return true;
			}else{
				return false;	
			}
		}
		
		//给定的sns_id加载,检查是否存在绑定用户
		function checkBindUser_userId($userId){
			if(!$this->connect_memcache){
				//若连接非法
				//自己创建连接
				$this->creat_MemCache_Connection();
			}
			//检查user_sns_baiduyts_id_{snsid}
			$key = 'user_sns_baiduyts_id_'.$userId;
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
			$query = 'SELECT * FROM `user_v_sns_baiduyts` WHERE `sns_userId`=\''.$userId .'\';';
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