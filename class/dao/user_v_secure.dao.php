<?php
	//负责从缓存或者数据库中 读写数据
	require_once(dirname(__FILE__).'/'.'DaoBase.dao.php');
	class user_v_secure_dao extends DaoBase{
		public $id;	//id 该表id可自增处理
		public $user_id;
		public $pwd_md5;
		public $question_1;
		public $answer_1;
		public $question_2;
		public $answer_2;
		public $question_3;
		public $answer_3;
	
		//初始化
		function __construct(){
			parent::__construct(); 
			$this->id			=	0;
			$this->user_id		=	0;
			$this->pwd_md5		=	'';
			$this->question_1	=	'';
			$this->answer_1		=	'';
			$this->question_2	=	'';
			$this->answer_2		=	'';
			$this->question_3	=	'';
			$this->answer_3		=	'';
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
			
			//检查user_id_{userid}_secure
			$key = 'user_id_'.$this->user_id.'_secure';
			if(($value = $this->connect_memcache ->get($key)) != FALSE) { 
				$this->id			=	$value['id'];
				$this->pwd_md5		=	$value['pwd_md5'];
				$this->question_1	=	$value['secure_question_1'];
				$this->answer_1		=	$value['secure_answer_1'];
				$this->question_1	=	$value['secure_question_2'];
				$this->answer_1		=	$value['secure_answer_2'];
				$this->question_1	=	$value['secure_question_3'];
				$this->answer_1		=	$value['secure_answer_3'];
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
			$key = 'user_id_'.$this->user_id.'_secure';
			if(($value = $this->connect_memcache ->get($key)) != FALSE) { 
				$this->connect_memcache->replace($key, $json, 0, 86400);
			}else{
				$this->connect_memcache->set($key, $json, 0, 86400);	
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
			
			$query = 'SELECT * FROM `user_v_secure` WHERE `user_id`='.$this->user_id .';';
			$result = mysqli_query($this->connect_database,$query); 
			if($result){ 
				if(mysqli_num_rows($result)>0){
					//找到了
					$row = mysqli_fetch_assoc($result);
					
					$this->id			=	$row['id'];
					$this->pwd_md5		=	$row['pwd_md5'];
					$this->question_1	=	$row['secure_question_1'];
					$this->answer_1		=	$row['secure_answer_1'];
					$this->question_1	=	$row['secure_question_2'];
					$this->answer_1		=	$row['secure_answer_2'];
					$this->question_1	=	$row['secure_question_3'];
					$this->answer_1		=	$row['secure_answer_3'];
					
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
			$query = 'SELECT * FROM `user_v_secure` WHERE `user_id`='.$this->user_id .';';
			$result = mysqli_query($this->connect_database,$query); 
			if($result){ 
				if(mysqli_num_rows($result)>0){
					//有 Update
					$query = 'UPDATE `user_v_secure` SET '.
						'`user_id`='					.$this->user_id.
						',`pwd_md5`=\''					.$this->pwd_md5.
						'\',`secure_question_1`=\''		.$this->question_1.
						'\',`secure_answer_1`=\''		.$this->answer_1.
						'\',`secure_question_2`=\''		.$this->question_2.
						'\',`secure_answer_2`=\''		.$this->answer_2.
						'\',`secure_question_3`=\''		.$this->question_3.
						'\',`secure_answer_3`=\''		.$this->answer_3.
						'\' WHERE `user_id`='			.$this->user_id.
						';';
				}else{
					//没有 Insert
					$query = 'INSERT INTO user_v_secure(
								user_id,
								pwd_md5,
								secure_question_1,
								secure_answer_1,
								secure_question_2,
								secure_answer_2,
								secure_question_3,
								secure_answer_3
								) VALUES ('.
								$this->user_id.','.
								'\''.$this->pwd_md5.'\','.
								'\''.$this->question_1.'\','.
								'\''.$this->answer_1.'\','.
								'\''.$this->question_2.'\','.
								'\''.$this->answer_2.'\','.
								'\''.$this->question_3.'\','.
								'\''.$this->answer_3.'\''.
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
				'id'	  => 0,
				'user_id' => 0,
				'pwd_md5' => '',
				'secure_question_1' => '',
				'secure_answer_1' => '',
				'secure_question_2' => '',
				'secure_answer_2' => '',
				'secure_question_3' => '',
				'secure_answer_3' => '',
			);
			$json['id']							=	$this->id;
			$json['user_id']					=	$this->user_id;
			$json['pwd_md5']					=	$this->pwd_md5;
			$json['secure_question_1']			=	$this->question_1;
			$json['secure_answer_1']			=	$this->answer_1;
			$json['secure_question_2']			=	$this->question_2;
			$json['secure_answer_2']			=	$this->answer_2;
			$json['secure_question_3']			=	$this->question_3;
			$json['secure_answer_3']			=	$this->answer_3;
			return $json;	
		}
		
		//按照给定的messages加载信息
		function loadMessages($userid,$messages,$new){
			$this->user_id= $userid;
			if(isset($messages['pwd_md5'])){ $this->pwd_md5 = $messages['pwd_md5']; }
			if($new){
				
			}
		}
		
	}
?>