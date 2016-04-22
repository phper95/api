<?php
//二级缓存,检查缓存中是否存在,并设置缓存

/*------------------------------------------------------------
	[client_user]:用户的二级缓存,缓存数据库client_user查询出的record
------------------------------------------------------------*/

//获取给定UserID的memcache
function secondCacheGet_client_user_id($mem,$userid){
	//20141017关闭调试
	return FALSE;
	
	if(!$mem) return FALSE;
	$cache_key = __FUNCTION__.'_'.$userid;
	if(($value = $mem-> get($cache_key)) != FALSE) {
		//存在
		return $value;
	}
	//不存在
	return FALSE;
}

//设置User的二级缓存
function secondCacheSet_client_user_id($mem,$userid,$user,$cachetime){
	
	return TRUE;
	
	if(!$mem) return FALSE;
	$cache_key = __FUNCTION__.'_'.$userid;
	$mem->set($cache_key, $user, 0, $cachetime);
	return TRUE;
}

/*------------------------------------------------------------
	[movie]:影片的二级缓存,缓存数据库movie查询出的record
------------------------------------------------------------*/

//获取给定MovieID的memcache
function secondCacheGet_movie_id($mem,$movieid){
	if(!$mem) return FALSE;
	$cache_key = __FUNCTION__.'_'.$movieid;
	if(($value = $mem-> get($cache_key)) != FALSE) {
		//存在
		return $value;
	}
	//不存在
	return FALSE;
}

//设置MovieID的二级缓存
function secondCacheSet_movie_id($mem,$movieid,$movie,$cachetime){
	if(!$mem) return FALSE;
	$cache_key = __FUNCTION__.'_'.$movieid;
	$mem->set($cache_key, $movie, 0, $cachetime);
	return TRUE;
}

/*------------------------------------------------------------
	[adv]:广告的二级缓存,缓存数据库adv查询出的record
------------------------------------------------------------*/

//获取给定advID的memcache
function secondCacheGet_adv_id($mem,$advid){
	if(!$mem) return FALSE;
	$cache_key = __FUNCTION__.'_'.$advid;
	if(($value = $mem-> get($cache_key)) != FALSE) {
		//存在
		return $value;
	}
	//不存在
	return FALSE;
}

//设置advID的二级缓存
function secondCacheSet_adv_id($mem,$advid,$adv,$cachetime){
	if(!$mem) return FALSE;
	$cache_key = __FUNCTION__.'_'.$advid;
	$mem->set($cache_key, $adv, 0, $cachetime);
	return TRUE;
}

/*------------------------------------------------------------
	[topic]:专题的二级缓存,缓存数据库topic查询出的record
------------------------------------------------------------*/

//获取给定topicid的memcache
function secondCacheGet_topic_id($mem,$advid){
	if(!$mem) return FALSE;
	$cache_key = __FUNCTION__.'_'.$advid;
	if(($value = $mem-> get($cache_key)) != FALSE) {
		//存在
		return $value;
	}
	//不存在
	return FALSE;
}

//设置topicid的二级缓存
function secondCacheSet_topic_id($mem,$topicid,$topic,$cachetime){
	if(!$mem) return FALSE;
	$cache_key = __FUNCTION__.'_'.$topicid;
	$mem->set($cache_key, $topic, 0, $cachetime);
	return TRUE;
}

/*------------------------------------------------------------
	[user_v_comment_movie]:用户电影评论的二级缓存,缓存数据库user_v_comment_movie查询出的record
------------------------------------------------------------*/

//获取给定commentID的memcache
function secondCacheGet_user_v_comment_movie_id($mem,$commentid){
	if(!$mem) return FALSE;
	$cache_key = __FUNCTION__.'_'.$commentid;
	if(($value = $mem-> get($cache_key)) != FALSE) {
		//存在
		return $value;
	}
	//不存在
	return FALSE;
}

//设置comment的二级缓存
function secondCacheSet_user_v_comment_movie_id($mem,$commentid,$comment,$cachetime){
	if(!$mem) return FALSE;
	$cache_key = __FUNCTION__.'_'.$commentid;
	$mem->set($cache_key, $comment, 0, $cachetime);
	return TRUE;
}


/*------------------------------------------------------------
	[user_v_comment_adv]:用户广告评论的二级缓存,缓存数据库user_v_comment_adv查询出的record
------------------------------------------------------------*/

//获取给定commentID的memcache
function secondCacheGet_user_v_comment_adv_id($mem,$commentid){
	if(!$mem) return FALSE;
	$cache_key = __FUNCTION__.'_'.$commentid;
	if(($value = $mem-> get($cache_key)) != FALSE) {
		//存在
		return $value;
	}
	//不存在
	return FALSE;
}

//设置comment的二级缓存
function secondCacheSet_user_v_comment_adv_id($mem,$commentid,$comment,$cachetime){
	if(!$mem) return FALSE;
	$cache_key = __FUNCTION__.'_'.$commentid;
	$mem->set($cache_key, $comment, 0, $cachetime);
	return TRUE;
}

/*------------------------------------------------------------
	[user_v_comment_user]:用户电影评论的二级缓存,缓存数据库user_v_comment_user查询出的record
------------------------------------------------------------*/

//获取给定commentID的memcache
function secondCacheGet_user_v_comment_user_id($mem,$commentid){
	if(!$mem) return FALSE;
	$cache_key = __FUNCTION__.'_'.$commentid;
	if(($value = $mem-> get($cache_key)) != FALSE) {
		//存在
		return $value;
	}
	//不存在
	return FALSE;
}

//设置comment的二级缓存
function secondCacheSet_user_v_comment_user_id($mem,$commentid,$comment,$cachetime){
	if(!$mem) return FALSE;
	$cache_key = __FUNCTION__.'_'.$commentid;
	$mem->set($cache_key, $comment, 0, $cachetime);
	return TRUE;
}

/*------------------------------------------------------------
	[user_v_comment_topic]:用户专题评论的二级缓存,缓存数据库user_v_comment_topic查询出的record
------------------------------------------------------------*/

//获取给定commentID的memcache
function secondCacheGet_user_v_comment_topic_id($mem,$commentid){
	if(!$mem) return FALSE;
	$cache_key = __FUNCTION__.'_'.$commentid;
	if(($value = $mem-> get($cache_key)) != FALSE) {
		//存在
		return $value;
	}
	//不存在
	return FALSE;
}

//设置comment的二级缓存
function secondCacheSet_user_v_comment_topic_id($mem,$commentid,$comment,$cachetime){
	if(!$mem) return FALSE;
	$cache_key = __FUNCTION__.'_'.$commentid;
	$mem->set($cache_key, $comment, 0, $cachetime);
	return TRUE;
}

/*------------------------------------------------------------
	[user_v_comment_paper]:用户资讯评论的二级缓存,缓存数据库user_v_comment_paper查询出的record
------------------------------------------------------------*/

//获取给定commentID的memcache
function secondCacheGet_user_v_comment_paper_id($mem,$commentid){
	if(!$mem) return FALSE;
	$cache_key = __FUNCTION__.'_'.$commentid;
	if(($value = $mem-> get($cache_key)) != FALSE) {
		//存在
		return $value;
	}
	//不存在
	return FALSE;
}

//设置comment的二级缓存
function secondCacheSet_user_v_comment_paper_id($mem,$commentid,$comment,$cachetime){
	if(!$mem) return FALSE;
	$cache_key = __FUNCTION__.'_'.$commentid;
	$mem->set($cache_key, $comment, 0, $cachetime);
	return TRUE;
}

/*------------------------------------------------------------
	[user_v_comment_wei]:用户微图解评论的二级缓存,缓存数据库wei_comments查询出的record
------------------------------------------------------------*/

//获取给定commentID的memcache
function secondCacheGet_user_v_comment_wei_id($mem,$commentid){
	if(!$mem) return FALSE;
	$cache_key = __FUNCTION__.'_'.$commentid;
	if(($value = $mem-> get($cache_key)) != FALSE) {
		//存在
		return $value;
	}
	//不存在
	return FALSE;
}

//设置comment的二级缓存
function secondCacheSet_user_v_comment_wei_id($mem,$commentid,$comment,$cachetime){
	if(!$mem) return FALSE;
	$cache_key = __FUNCTION__.'_'.$commentid;
	$mem->set($cache_key, $comment, 0, $cachetime);
	return TRUE;
}

/*------------------------------------------------------------
	[user_v_poptxt_movie]:用户电影弹幕的二级缓存,缓存数据库user_v_poptxt_movie查询出的record
------------------------------------------------------------*/

//获取给定poptxtxid的memcache
function secondCacheGet_user_v_poptxt_movie_id($mem,$popid){
	if(!$mem) return FALSE;
	$cache_key = __FUNCTION__.'_'.$popid;
	if(($value = $mem-> get($cache_key)) != FALSE) {
		//存在
		return $value;
	}
	//不存在
	return FALSE;
}

//设置poptxtid的二级缓存
function secondCacheSet_user_v_poptxt_movie_id($mem,$popid,$pop,$cachetime){
	if(!$mem) return FALSE;
	$cache_key = __FUNCTION__.'_'.$popid;
	$mem->set($cache_key, $pop, 0, $cachetime);
	return TRUE;
}

/*------------------------------------------------------------
	[user_v_poptxt_adv]:用户广告弹幕的二级缓存,缓存数据库user_v_poptxt_adv查询出的record
------------------------------------------------------------*/

//获取给定poptxtxid的memcache
function secondCacheGet_user_v_poptxt_adv_id($mem,$popid){
	if(!$mem) return FALSE;
	$cache_key = __FUNCTION__.'_'.$popid;
	if(($value = $mem-> get($cache_key)) != FALSE) {
		//存在
		return $value;
	}
	//不存在
	return FALSE;
}

//设置poptxtid的二级缓存
function secondCacheSet_user_v_poptxt_adv_id($mem,$popid,$pop,$cachetime){
	if(!$mem) return FALSE;
	$cache_key = __FUNCTION__.'_'.$popid;
	$mem->set($cache_key, $pop, 0, $cachetime);
	return TRUE;
}

/*------------------------------------------------------------
	[用户喜好数据数组缓存]:缓存用户喜好数据的查询数组
	接口UM_UserMoviePrefer使用
------------------------------------------------------------*/

//获取给定userid的memcache
function secondCacheGet_user_movie_prefer_all_id($mem,$userid){
	if(!$mem) return FALSE;
	$cache_key = __FUNCTION__.'_'.$userid;
	if(($value = $mem-> get($cache_key)) != FALSE) {
		//存在
		return $value;
	}
	//不存在
	return FALSE;
}

//设置userid的二级缓存
function secondCacheSet_user_movie_prefer_all_id($mem,$userid,$data,$cachetime){
	if(!$mem) return FALSE;
	$cache_key = __FUNCTION__.'_'.$userid;
	$mem->set($cache_key, $data, 0, $cachetime);
	return TRUE;
}

//获取给定userid的memcache
function secondCacheGet_user_movie_prefer_spider_id($mem,$userid){
	if(!$mem) return FALSE;
	$cache_key = __FUNCTION__.'_'.$userid;
	if(($value = $mem-> get($cache_key)) != FALSE) {
		//存在
		return $value;
	}
	//不存在
	return FALSE;
}

//设置userid的二级缓存
function secondCacheSet_user_movie_prefer_spider_id($mem,$userid,$data,$cachetime){
	if(!$mem) return FALSE;
	$cache_key = __FUNCTION__.'_'.$userid;
	$mem->set($cache_key, $data, 0, $cachetime);
	return TRUE;
}

/*
记录活跃用户的ID到MEMCACHE,检查磁盘写入错误情况
*/
//获取给定UserID的memcache
function debug_log_active_user_id($mem,$userid){
	//只先记录2014-08-21的活跃用户
	if(date('Y-m-d') != '2014-08-21' && date('Y-m-d') != '2014-08-20') return FALSE;
	if(!$mem) return FALSE;
	$cache_key = 'debug_active_users_'.date('Y-m-d');
	$value = array();
	if(($value = $mem-> get($cache_key)) != FALSE) {
		//存在
		//结构为
		/*
			{userid:1,userid:1...}
		*/
		if(!isset($value[$userid])){
			$value[$userid] = 1;	
		}
	}else{
		$value[$userid] = 1;
	}
	$mem->set($cache_key, $value, 0, 86400*2);
	return TRUE;
}

/*
记录电影详细评分的缓存
*/
//获取给定movieid的详细评分内容st,s1,s2,s3,s4,s5
function secondCacheGet_movie_gmscore_id($mem,$movieid){
	if(!$mem) return FALSE;
	$cache_key = __FUNCTION__.'_'.$movieid;
	if(($value = $mem-> get($cache_key)) != FALSE) {
		//存在
		return $value;
	}
	//不存在
	return FALSE;
}

//设置userid的二级缓存
function secondCacheSet_movie_gmscore_id($mem,$movieid,$data,$cachetime){
	if(!$mem) return FALSE;
	$cache_key = __FUNCTION__.'_'.$movieid;
	$mem->set($cache_key, $data, 0, $cachetime);
	return TRUE;
} 

/*
记录电影标记tag的缓存
*/
function secondCacheGet_movie_utag_id($mem,$movieid){
	if(!$mem) return FALSE;
	$cache_key = __FUNCTION__.'_'.$movieid;
	if(($value = $mem-> get($cache_key)) != FALSE) {
		//存在
		return $value;
	}
	//不存在
	return FALSE;
}

//设置二级缓存
function secondCacheSet_movie_utag_id($mem,$movieid,$data,$cachetime){
	if(!$mem) return FALSE;
	$cache_key = __FUNCTION__.'_'.$movieid;
	$mem->set($cache_key, $data, 0, $cachetime);
	return TRUE;
}


?>