<?php
	/*
	百度云推送的tag绑定,清除tag,重新绑定tag
	*/
	require_once(dirname(__FILE__).'/'.'../../../../../boo/admin/Lib/ORG/Baidu/Channel.class.php');
	define('APIKEY','3jCKHj4PN2R4BET0VX0pGUHd');
	define('SECRETKEY','A7wL3l3qthiT3NGUF2ykcaQ3GOz0c3wG');
	
	
	//给一个用户绑定一个tag
	function bindBdytsTagToUserID($mem,$connection,$userid,$tagstr){
		if($userid<=0 || strlen($tagstr)==0){
			return array(false,'Error userid or tagstr['.$userid.','.$tagstr.']');	
		}
		
		//查询用户的sns_bdyts_userId
		$query = 'SELECT `sns_bdyts_userId` FROM `client_user` WHERE `id`='.$userid.';';
		$result = mysqli_query($connection, $query);
		if($result){
			if(mysqli_num_rows($result)>0){
				$record = mysqli_fetch_assoc($result);
				$bdyts_userId = $record['sns_bdyts_userId'];
				if(strlen($bdyts_userId)>0){
					$channel = new Channel (APIKEY, SECRETKEY);
					$optional [Channel::USER_ID] = $bdyts_userId; 
					$ret = $channel->setTag ( $tagstr, $optional );
					if (false === $ret) {
						return array(false,$channel->errmsg ());
					} else {
						return array(true,'');
					}
				}
			}
		}
		return array(false,$query);
	}
	
	//取消一个用户的全部绑定的tagstr
	function cleanAllBdytsTagToUserID($mem,$connection,$userid){
		//目前木有这个方法
		
	}
	
	//取消一个用户的Tag
	function delBdytsTagToUserID($mem,$connection,$userid,$tagstr){
		if($userid<=0 || strlen($tagstr)==0){
			return array(false,'Error userid or tagstr['.$userid.','.$tagstr.']');	
		}
		
		$channel = new Channel (APIKEY, SECRETKEY);
		//需要首先查找tag的信息
		$optional [Channel::TAG_NAME] = $tagstr; 
		$ret = $channel->fetchTag ( $optional );
		if (false === $ret) {
			return array(false,$channel->errmsg ());
		} else {
			//成功了获取ID
			if(isset($ret['response_params']['tags'][0]['tid'])){
				$tagid = $ret['response_params']['tags'][0]['tid'];
				//查找到了ID 就取消
				$optional = array();
				//查询用户的sns_bdyts_userId
				$query = 'SELECT `sns_bdyts_userId` FROM `client_user` WHERE `id`='.$userid.';';
				$result = mysqli_query($connection, $query);
				if($result){
					if(mysqli_num_rows($result)>0){
						$record = mysqli_fetch_assoc($result);
						$bdyts_userId = $record['sns_bdyts_userId'];
						if(strlen($bdyts_userId)>0){
							$optional [Channel::USER_ID] = $bdyts_userId; 
							$ret = $channel->deleteTag ( $tagid, $optional );
							if (false === $ret) {
								return array(false,$channel->errmsg ());
							} else {
								return array(true,'');
							}
						}
					}
				}
			}
		}
	
		return array(false,$query);
	}
	
?>