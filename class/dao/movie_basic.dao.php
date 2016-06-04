<?php
	//负责从缓存或者数据库中 读写数据
	require_once(dirname(__FILE__).'/'.'DaoBase.dao.php');
	class movie_basic_dao extends DaoBase{
		public $id;
		public $name;
		public $sub_title;
		public $editor_note;
		public $author;
		public $actor;
		public $intro;
		public $score;
		public $bpic;
		public $spic;
		public $pic;
		public $jian;
		public $hot;
		public $open;
		public $add_time;
		public $total_size;
		public $total_page;
		
		//初始化
		function __construct(){
			
			parent::__construct(); 
			
			$this->id	=	0;
			$this->name = 	'';
			$this->sub_title = '';
			$this->editor_note = '';
			$this->author = '';
			$this->actor = '';
			$this->intro = '';
			$this->score = 0;
			$this->bpic = '';
			$this->spic = '';
			$this->pic = '';
			$this->jian = 0;
			$this->hot = 0;
			$this->open = 0;
			$this->add_time = '';
			$this->total_size = 0;
			$this->total_page = 0;
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
			$this->id = $movieid;
			if(!$this->connect_memcache){
				//若连接非法
				//自己创建连接
				$this->creat_MemCache_Connection();
			}
			
			//检查movie_id_{movieid}_basic
			$key = 'movie_id_'.$this->id.'_basic';
			if(($value = $this->connect_memcache-> get($key)) != FALSE) { 
				
				$this->name 		= $value['name'];
				$this->sub_title 	= $value['sub_title'];
				$this->editor_note 	= $value['editor_note'];
				$this->author 		= $value['author'];
				$this->actor 		= $value['actor'];
				$this->intro 		= $value['intro'];
				$this->score 		= $value['score'];
				$this->bpic 		= $value['bpic'];
				$this->spic 		= $value['spic'];
				$this->pic 			= $value['pic'];
				$this->jian 		= $value['jian'];
				$this->hot 			= $value['hot'];
				$this->open 		= $value['open'];
				$this->add_time 	= $value['add_time'];
				$this->total_size 	= $value['total_size'];
				$this->total_page 	= $value['total_page'];
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
			//movie_id_{movieid}_basic
			$key = 'movie_id_'.$this->id.'_basic';
			if(($value = $this->connect_memcache-> get($key)) != FALSE) { 
				$this->connect_memcache->replace($key, $json, 0, 86400);
			}else{
				$this->connect_memcache->set($key, $json, 0, 86400);	
			}
			
			return true;
		}
		
		//从数据库加载数据
		function loadFrom_DataBase_MovieId($movieid){
			$this->id = $movieid;
			if(!$this->connect_database){
				//若连接非法
				//自己创建连接
				$this->creat_DataBase_Connection();
			}
			
			$query = 'SELECT * FROM `movie_basic` WHERE `id`='.$this->id .';';
			$result = mysqli_query($this->connect_database,$query); 
			if($result){ 
				if(mysqli_num_rows($result)>0){
					//找到了
					$value = mysqli_fetch_assoc($result);
					
					$this->name 		= $value['name'];
					$this->sub_title 	= $value['sub_title'];
					$this->editor_note 	= $value['editor_note'];
					$this->author 		= $value['author'];
					$this->actor 		= $value['actor'];
					$this->intro 		= $value['intro'];
					$this->score 		= $value['score'];
					$this->bpic 		= $value['bpic'];
					$this->spic 		= $value['spic'];
					$this->pic 			= $value['pic'];
					$this->jian 		= $value['jian'];
					$this->hot 			= $value['hot'];
					$this->open 		= $value['open'];
					$this->add_time 	= $value['add_time'];
					$this->total_size 	= $value['total_size'];
					$this->total_page 	= $value['total_page'];
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
			$query = 'SELECT * FROM `movie_basic` WHERE `id`='.$this->id .';';
			$result = mysqli_query($this->connect_database,$query); 
			if($result){ 
				if(mysqli_num_rows($result)>0){
					//有 Update
					$query = 'UPDATE `movie_basic` SET '.
						'`name`='				.'\''.str_replace('\'','’',$this->name).'\''.','.
						'`sub_title`='			.'\''.str_replace('\'','’',$this->sub_title).'\''.','.
						'`editor_note`='		.'\''.str_replace('\'','’',$this->editor_note).'\''.','.
						'`author`='				.'\''.str_replace('\'','’',$this->author).'\''.','.
						'`actor`='				.'\''.str_replace('\'','’',$this->actor).'\''.','.
						'`intro`='				.'\''.str_replace('\'','’',$this->intro).'\''.','.
						'`score`='				.$this->score.','.
						'`bpic`='				.'\''.$this->bpic.'\''.','.
						'`spic`='				.'\''.$this->spic.'\''.','.
						'`pic`='				.'\''.$this->pic.'\''.','.
						'`jian`='				.$this->jian.','.
						'`hot`='				.$this->hot.','.
						'`open`='				.$this->open.','.
						'`add_time`='			.'\''.$this->add_time.'\''.
						'`total_size`='			.$this->total_size.','.
						'`total_page`='			.'\''.$this->total_page.'\''.
						' WHERE `id`='		.$this->id.
						';';
				}else{
					//没有 Insert
					$query = 'INSERT INTO movie_basic(name,sub_title,editor_note,author,actor,intro,score,bpic,spic,pic,jian,hot,open,add_time,total_size,total_page) VALUES ('.
								'\''.str_replace('\'','’',$this->name).'\','.
								'\''.str_replace('\'','’',$this->sub_title).'\','.
								'\''.str_replace('\'','’',$this->editor_note).'\','.
								'\''.str_replace('\'','’',$this->author).'\','.
								'\''.str_replace('\'','’',$this->actor).'\','.
								'\''.str_replace('\'','’',$this->intro).'\','.
									 $this->score.','.
								'\''.$this->bpic.'\','.
								'\''.$this->spic.'\','.
								'\''.$this->pic.'\','.
									 $this->jian.','.
									 $this->hot.','.
									 $this->open.','.
								'\''.$this->add_time.'\','.	
								''.$this->total_size.','.	
								''.$this->total_page.''.	 
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
				'sub_title' => '',
				'editor_note' => '',
				'author' => '',
				'actor' => '',
				'intro' => '',
				'score' => 0,
				'bpic' => '',
				'spic' => '',
				'pic' => '',
				'jian' => 0,
				'hot' => 0,
				'open' => 0,
				'add_time' => '',
				'total_size' => 0,
				'total_page' => 0
			);
			
			$json['id']				=	$this->id;
			$json['name']			=	$this->name;
			$json['sub_title']		=	$this->sub_title;
			$json['editor_note']	=	$this->editor_note;
			$json['author']			=	$this->author;
			$json['actor']			=	$this->actor;
			$json['intro']			=	$this->intro;
			$json['score']			=	$this->score;
			$json['bpic']			=	$this->bpic;
			$json['spic']			=	$this->spic;
			$json['pic']			=	$this->pic;
			$json['jian']			=	$this->jian;
			$json['hot']			=	$this->hot;
			$json['open']			=	$this->open;
			$json['add_time']		=	$this->add_time;
			$json['total_size']		=	$this->total_size;
			$json['total_page']		=	$this->total_page;
			return $json;	
		}
			
		//获取接口返回结构{movie}
		/*
		movie{
			id
			name
			sub_title
			editor_note
			author
			actor
			intro
			score
			bpic
			spic
			pic
			jian
			hot
			open
			add_time
			total_size
			total_page
		}
		*/
		function createResponse_movie($json){
			if(!$json){$json = array();}
			$json['id'] 			= (string)$this->id;
			$json['name'] 			= $this->name;
			$json['sub_title'] 		= $this->sub_title;
			$json['editor_note'] 	= $this->editor_note;
			$json['author'] 		= $this->author;
			$json['actor'] 			= $this->actor;
			$json['intro'] 			= $this->intro;
			$json['score'] 			= (string)$this->score;
			$json['bpic'] 			= $this->bpic;
			$json['spic'] 			= $this->spic;
			$json['pic'] 			= $this->pic;
			$json['jian'] 			= (string)$this->jian;
			$json['hot'] 			= (string)$this->hot;
			$json['open'] 			= (string)$this->open;
			$json['add_time'] 		= $this->add_time;
			$json['total_size'] 	= (string)$this->total_size;
			$json['total_page'] 	= (string)$this->total_page;
			return $json;
		}
		
		
	}
?>