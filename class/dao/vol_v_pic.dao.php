<?php
	//负责从缓存或者数据库中 读写数据
	require_once(dirname(__FILE__).'/'.'DaoBase.dao.php');
	require_once(dirname(__FILE__).'/'.'../../inc/methods.inc.php');
	class vol_v_pic_dao extends DaoBase{
		public $imgserver_id;
		public $movie_id;
		public $vol_id;
		public $pic_list;
		
		//初始化
		function __construct(){
			
			parent::__construct(); 
			
			$this->imgserver_id = 0;
			$this->pic_list = array();
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
			
			//检查vol_id_{vol_id}_{imgserver_id}_pic
			$key = 'vol_id_'.$this->vol_id.'_'.$this->imgserver_id.'_pic';
			if(($value = $this->connect_memcache-> get($key)) != FALSE) { 
				$this->pic_list = $value;
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
			//vol_id_{vol_id}_{imgserver_id}_pic
			$key = 'vol_id_'.$this->vol_id.'_'.$this->imgserver_id.'_pic';
			if(($value = $this->connect_memcache-> get($key)) != FALSE) { 
				$this->connect_memcache->replace($key, $this->pic_list, 0, 86400);
			}else{
				$this->connect_memcache->set($key, $this->pic_list, 0, 86400);	
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
			
			$this->pic_list = array();
			$query = 'SELECT * FROM `vol_v_pic` WHERE `vol_id`='.$this->vol_id .' ORDER BY `id` ASC;';
			$result = mysqli_query($this->connect_database,$query); 
			if($result){ 
				if(mysqli_num_rows($result)>0){
					$i=0;
					while($i<mysqli_num_rows($result)){
						//找到了
						$value = mysqli_fetch_assoc($result);
						$this->movie_id = $value['movie_id'];
						$this->pic_list[$i] = array(
							'id'	=> $value['id'],
							'image' => $value['image'],
							'intro' => trim($value['intro']),
							'script' => $value['script']
						);
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
			$i=0;
			foreach($this->pic_list as $pic){
				$query = 'SELECT * FROM `vol_v_pic` WHERE `vol_id`='.$this->vol_id .' AND `id`='.$pic['id'].';';
				$result = mysqli_query($this->connect_database,$query); 
				if($result){ 
					if(mysqli_num_rows($result)>0){
						//有 Update
						$query = 'UPDATE `vol_v_pic` SET '.
							'`movie_id`='	.$this->movie_id.','.
							'`vol_id`='		.$this->vol_id.','.
							'`image`='		.'\''.$pic['image'].'\''.','.
							'`intro`='		.'\''.formatTextQuo($pic['intro']).'\''.','.
							'`script`='		.'\''.$pic['script'].'\''.','.
							' WHERE `id`='	.$pic['id'].
							';';
						
					}else{
						//没有 Insert
						$query = 'INSERT INTO vol_v_pic(movie_id,vol_id,image,intro,script,pindex) VALUES ('.
										 $this->movie_id.','.
										 $this->vol_id.','.
										 '\''.$pic['image'].'\''.','.
										 '\''.formatTextQuo($pic['intro']).'\''.','.
										 '\''.$pic['script'].'\''.','.
										 $i.	 
						');';
						$result = mysqli_query($this->connect_database,$query);
						if(!$result){ 
							return false;
						}
					}
				}
				$i++;
			}
			
			return true;
		}
			
		//获取接口返回结构{script}
		/*
		script{
			{
				image
				intro
				script
			}
		}
		*/
		function createResponse_script($json,$imgserver_id){
			$this->imgserver_id = $imgserver_id;
			if(!$json){$json = array();}

			foreach($this->pic_list as $pic){
				$json[count($json)] = array(
					'id' => (string)$pic['id'],
					//'image' => otherURL_2_Server_URL($pic['image'],$this->movie_id,$this->imgserver_id),
					'image' => get_file_name($pic['image']).'.'.get_file_extension($pic['image']),
					'intro' => trim($pic['intro']),
					'script' => $pic['script']
				);
			}
			return $json;
		}
		
		
	}
?>