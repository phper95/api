<?php
	//负责从缓存或者数据库中 读写数据
	require_once(dirname(__FILE__).'/'.'DaoBase.dao.php');
	class vol_dao extends DaoBase{
		public $id;
		public $movie_id;
		public $name;
		public $intro;
		public $vindex;
		public $open;
		public $add_time;
		
		//初始化
		function __construct(){
			
			parent::__construct(); 
			
			$this->id = 0;
			$this->movie_id = 0;
			$this->name = '';
			$this->intro = '';
			$this->vindex = 0;
			$this->open = 0;
			$this->add_time = '';
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
			$this->id = $volid;
			if(!$this->connect_memcache){
				//若连接非法
				//自己创建连接
				$this->creat_MemCache_Connection();
			}
			
			//检查vol_id_{volid}
			$key = 'vol_id_'.$this->id;
			if(($value = $this->connect_memcache-> get($key)) != FALSE) { 
				
				$this->id 		= $value['id'];
				$this->movie_id = $value['movie_id'];
				$this->name 	= $value['name'];
				$this->intro 	= $value['intro'];
				$this->vindex 	= $value['vindex'];
				$this->open 	= $value['open'];
				$this->add_time = $value['add_time'];
				
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
			//vol_id_{vol_id}
			$key = 'vol_id_'.$this->id;
			if(($value = $this->connect_memcache-> get($key)) != FALSE) { 
				$this->connect_memcache->replace($key, $json, 0, 86400);
			}else{
				$this->connect_memcache->set($key, $json, 0, 86400);	
			}
			
			return true;
		}
		
		//从数据库加载数据
		function loadFrom_DataBase_VolId($volid){
			$this->id = $volid;
			if(!$this->connect_database){
				//若连接非法
				//自己创建连接
				$this->creat_DataBase_Connection();
			}
			
			$query = 'SELECT * FROM `movie_vol` WHERE `id`='.$this->id .';';
			$result = mysqli_query($this->connect_database,$query); 
			if($result){ 
				if(mysqli_num_rows($result)>0){
					//找到了
					$value = mysqli_fetch_assoc($result);
					
					$this->id 		= $value['id'];
					$this->movie_id = $value['movie_id'];
					$this->name 	= $value['name'];
					$this->intro 	= $value['intro'];
					$this->vindex 	= $value['vindex'];
					$this->open 	= $value['open'];
					$this->add_time = $value['add_time'];
					
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
			
			$query = 'SELECT * FROM `movie_vol` WHERE `id`='.$this->id .';';
			$result = mysqli_query($this->connect_database,$query); 
			if($result){ 
				if(mysqli_num_rows($result)>0){
					//有 Update
					$query = 'UPDATE `movie_vol` SET '.
						'`movie_id`='	.$this->movie_id.','.
						'`name`='		.'\''.formatTextQuo($this->name).'\''.','.
						'`intro`='		.'\''.formatTextQuo($this->intro).'\''.','.
						'`vindex`='		.$this->vindex.','.
						'`open`='		.$this->open.
						' WHERE `id`='	.$this->id.
						';';
				}else{
					//没有 Insert
					$query = 'INSERT INTO movie_vol(id,movie_id,name,intro,vindex,open,add_time) VALUES ('.
								$this->id.','.
								$this->movie_id.','.
								'\''.formatTextQuo($this->name).'\''.','.
								'\''.formatTextQuo($this->intro).'\''.','.
								$this->vindex.','.
								$this->open.','.
								'\''.$this->add_time.'\''.	 
					');';
				}
				
				$result = mysqli_query($this->connect_database,$query);
				if(!$result){ 
					return false;
				}
					
			}
			
			return false;
		}
		
		//获取JSON的数组表达
		function getJsonStruct(){
				
			$json = array(
				'id' => 0,
				'movie_id' => 0,
				'name' => '',
				'intro' => '',
				'vindex' => 0,
				'open' => 0,
				'add_time' => ''
			);
			
			$json['id']				=	$this->id;
			$json['movie_id']		=	$this->movie_id;
			$json['name']			=	$this->name;
			$json['intro']			=	$this->intro;
			$json['vindex']			=	$this->vindex;
			$json['open']			=	$this->open;
			$json['add_time']		=	$this->add_time;
			
			return $json;	
		}
			
		//获取接口返回结构{vol}
		/*
		vol{
			id
			movie_id
			name
			intro
		}
		*/
		function createResponse_vol(){
			$json = array(
				'id' => $this->id,
				'movie_id' => $this->movie_id,
				'name' => $this->name,
				'intro' => $this->intro
			);
			
			return $json;
		}
		
		
	}
?>