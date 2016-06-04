<?php
	//负责从缓存或者数据库中 读写数据
	require_once(dirname(__FILE__).'/'.'DaoBase.dao.php');
	class tag_dao extends DaoBase{
		public $id;
		public $name;
		public $level;
		public $tag_times;
		public $add_time;
		
		
		//初始化
		function __construct(){
			
			parent::__construct(); 
			
			$this->id	=	0;
			$this->name = 	'';
			$this->level = 1;
			$this->tag_times = 0;
			$this->add_time = '';
		}
		
		//加载数据,无论是MemCache还是Database
		function loadFrom_TagId($connect_memcache,$connect_database,$tagid){
			//先尝试缓存加载
			if(!$connect_memcache){
				//没有给定缓存连接
				//自己创建连接
				$this->creat_MemCache_Connection();
			}else{
				$this->set_MemCache_Connection($connect_memcache);	
			}
			
			if($this->loadFrom_MemCache_TagId($tagid)){
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
			
			if($this->loadFrom_DataBase_TagId($tagid)){
				return true;
			}
			
			//数据库也失败,那就真失败了
			return false;
		}
		
		//从缓存加载数据
		function loadFrom_MemCache_TagId($tagid){
			$this->id = $tagid;
			if(!$this->connect_memcache){
				//若连接非法
				//自己创建连接
				$this->creat_MemCache_Connection();
			}
			
			//检查tag_id_{tag_id}
			$key = 'tag_id_'.$this->id;
			if(($value = $this->connect_memcache-> get($key)) != FALSE) { 
				
				$this->name 		= $value['name'];
				$this->level 		= $value['level'];
				$this->tag_times 	= $value['tag_times'];
				$this->add_time 	= $value['add_time'];
				
				return true;
			}else{
				//没找到基本信息	
				return false;
			}
		}
		
		//把数据写入到缓存
		function saveTo_MemCache(){
			if(!$this->connect_memcache){
				//自己创建连接
				$this->creat_MemCache_Connection();
			}	
			
			$json = $this->getJsonStruct();
			
			//记录Memcache
			//tag_id_{tag_id}
			$key = 'tag_id_'.$this->id;
			if(($value = $this->connect_memcache-> get($key)) != FALSE) { 
				$this->connect_memcache->replace($key, $json, 0, 0);
			}else{
				$this->connect_memcache->set($key, $json, 0, 0);	
			}
			
			return true;
		}
		
		//从数据库加载数据
		function loadFrom_DataBase_TagId($tagid){
			$this->id = $tagid;
			if(!$this->connect_database){
				//若连接非法
				//自己创建连接
				$this->creat_DataBase_Connection();
			}
			
			$query = 'SELECT * FROM `m_tag` WHERE `id`='.$this->id .';';
			$result = mysqli_query($this->connect_database,$query); 
			if($result){ 
				if(mysqli_num_rows($result)>0){
					//找到了
					$value = mysqli_fetch_assoc($result);
					
					$this->name 		= $value['name'];
					$this->level 		= $value['level'];
					$this->tag_times 	= $value['tag_times'];
					$this->add_time 	= $value['add_time'];
					
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
				//自己创建连接
				$this->creat_DataBase_Connection();
			}
			$query = 'SELECT * FROM `m_tag` WHERE `id`='.$this->id .';';
			$result = mysqli_query($this->connect_database,$query); 
			if($result){ 
				if(mysqli_num_rows($result)>0){
					//有 Update
					$query = 'UPDATE `m_tag` SET '.
						'`name`='			.'\''.str_replace('\'','’',$this->name).'\''.','.
						'`level`='			.$this->level.','.
						'`tag_times`='		.$this->tag_times.','.
						'`add_time`='		.'\''.$this->add_time.'\''.
						' WHERE `id`='		.$this->id.
						';';
				}else{
					//没有 Insert
					$query = 'INSERT INTO m_tag(name,level,tag_times,add_time) VALUES ('.
								'\''.str_replace('\'','’',$this->name).'\','.
								''.$this->level.','.
								''.$this->tag_times.','.
								'\''.$this->add_time.'\''.	 
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
				'level' => 0,
				'tag_times' => 0,
				'add_time' => ''
			);
			
			$json['id']				=	$this->id;
			$json['name']			=	$this->name;
			$json['level']			=	$this->level;
			$json['tag_times']		=	$this->tag_times;
			$json['add_time']		=	$this->add_time;
			
			return $json;	
		}
			
		//获取接口返回结构{tag}
		/*
		tag{
			id
			name
			level
			tag_times
			add_time
		}
		*/
		function createResponse_tag($json){
			if(!$json){$json = array();}
			$json['id'] 			= (string)$this->id;
			$json['name'] 			= $this->name;
			$json['level'] 			= (string)$this->level;
			$json['tag_times'] 		= (string)$this->tag_times;
			return $json;
		}
		
		
	}
?>