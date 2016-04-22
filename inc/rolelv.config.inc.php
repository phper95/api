<?php
	//ROLE CONFIG
	//ERROR
	define('ROLE_ERROR','此人不在服务区');
	//匿名君
	define('ROLE_UNBIND_EMAIL','伸手党');
	//普通读者
	define('ROLE_BINDED_EMAIL_0','菜鸟');
	define('ROLE_BINDED_EMAIL_CPv5','素人');			//评论+弹幕 >5
	define('ROLE_BINDED_EMAIL_CPv10','学徒');		//评论+弹幕 >10
	define('ROLE_BINDED_EMAIL_CPv30','信徒');		//评论+弹幕 >30
	define('ROLE_BINDED_EMAIL_CPv50','评论家');		//评论+弹幕 >50
	define('ROLE_BINDED_EMAIL_CPv100','鉴定家');		//评论+弹幕 >100
	define('ROLE_BINDED_EMAIL_CPv150','大师');		//评论+弹幕 >150
	//创作团
	define('ROLE_PUBED_WORK_Wv1','创作团-初出茅庐');	//作品数 1
	define('ROLE_PUBED_WORK_Wv5','创作团-小有名气');	//作品数 >5
	define('ROLE_PUBED_WORK_Wv10','创作团-一呼百应');	//作品数 >10
	define('ROLE_PUBED_WORK_Wv15','创作团-名扬天下');	//作品数 >15
	define('ROLE_PUBED_WORK_Wv20','创作团-一代宗师'); //作品数 >20
	//榜单
	define('ROLE_PUBED_WORK_MLIKE_1','创作团-本月红人榜状元');
	define('ROLE_PUBED_WORK_MLIKE_2','创作团-本月红人榜榜眼');
	define('ROLE_PUBED_WORK_MLIKE_3','创作团-本月红人榜探花');
	define('ROLE_PUBED_WORK_MPLAYED_1','创作团-本月达人榜状元');
	define('ROLE_PUBED_WORK_MPLAYED_2','创作团-本月达人榜榜眼');
	define('ROLE_PUBED_WORK_MPLAYED_3','创作团-本月达人榜探花');
	define('ROLE_PUBED_WORK_TLIKE_1','创作团-红人总榜状元');
	define('ROLE_PUBED_WORK_TLIKE_2','创作团-红人总榜榜眼');
	define('ROLE_PUBED_WORK_TLIKE_3','创作团-红人总榜探花');
	define('ROLE_PUBED_WORK_TPLAYED_1','创作团-达人总榜状元');
	define('ROLE_PUBED_WORK_TPLAYED_2','创作团-达人总榜榜眼');
	define('ROLE_PUBED_WORK_TPLAYED_3','创作团-达人总榜探花');
	
	//榜单的缓存KEY
	define('MEMK_MLIKE_1','MEMK_MLIKE_1');
	define('MEMK_MLIKE_2','MEMK_MLIKE_2');
	define('MEMK_MLIKE_3','MEMK_MLIKE_3');
	define('MEMK_MPLAYED_1','MEMK_MPLAYED_1');
	define('MEMK_MPLAYED_2','MEMK_MPLAYED_2');
	define('MEMK_MPLAYED_3','MEMK_MPLAYED_3');
	define('MEMK_TLIKE_1','MEMK_TLIKE_1');
	define('MEMK_TLIKE_2','MEMK_TLIKE_2');
	define('MEMK_TLIKE_3','MEMK_TLIKE_3');
	define('MEMK_TPLAYED_1','MEMK_TPLAYED_1');
	define('MEMK_TPLAYED_2','MEMK_TPLAYED_2');
	define('MEMK_TPLAYED_3','MEMK_TPLAYED_3');
	
	
	//鞭基部
	define('ROLE_PUBED_CENTER','鞭基部');
	define('ROLE_PUBED_CENTER_SIGN','鞭基部-签约大神');
	
	define('ROLE_PUBED_CENTER_SIGN_MLIKE_1','签约大神-本月红人榜状元');
	define('ROLE_PUBED_CENTER_SIGN_MLIKE_2','签约大神-本月红人榜榜眼');
	define('ROLE_PUBED_CENTER_SIGN_MLIKE_3','签约大神-本月红人榜探花');
	define('ROLE_PUBED_CENTER_SIGN_MPLAYED_1','签约大神-本月达人榜状元');
	define('ROLE_PUBED_CENTER_SIGN_MPLAYED_2','签约大神-本月达人榜榜眼');
	define('ROLE_PUBED_CENTER_SIGN_MPLAYED_3','签约大神-本月达人榜探花');
	define('ROLE_PUBED_CENTER_SIGN_TLIKE_1','签约大神-红人总榜状元');
	define('ROLE_PUBED_CENTER_SIGN_TLIKE_2','签约大神-红人总榜榜眼');
	define('ROLE_PUBED_CENTER_SIGN_TLIKE_3','签约大神-红人总榜探花');
	define('ROLE_PUBED_CENTER_SIGN_TPLAYED_1','签约大神-达人总榜状元');
	define('ROLE_PUBED_CENTER_SIGN_TPLAYED_2','签约大神-达人总榜榜眼');
	define('ROLE_PUBED_CENTER_SIGN_TPLAYED_3','签约大神-达人总榜探花');
	
	//官方账号
	define('ROLE_GOD','光腚肿菊');
	define('ROLE_GOD_SOAP','吉祥物');
	define('ROLE_GOD_CLEANMM','保洁小妹');
	define('ROLE_GOD_KFMM','客服小蜜');
	
	//LEVEL CONFIG
	define('LEVEL_ERROR','等级 你猜？');
	define('LEVEL_1','  Lv1');
	define('LEVEL_2','  Lv2');
	define('LEVEL_3','  Lv3');
	define('LEVEL_4','  Lv4');
	define('LEVEL_5','  Lv5');
	define('LEVEL_6','  Lv6');
	define('LEVEL_7','  Lv7');
	define('LEVEL_8','  Lv8');
	define('LEVEL_9','  Lv9');
	define('LEVEL_10','  Lv10');
	define('LEVEL_11','  Lv11');
	define('LEVEL_12','  Lv12');
	define('LEVEL_13','  Lv13');
	define('LEVEL_14','  Lv14');
	define('LEVEL_15','  Lv15');
	define('LEVEL_16','  Lv16');
	define('LEVEL_17','  Lv17');
	define('LEVEL_18','  Lv18');
	define('LEVEL_19','  Lv19');
	define('LEVEL_20','  Lv20');
	
	//给定用户结构以及memcache和dbconnect,返回用户角色名称
	//不负责链接也不负责释放
	function check_user_role_string($user,$mem,$connection){
		if(is_array($user) && count($user)>0){
			//首先检查是否是官方账号
			if(isset($user['id'])){
				if($user['id']==985){
					//W蜀黍
					return '鞭基部';
				}elseif($user['id']==1 || $user['id']==2 || $user['id']==3){
					//官方
					return 	ROLE_GOD;
				}elseif($user['id']==4){
					//保洁小妹
					return 	ROLE_GOD_CLEANMM;
				}elseif($user['id']==5){
					//肥皂君
					return ROLE_GOD_SOAP;
				}elseif(
				$user['id']==159297 
				|| $user['id']==235243
				|| $user['id']==353654
				|| $user['id']==426087
				|| $user['id']==658766
				|| $user['id']==494124
				|| $user['id']==681986
				|| $user['id']==2459
				|| $user['id']==362279
				|| $user['id']==334524
				|| $user['id']==1087301
				|| $user['id']==18564
				|| $user['id']==290459
				|| $user['id']==300866
				|| $user['id']==270944
				|| $user['id']==225821
				|| $user['id']==402121
				|| $user['id']==201547
				|| $user['id']==431587
				|| $user['id']==488280){
					return '鞭基部';	
				}elseif(
				$user['id']==906550
				|| $user['id']==875782
				|| $user['id']==908054
				|| $user['id']==1897941
				|| $user['id']==314297
				|| $user['id']==775362
				|| $user['id']==5555
				|| $user['id']==799454
				|| $user['id']==337518
				|| $user['id']==679993
				|| $user['id']==304208
				|| $user['id']==918431
				|| $user['id']==762051
				){
					return '精英作者';	
					
				}else{
					//非官方账号
					//首先检查是否绑定邮箱
					if(isset($user['email'])){
						if(strlen($user['email'])<5){
							//没有
							return ROLE_UNBIND_EMAIL;
						}else{
							//有绑定邮箱
							//检查是不是作者
							if($user['stat_work']>0){
								//是作者啊	
								
								//是否签约 字段contract_signed
								if(isset($user['contract_signed']) && $user['contract_signed']>0){
									//签约作者
									//检查是否是榜单三甲
									if($mem){
										//优先规则
										/*
										1. 本月红人 和 本月达人 平级
										2. 红人总榜 和 达人总榜 平级
										3. 总榜 高于 月榜 优先显示
										4. 平级的榜单中 红人高于达人
										5. 平级的榜单中 显示排名较高的一项 
										*/
										//所以先检查总榜
										//红人总榜 冠军
										/*
										if(($value = $mem-> get(MEMK_TLIKE_1)) != FALSE) { 
											if($value['id']==$user['id']){
												return 	ROLE_PUBED_CENTER_SIGN_TLIKE_1;
											}
										}
										//达人总榜 冠军
										if(($value = $mem-> get(MEMK_TPLAYED_1)) != FALSE) { 
											if($value['id']==$user['id']){
												return 	ROLE_PUBED_CENTER_SIGN_TPLAYED_1;
											}
										}
										
										//红人总榜 亚军
										if(($value = $mem-> get(MEMK_TLIKE_2)) != FALSE) { 
											if($value['id']==$user['id']){
												return 	ROLE_PUBED_CENTER_SIGN_TLIKE_2;
											}
										}
										//达人总榜 亚军
										if(($value = $mem-> get(MEMK_TPLAYED_2)) != FALSE) { 
											if($value['id']==$user['id']){
												return 	ROLE_PUBED_CENTER_SIGN_TPLAYED_2;
											}
										}
										
										//红人总榜 季军
										if(($value = $mem-> get(MEMK_TLIKE_3)) != FALSE) { 
											if($value['id']==$user['id']){
												return 	ROLE_PUBED_CENTER_SIGN_TLIKE_3;
											}
										}
										//达人总榜 季军
										if(($value = $mem-> get(MEMK_TPLAYED_3)) != FALSE) { 
											if($value['id']==$user['id']){
												return 	ROLE_PUBED_CENTER_SIGN_TPLAYED_3;
											}
										}
										
										//本月红人榜 状元
										if(($value = $mem-> get(MEMK_MLIKE_1)) != FALSE) { 
											if($value['id']==$user['id']){
												return 	ROLE_PUBED_CENTER_SIGN_MLIKE_1;
											}
										}
										//本月达人榜 状元
										if(($value = $mem-> get(MEMK_MPLAYED_1)) != FALSE) { 
											if($value['id']==$user['id']){
												return 	ROLE_PUBED_CENTER_SIGN_MPLAYED_1;
											}
										}
										
										//本月红人榜 榜眼
										if(($value = $mem-> get(MEMK_MLIKE_2)) != FALSE) { 
											if($value['id']==$user['id']){
												return 	ROLE_PUBED_CENTER_SIGN_MLIKE_2;
											}
										}
										//本月达人榜 榜眼
										if(($value = $mem-> get(MEMK_MPLAYED_2)) != FALSE) { 
											if($value['id']==$user['id']){
												return 	ROLE_PUBED_CENTER_SIGN_MPLAYED_2;
											}
										}
										
										//本月红人榜 探花
										if(($value = $mem-> get(MEMK_MLIKE_3)) != FALSE) { 
											if($value['id']==$user['id']){
												return 	ROLE_PUBED_CENTER_SIGN_MLIKE_3;
											}
										}
										//本月达人榜 探花
										if(($value = $mem-> get(MEMK_MPLAYED_3)) != FALSE) { 
											if($value['id']==$user['id']){
												return 	ROLE_PUBED_CENTER_SIGN_MPLAYED_3;
											}
										}
										*/
									}
									
									return ROLE_PUBED_CENTER_SIGN;
									 
								}else{
									//没签约
									//检查是否是榜单三甲
									if($mem){
										//优先规则
										/*
										1. 本月红人 和 本月达人 平级
										2. 红人总榜 和 达人总榜 平级
										3. 总榜 高于 月榜 优先显示
										4. 平级的榜单中 红人高于达人
										5. 平级的榜单中 显示排名较高的一项 
										*/
										//所以先检查总榜
										//红人总榜 冠军
										/*
										if(($value = $mem-> get(MEMK_TLIKE_1)) != FALSE) { 
											if($value['id']==$user['id']){
												return 	ROLE_PUBED_WORK_TLIKE_1;
											}
										}
										//达人总榜 冠军
										if(($value = $mem-> get(MEMK_TPLAYED_1)) != FALSE) { 
											if($value['id']==$user['id']){
												return 	ROLE_PUBED_WORK_TPLAYED_1;
											}
										}
										
										//红人总榜 亚军
										if(($value = $mem-> get(MEMK_TLIKE_2)) != FALSE) { 
											if($value['id']==$user['id']){
												return 	ROLE_PUBED_WORK_TLIKE_2;
											}
										}
										//达人总榜 亚军
										if(($value = $mem-> get(MEMK_TPLAYED_2)) != FALSE) { 
											if($value['id']==$user['id']){
												return 	ROLE_PUBED_WORK_TPLAYED_2;
											}
										}
										
										//红人总榜 季军
										if(($value = $mem-> get(MEMK_TLIKE_3)) != FALSE) { 
											if($value['id']==$user['id']){
												return 	ROLE_PUBED_WORK_TLIKE_3;
											}
										}
										//达人总榜 季军
										if(($value = $mem-> get(MEMK_TPLAYED_3)) != FALSE) { 
											if($value['id']==$user['id']){
												return 	ROLE_PUBED_WORK_TPLAYED_3;
											}
										}
										
										//本月红人榜 状元
										if(($value = $mem-> get(MEMK_MLIKE_1)) != FALSE) { 
											if($value['id']==$user['id']){
												return 	ROLE_PUBED_WORK_MLIKE_1;
											}
										}
										//本月达人榜 状元
										if(($value = $mem-> get(MEMK_MPLAYED_1)) != FALSE) { 
											if($value['id']==$user['id']){
												return 	ROLE_PUBED_WORK_MPLAYED_1;
											}
										}
										
										//本月红人榜 榜眼
										if(($value = $mem-> get(MEMK_MLIKE_2)) != FALSE) { 
											if($value['id']==$user['id']){
												return 	ROLE_PUBED_WORK_MLIKE_2;
											}
										}
										//本月达人榜 榜眼
										if(($value = $mem-> get(MEMK_MPLAYED_2)) != FALSE) { 
											if($value['id']==$user['id']){
												return 	ROLE_PUBED_WORK_MPLAYED_2;
											}
										}
										
										//本月红人榜 探花
										if(($value = $mem-> get(MEMK_MLIKE_3)) != FALSE) { 
											if($value['id']==$user['id']){
												return 	ROLE_PUBED_WORK_MLIKE_3;
											}
										}
										//本月达人榜 探花
										if(($value = $mem-> get(MEMK_MPLAYED_3)) != FALSE) { 
											if($value['id']==$user['id']){
												return 	ROLE_PUBED_WORK_MPLAYED_3;
											}
										}
										*/
										
									}
									//普通作者 未在榜单中进前三甲
									//判断作品数
									if(isset($user['stat_work']) && $user['stat_work']>0){
										if($user['stat_work']>20){
											return ROLE_PUBED_WORK_Wv20;	
										}elseif($user['stat_work']>15){
											return ROLE_PUBED_WORK_Wv15;	
										}elseif($user['stat_work']>10){
											return ROLE_PUBED_WORK_Wv10;
										}elseif($user['stat_work']>5){
											return ROLE_PUBED_WORK_Wv5;
										}elseif($user['stat_work']>=1){
											return ROLE_PUBED_WORK_Wv1;
										}
									}
									return ROLE_PUBED_WORK_Wv1;	
								}//签约与否
								
							}else{
								//不是作者
								//检查发表过的评论和弹幕数目
								$send_comment_count = 0;
								$send_poptxt_count = 0;
								if($connection){
									//有数据库链接才查询
									$query = 'SELECT * FROM `user_v_stat_comment_poptxt` WHERE `user_id`='.$user['id'].';';
									$result = mysqli_query($connection, $query);
									if($result && mysqli_num_rows($result)>0){
										$value = mysqli_fetch_assoc($result);
										$send_comment_count = $value['send_comment_count'];
										$send_poptxt_count = $value['send_poptxt_count'];
									}	
								}
								//暂时都还没有
								if($send_comment_count+$send_poptxt_count>0){
									if($send_comment_count+$send_poptxt_count>150){
										return ROLE_BINDED_EMAIL_CPv150;
									}elseif($send_comment_count+$send_poptxt_count>100){
										return ROLE_BINDED_EMAIL_CPv100;
									}elseif($send_comment_count+$send_poptxt_count>50){
										return ROLE_BINDED_EMAIL_CPv50;
									}elseif($send_comment_count+$send_poptxt_count>30){
										return ROLE_BINDED_EMAIL_CPv30;
									}elseif($send_comment_count+$send_poptxt_count>10){
										return ROLE_BINDED_EMAIL_CPv10;
									}elseif($send_comment_count+$send_poptxt_count>5){
										return ROLE_BINDED_EMAIL_CPv5;
									}else{
										return ROLE_BINDED_EMAIL_0;
									}
								}	
								return ROLE_BINDED_EMAIL_0;	
							}//是否作者
						}//是否绑定邮箱
					}//是否有邮箱字段
				}//非官方账号
			}//是否用用户id
		}//是否有用户结构
		return ROLE_ERROR;	
	}
	
	//给定用户结构以及memcache和dbconnect,返回用户等级名称
	//不负责链接也不负责释放
	function check_user_level_string($user,$mem,$connection){
		if(is_array($user) && count($user)>0){
			if(!$connection){
				if(isset($user['level'])){
					if(strlen($user['level'])==0){
						return LEVEL_1;	
					}else{
						/*
						switch($user['level']){
							case 0: return LEVEL_1;
							case 1: return LEVEL_1;
							case 2: return LEVEL_2;
							case 3: return LEVEL_3;
							case 4: return LEVEL_4;
							case 5: return LEVEL_5;
							case 6: return LEVEL_6;
							case 7: return LEVEL_7;
							case 8: return LEVEL_8;
							case 9: return LEVEL_9;
							case 10: return LEVEL_10;
							case 11: return LEVEL_11;
							case 12: return LEVEL_12;
							case 13: return LEVEL_13;
							case 14: return LEVEL_14;
							case 15: return LEVEL_15;
							case 16: return LEVEL_16;
							case 17: return LEVEL_17;
							case 18: return LEVEL_18;
							case 19: return LEVEL_19;
							case 20: return LEVEL_20;
							default: return LEVEL_1;
						}
						*/
						return 'Lv'.$user['level'];
					}
				}
			}else{
				//查询用户等级
				//如果是匿名君
				//等级就是1
				if(strlen($user['email'])==0){
					return 'Lv1';
				}
				$query = 'SELECT * FROM `user_v_exp` WHERE `user_id`='.$user['id'].';';
				$result = mysqli_query($connection, $query);
				if($result && mysqli_num_rows($result)>0){
					$value = mysqli_fetch_assoc($result);
					$user_exp = $value['exp'];
					//再查询等级
					$query = 'SELECT * FROM `exp_v_level` WHERE `exp`>'.$user_exp.' ORDER BY `level` ASC;';
					$result = mysqli_query($connection, $query);
					if($result && mysqli_num_rows($result)>0){
						$value = mysqli_fetch_assoc($result);
						$lv = $value['level']-1;
						return 'Lv'.$lv;
					}
				}
				return 'Lv1';
			}
		}
		return LEVEL_ERROR;
	}
?>