<?php
	//负责从缓存或者数据库中 读写数据
	require_once(dirname(__FILE__).'/'.'DaoBase.dao.php');
	class user_basic_dao extends DaoBase{
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
		
		//初始化
		function __construct(){
			
			parent::__construct(); 
			
			$this->id	=	0;
			$this->name	=	'';
			$this->intro=	'';
			$this->email=	'';
			$this->phone_number	=	'';
			$this->sex	=	0;
			$this->age	=	0;
			$this->avatar	=	'';
			$this->open	=	0;
			$this->add_time	=	'';
			$this->update_time	=	'';
		}
		
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
			$this->id = $userid;
			if(!$this->connect_memcache){
				//若连接非法
				//自己创建连接
				$this->creat_MemCache_Connection();
			}
			
			//检查user_id_{userid}_basic
			$key = 'user_id_'.$this->id.'_basic';
			if(($value = $this->connect_memcache-> get($key)) != FALSE) { 
	
				$this->name			=	$value['name'];
				$this->intro		=	$value['intro'];
				$this->email		=	$value['email'];
				$this->phone_number	=	$value['phone_number'];
				$this->sex			=	$value['sex'];
				$this->age			=	$value['age'];
				$this->avatar		=	$value['avatar'];
				$this->open			=	$value['open'];
				$this->add_time		=	$value['add_time'];
				$this->update_time	=	$value['update_time'];
				
				return true;
			}else{
				//没找到基本信息	
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
			$key = 'user_id_'.$this->id;
			$login_time = array(
				'login_time'=>date('Y-m-d H:i:s'),
			);
			if(($value = $this->connect_memcache-> get($key)) != FALSE) { 
				$this->connect_memcache->replace($key, $login_time, 0, 86400*30);
			}else{
				$this->connect_memcache->set($key, $login_time, 0, 86400*30);	
			}
			
			$key = 'user_id_'.$this->id.'_basic';
			if(($value = $this->connect_memcache-> get($key)) != FALSE) { 
				$this->connect_memcache->replace($key, $json, 0, 86400);
			}else{
				$this->connect_memcache->set($key, $json, 0, 86400);	
			}
			
			if(strlen($this->email)>0){
				$key = 'user_email_'.$this->email;
				if(($value = $this->connect_memcache-> get($key)) != FALSE) { 
					$this->connect_memcache->replace($key, $this->id, 0, 86400);
				}else{
					$this->connect_memcache->set($key, $this->id, 0, 86400);	
				}
			}
			return true;
		}
		
		//从数据库加载数据
		function loadFrom_DataBase_UserId($userid){
			$this->id = $userid;
			if(!$this->connect_database){
				//若连接非法
				//自己创建连接
				$this->creat_DataBase_Connection();
			}
			
			$query = 'SELECT * FROM `user_basic` WHERE `id`='.$this->id .';';
			$result = mysqli_query($this->connect_database,$query); 
			if($result){ 
				if(mysqli_num_rows($result)>0){
					//找到了
					$row = mysqli_fetch_assoc($result);
					
					$this->name			=	$row['name'];
					$this->intro		=	$row['intro'];
					$this->email		=	$row['email'];
					$this->phone_number	=	$row['phone_number'];
					$this->sex			=	$row['sex'];
					$this->age			=	$row['age'];
					$this->avatar		=	$row['avatar'];
					$this->open			=	$row['open'];
					$this->add_time		=	$row['add_time'];
					$this->update_time	=	$row['update_time'];
					
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
			$query = 'SELECT * FROM `user_basic` WHERE `id`='.$this->id .';';
			$result = mysqli_query($this->connect_database,$query); 
			if($result){ 
				if(mysqli_num_rows($result)>0){
					//有 Update
					$query = 'UPDATE `user_basic` SET '.
						'`id`='					.$this->id.
						',`name`=\''			.$this->name.
						'\',`intro`=\''			.$this->intro.
						'\',`email`=\''			.$this->email.
						'\',`phone_number`=\''	.$this->phone_number.
						'\',`sex`='				.$this->sex.
						',`age`='				.$this->age.
						',`avatar`='			.$this->avatar.
						',`open`='				.$this->open.
						',`add_time`=\''		.$this->add_time.
						'\',`update_time`=\''	.$this->update_time.
						'\' WHERE `id`='		.$this->id.
						';';
				}else{
					//没有 Insert
					$query = 'INSERT INTO user_basic(id,name,intro,email,phone_number,sex,age,avatar,open,add_time,update_time) VALUES ('.
								$this->id.','.
								'\''.$this->name.'\','.
								'\''.$this->intro.'\','.
								'\''.$this->email.'\','.
								'\''.$this->phone_number.'\','.
									 $this->sex.','.
									 $this->age.','.
								'\''.$this->avatar.'\','.
									 $this->open.','.
								'\''.$this->add_time.'\','.	 
								'\''.$this->update_time.'\''.
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
				'id' => 0,
				'name' => '',
				'intro' => '',
				'email' => '',
				'phone_number' => '',
				'sex' => 0,
				'age' => 0,
				'avatar' => '',
				'open' => 0,
				'add_time' => '',
				'update_time' => ''
			);
			$json['id']				=	$this->id;
			$json['name']			=	$this->name;
			$json['intro']			=	$this->intro;
			$json['email']			=	$this->email;
			$json['phone_number']	=	$this->phone_number;
			$json['sex']			=	$this->sex;
			$json['age']			=	$this->age;
			$json['avatar']			=	$this->avatar;
			$json['open']			=	$this->open;
			$json['add_time']		=	$this->add_time;
			$json['update_time']	=	$this->update_time;
			return $json;	
		}
		
		//按照给定的messages加载信息
		//给定是否新用户
		//新用户需要创建add_time和update_time
		function loadMessages($userid,$messages,$new){
			$this->id = $userid;
			if(isset($messages['name'])){ $this->name = $messages['name']; }
			if(isset($messages['intro'])){ $this->intro = $messages['intro']; }
			if(isset($messages['email'])){ $this->email = $messages['email']; }
			if(isset($messages['phone_number'])){ $this->phone_number = $messages['phone_number']; }
			if(isset($messages['sex'])){ $this->sex = $messages['sex']; }
			if(isset($messages['age'])){ $this->age = $messages['age']; }
			if(isset($messages['avatar'])){ $this->avatar = $messages['avatar']; }
			if(isset($messages['open'])){ $this->open = $messages['open']; }
			if(isset($messages['add_time'])){ $this->add_time = $messages['add_time']; }
			if(isset($messages['update_time'])){ $this->update_time = $messages['update_time']; }
			if($new){
				$this->open = 1;
				$this->add_time = date('Y-m-d H:i:s');
				$this->update_time = date('Y-m-d H:i:s');
			}
		}
		
		
		//获取接口返回结构{msg}
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
		function createResponse_msg($json){
			if(!$json){$json = array();}
			$json['user_id'] = (string)$this->id;
			$json['name'] = $this->name?$this->name:'用户 ('.$this->id.')';
			$json['intro'] = $this->intro;
			$json['sex'] =  (string)$this->sex;
			$json['avatar'] = $this->avatar;
			$json['email'] = $this->email;
			$json['age'] =  (string)$this->age;
			return $json;
		}
		
		
		//检查给定的email是否已经注册过
		function checkEmailExist($connect_memcache,$connect_database,$email){
			//先尝试缓存加载
			if(!$connect_memcache){
				//没有给定缓存连接
				//自己创建连接
				$this->creat_MemCache_Connection();
			}else{
				$this->set_MemCache_Connection($connect_memcache);	
			}
			
			if($this->checkEmailExist_Memcahce($email)){
				//存在
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
			
			if($this->checkEmailExist_Database($email)){
				//存在
				return true;
			}
			
			//不存在
			return false;
		}
		
		function checkEmailExist_Memcahce($email){
			if(!$this->connect_memcache){
				//若连接非法
				//自己创建连接
				$this->creat_MemCache_Connection();
			}
			//user_email_{email}
			$key = 'user_email_'.$email;
			if(($value = $this->connect_memcache-> get($key)) != FALSE) { 
				//$this->id = $value;
				return true;
			}
			
			return false;
		}
		
		function checkEmailExist_Database($email){
			if(!$this->connect_database){
				//若连接非法
				//自己创建连接
				$this->creat_DataBase_Connection();
			}
			$query = 'SELECT * FROM `user_basic` WHERE `email`=\''.$this->email .'\';';
			$result = mysqli_query($this->connect_database,$query); 
			if($result){ 
				if(mysqli_num_rows($result)>0){
					return true;
				}
			}
			
			return false;
		}
	}
?>