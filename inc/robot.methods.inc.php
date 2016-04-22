<?php
	/*
	机器人添加任务的方法定义
	*/
	
	//任务添加时间
	function ck_robot_task_add_times(){
		$now = time();
		$rand = rand(1,100);
		if($now>=strtotime('2015-04-19 00:00:00')){
			return 10;
		}elseif($now>=strtotime('2015-04-18 00:00:00')){
			return 9;
		}elseif($now>=strtotime('2015-04-17 00:00:00')){
			return 8;
		}elseif($now>=strtotime('2015-04-16 00:00:00')){
			return 7;
		}elseif($now>=strtotime('2015-04-15 00:00:00')){
			return 6;
		}elseif($now>=strtotime('2015-04-14 00:00:00')){
			return 5;
		}elseif($now>=strtotime('2015-04-13 00:00:00')){
			return 4;
		}elseif($now>=strtotime('2015-04-09 00:00:00')){
			if($rand>=25){
				return 3;
			}else{
				return 2;	
			}
		}elseif($now>=strtotime('2015-04-07 00:00:00')){
			if($rand>=50){
				return 3;
			}else{
				return 2;	
			}
		}elseif($now>=strtotime('2015-04-05 00:00:00')){
			if($rand>=75){
				return 3;
			}else{
				return 2;	
			}
		}elseif($now>=strtotime('2015-04-03 00:00:00')){
			return 2;
		}elseif($now>=strtotime('2015-04-01 00:00:00')){
			if($rand>=25){
				return 2;
			}else{
				return 1;	
			}
		}elseif($now>=strtotime('2015-03-30 00:00:00')){
			if($rand>=50){
				return 2;
			}else{
				return 1;	
			}
		}elseif($now>=strtotime('2015-03-28 00:00:00')){
			if($rand>=75){
				return 2;
			}else{
				return 1;	
			}
		}else{
			return 1;	
		}
	}
	
	//添加任务
	//不负责连接数据库和缓存 也不负责释放
	//当前缓存没用
	//数据库连接 缓存连接 任务类型 任务数据 添加次数
	function add_robot_task($mem,$connection,$task_type,$task_data,$runtimes){
		//20150310 关闭机器人
		//return true;
		if(!$connection || $task_type<=0){
			return false;
		}
		
		//3号任务单独处理
		if($task_type=='3' && $runtimes>3){
			$runtimes = ceil($runtimes/2.0);
		}
		
		while($runtimes>0){
			$runtimes--;
			$rand_second = strtotime(date('Y-m-d H:i:s'))+rand(5,500);
			$rand_exe_date = date('Y-m-d H:i:s',$rand_second);
			$query = 'INSERT INTO robot_task(task_type,task_data,status,add_time,take_time,exe_time) VALUES ('.
					$task_type.','.
					'\''.json_encode($task_data).'\','.
					'0,'.
					'now(),'.
					'\''.'0000-00-00 00:00:00'.'\','.
					'\''.$rand_exe_date.'\''.
			');';
			$result = mysqli_query($connection, $query);
		}
		
		return true;
	}
	
?>