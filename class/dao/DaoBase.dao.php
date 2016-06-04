<?php
	require_once(dirname(__FILE__).'/'.'../../inc/config.inc.php');
	class DaoBase{
		//Connection
		public $connect_memcache;
		public $connect_database;
		
		//记录自身是否创建连接,只有自主创建的连接才关闭
		public $memcache_linked;
		public $database_linked;
		
		//初始化
		function __construct(){
			$this->memcache_linked = false;
			$this->database_linked = false;
		}
		
		//设置memcache连接
		public function set_MemCache_Connection($memcache){
			$this->close_MemCache_Connection();
			$this->connect_memcache = $memcache;
		}
		
		//设置database连接
		public function set_DataBase_Connection($database){
			$this->close_DataBase_Connection();
			$this->connect_database = $database;
		}
		
		//创建memcache连接
		public function creat_MemCache_Connection(){
			if(!$this->connect_memcache){
				$this->connect_memcache = new Memcache();  
				$this->connect_memcache->connect(MEM_HOST, MEM_POST); 
				$this->memcache_linked = true;
			}
			return true;
		}
		
		//创建database连接
		public function creat_DataBase_Connection(){
			if(!$this->connect_database){
				$this->connect_database = mysqli_connect(HOST,USER,PSD,DB);
				if(!$this->connect_database){ 
					return false;
				}
				mysqli_query($this->connect_database, "SET NAMES 'UTF8'");
				$this->database_linked = true;
			}
			return true;
		}
		
		//关闭连接
		public function close_MemCache_Connection(){
			if($this->connect_memcache && $this->memcache_linked){	
				$this->connect_memcache->close();
				$this->memcache_linked = false;
			}
		}
		
		//关闭连接
		public function close_DataBase_Connection(){
			if($this->connect_database && $this->database_linked){	
				mysqli_close($this->connect_database);
				$this->database_linked = false;
			}
		}
		
		//关闭全部连接
		public function close_Connection(){
			$this->close_MemCache_Connection();
			$this->close_DataBase_Connection();
		}
		
			
	}
?>