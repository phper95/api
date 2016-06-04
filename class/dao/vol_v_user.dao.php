<?php
	//负责从缓存或者数据库中 读写数据
	require_once(dirname(__FILE__).'/'.'DaoBase.dao.php');
	require_once(dirname(__FILE__).'/'.'user_basic.dao.php');
	class vol_v_user_dao extends DaoBase{
		public $id;
		public $movie_id;
		public $vol_id;
		public $user_list;
		
		//初始化
		function __construct(){
			
			parent::__construct(); 
			
			$this->id =	0;
			$this->user_list = array();
			$this->movie_id = 0;
			$this->vol_id = 0;
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
			
			//检查vol_id_{vol_id}_user
			$key = 'vol_id_'.$this->vol_id.'_user';
			if(($value = $this->connect_memcache-> get($key)) != FALSE) { 
				$this->user_list = $value;
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
			//vol_id_{vol_id}_user
			$key = 'vol_id_'.$this->vol_id.'_user';
			if(($value = $this->connect_memcache-> get($key)) != FALSE) { 
				$this->connect_memcache->replace($key, $this->user_list, 0, 86400);
			}else{
				$this->connect_memcache->set($key, $this->user_list, 0, 86400);	
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
			
			$this->user_list = array();
			$query = 'SELECT * FROM `vol_v_user` WHERE `vol_id`='.$this->vol_id .' ORDER BY `add_time` ASC;';
			$result = mysqli_query($this->connect_database,$query); 
			if($result){ 
				if(mysqli_num_rows($result)>0){
					$i=0;
					while($i<mysqli_num_rows($result)){
						//找到了
						$value = mysqli_fetch_assoc($result);
						$this->user_list[$i] = $value['user_id'];
					$i++;
					}
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
			foreach($this->user_list as $user_id){
				$query = 'SELECT * FROM `vol_v_user` WHERE `vol_id`='.$this->vol_id .' AND `user_id`='.$user_id.';';
				$result = mysqli_query($this->connect_database,$query); 
				if($result){ 
					if(mysqli_num_rows($result)>0){
						//有 不操作
						
					}else{
						//没有 Insert
						$query = 'INSERT INTO vol_v_user(movie_id,vol_id,user_id,add_time) VALUES ('.
										 $this->movie_id.','.
										 $this->vol_id.','.
										 $user_id.','.
										 'now()'.	 
						');';
						$result = mysqli_query($this->connect_database,$query);
						if(!$result){ 
							return false;
						}
					}
				}
			}
			
			return true;
		}
			
		//获取接口返回结构{user}
		/*
		user{
			id
			name
			avatar
			sex
		}
		*/
		function createResponse_user($json,$limit){
			if(!$json){$json = array();}
			//加载用户信息
			$user = new user_basic_dao();
			foreach($this->user_list as $user_id){
				//加载user信息
				if($user->loadFrom_UserId($this->connect_memcache,$this->connect_database,$user_id)){
					$json[count($json)] = array(
						'id' => (string)$user->id,
						'name' => $user->name?$user->name:'图解电影',
						'avatar' => $user->avatar,
						'sex' => (string)$user->sex,
					);
					$limit--;
					if($limit<=0){break;}
				}
			}
			
			//如果没有加载到
			if(count($json)==0){
				$json[0] = array(
					'id' => '0',
					'name' => '图解电影',
					'avatar' => '',
					'sex' => '2',
				);	
			}
			
			//如果user_basic_dao创建了连接,就关闭
			$user->close_Connection();
			return $json;
		}
		
		
	}
?>