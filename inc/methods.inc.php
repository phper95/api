<?php
	//一些接口辅助方法
	
	//记录开始结束时间
	function starttime(){
					/*
					$rtime = explode(" ",microtime());
					$usec = (double)$rtime[0];
					$sec = (double)$rtime[1];
					return $sec+$usec;
					*/
					$rtime = microtime(true);
					return $rtime;
					}
					
	function endtime($start){
		//$end = starttime();
		//return substr(($end-$start),0,8);	
		$end = microtime(true);
		return (string)round(($end-$start),5);
		//return $end-$start;
	}
	
	function otherURL_2_Server_URL($other_url,$movieid,$imgserver_id){
		switch($imgserver_id){
			case 0:
				return $other_url;
				break;
			case 1:
				return otherURL_2_Server_1_URL($other_url,$movieid);
				break;
			case 2:
				return otherURL_2_Server_2_URL($other_url,$movieid);
				break;
			default:
				return $other_url;
				break;
		}
	}
	
	//替换url中的domin
	function otherURL_2_Server_1_URL($other_url,$movieid){
		
		if(strlen($other_url)==0 || $movieid<=0){
			return $other_url;
		}	
		
		//获取k165图片名称
		$url_parts = explode('/',$other_url);
		if(count($url_parts)<=1){
			//没有找到'/',错误的URL
			return $other_url;
		}
		//最后一个元素即为图片名称
		$img_name = $url_parts[count($url_parts)-1];
		
		return 'http://gaoqing.mobi/BackStage2/movies/'.$movieid.'/'.$img_name;
	}
	
	//替换url中的domin
	function otherURL_2_Server_2_URL($other_url,$movieid){
		
		if(strlen($other_url)==0 || $movieid<=0){
			return $other_url;
		}	
		
		//图片名称
		$url_parts = explode('/',$other_url);
		if(count($url_parts)<=1){
			//没有找到'/',错误的URL
			return $other_url;
		}
		//最后一个元素即为图片名称
		$img_name = $url_parts[count($url_parts)-1];
		
		return 'http://113.107.72.248:80/movies/'.$movieid.'/'.$img_name;
	}
	
	//获取服务器地址
	function getImgServerURL($imgserverid){
		switch($imgserverid){
			case 0:
				return 'http://gaoqing.mobi/BackStage2';
				break;
			case 1:
				return 'http://gaoqing.mobi/BackStage2';
				break;
			case 2:
				//return 'http://imgs4.graphmovie.com';
				return 'http://imgs3.graphmovie.com:80';
				break;
			case 3:
				return 'http://ser3.graphmovie.com/boo';
				break;
			case 4:
				return 'http://imgs3.graphmovie.com:80';
				break;
			case 5:
				return 'http://imgs2.graphmovie.com:80';
				break;
			case 6:
				return 'http://imgs2.graphmovie.com:80/graphmovie';
				break;
			case 7:
				//bobo 2015年3月16日16:32:00 直接修改成七牛的镜像服务器
				return 'http://img7n2.graphmovie.com:80';
// 				return 'http://imgs4.graphmovie.com:80';
			case 8:
				return 'http://imgs5.graphmovie.com:80';
			default:
				return '';
		}	
	}
	
	//日期转时间戳
	function datetime_YmdHis_2_timestamp($datetime){
		//2013-01-01 00:00:00
		
		$year=((int)substr($datetime,0,4));//取得年份

		$month=((int)substr($datetime,5,2));//取得月份
		
		$day=((int)substr($datetime,8,2));//取得几号
		
		$hour = ((int)substr($datetime,11,2));//取得小时
		
		$min = ((int)substr($datetime,14,2));//取得分钟
		
		$second = ((int)substr($datetime,17,2));//取得秒
		
		//2999年改不了
		if($year>=2500){
			$year=date('Y')+10;	
		}
		
		return mktime($hour,$min,$second,$month,$day,$year);	
	}
	
	//版本号String转换为数值
	//1.0.2.4=>1.024
	function versionString_2_versionFloat($verString){
		$ver = 0.0;
		$ver_nums = explode('.',$verString);
		for($i=0;$i<count($ver_nums);$i++){
			$num = (int)$ver_nums[$i];
			$j=0;
			while($j<$i){
				$num /= 10;
				$j++;
			}
			$ver += $num;
		}
		return $ver;
	}
	
	//把数字转换成汉字万形式
	//1154321->115.4万
	function number_2_numberFont($number){
		$number = (int)($number);
		if($number<10000){
			return $number;
		}else{
			$number /= 10000;
			$number = number_format($number,1).'万';
			return $number;
		}
	}
	
	//凯撒加密解密
	//用于在线看影片
	function movieIdOnlineKeyEncode($movieid){
		//先转换为16进制
		$movieid = dechex($movieid);
		//
		
		//不足8位补足8位
		$fill_8 = 8 - strlen($movieid);
		if($fill_8>0){
			while($fill_8>0){
				$movieid = '0'.$movieid;
				$fill_8--;	
			}
		}
		
		
		$online_key = '';
		//开始逐个转码
		for($i=0;$i<strlen($movieid);$i++){
			$font = substr($movieid,$i,1);
			//if($font){
				$font = caesarEncode($font);
				$online_key .= $font;
			//}
		}
		
		return $online_key;
	}
	
	function movieIdOnlineKeyDecode($onlinekey){
		//先转换为全大写
		$onlinekey = strtoupper($onlinekey);
		$movieid = '';
		//逐个解码
		for($i=0;$i<strlen($onlinekey);$i++){
			$font = substr($onlinekey,$i,1);
			//if($font){
				$font = caesarDecode($font);
				$movieid .= $font;
			//}
		}
		//转化为小写
		$movieid = strtolower($movieid);
		
		//十六进制转换为十进制
		$movieid = hexdec($movieid);
		
		return $movieid;
	} 
	
	//静态固定的加密解密
	function movieIdOnlineKeyStaticEncode($movieid){
		//先转换为16进制
		$movieid = dechex($movieid);
		//
		
		//不足8位补足8位
		$fill_8 = 8 - strlen($movieid);
		if($fill_8>0){
			while($fill_8>0){
				$movieid = '0'.$movieid;
				$fill_8--;	
			}
		}
		
		
		$online_key = '';
		//开始逐个转码
		for($i=0;$i<strlen($movieid);$i++){
			$font = substr($movieid,$i,1);
			//if($font){
				$font = caesarStaticEncode($font);
				$online_key .= $font;
			//}
		}
		
		return $online_key;
	}
	
	function movieIdOnlineKeyStaticDecode($onlinekey){
		//先转换为全大写
		$onlinekey = strtoupper($onlinekey);
		$movieid = '';
		//逐个解码
		for($i=0;$i<strlen($onlinekey);$i++){
			$font = substr($onlinekey,$i,1);
			//if($font){
				$font = caesarStaticDecode($font);
				$movieid .= $font;
			//}
		}
		//转化为小写
		$movieid = strtolower($movieid);
		
		//十六进制转换为十进制
		$movieid = hexdec($movieid);
		
		return $movieid;
	} 
	
	//凯撒加密解密
	//用于处理用户ID
	function userIdKeyEncode($userid){
		//先转换为16进制
		$userid = dechex($userid);
		//
		
		//不足8位补足8位
		$fill_8 = 8 - strlen($userid);
		if($fill_8>0){
			while($fill_8>0){
				$userid = '0'.$userid;
				$fill_8--;	
			}
		}
		
		
		$show_key = '';
		//开始逐个转码
		for($i=0;$i<strlen($userid);$i++){
			$font = substr($userid,$i,1);
			//if($font){
				$font = caesarStaticEncode($font);
				$show_key .= $font;
			//}
		}
		
		return $show_key;
	}
	
	function userIdKeyDecode($userkey){
		//先转换为全大写
		$userkey = strtoupper($userkey);
		$userid = '';
		//逐个解码
		for($i=0;$i<strlen($userkey);$i++){
			$font = substr($userkey,$i,1);
			//if($font){
				$font = caesarStaticDecode($font);
				$userid .= $font;
			//}
		}
		
		//如果ID转化前后没变就不用再转换进制了
		//发的就是ID
		if($userkey != $userid){
			//转化为小写
			$userid = strtolower($userid);
			
			//十六进制转换为十进制
			$userid = hexdec($userid);
		}
		
		return $userid;
	} 
	
	//凯撒转化
	function caesarEncode($font){
		$font = strtoupper($font);
		if($font=='0'){
			$rand_num = rand(0,5);
			switch($rand_num){
				case 0:
					return 'X';
				case 1:
					return 'S';
				case 2:
					return '7';
				case 3:
					return '8';
				case 4:
					return '9';
				case 5:
					return '0';
				default:
					return 'S';
			}	
		}
		if($font=='1'){
			return rand(0,1)>0?'H':'V';	
		}
		if($font=='2'){
			return rand(0,1)>0?'E':'O';	
		}
		if($font=='3'){
			return rand(0,1)>0?'G':'T';	
		}
		if($font=='4'){
			return rand(0,1)>0?'M':'Y';	
		}
		if($font=='5'){
			return rand(0,1)>0?'Z':'R';	
		}
		if($font=='6'){
			return rand(0,1)>0?'W':'I';	
		}
		if($font=='7'){
			return rand(0,1)>0?'B':'6';	
		}
		if($font=='8'){
			return rand(0,1)>0?'C':'4';	
		}
		if($font=='9'){
			return rand(0,1)>0?'A':'L';	
		}
		if($font=='A' || $font=='a'){
			return rand(0,1)>0?'D':'N';	
		}
		if($font=='B' || $font=='b'){
			return rand(0,1)>0?'J':'U';	
		}
		if($font=='C' || $font=='c'){
			return rand(0,1)>0?'K':'2';	
		}
		if($font=='D' || $font=='d'){
			return rand(0,1)>0?'Q':'3';	
		}
		if($font=='E' || $font=='e'){
			return rand(0,1)>0?'F':'5';	
		}
		if($font=='F' || $font=='f'){
			return rand(0,1)>0?'P':'1';	
		}
		return $font;
	}
	
	function caesarDecode($font){
		$font = strtoupper($font);
		if($font=='X' || $font=='S' || $font=='7' || $font=='8' || $font=='9' || $font=='0'){
			return '0';	
		}
		if($font=='H' || $font=='V'){
			return '1';	
		}
		if($font=='E' || $font=='O'){
			return '2';	
		}
		if($font=='G' || $font=='T'){
			return '3';	
		}
		if($font=='M' || $font=='Y'){
			return '4';	
		}
		if($font=='Z' || $font=='R'){
			return '5';	
		}
		if($font=='W' || $font=='I'){
			return '6';	
		}
		if($font=='B' || $font=='6'){
			return '7';	
		}
		if($font=='C' || $font=='4'){
			return '8';	
		}
		if($font=='A' || $font=='L'){
			return '9';	
		}
		if($font=='D' || $font=='N'){
			return 'A';	
		}
		if($font=='J' || $font=='U'){
			return 'B';	
		}
		if($font=='K' || $font=='2'){
			return 'C';	
		}
		if($font=='Q' || $font=='3'){
			return 'D';	
		}
		if($font=='F' || $font=='5'){
			return 'E';	
		}
		if($font=='P' || $font=='1'){
			return 'F';	
		}
		return $font;
	}
	
	//凯撒静态转化
	//每次转化出来的编码是相同的
	//用于处理userid
	function caesarStaticEncode($font){
		$font = strtoupper($font);
		if($font=='0'){
			return '9';
		}
		if($font=='1'){
			return 'H';	
		}
		if($font=='2'){
			return 'O';	
		}
		if($font=='3'){
			return 'G';	
		}
		if($font=='4'){
			return 'M';	
		}
		if($font=='5'){
			return 'Z';	
		}
		if($font=='6'){
			return 'W';	
		}
		if($font=='7'){
			return '6';	
		}
		if($font=='8'){
			return '4';	
		}
		if($font=='9'){
			return 'A';	
		}
		if($font=='A' || $font=='a'){
			return 'D';	
		}
		if($font=='B' || $font=='b'){
			return 'J';	
		}
		if($font=='C' || $font=='c'){
			return '2';	
		}
		if($font=='D' || $font=='d'){
			return '3';	
		}
		if($font=='E' || $font=='e'){
			return 'F';	
		}
		if($font=='F' || $font=='f'){
			return 'P';	
		}
		return $font;
	}
	
	function caesarStaticDecode($font){
		$font = strtoupper($font);
		if($font=='X' || $font=='S' || $font=='7' || $font=='8' || $font=='9' || $font=='0'){
			return '0';	
		}
		if($font=='H' || $font=='V'){
			return '1';	
		}
		if($font=='E' || $font=='O'){
			return '2';	
		}
		if($font=='G' || $font=='T'){
			return '3';	
		}
		if($font=='M' || $font=='Y'){
			return '4';	
		}
		if($font=='Z' || $font=='R'){
			return '5';	
		}
		if($font=='W' || $font=='I'){
			return '6';	
		}
		if($font=='B' || $font=='6'){
			return '7';	
		}
		if($font=='C' || $font=='4'){
			return '8';	
		}
		if($font=='A' || $font=='L'){
			return '9';	
		}
		if($font=='D' || $font=='N'){
			return 'A';	
		}
		if($font=='J' || $font=='U'){
			return 'B';	
		}
		if($font=='K' || $font=='2'){
			return 'C';	
		}
		if($font=='Q' || $font=='3'){
			return 'D';	
		}
		if($font=='F' || $font=='5'){
			return 'E';	
		}
		if($font=='P' || $font=='1'){
			return 'F';	
		}
		return $font;
	}
	
	//单引号替换
	//不需要这个转换了
	//addslashes()
	function formatTextQuo($text){
		$text = str_replace("'",'’',$text);
		$text = str_replace('"','”',$text);
		return $text;	
	}
	
	//获取文件路径
	function get_file_dir($file){
		return pathinfo($file, PATHINFO_DIRNAME);
	}
	
	//获取文件名
	function get_file_name($file){
		return pathinfo($file, PATHINFO_FILENAME);
	}
	//获取文件后缀名
	function get_file_extension($file){
		return pathinfo($file, PATHINFO_EXTENSION);
	}
	
	//给定文件名改为时间戳命名
	//可以给定前缀后缀
	function formatToTimeName($pre_string,$filename,$tail_string){
		return $pre_string.date('YmdHis').$tail_string.'.'.get_file_extension($filename);
	}
	
	//求数组交集
	function my_array_intersect($arr1,$arr2)
	{
	    for($i=0;$i<sizeof($arr1);$i++)
	    {
	        $temp[]=$arr1[$i];
	    }
	     
	    for($i=0;$i<sizeof($arr1);$i++)
	    {
	        $temp[]=$arr2[$i];
	    }
	     
	    sort($temp);
	    $get=array();
	     
	    for($i=0;$i<sizeof($temp);$i++)
	    {
	        if($temp[$i]==$temp[$i+1])
	         $get[]=$temp[$i];
	    }
     
	    return $get;
	}
	//快速排序
	//按照sort_value的字段排序
	function quickSort($arr,$desc) {
		if (count($arr) > 1) {
			$k = $arr[0];
			$x = array();
			$y = array();
			$_size = count($arr);    
			for ($i=1; $i<$_size; $i++) {
			  if($desc){
				 if ($arr[$i] > $k) {
					$x[] = $arr[$i];
				} else {
					$y[] = $arr[$i];
				} 
			  }else{
				if ($arr[$i] <= $k) {
					$x[] = $arr[$i];
				} else {
					$y[] = $arr[$i];
				}  
			  }
			  
			}
			$x = quickSort($x,$desc);
			$y = quickSort($y,$desc);
			return array_merge($x, array($k), $y);
		} else {
		  	return $arr;
		}
	}
	//获取IP
	function getClientIP(){
		$cip = 'unknow';
		if(!empty($_SERVER["HTTP_CLIENT_IP"])){
		  $cip = $_SERVER["HTTP_CLIENT_IP"];
		}
		elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
		  $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		}
		elseif(!empty($_SERVER["REMOTE_ADDR"])){
		  $cip = $_SERVER["REMOTE_ADDR"];
		}
		else{
		  $cip = "unknow";
		}
		return $cip;
	}
	
	//生成随机用户名
	function randomDefaultName(){
		//随机前缀
		$pre_list = array(
			0=>'傲娇',
			1=>'腹黑',
			2=>'呆萌',
			3=>'工口',
			4=>'黑化',
			5=>'三次元',
			6=>'基情',
			7=>'妹控',
			8=>'兄控',
			9=>'娘化',
			10=>'百合',
			11=>'鬼畜',
			12=>'中二',
			13=>'娇羞',
			14=>'二次元',
			15=>'宅腐',
			16=>'病娇',
			17=>'弱娇',
			18=>'正莉',
			19=>'萝太',
		);
		$pre_str = $pre_list[mt_rand(0,count($pre_list)-1)];
		
		$tail_name = 'の匿名君';
		
		//极小的概率出现女王
		//10%
		/*
		$show_queen = mt_rand(0, 9);
		if($show_queen == 0){
			$tail_name = 'の女王';
		}
		*/
		
		$random_name = $pre_str.$tail_name;	
		
		return $random_name;
	}
	
	/**
	 * 加密数组，或者字符串
	 * @param array | String $arr 要加密的参数
	 * @param bool $url_encode  是否url_encode转换 默认true
	 * @return String 如果是数组则返回 数组的json串 加密
	 */
	function pramsEncode($arr, $url_encode=TRUE){
		require_once 'jiami.php';
		$str = '';
		if (is_array($arr)) {
			$str = json_encode($arr);
		} else {
			$str = $arr;
		}
		if ($url_encode) {
			return urlencode(authcode($str,'ENCODE',MD_KEY));
		} else {
			return authcode($str,'ENCODE',MD_KEY);
		}
	}
	
	
	/**
	 * 解密数组，或者字符串
	 * @param  String $str 加密参数
	 * @param bool $url_decode  是否url_decode转换 默认true
	 * @return String | ArrayObject 如果是json格式的，则返回数组，否则返回字符串
	 */
	function pramsDecode($str, $url_decode=TRUE){
		require_once 'jiami.php';
		if ($url_decode) {
			$de_str = authcode(urldecode($str),'DECODE',MD_KEY);
		} else {
			$de_str = authcode($str,'DECODE',MD_KEY);
		}
		if (!empty($de_str)) {
			$rst = json_decode($de_str, TRUE);
			return is_array($rst) ? $rst : $de_str;
		}
		return '';
	}
	
	/*
	专辑ORKEY的阅读加密key
	*/
	function topicIdOnlineKeyEncode($user_id='0',$topic_id='0',$pub_platform='unknow',$pub_channel='unknow',$ver='0'){
		$encode_dict = array(
			'user_id' => $user_id,
			'topic_id' => $topic_id,
			'pub_platform' => $pub_platform,
			'pub_channel' => $pub_channel,
			'ver' => $ver
		);	
		
		return pramsEncode($encode_dict);
	}
	
	/**
	 * 获取MemcachValue 
	 *  说明： 
	 *  	当memcach存在时，返回memcach里面的值
	 *  	当获取错误时，则获取缓存文件里的值，如果缓存文件不存在，则返回false
	 * Enter description here ...
	 * @param unknown_type $mem
	 * @param unknown_type $cache_key
	 */
	function getMemcachValue($mem, $cache_key){
		$value = $mem-> get($cache_key);
		if ($value == FALSE) {
			$file = 'tmp/mem_value/'.$cache_key;
			if (!file_exists($file)) { // 缓存文件不存在，返回false 
				return FALSE;
			} else {
				$time = time();
				$cha_time = $time - filemtime($file);
				if ($cha_time > 480) { // 如果缓存文件存在了8分钟以上，这个时候可能需要更新缓存文件  
					$lockfile = 'tmp/mem_lock/'.$cache_key; 
					if (file_exists($lockfile)) { // 判断是否锁住
						if ($time - filemtime($lockfile) > 60) { // 如果锁文件存在了1分钟，则更新锁文件，并且返回false
							file_put_contents($lockfile, '1'); 
							return FALSE;
						} else { // 别人正在执行数据库操作，所以这个时候直接返回缓存内容
							return file_get_contents($file);
						}
					} else {  // 如果不存在锁，则加锁，并且返回false
						file_put_contents($lockfile, '1');
						return FALSE;
					}
				} else { // 缓存文件更新时间小于8分钟，直接返回缓存文件的值
					$value = file_get_contents($file);
					if ($cha_time>10)  { // 如果时间大于10秒，则在这里写入memcach，下次就可以直接读取memcach了
						$mem->set($cache_key, $value, 0, $cha_time-2);
					}
					return $value;
				}
			}
		} else { // 直接返回memcach里的值
			return $value;
		}
	}
	
	/**
	 * 设置memcach并且写入缓存文件
	 * 	说明：
	 * 		只要有写入操作，无论什么情况，都进行写入，如果存在写入锁，则删除
	 * @param unknown_type $mem
	 * @param unknown_type $cache_key
	 * @param unknown_type $value
	 * @param unknown_type $cach_time
	 */
	function setMemcachedValue($mem, $cache_key, $value, $cach_time=479) {
		$time = Date('Y-m-d H:i:s');
		$file = 'tmp/mem_value/'.$cache_key;
		$lockfile = 'tmp/mem_lock/'.$cache_key;
		file_put_contents($file, $value);
		$mem->set($cache_key, $value, 0, $cach_time);
		if (file_exists($lockfile)) {
			@unlink($lockfile);
		}
	}
?>