<?php
	//负责从缓存或者数据库中 读写数据
	require_once(dirname(__FILE__).'/'.'DaoBase.dao.php');
	class vol_v_option_dao extends DaoBase{
		public $id;
		public $movie_id;
		public $vol_id;
		public $option_id;
		public $option_data;
		
		//初始化
		function __construct(){
			
			parent::__construct(); 
			
			$this->id = 0;
			$this->movie_id = 0;
			$this->vol_id = 0;
			$this->option_id = 0;
			$this->option_data = '';
		}
		
		//加载数据,无论是MemCache还是Database
		function loadFrom_VolId($connect_memcache,$connect_database,$volid){
			//先尝试缓存加载
			if(!$connect_memcache){
				//没有给定缓存连接
				//自己创建连接
				$this->creat_MemCache_Connection();
			}else{
				$this->set_MemCache_Connection($connect_memcache);	
			}
			
			if($this->loadFrom_MemCache_VolId($volid)){
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
			
			if($this->loadFrom_DataBase_VolId($volid)){
				//数据库加载完就写入Memcache
				$this->saveTo_MemCache();
				return true;
			}
			
			//数据库也失败,那就真失败了
			return false;
		}
		
		//从缓存加载数据
		function loadFrom_MemCache_VolId($volid){
			$this->vol_id = $volid;
			if(!$this->connect_memcache){
				//若连接非法
				//自己创建连接
				$this->creat_MemCache_Connection();
			}
			
			//检查vol_id_{vol_id}_option
			$key = 'vol_id_'.$this->vol_id.'_option';
			if(($value = $this->connect_memcache-> get($key)) != FALSE) { 
				
				$this->id = $value['id'];
				$this->movie_id = $value['movie_id'];
				$this->vol_id = $value['vol_id'];
				$this->option_id = $value['option_id'];
				$this->option_data = $value['option_data'];
				
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
			//vol_id_{vol_id}_option
			$key = 'vol_id_'.$this->vol_id.'_option';
			if(($value = $this->connect_memcache-> get($key)) != FALSE) { 
				$this->connect_memcache->replace($key, $json, 0, 86400);
			}else{
				$this->connect_memcache->set($key, $json, 0, 86400);	
			}
			
			return true;
		}
		
		//从数据库加载数据
		function loadFrom_DataBase_VolId($volid){
			$this->vol_id = $volid;
			if(!$this->connect_database){
				//若连接非法
				//自己创建连接
				$this->creat_DataBase_Connection();
			}
			
			$query = 'SELECT * FROM `vol_v_option` WHERE `vol_id`='.$this->vol_id .';';
			$result = mysqli_query($this->connect_database,$query); 
			if($result){ 
				if(mysqli_num_rows($result)>0){
					//找到了
					$value = mysqli_fetch_assoc($result);
					$this->id 		= $value['id'];
					$this->movie_id = $value['movie_id'];
					$this->vol_id 	= $value['vol_id'];
					$this->option_id 	= $value['option_id'];
					$this->option_data 	= $value['option_data'];
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
			$query = 'SELECT * FROM `vol_v_option` WHERE `vol_id`='.$this->vol_id .';';
			$result = mysqli_query($this->connect_database,$query); 
			if($result){ 
				if(mysqli_num_rows($result)>0){
					//有 Update
					$query = 'UPDATE `vol_v_option` SET '.
						'`movie_id`='		.$this->movie_id.','.
						'`vol_id`='			.$this->vol_id.','.
						'`option_id`='		.$this->option_id.','.
						'`option_data`='	.$this->option_data.
						' WHERE `vol_id`='	.$this->vol_id.
						';';
				}else{
					//没有 Insert
					$query = 'INSERT INTO vol_v_option(movie_id,vol_id,option_id,option_data) VALUES ('.
								$this->movie_id.','.
								$this->vol_id.','.
								$this->option_id.','.
								$this->option_data.	 
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
				'movie_id' => 0,
				'vol_id' => 0,
				'option_id' => 1,
				'option_data' => ''
			);
			
			$json['id']			=	$this->id;
			$json['movie_id']	=	$this->movie_id;
			$json['vol_id']		=	$this->vol_id;
			$json['option_id']	=	$this->option_id;
			$json['option_data']=	$this->option_data;
			
			return $json;	
		}
			
		//获取接口返回结构{option}
		/*
		option{
			option_id
			option_data
		}
		*/
		function createResponse_option($json){
			if(!$json){$json = array();}
			$json['option_id'] = (string)$this->option_id;
			$json['option_data'] = $this->option_data;
			return $json;
		}
		
		
	}
?>