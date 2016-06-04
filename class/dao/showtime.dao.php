<?php
	//负责从缓存或者数据库中 读写数据
	require_once(dirname(__FILE__).'/'.'DaoBase.dao.php');
	class showtime_dao extends DaoBase{
		public $id;
		public $name;
		public $add_time;
		public $movie_id;
		
		//初始化
		function __construct(){
			
			parent::__construct(); 
			
			$this->id	=	0;
			$this->name = 	'';
			$this->add_time = '';
			$this->movie_id = 0;
		}
		
		//加载数据,无论是MemCache还是Database
		function loadFrom_ShowtimeId($connect_memcache,$connect_database,$showtimeid){
			//先尝试缓存加载
			if(!$connect_memcache){
				//没有给定缓存连接
				//自己创建连接
				$this->creat_MemCache_Connection();
			}else{
				$this->set_MemCache_Connection($connect_memcache);	
			}
			
			if($this->loadFrom_MemCache_ShowtimeId($showtimeid)){
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
			
			if($this->loadFrom_DataBase_ShowtimeId($showtimeid)){
				//数据库加载完就写入Memcache
				$this->saveTo_MemCache();
				return true;
			}
			
			//数据库也失败,那就真失败了
			return false;
		}
		function loadFrom_MovieId($connect_memcache,$connect_database,$movieid){
			//先尝试缓存加载
			if(!$connect_memcache){
				//没有给定缓存连接
				//自己创建连接
				$this->creat_MemCache_Connection();
			}else{
				$this->set_MemCache_Connection($connect_memcache);	
			}
			
			if($this->loadFrom_MemCache_MovieId($movieid)){
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
			
			if($this->loadFrom_DataBase_MovieId($movieid)){
				return true;
			}
			
			//数据库也失败,那就真失败了
			return false;
		}
		
		//从缓存加载数据
		function loadFrom_MemCache_ShowtimeId($showtimeid){
			$this->id = $showtimeid;
			if(!$this->connect_memcache){
				//若连接非法
				//自己创建连接
				$this->creat_MemCache_Connection();
			}
			
			//检查showtime_id_{showtime_id}
			$key = 'showtime_id_'.$this->id;
			if(($value = $this->connect_memcache-> get($key)) != FALSE) { 
				
				$this->name 		= $value['name'];
				$this->add_time 	= $value['add_time'];
				
				return true;
			}else{
				//没找到基本信息	
				return false;
			}
		}
		function loadFrom_MemCache_MovieId($movieid){
			$this->movie_id = $movieid;
			if(!$this->connect_memcache){
				//若连接非法
				//自己创建连接
				$this->creat_MemCache_Connection();
			}
			
			//检查movie_id_{movieid}_showtime
			$key = 'movie_id_'.$movieid.'_showtime';
			if(($value = $this->connect_memcache-> get($key)) != FALSE) { 
				$this->id 			= $value['id'];
				$this->name 		= $value['name'];
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
			//showtime_id_{showtime_id}
			$key = 'showtime_id_'.$this->id;
			if(($value = $this->connect_memcache-> get($key)) != FALSE) { 
				$this->connect_memcache->replace($key, $json, 0, 86400);
			}else{
				$this->connect_memcache->set($key, $json, 0, 86400);	
			}
			
			if($this->movie_id>0){
				//movie_id_{movieid}_showtime
				$key = 'movie_id_'.$this->movie_id.'_showtime';
				if(($value = $this->connect_memcache-> get($key)) != FALSE) { 
					$this->connect_memcache->replace($key, $json, 0, 86400);
				}else{
					$this->connect_memcache->set($key, $json, 0, 86400);	
				}
			}
			
			return true;
		}
		
		//从数据库加载数据
		function loadFrom_DataBase_ShowtimeId($showtimeid){
			$this->id = $showtimeid;
			if(!$this->connect_database){
				//若连接非法
				//自己创建连接
				$this->creat_DataBase_Connection();
			}
			
			$query = 'SELECT * FROM `m_showtime` WHERE `id`='.$this->id .';';
			$result = mysqli_query($this->connect_database,$query); 
			if($result){ 
				if(mysqli_num_rows($result)>0){
					//找到了
					$value = mysqli_fetch_assoc($result);
					
					$this->name 		= $value['name'];
					$this->add_time 	= $value['add_time'];
					
					return true;
					
				}else{
					//没找到	
					return false;
				}
			}
			return false;
		}
		function loadFrom_DataBase_MovieId($movieid){
			$this->movie_id = $movieid;
			if(!$this->connect_database){
				//若连接非法
				//自己创建连接
				$this->creat_DataBase_Connection();
			}
			
			$query = 'SELECT * FROM `movie_v_showtime` WHERE `movie_id`='.$movieid.';';
			$result = mysqli_query($this->connect_database,$query); 
			if($result){ 
				if(mysqli_num_rows($result)>0){
					//找到了
					$value = mysqli_fetch_assoc($result);
					$this->id 	= $value['showtime_id'];
					
					return $this->loadFrom_ShowtimeId($this->connect_memcache,$this->connect_database,$this->id);
					
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
			$query = 'SELECT * FROM `m_showtime` WHERE `id`='.$this->id .';';
			$result = mysqli_query($this->connect_database,$query); 
			if($result){ 
				if(mysqli_num_rows($result)>0){
					//有 Update
					$query = 'UPDATE `m_showtime` SET '.
						'`name`='			.'\''.str_replace('\'','’',$this->name).'\''.','.
						'`add_time`='		.'\''.$this->add_time.'\''.
						' WHERE `id`='		.$this->id.
						';';
				}else{
					//没有 Insert
					$query = 'INSERT INTO m_showtime(name,add_time) VALUES ('.
								'\''.str_replace('\'','’',$this->name).'\','.
								'\''.$this->add_time.'\''.	 
					');';
				}
				
				$result = mysqli_query($this->connect_database,$query);
				if(!$result){ 
					return false;
				}
				
			}
			
			if($this->movie_id>0){
				$query = 'SELECT * FROM `movie_v_showtime` WHERE `movie_id`='.$this->movie_id .';';
				$result = mysqli_query($this->connect_database,$query); 
				if($result){ 
					if(mysqli_num_rows($result)>0){
						//有 Update
						$query = 'UPDATE `movie_v_showtime` SET '.
							'`showtime_id`='			.$this->id.
							' WHERE `movie_id`='	.$this->movie_id.
							';';
					}else{
						//没有 Insert
						$query = 'INSERT INTO movie_v_showtime(movie_id,showtime_id) VALUES ('.
									$this->movie_id.','.
									$this->id.	 
						');';
					}
					
					$result = mysqli_query($this->connect_database,$query);
					if(!$result){ 
						return false;
					}
					
					return true;
				}
				
			}else{
				return true;	
			}
			
			return false;
		}
		
		//获取JSON的数组表达
		function getJsonStruct(){
				
			$json = array(
				'id' => 0,
				'name' => '',
				'add_time' => ''
			);
			
			$json['id']				=	$this->id;
			$json['name']			=	$this->name;
			$json['add_time']		=	$this->add_time;
			
			return $json;	
		}
			
		//获取接口返回结构{showtime}
		/*
		showtime{
			id
			name
		}
		*/
		function createResponse_showtime($json){
			if(!$json){$json = array();}
			$json['id'] = $this->id;
			$json['showtime'] = $this->name;
			return $json;
		}
		
		
	}
?>