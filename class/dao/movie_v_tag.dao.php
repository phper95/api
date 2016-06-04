<?php
	//负责从缓存或者数据库中 读写数据
	require_once(dirname(__FILE__).'/'.'DaoBase.dao.php');
	require_once(dirname(__FILE__).'/'.'tag.dao.php');
	class movie_v_tag_dao extends DaoBase{
		
		public $movie_id;
		public $tag_list;
		
		//初始化
		function __construct(){
			parent::__construct(); 
			$this->movie_id = 0;
			$this->tag_list = array();
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
			
			//检查
			/*
			movie_id_{movieid}_tag
			*/
			$key = 'movie_id_'.$this->movie_id.'_tag';
			if(($value = $this->connect_memcache ->get($key)) != FALSE) { 
				$this->tag_list = $value;
				return true;
			}else{
				//没找到
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
			$key = 'movie_id_'.$this->movie_id.'_tag';
			
			if(($value = $this->connect_memcache ->get($key)) != FALSE) { 
				$this->connect_memcache->replace($key, $this->tag_list, 0, 86400);
			}else{
				$this->connect_memcache->set($key, $this->tag_list, 0, 86400);	
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
			
			$this->tag_list = array();
			
			$query = 'SELECT * FROM `movie_v_tag` WHERE `movie_id`='.$this->movie_id.' ORDER BY `tag_id` ASC;';
			$result = mysqli_query($this->connect_database,$query); 
			if($result){ 
				if(mysqli_num_rows($result)>0){
					//找到了
					//初始化一个tag,最多只创建一次连接
					$tag = new tag_dao();
					$i=0;
					while($i<mysqli_num_rows($result)){
						$row = mysqli_fetch_assoc($result);
						
						
						//加载tag信息
						if($tag->loadFrom_TagId($this->connect_memcache,$this->connect_database,$row['tag_id'])){
							//tag信息加载成功
							//检查tag等级
							if(!isset($this->tag_list['lv_'.$tag->level])){
								$this->tag_list['lv_'.$tag->level] = array();
							}
							//存入给定的level中
							//
							$this->tag_list['lv_'.$tag->level][count($this->tag_list['lv_'.$tag->level])] = array(
								'tag_id' => $row['tag_id'],
								'tag_level' => $tag->level,
								'tag_times' => $row['tag_times'],
								'add_time' => $row['add_time'] 
							);
							
						}else{}
						
						
						
						$i++;
					}
					//如果tag_dao创建了连接,就关闭
					$tag->close_Connection();
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
			foreach ($this->tag_list as $lv_key => $lv_value){
				$i = 0;
				while($i<count($this->tag_list[$lv_key])){
					$query = 'SELECT * FROM `movie_v_tag` WHERE `movie_id`='.$this->movie_id .' AND `tag_id`='.$this->tag_list[$lv_key][$i]['tag_id'].';';
					$result = mysqli_query($this->connect_database,$query); 
					if($result){ 
						if(mysqli_num_rows($result)>0){
							//有 Update
							$value = mysqli_fetch_assoc($result);
							$query = 'UPDATE `movie_v_tag` SET '.
									'`movie_id`='		.$this->movie_id.','.
									'`tag_id`='			.$this->tag_list[$lv_key][$i]['tag_id'].','.
									'`tag_times`='		.$this->tag_list[$lv_key][$i]['tag_times'].','.
									' WHERE `id`='		.$value['id'].
									';';
						}else{
							//没有 Insert
							$query = 'INSERT INTO movie_v_tag(movie_id,tag_id,tag_times,add_time) VALUES ('.
										$this->movie_id.','.
										$this->tag_list[$lv_key][$i]['tag_id'].','.
										$this->tag_list[$lv_key][$i]['tag_times'].','.
										'\''.$this->tag_list[$lv_key][$i]['add_time'].'\''. 
							');';
						}
						
						$result = mysqli_query($this->connect_database,$query);
						if(!$result){ 
							return false;
						}
					}
					
					$i++;
				}
			}
			return true;
		}
		
		//检查一个tag如不存在就添加
		function checkMovieTag($tag_id){
			
			if(!$this->checkMovieTagExist($tag_id)){
				//新Tag
				//检查level
				$tag = new tag_dao();
				//加载tag信息
				if($tag->loadFrom_TagId($this->connect_memcache,$this->connect_database,$tag_id)){
					//tag信息加载成功
					//检查tag等级
					if(!isset($this->tag_list['lv_'.$tag->level])){
						$this->tag_list['lv_'.$tag->level] = array();
					}
					//存入给定的level中
					//
					$this->tag_list['lv_'.$tag->level][count($this->tag_list['lv_'.$tag->level])] = array(
						'tag_id' => $tag_id,
						'tag_level' => $tag->level,
						'tag_times' => 1,
						'add_time' => date('Y-m-d H:i:s')
					);
					
					//保存入缓存
					$this->saveTo_MemCache();
					
				}else{}
				
				//如果tag_dao创建了连接,就关闭
				$tag->close_Connection();
				
				return true;
			}
			return false;
		}
		
		//检查一个tag是否存在
		function checkMovieTagExist($tag_id){
			$find = false;
			foreach ($this->tag_list as $lv_key => $lv_value){
				$i = 0;
				while($i<count($this->tag_list[$lv_key])){
					if($tag_id == $this->tag_list[$lv_key][$i]['tag_id']){
						$find = true;
						break;
					}
					$i++;
				}
				if($find){break;}
			}
			
			return $find;
		}
		
		//获取一条记录的JSON数组
		function getOneRecordJsonStruct(){
			$record = array(
				'id' => 0,
				'movie_id' => 0,
				'tag_id' => 0,
				'tag_times' => 0,
				'add_time' => ''
			);	
			return $record;
		}
		
		//获取接口返回结构{tag_list}
		/*
		tag_list{
			lv_0{
				{tag}{
						id
						name
						-total 分类目录中有该项
						-new   分类目录中有该项
						-cover 分类目录中有该项
						-color 搜索界面的Tag列表中有该项
					}
				...
			}
			...
		}
		*/
		//如果level=0就是全部tag
		//details是是否加载各个分类中最新的一部影片信息
		//color是是否加载颜色配置
		function createResponse_tag_list($json,$level,$details,$color){
			if(!$json){$json = array();}
			//检查level
			if($level==0){
				//全部tag
				//初始化一个tag,需要名字,如果创建连接只创建一次
				$tag = new tag_dao();
				foreach ($this->tag_list as $lv_key => $lv_value){
					$i = 0;
					while($i<count($this->tag_list[$lv_key])){
						
						//加载tag信息
						if($tag->loadFrom_TagId($this->connect_memcache,$this->connect_database,$this->tag_list[$lv_key][$i]['tag_id'])){
							if(!isset($json['lv_'.$tag->level])){
								$json['lv_'.$tag->level] = array();
							}
							//存入给定的level中
							//
							$json['lv_'.$tag->level][count($json['lv_'.$tag->level])] = array(
								'id' => $tag->id,
								'name' => $tag->name,
								'level' => $tag->level,
								'times' => $this->tag_list[$lv_key][$i]['tag_times']
							);
						}
						
						$i++;
					}
				}
				//如果tag_dao创建了连接,就关闭
				$tag->close_Connection();
				
			}else{
				if(isset($this->tag_list['lv_'.$level])){
					$lv_key = 'lv_'.$level;
					//初始化一个tag,需要名字,如果创建连接只创建一次
					$tag = new tag_dao();
					$i = 0;
					while($i<count($this->tag_list[$lv_key])){
						
						//加载tag信息
						if($tag->loadFrom_TagId($this->connect_memcache,$this->connect_database,$this->tag_list[$lv_key][$i]['tag_id'])){
							if(!isset($json['lv_'.$tag->level])){
								$json['lv_'.$tag->level] = array();
							}
							//存入给定的level中
							//
							$json['lv_'.$tag->level][count($json['lv_'.$tag->level])] = array(
								'id' => $tag->id,
								'name' => $tag->name,
								'level' => $tag->level,
								'times' => $this->tag_list[$lv_key][$i]['tag_times']
							);
						}
						
						$i++;
					}
					//如果tag_dao创建了连接,就关闭
					$tag->close_Connection();
				}
			}
			return $json;
		}
		
		//创建一个字符串
		function createResponse_tag_string($level,$limit){
			$tag_string = '';
			//检查level
			if($level==0){
				//全部tag
				//初始化一个tag,需要名字,如果创建连接只创建一次
				$tag = new tag_dao();
				foreach ($this->tag_list as $lv_key => $lv_value){
					$i = 0;
					while($i<count($this->tag_list[$lv_key])){
						
						//加载tag信息
						if($tag->loadFrom_TagId($this->connect_memcache,$this->connect_database,$this->tag_list[$lv_key][$i]['tag_id'])){
							//存入给定的level中
							//
							if(strlen($tag_string)>0){
								$tag_string .= ' | ';
							}
							$tag_string .= $tag->name;
							$limit--;
							if($limit==0){break;}
						}
						
						$i++;
					}
				}
				//如果tag_dao创建了连接,就关闭
				$tag->close_Connection();
				
			}else{
				if(isset($this->tag_list['lv_'.$level])){
					$lv_key = 'lv_'.$level;
					//初始化一个tag,需要名字,如果创建连接只创建一次
					$tag = new tag_dao();
					$i = 0;
					while($i<count($this->tag_list[$lv_key])){
						
						//加载tag信息
						if($tag->loadFrom_TagId($this->connect_memcache,$this->connect_database,$this->tag_list[$lv_key][$i]['tag_id'])){
							if(strlen($tag_string)>0){
								$tag_string .= ' | ';
							}
							$tag_string .= $tag->name;
							$limit--;
							if($limit==0){break;}
						}
						
						$i++;
					}
					//如果tag_dao创建了连接,就关闭
					$tag->close_Connection();
				}
			}
			return $tag_string;
		}
	}
?>