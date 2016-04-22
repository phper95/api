<?php
	//定时调用的脚本
	/*
	1. 自动结算排行榜ExpGold奖励
	2. 定期清空ExpGold的获取记录表
	*/
	header('Content-Type:text/html;charset= UTF-8');
	require_once(dirname(__FILE__).'/'.'methods.inc.php');
	require_once(dirname(__FILE__).'/'.'time.methods.inc.php');
	require_once(dirname(__FILE__).'/'.'config.inc.php');
	
	//二级缓存
	require_once(dirname(__FILE__).'/'.'secondcache.methods.php');
	
	//新增经验和金币的配置方法
	require_once(dirname(__FILE__).'/'.'exp.method.inc.php');
	require_once(dirname(__FILE__).'/'.'gold.method.inc.php');
	
	//logger
	require_once(dirname(__FILE__).'/'.'Logger_Writter.php');
	
	if($_GET['key']!='ccc5e4178771754ea4b93836e4df34a2'){
		echo('ERROR');
		die();	
	}
	
	//记录日志
	$logger = new Logger_Writer(dirname(__FILE__).'/'.'../../../../../logger/expgold/','');
	$logger_content = '';
	
	//时间记录
	$start_time = starttime();
	
	//当前时间
	$today_y = date('Y');
	$today_m = date('m');
	$today_d = date('d');
	$today_start = date('Y-m-d 00:00:00');
	
	//两天前
	$twoday_before = date('Y-m-d 00:00:00',time()-86400*2);
	
	//链接数据库
	$connection = mysqli_connect(HOST,USER,PSD,DB);
			if(!$connection){ 
				//链接失败就中止
				die();
			}
	mysqli_query($connection, "SET NAMES 'UTF8'");
	
	//连接缓存
	$mem = new Memcache();  
	$mem -> connect(MEM_HOST, MEM_POST);
	
	//1.定期清空三天前的ExpGold记录
	//***********************************************************************************************
	$query = 'delete from `gold_get_record` where `add_time`<\''.$twoday_before.'\';';
	mysqli_query($connection,$query);
	$query = 'delete from `exp_get_record` where `add_time`<\''.$twoday_before.'\';';
	mysqli_query($connection,$query);
	//***********************************************************************************************
	
	//2.检查是否是一个月的第一天,结算月榜
	//***********************************************************************************************
	/*
	sort 0-最赞 1-最多播放 2-最近上线 默认0
	ttype 时间 0-总榜 1-周 2-月 3-季 4-年
	*/
	if($today_d==1){
		//月榜
		echo('检查月榜:<br/><hr/>');
		$logger_content .= '检查月榜:'.PHP_EOL;
		
		//本月开始日期
		$thism_start_date = $today_y.'-'.$today_m.'-01 00:00:00';
		//上个月开始日期
		$lastm_start_date = date('Y-m-d 00:00:00', strtotime($today_y.'-'.($today_m-1).'-01 00:00:00'));
		
		echo('本月开始日期:'.$thism_start_date.'<br/>');
		echo('上个月开始日期:'.$lastm_start_date.'<br/>');
		$logger_content .= '本月开始日期:'.$thism_start_date.PHP_EOL;
		$logger_content .= '上个月开始日期:'.$lastm_start_date.PHP_EOL;
		
		//首先要检查是否已经结算过上月的了
		//日期大于本月1号的,sort为0或1 ttyoe为2的记录
		//最赞
		$query = 'SELECT * FROM `user_win_champion` WHERE `add_time`>\''.$thism_start_date.'\' AND `rank_ttype`=2 AND `rank_sort`=0;';
		$result = mysqli_query($connection,$query);
		if($result && mysqli_num_rows($result)==0){
			//没有记录才结算
			
			echo('上月红人榜单结算开始:'.'<br/>');
			$logger_content .= '上月红人榜单结算开始:'.PHP_EOL;
			//本月红人榜单
			//查询user_v_movie_ding 红人榜
			$query = 'SELECT `user_id`,SUM(`ding`) AS \'sum_ding\' FROM `user_v_ding_day` '.
						' WHERE '.
							'`day_time`>=\''.$lastm_start_date.'\' AND `day_time`<\''.$thism_start_date.'\' AND  `user_id`<>2 '.
						' GROUP BY `user_id` '.
						' ORDER BY SUM(`ding`) DESC LIMIT 10'.
						';';
			$result = mysqli_query($connection,$query);
			echo('SQL:'.$query);
			$logger_content .= 'SQL:'.$query.PHP_EOL;
			$i = 0;
			while($i<mysqli_num_rows($result) && $i<10){
				$grapher = mysqli_fetch_assoc($result);
				
				echo('用户['.$grapher['user_id'].']:'.$i.'<br/>');
				$logger_content .= '用户['.$grapher['user_id'].']:'.$i.PHP_EOL;
				
				switch($i){
					case 0:
						//记录登录金币和经验
						addGoldToUser($mem,$connection,$grapher['user_id'],'12');
						addExpToUser($mem,$connection,$grapher['user_id'],'24');
						break;
					case 1:
						//记录登录金币和经验
						addGoldToUser($mem,$connection,$grapher['user_id'],'25');
						addExpToUser($mem,$connection,$grapher['user_id'],'36');
						break;
					case 2:
						//记录登录金币和经验
						addGoldToUser($mem,$connection,$grapher['user_id'],'26');
						addExpToUser($mem,$connection,$grapher['user_id'],'37');
						break;
					case 3:
						//记录登录金币和经验
						addGoldToUser($mem,$connection,$grapher['user_id'],'27');
						addExpToUser($mem,$connection,$grapher['user_id'],'38');
						break;
					case 4:
						//记录登录金币和经验
						addGoldToUser($mem,$connection,$grapher['user_id'],'28');
						addExpToUser($mem,$connection,$grapher['user_id'],'39');
						break;
					case 5:
						//记录登录金币和经验
						addGoldToUser($mem,$connection,$grapher['user_id'],'29');
						addExpToUser($mem,$connection,$grapher['user_id'],'40');
						break;
					case 6:
						//记录登录金币和经验
						addGoldToUser($mem,$connection,$grapher['user_id'],'30');
						addExpToUser($mem,$connection,$grapher['user_id'],'41');
						break;
					case 7:
						//记录登录金币和经验
						addGoldToUser($mem,$connection,$grapher['user_id'],'31');
						addExpToUser($mem,$connection,$grapher['user_id'],'42');
						break;
					case 8:
						//记录登录金币和经验
						addGoldToUser($mem,$connection,$grapher['user_id'],'32');
						addExpToUser($mem,$connection,$grapher['user_id'],'43');
						break;
					case 9:
						//记录登录金币和经验
						addGoldToUser($mem,$connection,$grapher['user_id'],'33');
						addExpToUser($mem,$connection,$grapher['user_id'],'44');
						break;
					default:
						break;
				}
				
				//再INSERT记录
				//记录该用户获取过
				$query = 'INSERT INTO user_win_champion(user_id,rank_sort,rank_ttype,rank_num,rank_data,add_time) VALUES ('.
									 $grapher['user_id'].','.
									 '0,'.
									 '2,'.
									 $i.','.
									 $grapher['sum_ding'].','.
									 'now()'.
				');';
				
				echo('SQL:'.$query.'<br/><br/>');
				$logger_content .= 'SQL:'.$query.PHP_EOL;
				mysqli_query($connection, $query);
				
				$i++;
			}	
		}
		
		//最热
		$query = 'SELECT * FROM `user_win_champion` WHERE `add_time`>\''.$thism_start_date.'\' AND `rank_ttype`=2 AND `rank_sort`=1;';
		$result = mysqli_query($connection,$query);
		if($result && mysqli_num_rows($result)==0){
			//没有记录才结算
			
			echo('上月达人榜单结算开始:'.'<br/>');
			$logger_content .= '上月达人榜单结算开始:'.PHP_EOL;
			//本月达人榜单
			//查询user_v_movie_ding 达人榜
			$query = 'SELECT `user_id`,SUM(`played`) AS \'sum_played\' FROM `user_v_played_day` '.
						' WHERE '.
							'`day_time`>=\''.$lastm_start_date.'\' AND `day_time`<\''.$thism_start_date.'\' AND  `user_id`<>2 '.
						' GROUP BY `user_id` '.
						' ORDER BY SUM(`played`) DESC LIMIT 10'.
						';';
			$result = mysqli_query($connection,$query);
			echo('SQL:'.$query);
			$logger_content .= 'SQL:'.$query.PHP_EOL;
			
			$i = 0;
			while($i<mysqli_num_rows($result) && $i<10){
				$grapher = mysqli_fetch_assoc($result);
				
				echo('用户['.$grapher['user_id'].']:'.$i.'<br/>');
				$logger_content .= '用户['.$grapher['user_id'].']:'.$i.PHP_EOL;
				
				switch($i){
					case 0:
						//记录登录金币和经验
						addGoldToUser($mem,$connection,$grapher['user_id'],'12');
						addExpToUser($mem,$connection,$grapher['user_id'],'24');
						break;
					case 1:
						//记录登录金币和经验
						addGoldToUser($mem,$connection,$grapher['user_id'],'25');
						addExpToUser($mem,$connection,$grapher['user_id'],'36');
						break;
					case 2:
						//记录登录金币和经验
						addGoldToUser($mem,$connection,$grapher['user_id'],'26');
						addExpToUser($mem,$connection,$grapher['user_id'],'37');
						break;
					case 3:
						//记录登录金币和经验
						addGoldToUser($mem,$connection,$grapher['user_id'],'27');
						addExpToUser($mem,$connection,$grapher['user_id'],'38');
						break;
					case 4:
						//记录登录金币和经验
						addGoldToUser($mem,$connection,$grapher['user_id'],'28');
						addExpToUser($mem,$connection,$grapher['user_id'],'39');
						break;
					case 5:
						//记录登录金币和经验
						addGoldToUser($mem,$connection,$grapher['user_id'],'29');
						addExpToUser($mem,$connection,$grapher['user_id'],'40');
						break;
					case 6:
						//记录登录金币和经验
						addGoldToUser($mem,$connection,$grapher['user_id'],'30');
						addExpToUser($mem,$connection,$grapher['user_id'],'41');
						break;
					case 7:
						//记录登录金币和经验
						addGoldToUser($mem,$connection,$grapher['user_id'],'31');
						addExpToUser($mem,$connection,$grapher['user_id'],'42');
						break;
					case 8:
						//记录登录金币和经验
						addGoldToUser($mem,$connection,$grapher['user_id'],'32');
						addExpToUser($mem,$connection,$grapher['user_id'],'43');
						break;
					case 9:
						//记录登录金币和经验
						addGoldToUser($mem,$connection,$grapher['user_id'],'33');
						addExpToUser($mem,$connection,$grapher['user_id'],'44');
						break;
					default:
						break;
				}
				
				//再INSERT记录
				//记录该用户获取过
				$query = 'INSERT INTO user_win_champion(user_id,rank_sort,rank_ttype,rank_num,rank_data,add_time) VALUES ('.
									 $grapher['user_id'].','.
									 '1,'.
									 '2,'.
									 $i.','.
									 $grapher['sum_played'].','.
									 'now()'.
				');';
				mysqli_query($connection, $query);
				
				echo('SQL:'.$query.'<br/><br/>');
				$logger_content .= 'SQL:'.$query.PHP_EOL;
				$i++;
			}	
		}
	}
	//***********************************************************************************************
	
	
	//3.总榜Top10
	//总榜Top10的经验是累加经验
	//***********************************************************************************************
	/*
	sort 0-最赞 1-最多播放 2-最近上线 默认0
	ttype 时间 0-总榜 1-周 2-月 3-季 4-年
	*/
	echo('<br/><br/>总榜检查开始:<hr/>');
	$logger_content .= '总榜检查开始:'.PHP_EOL;
	echo('红人总榜:<br/>');
	$logger_content .= '红人总榜:'.PHP_EOL;
	//最赞
	$query = 'SELECT * FROM `client_user` '.
				' WHERE '. 
					'`open`=1 AND '.
					'`limit_grapherlist`=0 AND '.
					'`stat_work`>0 AND '.
					'`stat_belike`>0 '.
				' ORDER BY `stat_belike` DESC,`stat_beplayed` DESC,`stat_work` DESC '.
				' LIMIT 10'.
				';';
	$result = mysqli_query($connection,$query);
	$i=0;
	while($i<mysqli_num_rows($result)){
		$grapher = mysqli_fetch_assoc($result);
		
		echo('用户['.$grapher['id'].':'.$grapher['name'].']:'.$i.'<br/>');
		$logger_content .= '用户['.$grapher['id'].':'.$grapher['name'].']:'.$i.PHP_EOL;
		
		//检查该人是否获取过更高的名次
		$query = 'SELECT `rank_num` FROM `user_win_champion` WHERE `user_id`='.$grapher['id'].' AND `rank_ttype`=0 AND `rank_sort`=0 ORDER BY `rank_num` ASC LIMIT 1;';
		$result_ckwin = mysqli_query($connection,$query);
		if($result_ckwin && mysqli_num_rows($result_ckwin)>0){
			//有过名次
			//查询最高名次是多少
			$winrecord = mysqli_fetch_assoc($result_ckwin);
			
			echo('用户获取的更高名次!['.$winrecord['rank_num'].']:'.$i.'<br/>');
			$logger_content .= '用户获取的更高名次!['.$winrecord['rank_num'].']:'.$i.PHP_EOL;
			
			if($winrecord['rank_num']>$i){
				//获取了更高的名次
				
				//经验
				//查询该次结算应当补足多少经验
				//已发的经验
				$added_exp = 0;
				$ck_id = 25;
				switch($winrecord['rank_num']){
					case 0:$ck_id = 25;break;
					case 1:$ck_id = 27;break;
					case 2:$ck_id = 28;break;
					case 3:$ck_id = 29;break;
					case 4:$ck_id = 30;break;
					case 5:$ck_id = 31;break;
					case 6:$ck_id = 32;break;
					case 7:$ck_id = 33;break;
					case 8:$ck_id = 34;break;
					case 9:$ck_id = 35;break;
					default:break;
				}
				$query = 'SELECT `time_add` FROM `exp_get_type` WHERE `id`='.$ck_id.';';
				$result_type = mysqli_query($connection,$query);
				if($result_type && mysqli_num_rows($result_type)>0){
					$exptype = mysqli_fetch_assoc($result_type);
					$added_exp = $exptype['time_add'];
				}
				
				echo('已结算经验:['.$added_exp.']<br/>');
				$logger_content .= '已结算经验:['.$added_exp.']'.PHP_EOL;
				
				//本次应该增加的经验
				$add_exp = 0;
				$ck_id = 35;
				switch($i){
					case 0:$ck_id = 25;break;
					case 1:$ck_id = 27;break;
					case 2:$ck_id = 28;break;
					case 3:$ck_id = 29;break;
					case 4:$ck_id = 30;break;
					case 5:$ck_id = 31;break;
					case 6:$ck_id = 32;break;
					case 7:$ck_id = 33;break;
					case 8:$ck_id = 34;break;
					case 9:$ck_id = 35;break;
					default:break;
				}
				$query = 'SELECT `time_add` FROM `exp_get_type` WHERE `id`='.$ck_id.';';
				$result_type = mysqli_query($connection,$query);
				if($result_type && mysqli_num_rows($result_type)>0){
					$exptype = mysqli_fetch_assoc($result_type);
					$add_exp = $exptype['time_add'];
				}
				
				echo('共需结算经验:['.$add_exp.']<br/>');
				$logger_content .= '共需结算经验:['.$add_exp.']'.PHP_EOL;
				
				//经验差值
				$exp_give = $add_exp - $added_exp;
				
				echo('需要补足经验:['.$exp_give.']<br/>');
				$logger_content .= '需要补足经验:['.$exp_give.']'.PHP_EOL;
				
				if($exp_give>0){
					//补足经验
					$query = 'INSERT INTO exp_get_record(user_id,exp,add_time,type) VALUES ('.
										 $grapher['id'].','.
										 $exp_give.','.
										 'now()'.','.
										 $ck_id.
					');';
					mysqli_query($connection, $query);
					
					$query = 'INSERT INTO exp_get_record_save(user_id,exp,add_time,type) VALUES ('.
										 $grapher['id'].','.
										 $exp_give.','.
										 'now()'.','.
										 $ck_id.
					');';
					mysqli_query($connection, $query);
					
					$query = 'SELECT * FROM `user_v_exp` WHERE `user_id`='.$grapher['id'].' ;';
					$result_expadd = mysqli_query($connection, $query);
					if($result_expadd){
						if(mysqli_num_rows($result_expadd)>0){
							//有了UPDATE
							$query = 'UPDATE `user_v_exp` SET `exp`=`exp`+'.$exp_give.',`update_time`=now() WHERE `user_id`='.$grapher['id'].';';
							mysqli_query($connection, $query);	
							
						}else{
							//没有
							//INSERT
							$query = 'INSERT INTO user_v_exp(user_id,exp,add_time,update_time) VALUES ('.
												 $grapher['id'].','.
												 $exp_give.','.
												 'now()'.','.
												 'now()'.
							');';
							mysqli_query($connection, $query);
							
						}
					}
				}
				
				//金币
				//查询该次结算应当补足多少金币
				//已发的金币
				$added_gold = 0;
				$ck_id = 13;
				switch($winrecord['rank_num']){
					case 0:$ck_id = 13;break;
					case 1:$ck_id = 16;break;
					case 2:$ck_id = 17;break;
					case 3:$ck_id = 18;break;
					case 4:$ck_id = 19;break;
					case 5:$ck_id = 20;break;
					case 6:$ck_id = 21;break;
					case 7:$ck_id = 22;break;
					case 8:$ck_id = 23;break;
					case 9:$ck_id = 24;break;
					default:break;
				}
				$query = 'SELECT `time_add` FROM `gold_get_type` WHERE `id`='.$ck_id.';';
				$result_type = mysqli_query($connection,$query);
				if($result_type && mysqli_num_rows($result_type)>0){
					$goldtype = mysqli_fetch_assoc($result_type);
					$added_gold = $goldtype['time_add'];
				}
				
				echo('已结算金币:['.$added_gold.']<br/>');
				$logger_content .= '已结算金币:['.$added_gold.']'.PHP_EOL;
				
				//本次应该增加的金币
				$add_gold = 0;
				$ck_id = 24;
				switch($i){
					case 0:$ck_id = 13;break;
					case 1:$ck_id = 16;break;
					case 2:$ck_id = 17;break;
					case 3:$ck_id = 18;break;
					case 4:$ck_id = 19;break;
					case 5:$ck_id = 20;break;
					case 6:$ck_id = 21;break;
					case 7:$ck_id = 22;break;
					case 8:$ck_id = 23;break;
					case 9:$ck_id = 24;break;
					default:break;
				}
				$query = 'SELECT `time_add` FROM `gold_get_type` WHERE `id`='.$ck_id.';';
				$result_type = mysqli_query($connection,$query);
				if($result_type && mysqli_num_rows($result_type)>0){
					$goldtype = mysqli_fetch_assoc($result_type);
					$add_gold = $goldtype['time_add'];
				}
				
				echo('共需结算金币:['.$add_gold.']<br/>');
				$logger_content .= '共需结算金币:['.$add_gold.']'.PHP_EOL;
				
				//金币差值
				$gold_give = $add_gold - $added_gold;
				
				echo('需要补足金币:['.$gold_give.']<br/>');
				$logger_content .= '需要补足金币:['.$gold_give.']'.PHP_EOL;
				
				if($gold_give>0){
					//补足金币
					$query = 'INSERT INTO gold_get_record(user_id,gold,add_time,type) VALUES ('.
										 $grapher['id'].','.
										 $gold_give.','.
										 'now()'.','.
										 $ck_id.
					');';
					mysqli_query($connection, $query);
					
					$query = 'INSERT INTO gold_get_record_save(user_id,gold,add_time,type) VALUES ('.
										 $grapher['id'].','.
										 $gold_give.','.
										 'now()'.','.
										 $ck_id.
					');';
					mysqli_query($connection, $query);
					
					$query = 'SELECT * FROM `user_v_gold` WHERE `user_id`='.$grapher['id'].' ;';
					$result_goldadd = mysqli_query($connection, $query);
					if($result_goldadd){
						if(mysqli_num_rows($result_goldadd)>0){
							//有了UPDATE
							$query = 'UPDATE `user_v_gold` SET `gold`=`gold`+'.$gold_give.',`update_time`=now() WHERE `user_id`='.$grapher['id'].';';
							mysqli_query($connection, $query);	
							
						}else{
							//没有
							//INSERT
							$query = 'INSERT INTO user_v_gold(user_id,gold,add_time,update_time) VALUES ('.
												 $grapher['id'].','.
												 $gold_give.','.
												 'now()'.','.
												 'now()'.
							');';
							mysqli_query($connection, $query);
							
						}
					}
				}
				
				//再INSERT记录
				//记录该用户获取过
				if($exp_give>0 && $gold_give>0){
					$query = 'INSERT INTO user_win_champion(user_id,rank_sort,rank_ttype,rank_num,rank_data,add_time) VALUES ('.
										 $grapher['id'].','.
										 '0,'.
										 '0,'.
										 $i.','.
										 $grapher['stat_belike'].','.
										 'now()'.
					');';
					mysqli_query($connection, $query);
					
					echo('SQL:'.$query.'<br/><br/>');
					$logger_content .= 'SQL:'.$query.PHP_EOL;
				}
			}
		}else{
			//没有记录直接结算经验
			switch($i){
				case 0:
					//记录登录金币和经验
					addGoldToUser($mem,$connection,$grapher['id'],'13');
					addExpToUser($mem,$connection,$grapher['id'],'25');
					break;
				case 1:
					//记录登录金币和经验
					addGoldToUser($mem,$connection,$grapher['id'],'16');
					addExpToUser($mem,$connection,$grapher['id'],'27');
					break;
				case 2:
					//记录登录金币和经验
					addGoldToUser($mem,$connection,$grapher['id'],'17');
					addExpToUser($mem,$connection,$grapher['id'],'28');
					break;
				case 3:
					//记录登录金币和经验
					addGoldToUser($mem,$connection,$grapher['id'],'18');
					addExpToUser($mem,$connection,$grapher['id'],'29');
					break;
				case 4:
					//记录登录金币和经验
					addGoldToUser($mem,$connection,$grapher['id'],'19');
					addExpToUser($mem,$connection,$grapher['id'],'30');
					break;
				case 5:
					//记录登录金币和经验
					addGoldToUser($mem,$connection,$grapher['id'],'20');
					addExpToUser($mem,$connection,$grapher['id'],'31');
					break;
				case 6:
					//记录登录金币和经验
					addGoldToUser($mem,$connection,$grapher['id'],'21');
					addExpToUser($mem,$connection,$grapher['id'],'32');
					break;
				case 7:
					//记录登录金币和经验
					addGoldToUser($mem,$connection,$grapher['id'],'22');
					addExpToUser($mem,$connection,$grapher['id'],'33');
					break;
				case 8:
					//记录登录金币和经验
					addGoldToUser($mem,$connection,$grapher['id'],'23');
					addExpToUser($mem,$connection,$grapher['id'],'34');
					break;
				case 9:
					//记录登录金币和经验
					addGoldToUser($mem,$connection,$grapher['id'],'24');
					addExpToUser($mem,$connection,$grapher['id'],'35');
					break;
				default:break;
			}
			
			$query = 'INSERT INTO user_win_champion(user_id,rank_sort,rank_ttype,rank_num,rank_data,add_time) VALUES ('.
								 $grapher['id'].','.
								 '0,'.
								 '0,'.
								 $i.','.
								 $grapher['stat_belike'].','.
								 'now()'.
			');';
			mysqli_query($connection, $query);
			
			echo('SQL:'.$query.'<br/><br/>');
			$logger_content .= 'SQL:'.$query.PHP_EOL;
		}
		$i++;
		
	}
						
	//最热	
	echo('达人总榜:<br/>');	
	$logger_content .= '达人总榜:'.PHP_EOL;	
			
	$query = 'SELECT * FROM `client_user` '.
				  ' WHERE '. 
					  '`open`=1 AND '.
					  '`limit_grapherlist`=0 AND '.
					  '`stat_work`>0 AND '.
					  '`stat_beplayed`>0 '.
				  ' ORDER BY `stat_beplayed` DESC,`stat_belike` DESC,`stat_work` DESC '.
				  ' LIMIT 10'.
				  ';';
	$result = mysqli_query($connection,$query);
	$i=0;
	while($i<mysqli_num_rows($result)){
		$grapher = mysqli_fetch_assoc($result);
		
		echo('用户['.$grapher['id'].':'.$grapher['name'].']:'.$i.'<br/>');
		$logger_content .= '用户['.$grapher['id'].':'.$grapher['name'].']:'.$i.PHP_EOL;
		
		//检查该人是否获取过更高的名次
		$query = 'SELECT `rank_num` FROM `user_win_champion` WHERE `user_id`='.$grapher['id'].' AND `rank_ttype`=0 AND `rank_sort`=1 ORDER BY `rank_num` ASC LIMIT 1;';
		$result_ckwin = mysqli_query($connection,$query);
		if($result_ckwin && mysqli_num_rows($result_ckwin)>0){
			//有过名次
			//查询最高名次是多少
			$winrecord = mysqli_fetch_assoc($result_ckwin);
			
			echo('用户获取的更高名次!['.$winrecord['rank_num'].']:'.$i.'<br/>');
			$logger_content .= '用户获取的更高名次!['.$winrecord['rank_num'].']:'.$i.PHP_EOL;
			
			if($winrecord['rank_num']>$i){
				//获取了更高的名次
				
				//经验
				//查询该次结算应当补足多少经验
				//已发的经验
				$added_exp = 0;
				$ck_id = 25;
				switch($winrecord['rank_num']){
					case 0:$ck_id = 25;break;
					case 1:$ck_id = 27;break;
					case 2:$ck_id = 28;break;
					case 3:$ck_id = 29;break;
					case 4:$ck_id = 30;break;
					case 5:$ck_id = 31;break;
					case 6:$ck_id = 32;break;
					case 7:$ck_id = 33;break;
					case 8:$ck_id = 34;break;
					case 9:$ck_id = 35;break;
					default:break;
				}
				$query = 'SELECT `time_add` FROM `exp_get_type` WHERE `id`='.$ck_id.';';
				$result_type = mysqli_query($connection,$query);
				if($result_type && mysqli_num_rows($result_type)>0){
					$exptype = mysqli_fetch_assoc($result_type);
					$added_exp = $exptype['time_add'];
				}
				
				echo('已结算经验:['.$added_exp.']<br/>');
				$logger_content .= '已结算经验:['.$added_exp.']'.PHP_EOL;
				
				//本次应该增加的经验
				$add_exp = 0;
				$ck_id = 35;
				switch($i){
					case 0:$ck_id = 25;break;
					case 1:$ck_id = 27;break;
					case 2:$ck_id = 28;break;
					case 3:$ck_id = 29;break;
					case 4:$ck_id = 30;break;
					case 5:$ck_id = 31;break;
					case 6:$ck_id = 32;break;
					case 7:$ck_id = 33;break;
					case 8:$ck_id = 34;break;
					case 9:$ck_id = 35;break;
					default:break;
				}
				$query = 'SELECT `time_add` FROM `exp_get_type` WHERE `id`='.$ck_id.';';
				$result_type = mysqli_query($connection,$query);
				if($result_type && mysqli_num_rows($result_type)>0){
					$exptype = mysqli_fetch_assoc($result_type);
					$add_exp = $exptype['time_add'];
				}
				
				echo('共需结算经验:['.$add_exp.']<br/>');
				$logger_content .= '共需结算经验:['.$add_exp.']'.PHP_EOL;
				
				//经验差值
				$exp_give = $add_exp - $added_exp;
				
				echo('需要补足经验:['.$exp_give.']<br/>');
				$logger_content .= '需要补足经验:['.$exp_give.']'.PHP_EOL;
				
				if($exp_give>0){
					//补足经验
					$query = 'INSERT INTO exp_get_record(user_id,exp,add_time,type) VALUES ('.
										 $grapher['id'].','.
										 $exp_give.','.
										 'now()'.','.
										 $ck_id.
					');';
					mysqli_query($connection, $query);
					
					$query = 'INSERT INTO exp_get_record_save(user_id,exp,add_time,type) VALUES ('.
										 $grapher['id'].','.
										 $exp_give.','.
										 'now()'.','.
										 $ck_id.
					');';
					mysqli_query($connection, $query);
					
					$query = 'SELECT * FROM `user_v_exp` WHERE `user_id`='.$grapher['id'].' ;';
					$result_expadd = mysqli_query($connection, $query);
					if($result_expadd){
						if(mysqli_num_rows($result_expadd)>0){
							//有了UPDATE
							$query = 'UPDATE `user_v_exp` SET `exp`=`exp`+'.$exp_give.',`update_time`=now() WHERE `user_id`='.$grapher['id'].';';
							mysqli_query($connection, $query);	
							
						}else{
							//没有
							//INSERT
							$query = 'INSERT INTO user_v_exp(user_id,exp,add_time,update_time) VALUES ('.
												 $grapher['id'].','.
												 $exp_give.','.
												 'now()'.','.
												 'now()'.
							');';
							mysqli_query($connection, $query);
							
						}
					}
				}
				
				//金币
				//查询该次结算应当补足多少金币
				//已发的金币
				$added_gold = 0;
				$ck_id = 13;
				switch($winrecord['rank_num']){
					case 0:$ck_id = 13;break;
					case 1:$ck_id = 16;break;
					case 2:$ck_id = 17;break;
					case 3:$ck_id = 18;break;
					case 4:$ck_id = 19;break;
					case 5:$ck_id = 20;break;
					case 6:$ck_id = 21;break;
					case 7:$ck_id = 22;break;
					case 8:$ck_id = 23;break;
					case 9:$ck_id = 24;break;
					default:break;
				}
				$query = 'SELECT `time_add` FROM `gold_get_type` WHERE `id`='.$ck_id.';';
				$result_type = mysqli_query($connection,$query);
				if($result_type && mysqli_num_rows($result_type)>0){
					$goldtype = mysqli_fetch_assoc($result_type);
					$added_gold = $goldtype['time_add'];
				}
				
				echo('已结算金币:['.$added_gold.']<br/>');
				$logger_content .= '已结算金币:['.$added_gold.']'.PHP_EOL;
				
				//本次应该增加的金币
				$add_gold = 0;
				$ck_id = 24;
				switch($i){
					case 0:$ck_id = 13;break;
					case 1:$ck_id = 16;break;
					case 2:$ck_id = 17;break;
					case 3:$ck_id = 18;break;
					case 4:$ck_id = 19;break;
					case 5:$ck_id = 20;break;
					case 6:$ck_id = 21;break;
					case 7:$ck_id = 22;break;
					case 8:$ck_id = 23;break;
					case 9:$ck_id = 24;break;
					default:break;
				}
				$query = 'SELECT `time_add` FROM `gold_get_type` WHERE `id`='.$ck_id.';';
				$result_type = mysqli_query($connection,$query);
				if($result_type && mysqli_num_rows($result_type)>0){
					$goldtype = mysqli_fetch_assoc($result_type);
					$add_gold = $goldtype['time_add'];
				}
				
				echo('共需结算金币:['.$add_gold.']<br/>');
				$logger_content .= '共需结算金币:['.$add_gold.']'.PHP_EOL;
				
				//金币差值
				$gold_give = $add_gold - $added_gold;
				
				echo('需要补足金币:['.$gold_give.']<br/>');
				$logger_content .= '需要补足金币:['.$gold_give.']'.PHP_EOL;
				
				if($gold_give>0){
					//补足金币
					$query = 'INSERT INTO gold_get_record(user_id,gold,add_time,type) VALUES ('.
										 $grapher['id'].','.
										 $gold_give.','.
										 'now()'.','.
										 $ck_id.
					');';
					mysqli_query($connection, $query);
					
					$query = 'INSERT INTO gold_get_record_save(user_id,gold,add_time,type) VALUES ('.
										 $grapher['id'].','.
										 $gold_give.','.
										 'now()'.','.
										 $ck_id.
					');';
					mysqli_query($connection, $query);
					
					$query = 'SELECT * FROM `user_v_gold` WHERE `user_id`='.$grapher['id'].' ;';
					$result_goldadd = mysqli_query($connection, $query);
					if($result_goldadd){
						if(mysqli_num_rows($result_goldadd)>0){
							//有了UPDATE
							$query = 'UPDATE `user_v_gold` SET `gold`=`gold`+'.$gold_give.',`update_time`=now() WHERE `user_id`='.$grapher['id'].';';
							mysqli_query($connection, $query);	
							
						}else{
							//没有
							//INSERT
							$query = 'INSERT INTO user_v_gold(user_id,gold,add_time,update_time) VALUES ('.
												 $grapher['id'].','.
												 $gold_give.','.
												 'now()'.','.
												 'now()'.
							');';
							mysqli_query($connection, $query);
							
						}
					}
				}
				
				//再INSERT记录
				if($exp_give>0 && $gold_give>0){
					//记录该用户获取过
					$query = 'INSERT INTO user_win_champion(user_id,rank_sort,rank_ttype,rank_num,rank_data,add_time) VALUES ('.
										 $grapher['id'].','.
										 '1,'.
										 '0,'.
										 $i.','.
										 $grapher['stat_beplayed'].','.
										 'now()'.
					');';
					mysqli_query($connection, $query);
					
					echo('SQL:'.$query.'<br/><br/>');
					$logger_content .= 'SQL:'.$query.PHP_EOL;
				}
				
			}
		}else{
			//没有记录直接结算经验
			switch($i){
				case 0:
					//记录登录金币和经验
					addGoldToUser($mem,$connection,$grapher['id'],'13');
					addExpToUser($mem,$connection,$grapher['id'],'25');
					break;
				case 1:
					//记录登录金币和经验
					addGoldToUser($mem,$connection,$grapher['id'],'16');
					addExpToUser($mem,$connection,$grapher['id'],'27');
					break;
				case 2:
					//记录登录金币和经验
					addGoldToUser($mem,$connection,$grapher['id'],'17');
					addExpToUser($mem,$connection,$grapher['id'],'28');
					break;
				case 3:
					//记录登录金币和经验
					addGoldToUser($mem,$connection,$grapher['id'],'18');
					addExpToUser($mem,$connection,$grapher['id'],'29');
					break;
				case 4:
					//记录登录金币和经验
					addGoldToUser($mem,$connection,$grapher['id'],'19');
					addExpToUser($mem,$connection,$grapher['id'],'30');
					break;
				case 5:
					//记录登录金币和经验
					addGoldToUser($mem,$connection,$grapher['id'],'20');
					addExpToUser($mem,$connection,$grapher['id'],'31');
					break;
				case 6:
					//记录登录金币和经验
					addGoldToUser($mem,$connection,$grapher['id'],'21');
					addExpToUser($mem,$connection,$grapher['id'],'32');
					break;
				case 7:
					//记录登录金币和经验
					addGoldToUser($mem,$connection,$grapher['id'],'22');
					addExpToUser($mem,$connection,$grapher['id'],'33');
					break;
				case 8:
					//记录登录金币和经验
					addGoldToUser($mem,$connection,$grapher['id'],'23');
					addExpToUser($mem,$connection,$grapher['id'],'34');
					break;
				case 9:
					//记录登录金币和经验
					addGoldToUser($mem,$connection,$grapher['id'],'24');
					addExpToUser($mem,$connection,$grapher['id'],'35');
					break;
				default:break;
			}
			
			//记录该用户获取过
			$query = 'INSERT INTO user_win_champion(user_id,rank_sort,rank_ttype,rank_num,rank_data,add_time) VALUES ('.
								 $grapher['id'].','.
								 '1,'.
								 '0,'.
								 $i.','.
								 $grapher['stat_beplayed'].','.
								 'now()'.
			');';
			mysqli_query($connection, $query);
			
			echo('SQL:'.$query.'<br/><br/>');
			$logger_content .= 'SQL:'.$query.PHP_EOL;
		}
		$i++;
	}
	
	//***********************************************************************************************
	
	//执行完毕
	if($connection)mysqli_close($connection);
	if($mem)$mem-> close();
	$usetime = endtime($start_time);
	//记录日志
	echo('usetime:'.$usetime.'<br/>');
	$logger_content .= 'usetime:'.$usetime.PHP_EOL;
	$logger->add($logger_content);
?>