<?php
	//负责从缓存或者数据库中 读写数据
	require_once(dirname(__FILE__).'/'.'DaoBase.dao.php');
	require_once(dirname(__FILE__).'/'.'user_basic.dao.php');
	require_once(dirname(__FILE__).'/'.'vol.dao.php');
	class volpage_poptxt_dao extends DaoBase{
		public $id;
		public $user_id;
		public $movie_id;
		public $vol_id;
		public $page_index;
		public $comment_content;
		public $reply_comment_id;
		public $add_time;
		
		//初始化
		function __construct(){
			
			parent::__construct(); 
			
			$this->id	=	0;
			$this->user_id = 0;
			$this->movie_id = 0;
			$this->vol_id = 0;
			$this->page_index = 0;
			$this->comment_content = '';
			$this->reply_comment_id = 0;
			$this->add_time = '';
		}
		
		//加载数据,无论是MemCache还是Database
		function loadFrom_PoptxtId($connect_memcache,$connect_database,$poptxtid){
			//先尝试缓存加载
			if(!$connect_memcache){
				//没有给定缓存连接
				//自己创建连接
				$this->creat_MemCache_Connection();
			}else{
				$this->set_MemCache_Connection($connect_memcache);	
			}
			
			if($this->loadFrom_MemCache_PoptxtId($poptxtid)){
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
			
			if($this->loadFrom_DataBase_PoptxtId($poptxtid)){
				//数据库加载完就写入Memcache
				$this->saveTo_MemCache();
				return true;
			}
			
			//数据库也失败,那就真失败了
			return false;
		}
		

		//从缓存加载数据
		function loadFrom_MemCache_PoptxtId($poptxtid){
			$this->id = $poptxtid;
			if(!$this->connect_memcache){
				//若连接非法
				//自己创建连接
				$this->creat_MemCache_Connection();
			}
			
			//检查movie_poptxt_id_{poptxt_id}
			$key = 'movie_poptxt_id_'.$this->id;
			if(($value = $this->connect_memcache-> get($key)) != FALSE) { 
			
				$this->user_id = $value['user_id'];
				$this->movie_id = $value['movie_id'];
				$this->vol_id = $value['vol_id'];
				$this->page_index = $value['page_index'];
				$this->comment_content = $value['comment_content'];
				$this->reply_comment_id = $value['reply_comment_id'];
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
			//movie_poptxt_id_{poptxt_id}
			$key = 'movie_poptxt_id_'.$this->id;
			if(($value = $this->connect_memcache-> get($key)) != FALSE) { 
				$this->connect_memcache->replace($key, $json, 0, 300);
			}else{
				$this->connect_memcache->set($key, $json, 0, 300);	
			}
		
			return true;
		}
		
		//从数据库加载数据
		function loadFrom_DataBase_PoptxtId($poptxtid){
			$this->id = $poptxtid;
			if(!$this->connect_database){
				//若连接非法
				//自己创建连接
				$this->creat_DataBase_Connection();
			}
			
			$query = 'SELECT * FROM `user_v_poptxt_movie` WHERE `id`='.$this->id .';';
			$result = mysqli_query($this->connect_database,$query); 
			if($result){ 
				if(mysqli_num_rows($result)>0){
					//找到了
					$value = mysqli_fetch_assoc($result);
					
					$this->user_id = $value['user_id'];
					$this->movie_id = $value['movie_id'];
					$this->vol_id = $value['vol_id'];
					$this->page_index = $value['page_index'];
					$this->comment_content = $value['comment_content'];
					$this->reply_comment_id = $value['reply_comment_id'];
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
			$query = 'SELECT * FROM `user_v_poptxt_movie` WHERE `id`='.$this->id .';';
			$result = mysqli_query($this->connect_database,$query); 
			if($result){ 
				if(mysqli_num_rows($result)>0){
					//有 Update
					$query = 'UPDATE `user_v_poptxt_movie` SET '.
							'`user_id`='			.$this->user_id.','.
							'`movie_id`='			.$this->movie_id.','.
							'`vol_id`='				.$this->vol_id.','.
							'`page_index`='			.$this->page_index.','.
							'`comment_content`='	.'\''.formatTextQuo($this->comment_content).'\''.','.
							'`reply_comment_id`='	.$this->reply_comment_id.
							' WHERE `id`='	.$this->id.
							';';
				}else{
					//没有 Insert
					$query = 'INSERT INTO user_v_poptxt_movie(id,user_id,movie_id,vol_id,page_index,comment_content,reply_comment_id,add_time) VALUES ('.
										 $this->id.','.
										 $this->user_id.','.
										 $this->movie_id.','.
										 $this->vol_id.','.
										 $this->page_index.','.
										 '\''.formatTextQuo($this->comment_content).'\''.','.
										 $this->reply_comment_id.','.
										 $this->add_time.
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
				'user_id' => 0,
				'movie_id' => 0,
				'vol_id' => 0,
				'page_index' => 0,
				'comment_content' => '',
				'reply_comment_id' => 0,
				'add_time' => ''
			);
			
			$json['id']					=	$this->id;
			$json['user_id']			=	$this->user_id;
			$json['movie_id']			=	$this->movie_id;
			$json['vol_id']				=	$this->vol_id;
			$json['page_index']			=	$this->page_index;
			$json['comment_content']	=	$this->comment_content;
			$json['reply_comment_id']	=	$this->reply_comment_id;
			$json['add_time']			=	$this->add_time;
			
			return $json;
		}
			
		//获取接口返回结构{poptxt}
		/*
		poptxt{
			id
			vol{}
			page_index
			user{}
			reply
			content
			add_time
			
		}
		*/
		function createResponse_poptxt($loadvol,$loaduser,$loadreply){
			$json = array();
			$json['id'] = $this->id;
			
			if($loadvol){
				$vol = new vol_dao();
				if($vol->loadFrom_VolId($this->connect_memcache,$this->connect_database,$this->vol_id)){
					$json['vol'] = $vol->createResponse_vol();	
				}
				$vol->close_Connection();
			}
			
			$json['page_index'] = $this->page_index;
			
			if($loaduser){
				$user = new user_basic_dao();
				if($user->loadFrom_UserId($this->connect_memcache,$this->connect_database,$this->user_id)){
					$json['user'] = array(
						'id' => (string)$this->user_id,
						'name' => $user->name?$user->name:'用户('.$this->user_id.')',
						'avatar' => $user->avatar,
						'sex' => (string)$user->sex
					);	
				}else{
					$json['user'] = array(
						'id' => '0',
						'name' => '匿名用户',
						'avatar' => '',
						'sex' => '0'
					);
				}
				$user->close_Connection();
			}
			
			if($loadreply && $this->reply_comment_id>0){
				$reply = new movie_comment_dao();
				if($reply->loadFrom_PoptxtId($this->connect_memcache,$this->connect_database,$this->reply_comment_id)){
					//如果reply一直深层加载进去,类似网易新闻的效果
					$json['reply'] = $reply->createResponse_comment(false,true,false);
				}
				$reply->close_Connection();
			}
			
			$json['content'] = $this->comment_content;
			
			$json['add_time'] = $this->add_time;
			
			return $json;
		}
		
		
	}
?>