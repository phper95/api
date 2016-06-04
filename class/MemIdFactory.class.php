<?php
	require_once(dirname(__FILE__).'/'.'../inc/config.inc.php');
	//提供ID生成器的静态方法
	class MemIdFactory{
		
		//创建连接
		static function creat_MemCache_Connection(){
			$connect_memcache = new Memcache();  
			$connect_memcache->connect(MEM_HOST, MEM_POST); 
			return $connect_memcache;
		}
		
		static function creat_DataBase_Connection(){
			$connect_database = mysqli_connect(HOST,USER,PSD,DB);
			if(!$connect_database){ 
				return false;
			}
			mysqli_query($connect_database, "SET NAMES 'UTF8'");
			return $connect_database;
		}
		
		//创建一个新的UserID
		//表:user_basic
		static function creatNew_userId(){
			$key = 'idfactory_user';
			
			if($connect_memcache = MemIdFactory::creat_MemCache_Connection()){
				
				if(($value = $connect_memcache-> get($key)) != FALSE) { 
					//先占住位置
					$value++;
					$connect_memcache->replace($key, $value, 0, 0);
					
					//关闭连接
					$connect_memcache->close();
					
					//再返回ID
					return $value;
				}
			}
			
			//没有此生成器 或 缓存错误
			//数据库加载
			if($connect_database = MemIdFactory::creat_DataBase_Connection()){
				$query = 'SELECT * FROM `user_basic` ORDER BY `id` DESC LIMIT 1;';
				$result = mysqli_query($connect_database,$query); 
				if($result){ 
					if(mysqli_num_rows($result)>0){
						//找到了
						$row = mysqli_fetch_assoc($result);
						$value = $row['id']+1;
						
						//记录到缓存
						if($connect_memcache){
							$connect_memcache->set($key, $value, 0, 0);
						}
						
						//关闭连接
						mysqli_close($connect_database);
						
						return $value;
					}
				}
				//没结果或没找到
				//1
			}
			
			//数据库连接错误
			//暂时设置为1
			if($connect_memcache){
				$connect_memcache->set($key, 1, 0, 0);
				$connect_memcache->close();
				if($connect_database){mysqli_close($connect_database);}
				return 1;
			}else{
				if($connect_database){mysqli_close($connect_database);}
				return 0;	
			}
		}
	}
?>