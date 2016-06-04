<?php
	//负责从缓存或者数据库中 读写数据
	require_once(dirname(__FILE__).'/'.'DaoBase.dao.php');
	//require_once(dirname(__FILE__).'/'.'user_basic.dao.php');
	require_once(dirname(__FILE__).'/'.'volpage_poptxt.dao.php');
	class user_v_poptxt_movie_dao extends DaoBase{
		
		public $movie_id;
		public $vol_id;
		public $page_index;
		public $base_time;
		public $poptxt_list;
		public $limit;
		
		//初始化
		function __construct(){
			
			parent::__construct(); 
	
			$this->poptxt_list = array();
			$this->movie_id = 0;
			$this->vol_id = 0;
			$this->page_index = 0;
			$this->base_time = '2999-01-01 00:00:00';
			$this->limit = 20;
		}
		
		//加载数据,无论是MemCache还是Database
		function loadFrom_MovieId($connect_memcache,$connect_database,$movieid,$volid,$pageindex,$base_time,$limit){
			//先尝试缓存加载
			if(!$connect_memcache){
				//没有给定缓存连接
				//自己创建连接
				$this->creat_MemCache_Connection();
			}else{
				$this->set_MemCache_Connection($connect_memcache);	
			}
			
			if($this->loadFrom_MemCache_MovieId($movieid,$volid,$pageindex,$base_time,$limit)){
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
			
			if($this->loadFrom_DataBase_MovieId($movieid,$volid,$pageindex,$base_time,$limit)){
				//数据库加载完就写入Memcache
				$this->saveTo_MemCache();
				return true;
			}
			
			//数据库也失败,那就真失败了
			return false;
		}
		
		//从缓存加载数据
		function loadFrom_MemCache_MovieId($movieid,$volid,$pageindex,$base_time,$limit){
			$this->movie_id = $movieid;
			$this->base_time = $base_time;
			$this->limit = $limit;
			$this->vol_id = $volid;
			$this->page_index = $pageindex;
			
			if($this->limit<=0)$this->limit=20;
			
			if(!$this->connect_memcache){
				//若连接非法
				//自己创建连接
				$this->creat_MemCache_Connection();
			}
			
			//检查movie_id_{movieid}_user_poptxt_{volid}_{pageindex}_{base_time}_{limit}
			$key = 'movie_id_'.$this->movie_id.'_user_poptxt'.'_'.$this->vol_id.'_'.$this->page_index.'_'.$this->base_time.'_'.$this->limit;
			if(($value = $this->connect_memcache-> get($key)) != FALSE) { 
				$this->poptxt_list = $value;
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
			//记录Memcache
			//movie_id_{movieid}_user_poptxt_{volid}_{pageindex}_{base_time}_{limit}
			$key = 'movie_id_'.$this->movie_id.'_user_poptxt'.'_'.$this->vol_id.'_'.$this->page_index.'_'.$this->base_time.'_'.$this->limit;
			
			if(($value = $this->connect_memcache-> get($key)) != FALSE) { 
				$this->connect_memcache->replace($key, $this->poptxt_list, 0, 300);
			}else{
				$this->connect_memcache->set($key, $this->poptxt_list, 0, 300);	
			}
			
			return true;
		}
		
		//从数据库加载数据
		function loadFrom_DataBase_MovieId($movieid,$volid,$pageindex,$base_time,$limit){
			$this->movie_id = $movieid;
			$this->base_time = $base_time;
			$this->limit = $limit;
			$this->vol_id = $volid;
			$this->page_index = $pageindex;
			
			if(!$this->connect_database){
				//若连接非法
				//自己创建连接
				$this->creat_DataBase_Connection();
			}
			
			$this->poptxt_list = array();
			$query = 'SELECT * FROM `user_v_poptxt_movie` WHERE `movie_id`='.$this->movie_id .' AND `vol_id`='.$this->vol_id.' AND `page_index`= '.$this->page_index.' AND `add_time`<\''.$this->base_time.'\' ORDER BY `add_time` DESC LIMIT '.$this->limit.';';
			
			$result = mysqli_query($this->connect_database,$query); 
			if($result){ 
				if(mysqli_num_rows($result)>0){
					$i=0;
					//初始化一个poptxt
					$poptxt = new volpage_poptxt_dao();
					while($i<mysqli_num_rows($result)){
						//找到了
						$value = mysqli_fetch_assoc($result);
						
						//加载数据
						if($poptxt->loadFrom_PoptxtId($this->connect_memcache,$this->connect_database,$value['id'])){
							//加载成功	
							$this->poptxt_list[count($this->poptxt_list)] = $poptxt->createResponse_poptxt(false,true,false);
						}
						
						$i++;
					}
					//如果poptxt创建了连接,就关闭
					$poptxt->close_Connection();
					
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
			//整部电影的评论不需要这样遍历存储
			/*
			if(!$this->connect_database){
				//若连接非法
				//自己创建连接
				$this->creat_DataBase_Connection();
			}
			foreach($this->poptxt_list as $comment){
				$query = 'SELECT * FROM `user_v_comment_movie` WHERE `id`='.$comment['id'].';';
				$result = mysqli_query($this->connect_database,$query); 
				if($result){ 
					if(mysqli_num_rows($result)>0){
						//有 Update
						$query = 'UPDATE `user_v_comment_movie` SET '.
							'`user_id`='			.$comment['user_id'].','.
							'`movie_id`='			.$comment['movie_id'].','.
							'`vol_id`='				.$comment['vol_id'].','.
							'`page_index`='			.$comment['page_index'].','.
							'`comment_content`='	.'\''.formatTextQuo($pic['script']).'\''.','.
							'`reply_comment_id`='	.$comment['reply_comment_id'].
							' WHERE `id`='	.$comment['id'].
							';';
					}else{
						//没有 Insert
						$query = 'INSERT INTO user_v_comment_movie(id,user_id,movie_id,vol_id,page_index,comment_content,reply_comment_id,add_time) VALUES ('.
										 $comment['id'].','.
										 $comment['user_id'].','.
										 $comment['movie_id'].','.
										 $comment['vol_id'].','.
										 $comment['page_index'].','.
										 $comment['comment_content'].','.
										 $comment['reply_comment_id'].','.
										 $comment['add_time'].
						');';
						$result = mysqli_query($this->connect_database,$query);
						if(!$result){ 
							return false;
						}
					}
				}
			}
			*/
			return true;
		}
			
		//获取接口返回结构{poptxts}
		/*
		poptxts{
			{
			poptxt,poptxt	
			}
		}
		*/
		function createResponse_poptxts(){
			$json = $this->poptxt_list;
			return $json;
		}
		
		
	}
?>