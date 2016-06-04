<?php
	//负责从缓存或者数据库中 读写数据
	require_once(dirname(__FILE__).'/'.'DaoBase.dao.php');
	class user_v_phone_dao extends DaoBase{
		
		public $user_id;
		public $phone_list;
		
		//初始化
		function __construct(){
			parent::__construct(); 
			$this->user_id = 0;
			$this->phone_list = array();
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
			
			//检查user_id_{userid}_phone
			$key = 'user_id_'.$this->user_id.'_phone';
			if(($value = $this->connect_memcache ->get($key)) != FALSE) { 
				$this->phone_list	=	$value;
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
			
			//记录Memcache
			$key = 'user_id_'.$this->user_id.'_phone';
			
			if(($value = $this->connect_memcache ->get($key)) != FALSE) { 
				$this->connect_memcache->replace($key, $this->phone_list, 0, 86400);
			}else{
				$this->connect_memcache->set($key, $this->phone_list, 0, 86400);	
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
			
			$this->phone_list = array();
			
			$query = 'SELECT * FROM `user_v_phone` WHERE `user_id`='.$this->user_id .';';
			$result = mysqli_query($this->connect_database,$query); 
			if($result){ 
				if(mysqli_num_rows($result)>0){
					//找到了
					$i = 0;
					while($i<mysqli_num_rows($result)){
						$row = mysqli_fetch_assoc($result);
			
						$record = $this->getOneRecordJsonStruct();
						
						$record['id']			=	$row['id'];
						$record['user_id']		=	$row['user_id'];
						$record['imei']			=	$row['imei'];
						$record['phone_type']	=	$row['phone_type'];
						$record['pub_channel']	=	$row['pub_channel'];
						$record['pub_platform']	=	$row['pub_platform'];
						$record['add_time']		=	$row['add_time'];
						
						$this->phone_list[$i] = $record;
						
						$i++;
					}
					return true;
					
				}else{
					//没找到	
					return false;
				}
			}
			return false;
		}
		
		//存储到数据库
		//用户IMEI唯一
		function saveTo_DataBase(){
			if(!$this->connect_database){
				//若连接非法
				//自己创建连接
				$this->creat_DataBase_Connection();
			}
			$i = 0;
			while($i<count($this->phone_list)){
				$query = 'SELECT * FROM `user_v_phone` WHERE `user_id`='.$this->user_id .' AND `imei`=\''.$this->phone_list[$i]['imei'].'\';';
				$result = mysqli_query($this->connect_database,$query); 
				if($result){ 
					if(mysqli_num_rows($result)>0){
						//有 Update
						$query = 'UPDATE `user_v_phone` SET '.
							'`user_id`='					.$this->user_id.
							',`imei`=\''					.$this->phone_list[$i]['imei'].
							'\',`phone_type`=\''			.$this->phone_list[$i]['phone_type'].
							'\',`pub_channel`=\''			.$this->phone_list[$i]['pub_channel'].
							'\',`pub_platform`=\''			.$this->phone_list[$i]['pub_platform'].
							'\' WHERE `user_id`='			.$this->user_id.
							' AND `imei` = \''				.$this->phone_list[$i]['imei'].
							'\' ;';
					}else{
						//没有 Insert
						$query = 'INSERT INTO user_v_phone(
									user_id,
									imei,
									phone_type,
									pub_channel,
									pub_platform,
									add_time
									) VALUES ('.
									$this->user_id.','.
									'\''.$this->phone_list[$i]['imei'].'\','.
									'\''.$this->phone_list[$i]['phone_type'].'\','.
									'\''.$this->phone_list[$i]['pub_channel'].'\','.
									'\''.$this->phone_list[$i]['pub_platform'].'\','.
									'now()'.
						');';
					}
					
					$result = mysqli_query($this->connect_database,$query);
					if(!$result){ 
						return false;
					}
				}
				
				$i++;
			}
			return true;
		}
		
		//检查一个设备,如果存在则不处理,新设备则注册
		function checkUserPhone($userid,$messages){
			$this->user_id = $userid;
			if(!isset($messages['imei'])){return false;}
			$imei = $messages['imei'];
			
			$find = false;
			foreach($this->phone_list as $phone){
				if($imei == $phone['imei']){
					$find = true;
					break;
				}
			}
			if(!$find){
				//新设备
				$new_record = $this->getOneRecordJsonStruct();
				$new_record['user_id'] = $this->user_id;
				$new_record['imei'] = $imei;
				if(isset($messages['phone_type'])){ $new_record['phone_type'] = $messages['phone_type']; }
				if(isset($messages['pub_channel'])){ $new_record['pub_channel'] = $messages['pub_channel']; }
				if(isset($messages['pub_platform'])){ $new_record['pub_platform'] = $messages['pub_platform']; }
				$this->phone_list[count($this->phone_list)] = $new_record;
				$this->saveTo_MemCache();
				return true;
			}
			return false;
		}
		
		//获取JSON的数组表达
		function getJsonStruct(){
			return $this->phone_list;	
		}
		
		//获取一条记录的JSON数组
		function getOneRecordJsonStruct(){
			$record = array(
				'id' => 0,
				'user_id' => 0,
				'imei' => '',
				'phone_type' => '',
				'pub_channel' => '',
				'pub_platform' => '',
				'add_time' => ''
			);	
			return $record;
		}
		
		//按照给定的messages加载信息
		function loadMessages($userid,$messages,$new){
			$this->user_id= $userid;
			$new_record = $this->getOneRecordJsonStruct();
			$new_record['user_id'] = $this->user_id;
			if(isset($messages['imei'])){ $new_record['imei'] = $messages['imei']; }
			if(isset($messages['phone_type'])){ $new_record['phone_type'] = $messages['phone_type']; }
			if(isset($messages['pub_channel'])){ $new_record['pub_channel'] = $messages['pub_channel']; }
			if(isset($messages['pub_platform'])){ $new_record['pub_platform'] = $messages['pub_platform']; }
			$this->phone_list[count($this->phone_list)] = $new_record;
			if($new){
				
			}
		}
	}
?>