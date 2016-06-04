<?php
	//负责从缓存或者数据库中 读写数据
	require_once(dirname(__FILE__).'/'.'DaoBase.dao.php');
	require_once(dirname(__FILE__).'/'.'vol_v_option.dao.php');
	require_once(dirname(__FILE__).'/'.'vol_v_size.dao.php');
	require_once(dirname(__FILE__).'/'.'vol_v_user.dao.php');
	require_once(dirname(__FILE__).'/'.'vol_v_readdata.dao.php');
	require_once(dirname(__FILE__).'/'.'vol.dao.php');
	class movie_v_vol_dao extends DaoBase{

		public $movie_id;
		public $vol_list;
		
		//初始化
		function __construct(){
			
			parent::__construct(); 
			
			$this->vol_list = array();
			$this->movie_id = 0;
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
			
			//检查movie_id_{movieid}_vol
			$key = 'movie_id_'.$this->movie_id.'_vol';
			if(($value = $this->connect_memcache-> get($key)) != FALSE) { 
				$this->vol_list = $value;
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
			//movie_id_{movieid}_vol
			$key = 'movie_id_'.$this->movie_id.'_vol';
			if(($value = $this->connect_memcache-> get($key)) != FALSE) { 
				$this->connect_memcache->replace($key, $this->vol_list, 0, 86400);
			}else{
				$this->connect_memcache->set($key, $this->vol_list, 0, 86400);	
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
			
			$this->vol_list = array();
			$query = 'SELECT * FROM `movie_vol` WHERE `movie_id`='.$this->movie_id .' ORDER BY `id` ASC;';
			$result = mysqli_query($this->connect_database,$query); 
			if($result){ 
				if(mysqli_num_rows($result)>0){
					$i=0;
					while($i<mysqli_num_rows($result)){
						//找到了
						$value = mysqli_fetch_assoc($result);
						$this->vol_list[$i] = $value['id'];
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
			return true;
			if(!$this->connect_database){
				//若连接非法
				//自己创建连接
				$this->creat_DataBase_Connection();
			}
			foreach($this->vol_list as $volid){
				$query = 'SELECT * FROM `movie_vol` WHERE `movie_id`='.$this->movie_id .' AND `id`='.$volid.';';
				$result = mysqli_query($this->connect_database,$query); 
				if($result){ 
					if(mysqli_num_rows($result)>0){
						//有 不操作
						
					}else{
						//没有 不操作
						//vol单独负责自己的存储
						
					}
				}
			}
			
			return true;
		}
			
		//获取接口返回结构{vols}
		/*
		vols{
			{
				id
				name
				intro
				size
				user{}
				readdata{}
				option{}
			}
			...
		}
		*/
		function createResponse_vols($load_size,$load_user,$load_readdata,$load_option){
			$json = array();
			//初始化变量
			$vol = new vol_dao();
			if($load_size)$size = new vol_v_size_dao();
			if($load_user)$user = new vol_v_user_dao();
			if($load_readdata)$readdata = new vol_v_readdata_dao();
			if($load_option)$option = new vol_v_option_dao();
			
			$i=0;
			foreach($this->vol_list as $volid){
				$json[$i] = array();
				
				//加载vol信息
				if($vol->loadFrom_VolId($this->connect_memcache,$this->connect_database,$volid)){
					$json[$i]['id'] = (string)$volid;
					$json[$i]['name'] = $vol->name;
					$json[$i]['intro'] = $vol->intro;	
				}else{
					$json[$i]['id'] = (string)$volid;
					$json[$i]['name'] = (string)$i;
					$json[$i]['intro'] = '';
				}
				
				//size
				if($load_size){
					if($size->loadFrom_VolId($this->connect_memcache,$this->connect_database,$volid)){
						$json[$i]['size'] = number_format((float)$size->createResponse_size()/1024,2);
					}else{
						$json[$i]['size'] = '-.-';
					}	
				}
				
				//user
				if($load_user){
					if($user->loadFrom_VolId($this->connect_memcache,$this->connect_database,$volid)){
						$json[$i]['user'] = $user->createResponse_user(array(),1);
					}else{
						$json[$i]['user'] = array(
							0=>array(
								'id' => '0',
								'name' => '图解电影',
								'avatar' => '',
								'sex' => '2',
							),
						);
					}
				}
				
				//readdata
				if($load_readdata){
					if($readdata->loadFrom_VolId($this->connect_memcache,$this->connect_database,$volid)){
						$json[$i]['readdata'] = $readdata->createResponse_readdata(array());
					}else{
						$json[$i]['readdata'] = array(
							'ding' => '0',
							'cai' => '0',
							'played' => '0',
							'share' => '0',
							'keep' => '1'
						);
					}
				}
				
				//option
				if($load_option){
					if($option->loadFrom_VolId($this->connect_memcache,$this->connect_database,$volid)){
						$json[$i]['option'] = $option->createResponse_option(array());
					}else{
						$json[$i]['option'] = array(
							'option_id' => '1',
							'option_data' => (string)$volid
						);
					}
				}
				
				//至此一部完成
			}
			

			//如果创建了连接,就关闭
			$vol->close_Connection();
			if($load_size)$size->close_Connection();
			if($load_user)$user->close_Connection();
			if($load_readdata)$readdata->close_Connection();
			if($load_option)$option->close_Connection();
			return $json;
		}
		
		
	}
?>