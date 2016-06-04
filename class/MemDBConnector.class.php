<?php
	//Inc Config
	//require_once(dirname(__FILE__).'/'.'../../../../inc/config.inc.php');
	//Dao类可以自主创建连接了,该类暂时无用
	require_once(dirname(__FILE__).'/'.'dao/DaoBase.dao.php');
	class MemDBConnector extends DaoBase{
		//初始化
		function __construct(){
			parent::__construct(); 
		}
	}
?>