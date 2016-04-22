<?php
	/*
	Gold金币结算方法,按照类型判断是否日常,更新金币数目
	*/
	
	//添加金币
	//不负责链接数据库和释放链接
	//但是数据库链接是必须的
	function addGoldToUser($mem,$connection,$userid,$goldtype){
		if(!$connection){
			return false;	
		}
		
		//判断是否是匿名君
		$query = 'SELECT * FROM `client_user` WHERE `id`='.$userid.' AND `email`<>\'\' ;';
		$result = mysqli_query($connection, $query);
		if(!$result || mysqli_num_rows($result)==0){
			return false;
		}
		
		//首先验证是否有金币获取类别的全缓存
		$gold_map = checkLoadGoldTypes($mem,$connection);
		//有记录才继续
		if(count($gold_map) && isset($gold_map[$goldtype])){
			//如果是日常
			if($gold_map[$goldtype]['is_daily']==1){
				//首先检查该用户该类型在当天是否已有记录
				$today_date = date('Y-m-d 00:00:00');
				$nextday_date = date('Y-m-d 00:00:00',time()+86400);
				$query = 'SELECT * FROM `gold_get_record` WHERE `user_id`='.$userid.' AND `type`='.$goldtype.' AND `add_time`>=\''.$today_date.'\' AND `add_time`<\''.$nextday_date.'\' ;';
				$result = mysqli_query($connection, $query);
				if($result){
					if(mysqli_num_rows($result)>0){
						//找到了
						$record = mysqli_fetch_assoc($result);
						//判断是否满额
						if($record['gold']>=$gold_map[$goldtype]['day_max_add']){
							//满额了,不处理
							return false;
								
						}else{
							//没满,增加经验
							$query = 'UPDATE `gold_get_record` SET `gold`=`gold`+'.$gold_map[$goldtype]['time_add'].' WHERE `id`='.$record['id'].';';
							mysqli_query($connection, $query);	
							
							$query = 'UPDATE `gold_get_record_save` SET `gold`=`gold`+'.$gold_map[$goldtype]['time_add'].' WHERE `id`='.$record['id'].';';
							mysqli_query($connection, $query);
						}
					}else{
						//没找到
						//INSERT
						$query = 'INSERT INTO gold_get_record(user_id,gold,add_time,type) VALUES ('.
											 $userid.','.
											 $gold_map[$goldtype]['time_add'].','.
											 'now()'.','.
											 $goldtype.
						');';
						mysqli_query($connection, $query);
						
						$query = 'INSERT INTO gold_get_record_save(user_id,gold,add_time,type) VALUES ('.
											 $userid.','.
											 $gold_map[$goldtype]['time_add'].','.
											 'now()'.','.
											 $goldtype.
						');';
						mysqli_query($connection, $query);
					}
				}
				
				
			}else{
				//如果是奖励
				//直接记录
				//INSERT
				$query = 'INSERT INTO gold_get_record(user_id,gold,add_time,type) VALUES ('.
									 $userid.','.
									 $gold_map[$goldtype]['time_add'].','.
									 'now()'.','.
									 $goldtype.
				');';
				mysqli_query($connection, $query);
				
				$query = 'INSERT INTO gold_get_record_save(user_id,gold,add_time,type) VALUES ('.
									 $userid.','.
									 $gold_map[$goldtype]['time_add'].','.
									 'now()'.','.
									 $goldtype.
				');';
				mysqli_query($connection, $query);
			}
			
			//记录用户经验
			//先查询是否存在
			$query = 'SELECT * FROM `user_v_gold` WHERE `user_id`='.$userid.' ;';
			$result = mysqli_query($connection, $query);
			if($result){
				if(mysqli_num_rows($result)>0){
					//有了UPDATE
					$query = 'UPDATE `user_v_gold` SET `gold`=`gold`+'.$gold_map[$goldtype]['time_add'].',`update_time`=now() WHERE `user_id`='.$userid.';';
					mysqli_query($connection, $query);	
					
				}else{
					//没有
					//INSERT
					$query = 'INSERT INTO user_v_gold(user_id,gold,add_time,update_time) VALUES ('.
										 $userid.','.
										 $gold_map[$goldtype]['time_add'].','.
										 'now()'.','.
										 'now()'.
					');';
					mysqli_query($connection, $query);
					
				}
			}
			
			//检查用户是否升级
			//暂无
			
			return true;
		}
		
		return false;
	}
	
	//加载Gold获取类别的缓存
	//返回Dict
	function checkLoadGoldTypes($mem,$connection){
		$cache_key = __FUNCTION__;
		if($mem){
			if(($value = $mem-> get($cache_key)) != FALSE) {
				//存在
				//直接返回
				return $value;
			}
		}
		
		$gold_map = array();
		
		//没有缓存链接或者不存在
		if($connection){
			$query = 'SELECT * FROM `gold_get_type` WHERE `open`=1;';
			$result = mysqli_query($connection, $query);
			if($result && mysqli_num_rows($result)>0){
				$i=0;
				while($i < mysqli_num_rows($result)){
					$gold = mysqli_fetch_assoc($result);	
					$gold_map[$gold['id']] = $gold;
					$i++;
				}
				//写入缓存
				if($mem){
					//默认缓存1天
					$mem->set($cache_key, $gold_map, 0, 86400);	
				}
				return $gold_map;
			}
		}
		
		//也没有数据库链接
		//ERROR
		return $gold_map;
	}
	
	//检查用户Gold返回等级字符
	//不负责创建链接和释放链接
	//但是必须有
	function checkUserGoldString($mem,$connection,$userid){
		
		//检查该用户的金币
		$user_gold = 0;
		if($connection){
			$query = 'SELECT * FROM `user_v_gold` WHERE `user_id`='.$userid.';';
			$result = mysqli_query($connection, $query);
			if($result && mysqli_num_rows($result)>0){
				$record = mysqli_fetch_assoc($result);	
				$user_gold = $record['gold'];
			}
		}
		return $user_gold;
	}
	
	//获取用户当日金币任务的完成状况
	//不负责创建链接和释放链接
	//但链接是必须的
	function getUserGoldTodayStatus($mem,$connection,$userid){
		if(!$connection){
			return false;
		}
		//首先验证是否有Gold获取类别的全缓存
		$gold_map = checkLoadGoldTypes($mem,$connection);
		$gold_record = array();
		
		foreach($gold_map as $gold){
			if($gold['is_daily']==1){
				$gold_record[$gold['id']] = array(
					'type' => (string)$gold['id'],
					'name' => $gold['name'],
					'intro' => $gold['intro'],
					'daily' => (string)$gold['is_daily'],
					'gold' => '0',
					'max' => (string)$gold['day_max_add']
				);	
			}
		}
		
		/*
			type
			name
			intro
			daily
			gold
			max
		*/
		
		if($gold_map && count($gold_map)){
			$today_date = date('Y-m-d 00:00:00');
			$nextday_date = date('Y-m-d 00:00:00',time()+86400);
			$query = 'SELECT * FROM `gold_get_record` WHERE `user_id`='.$userid.' AND `add_time`>=\''.$today_date.'\' AND `add_time`<\''.$nextday_date.'\' ORDER BY `type` ASC ;';
			$result = mysqli_query($connection, $query);
			if($result){
				if(mysqli_num_rows($result)>0){
					//找到了
					$i=0;
					while($i<mysqli_num_rows($result)){
						$record = mysqli_fetch_assoc($result);
						if(isset($gold_record[$record['type']])){
							$gold_record[$record['type']]['gold'] = (string)$record['gold'];
							
						}elseif(isset($gold_map[$record['type']]) && isset($gold_map[$record['type']]['is_daily']) && $gold_map[$record['type']]['is_daily']==0){
							$gold_record[$record['type']] = array(
								'type' => (string)$record['type'],
								'name' => (string)$gold_map[$record['type']]['name'],
								'intro' => (string)$gold_map[$record['type']]['intro'],
								'daily' => (string)$gold_map[$record['type']]['is_daily'],
								'gold' => (string)$record['gold'],
								'max' => (string)$gold_map[$record['type']]['day_max_add']
							);	
						}
						$i++;
					}
					
					//再遍历重置为数组
					$return_record = array();
					foreach($gold_record as $record){
						$return_record[] = $record;	
					}
					$gold_record = $return_record;
					
				}
			}
		}
		return $gold_record;
	}
?>