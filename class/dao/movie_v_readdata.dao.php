<?php
	//负责从缓存或者数据库中 读写数据
	require_once(dirname(__FILE__).'/'.'DaoBase.dao.php');
	class movie_v_readdata_dao extends DaoBase{
		public $id;
		public $movie_id;
		public $ding;
		public $cai;
		public $played;
		public $share;
		public $keep;
		
		//初始化
		function __construct(){
			
			parent::__construct(); 
			
			$this->id	=	0;
			$this->movie_id = 	0;
			$this->ding = 0;
			$this->cai = 0;
			$this->played = 0;
			$this->share = 0;
			$this->keep = 0;
		}
		
		//加载数据,无论是MemCache还是Database
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
				//数据库加载完就写入Memcache
				$this->saveTo_MemCache();
				return true;
			}
			
			//数据库也失败,那就真失败了
			return false;
		}
		
		//从缓存加载数据
		function loadFrom_MemCache_MovieId($movieid){
			$this->movie_id = $movieid;
			if(!$this->connect_memcache){
				//若连接非法
				//自己创建连接
				$this->creat_MemCache_Connection();
			}
			
			//检查movie_id_{movieid}_readdata
			$key = 'movie_id_'.$this->movie_id.'_readdata';
			if(($value = $this->connect_memcache-> get($key)) != FALSE) { 
				
				$this->id 		= $value['id'];
				$this->ding 	= $value['ding'];
				$this->cai 		= $value['cai'];
				$this->played 	= $value['played'];
				$this->share 	= $value['share'];
				$this->keep 	= $value['keep'];
				
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
			//movie_id_{movieid}_readdata
			$key = 'movie_id_'.$this->movie_id.'_readdata';
			if(($value = $this->connect_memcache-> get($key)) != FALSE) { 
				$this->connect_memcache->replace($key, $json, 0, 0);
			}else{
				$this->connect_memcache->set($key, $json, 0, 0);	
			}
			
			return true;
		}
		
		//从数据库加载数据
		function loadFrom_DataBase_MovieId($movieid){
			$this->movie_id = $movieid;
			if(!$this->connect_database){
				//若连接非法
				//自己创建连接
				$this->creat_DataBase_Connection();
			}
			
			$query = 'SELECT * FROM `movie_v_readdata` WHERE `movie_id`='.$this->movie_id .';';
			$result = mysqli_query($this->connect_database,$query); 
			if($result){ 
				if(mysqli_num_rows($result)>0){
					//找到了
					$value = mysqli_fetch_assoc($result);
					
					$this->id 		= $value['id'];
					$this->ding 	= $value['ding'];
					$this->cai 		= $value['cai'];
					$this->played 	= $value['played'];
					$this->share 	= $value['share'];
					$this->keep 	= $value['keep'];
					
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
			$query = 'SELECT * FROM `movie_v_readdata` WHERE `movie_id`='.$this->movie_id .';';
			$result = mysqli_query($this->connect_database,$query); 
			if($result){ 
				if(mysqli_num_rows($result)>0){
					//有 Update
					$query = 'UPDATE `movie_v_readdata` SET '.
						'`ding`='		.$this->ding.','.
						'`cai`='		.$this->cai.','.
						'`played`='		.$this->played.','.
						'`share`='		.$this->share.','.
						'`keep`='		.$this->keep.
						' WHERE `movie_id`='		.$this->movie_id.
						';';
				}else{
					//没有 Insert
					$query = 'INSERT INTO movie_v_readdata(ding,cai,played,share,keep) VALUES ('.
								$this->ding.','.
								$this->cai.','.
								$this->played.','.
								$this->share.','.
								$this->keep. 
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
				'ding' => 0,
				'cai' => 0,
				'played' => 0,
				'share' => 0,
				'keep' => 0
			);
			
			$json['id']				=	$this->id;
			$json['movie_id']		=	$this->movie_id;
			$json['ding']			=	$this->ding;
			$json['cai']			=	$this->cai;
			$json['played']			=	$this->played;
			$json['share']			=	$this->share;
			$json['keep']			=	$this->keep;
			
			return $json;	
		}
			
		//获取接口返回结构{readdata}
		/*
		readdata{
			ding
			cai
			played
			share
			keep
		}
		*/
		function createResponse_readdata($json){
			if(!$json){$json = array();}
			$json['ding'] 			= (string)$this->ding;
			$json['cai'] 			= $this->cai;
			$json['played'] 		= $this->played;
			$json['share'] 			= $this->share;
			$json['keep'] 			= $this->keep;
			return $json;
		}
		
		
	}
?>