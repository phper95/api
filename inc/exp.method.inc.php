<?php
	/*
	EXP经验结算方法,按照类型判断是否日常,更新经验和等级
	*/
	
	//添加经验
	//不负责链接数据库和释放链接
	//但是数据库链接是必须的
	function addExpToUser($mem,$connection,$userid,$exptype){
		if(!$connection){
			return false;	
		}
		
		//判断是否是匿名君
		$query = 'SELECT * FROM `client_user` WHERE `id`='.$userid.' AND `email`<>\'\' ;';
		$result = mysqli_query($connection, $query);
		if(!$result || mysqli_num_rows($result)==0){
			return false;
		}
		
		//首先验证是否有经验获取类别的全缓存
		$exp_map = checkLoadExpTypes($mem,$connection);
		
		//有记录才继续
		if(count($exp_map) && isset($exp_map[$exptype])){
			//如果是日常
			if($exp_map[$exptype]['is_daily']==1){
				//首先检查该用户该类型在当天是否已有记录
				$today_date = date('Y-m-d 00:00:00');
				$nextday_date = date('Y-m-d 00:00:00',time()+86400);
				$query = 'SELECT * FROM `exp_get_record` WHERE `user_id`='.$userid.' AND `type`='.$exptype.' AND `add_time`>=\''.$today_date.'\' AND `add_time`<\''.$nextday_date.'\' ;';
				$result = mysqli_query($connection, $query);
				if($result){
					if(mysqli_num_rows($result)>0){
						//找到了
						$record = mysqli_fetch_assoc($result);
						//判断是否满额
						if($record['exp']>=$exp_map[$exptype]['day_max_add']){
							//满额了,不处理
							return false;
								
						}else{
							//没满,增加经验
							$query = 'UPDATE `exp_get_record` SET `exp`=`exp`+'.$exp_map[$exptype]['time_add'].' WHERE `id`='.$record['id'].';';
							mysqli_query($connection, $query);	
							
							$query = 'UPDATE `exp_get_record_save` SET `exp`=`exp`+'.$exp_map[$exptype]['time_add'].' WHERE `id`='.$record['id'].';';
							mysqli_query($connection, $query);
						}
					}else{
						//没找到
						//INSERT
						$query = 'INSERT INTO exp_get_record(user_id,exp,add_time,type) VALUES ('.
											 $userid.','.
											 $exp_map[$exptype]['time_add'].','.
											 'now()'.','.
											 $exptype.
						');';
						mysqli_query($connection, $query);
						
						$query = 'INSERT INTO exp_get_record_save(user_id,exp,add_time,type) VALUES ('.
											 $userid.','.
											 $exp_map[$exptype]['time_add'].','.
											 'now()'.','.
											 $exptype.
						');';
						mysqli_query($connection, $query);
					}
				}
				
				
			}else{
				//如果是奖励
				//直接记录
				//INSERT
				$query = 'INSERT INTO exp_get_record(user_id,exp,add_time,type) VALUES ('.
									 $userid.','.
									 $exp_map[$exptype]['time_add'].','.
									 'now()'.','.
									 $exptype.
				');';
				mysqli_query($connection, $query);
				
				$query = 'INSERT INTO exp_get_record_save(user_id,exp,add_time,type) VALUES ('.
									 $userid.','.
									 $exp_map[$exptype]['time_add'].','.
									 'now()'.','.
									 $exptype.
				');';
				mysqli_query($connection, $query);
			}
			
			//记录用户经验
			//先查询是否存在
			$query = 'SELECT * FROM `user_v_exp` WHERE `user_id`='.$userid.' ;';
			$result = mysqli_query($connection, $query);
			if($result){
				if(mysqli_num_rows($result)>0){
					//有了UPDATE
					$query = 'UPDATE `user_v_exp` SET `exp`=`exp`+'.$exp_map[$exptype]['time_add'].',`update_time`=now() WHERE `user_id`='.$userid.';';
					mysqli_query($connection, $query);	
					
				}else{
					//没有
					//INSERT
					$query = 'INSERT INTO user_v_exp(user_id,exp,add_time,update_time) VALUES ('.
										 $userid.','.
										 $exp_map[$exptype]['time_add'].','.
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
	
	//加载经验获取类别的缓存
	//返回Dict
	function checkLoadExpTypes($mem,$connection){
		$cache_key = __FUNCTION__;
		if($mem){
			if(($value = $mem-> get($cache_key)) != FALSE) {
				//存在
				//直接返回
				return $value;
			}
		}
		
		$exp_map = array();
		
		//没有缓存链接或者不存在
		if($connection){
			$query = 'SELECT * FROM `exp_get_type` WHERE `open`=1;';
			$result = mysqli_query($connection, $query);
			if($result && mysqli_num_rows($result)>0){
				$i=0;
				while($i < mysqli_num_rows($result)){
					$exp = mysqli_fetch_assoc($result);	
					$exp_map[$exp['id']] = $exp;
					$i++;
				}
				//写入缓存
				if($mem){
					//默认缓存1天
					$mem->set($cache_key, $exp_map, 0, 86400);	
				}
				return $exp_map;
			}
		}
		
		//也没有数据库链接
		//ERROR
		return $exp_map;
	}
	
	//检查用户经验返回等级字符
	//不负责创建链接和释放链接
	//但是必须有
	function checkUserLevelString($mem,$connection,$userid){
		//首先加载等级列表
		$cache_key = __FUNCTION__.'-level-map';
		$level_map = false;
		if($mem){
			if(($value = $mem-> get($cache_key)) != FALSE) {
				//存在
				$level_map = $value;
			}
		}
		
		//如果缓存没加载成功就数据库加载
		if(!$level_map){
			if($connection){
				$query = 'SELECT * FROM `exp_v_level`;';
				$result = mysqli_query($connection, $query);
				if($result && mysqli_num_rows($result)>0){
					$i=0;
					while($i < mysqli_num_rows($result)){
						$exp = mysqli_fetch_assoc($result);	
						$level_map[$exp['level']] = $exp;
						$i++;
					}
					//写入缓存
					if($mem){
						//默认缓存1天
						$mem->set($cache_key, $level_map, 0, 86400);	
					}
				}
			}else{
				//没有数据库链接
				return false;	
			}	
		}
		
		//加载成功后再检查该用户的经验
		$user_exp = 0;
		if($connection){
			$query = 'SELECT * FROM `user_v_exp` WHERE `user_id`='.$userid.';';
			$result = mysqli_query($connection, $query);
			if($result && mysqli_num_rows($result)>0){
				$record = mysqli_fetch_assoc($result);	
				$user_exp = $record['exp'];
			}
		}
		
		//加载成功后判断用户的等级
		for($i=1;$i<=count($level_map);$i++){
			if(isset($level_map[$i]) && isset($level_map[$i]['exp'])){
				if($user_exp>$level_map[$i]['exp']){
					continue;	
				}else{
					break;	
				}
			}else{
				continue;	
			}
		}
		
		$user_level = $i-1;
		if($user_level<1)$user_level=1;
		
		if(isset($level_map[$user_level]) && isset($level_map[$user_level]['name']) && strlen($level_map[$user_level]['name'])>0){
			//有定义名字就返回名字	
			return $level_map[$user_level]['name'];
		}else{
			//否则就按照默认返回
			return 'Lv'.$user_level;	
		}
	}
	
	//获取用户当日经验任务的完成状况
	//不负责创建链接和释放链接
	//但链接是必须的
	function getUserExpTodayStatus($mem,$connection,$userid){
		if(!$connection){
			return false;
		}
		//首先验证是否有经验获取类别的全缓存
		$exp_map = checkLoadExpTypes($mem,$connection);
		$exp_record = array();
		
		foreach($exp_map as $exp){
			if($exp['is_daily']==1){
				$exp_record[$exp['id']] = array(
					'type' => (string)$exp['id'],
					'name' => $exp['name'],
					'intro' => $exp['intro'],
					'daily' => (string)$exp['is_daily'],
					'exp' => '0',
					'max' => (string)$exp['day_max_add']
				);	
			}
		}
		
		/*
			type
			name
			intro
			daily
			exp
			max
		*/
		
		if($exp_map && count($exp_map)){
			$today_date = date('Y-m-d 00:00:00');
			$nextday_date = date('Y-m-d 00:00:00',time()+86400);
			$query = 'SELECT * FROM `exp_get_record` WHERE `user_id`='.$userid.' AND `add_time`>=\''.$today_date.'\' AND `add_time`<\''.$nextday_date.'\' ORDER BY `type` ASC ;';
			$result = mysqli_query($connection, $query);
			if($result){
				if(mysqli_num_rows($result)>0){
					//找到了
					$i=0;
					while($i<mysqli_num_rows($result)){
						$record = mysqli_fetch_assoc($result);
						if(isset($exp_record[$record['type']])){
							$exp_record[$record['type']]['exp'] = (string)$record['exp'];
						}elseif(isset($exp_map[$record['type']]) && isset($exp_map[$record['type']]['is_daily']) && $exp_map[$record['type']]['is_daily']==0){
							$exp_record[$record['type']] = array(
								'type' => (string)$record['type'],
								'name' => (string)$exp_map[$record['type']]['name'],
								'intro' => (string)$exp_map[$record['type']]['intro'],
								'daily' => (string)$exp_map[$record['type']]['is_daily'],
								'exp' => (string)$record['exp'],
								'max' => (string)$exp_map[$record['type']]['day_max_add']
							);	
						}
						$i++;
					}
					
					//再遍历重置为数组
					$return_record = array();
					foreach($exp_record as $record){
						$return_record[] = $record;	
					}
					$exp_record = $return_record;
					
				}
			}
		}
		return $exp_record;
	}

?>