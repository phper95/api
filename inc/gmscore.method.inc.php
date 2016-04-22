<?php
	/*
	GMSCORE评分方法
	*/

	//加载GMSCORE类别的缓存
	//返回Dict
	function checkLoadGmscoreTypes($mem,$connection){
		$cache_key = __FUNCTION__;
		if($mem){
			if(($value = $mem-> get($cache_key)) != FALSE) {
				//存在
				//直接返回
				return $value;
			}
		}
		
		$gmscore_map = array();
		
		//没有缓存链接或者不存在
		if($connection){
			$query = 'SELECT * FROM `movie_v_gmscore_type` WHERE `open`=1;';
			$result = mysqli_query($connection, $query);
			if($result && mysqli_num_rows($result)>0){
				$i=0;
				while($i < mysqli_num_rows($result)){
					$gmscore = mysqli_fetch_assoc($result);	
					$gmscore_map[$gmscore['id']] = $gmscore;
					$i++;
				}
				//写入缓存
				if($mem){
					//默认缓存1天
					$mem->set($cache_key, $gmscore_map, 0, 86400);	
				}
				return $gmscore_map;
			}
		}
		
		//也没有数据库链接
		//ERROR
		return $gmscore_map;
	}
	
	//加载GMSCORE计算系数的缓存
	//返回Dict
	function checkLoadGmscoreComp($mem,$connection){
		$cache_key = __FUNCTION__;
		if($mem){
			if(($value = $mem-> get($cache_key)) != FALSE) {
				//存在
				//直接返回
				return $value;
			}
		}
		
		$gmscore_map = array();
		
		//没有缓存链接或者不存在
		if($connection){
			$query = 'SELECT * FROM `movie_v_gmscore_comp` WHERE `open`=1;';
			$result = mysqli_query($connection, $query);
			if($result && mysqli_num_rows($result)>0){
				$i=0;
				while($i < mysqli_num_rows($result)){
					$record = mysqli_fetch_assoc($result);	
					$gmscore_map[$record['id']] = $record;
					$i++;
				}
				//写入缓存
				if($mem){
					//默认缓存1天
					$mem->set($cache_key, $gmscore_map, 0, 86400);	
				}
				return $gmscore_map;
			}
		}
		
		//也没有数据库链接
		//ERROR
		return $gmscore_map;
	}

?>