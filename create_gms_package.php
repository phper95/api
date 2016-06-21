<?php
/**
 * 2016年6月20日
 * 生成作品文件夹,制作器接口调用（返回json）
 */
// $work_key = '832e3e55fae2c99926149c0232edc898';
// $zip_key = '47b5aeac1b5141b125da330fe4a07e98';

$k = isset($_GET['k']) ? $_GET['k'] : '';
if (empty($k)) {
	$json['status'] = 0;
	$json['msg'] = '参数缺失';
	echo $json;
	exit;
}
$tmp = explode('/', $k);
if (count($tmp) != 2) {
	$json['status'] = 0;
	$json['msg'] = '缺少必要参数';
	echo $json;
	exit;
}
$work_key = $tmp[0];
$zip_key = $tmp[1];

if (!ctype_alnum($work_key.$zip_key) || strlen($work_key) != 32 || strlen($zip_key) != 32) {
	$json['status'] = 0;
	$json['msg'] = '参数有误';
	echo $json;
	exit;
}
$originPath = '/mnt/wwwroot/default/gms_works/';
$basePath = $originPath.'work_imgs/';
$path = $basePath.$work_key."/".$zip_key.'_gms';
//$filename = $path.".zip"; //最终生成的文件名（含路径）
/*if (!is_dir($path)) {
	exit('dir not exists;');
}*/

if (isset($_GET['del'])) {
	if (file_exists($path)) @deldir($path);
}

if(!file_exists($path)){
	//重新生成文件夹

	//$zip = new ZipArchive();//使用本类，linux需开启zlib，windows需取消php_zip.dll前的注释
	$result = mkdir($path,0777,true);
	if($result){
		//chmod($basePath.$work_key,0777);
		chmod($path,0777);
	}else{
		$json['status'] = 2;
		$json['msg'] = '目录'.$path.'创建失败';
		echo $json;
		exit;
	}
	if(!file_exists($path.'/PosterImages')){
		$result = mkdir($path.'/PosterImages',0777,true);
		if($result){
			chmod($path.'/PosterImages',0777);
		}else{
			$json['status'] = 2;
			$json['msg'] = $path.'/PosterImages'.'创建失败';
			echo $json;
			exit;
		}
	}

	//$zip->addEmptyDir('PosterImages');
	include 'inc/HttpClient.class.php';
	$url = 'http://ser3.graphmovie.com/boo/interface/api/Pcmaker_Movie_Script.api.php?k='.$work_key;
	$content = HttpClient::quickGet($url);
	$rst = json_decode($content, true);
	if (is_array($rst)) {
		if ($rst['a'] == 1) {
			foreach ($rst['d'] as $item) {
				if(file_exists($item['file'])){
					if ($item['page_index'] < 0) {
						if($item['page_index']==-1){
							//$zip->addFile($item['file'], 'PosterImages/PosterBigImage'.".".$item['type']);
							copy($originPath.$item['file'],$path.'/PosterImages/PosterBigImage'.".".'jpg');
						}elseif ($item['page_index']==-2){
							//$zip->addFile($item['file'], 'PosterImages/PosterFirstPageImage'.".".$item['type']);
							copy($originPath.$item['file'],$path.'/PosterImages/PosterFirstPageImage'.".".'jpg');
						}elseif($item['page_index']==-3){
							//$zip->addFile($item['file'], 'PosterImages/PosterSmallImage'.".".$item['type']);
							copy($originPath.$item['file'],$path.'/PosterImages/PosterSmallImage'.".".'jpg');
						}
						//$zip->addFile($item['file'], 'PosterImages/0'.abs($item['page_index']).".".$item['type']);
					} else {
						if($item['page_index']==1){
							//$zip->addFile($item['file'], 'PosterImages/PosterFirstPageImageGS'.".".$item['type']);
							copy($originPath.$item['file'],$path.'/PosterImages/PosterFirstPageImageGS'.".".'jpg');
						}else{
							//$zip->addFile( $item['file'], 'gm_'.$item['page_index'].".".$item['type']);//第二个参数是放在压缩包中的文件名称，如果文件可能会有重复，就需要注意一下
							copy($originPath.$item['file'],$path.'/gm_'.$item['page_index'].".".$item['type']);
						}

					}
				} else {
					//echo $item['file'];
				}
			}
		} else {
			//echo($rst['e']);
		}

		$url = 'http://ser3.graphmovie.com/gmspanel/interface/zh-cn/3.1/PCM_OFC_CreatScriptGms.php';
		$data = array('workkey'=>$work_key, 'ck'=>md5($work_key.'graphmoviestudiosapi'));
		$content = HttpClient::quickPost($url, $data);
		$rst = json_decode($content, true);
		if (is_array($rst)) {
			if ($rst['status'] == 1) {
				$work_name = $rst['work_name'];
				foreach ($rst['files'] as $file=>$c) {
					/*if($file == 'JsonScript.data') {
						$c = json_encode(json_decode($c, true),JSON_UNESCAPED_UNICODE);
					}*/
						$res = file_put_contents($path.'/'.$file, $c);
						//$zip->addFile($path.'.'.$file, $file);
				}
				//打包完成，生成gms包
				if(file_exists($path.'/GraphEditorScript.ges') && !file_exists($path.'.'.'gms')){
					//生成下载包.gms
					$package_url = 'http://imgs4.graphmovie.com:8890/gms?fp='.$work_key.'&fn='.$zip_key.'_gms';
					$package = HttpClient::quickGet($package_url);
					$res = json_decode($package);
					if($res->code==0){
						$json['status'] = 1;
						$json['msg'] = '打包成功';
						$json['name'] = $work_name;
						$json['downUrl'] = $res->content;
						echo json_encode($json);
						//echo '打包成功o(≧v≦)o~~好棒';
						exit;
					}else{
						$json['status'] = 2;
						$json['msg'] = '打包失败，请稍后再试';
						echo json_encode($json);
						//echo '打包失败，请返回重新打包,成功后再进行退稿操作';
						exit;
					}

				}else{
					$json['status'] = 2;
					$json['msg'] = 'GraphEditorScript.ges脚本生成错误，请稍后再试';
					echo json_encode($json);
					//echo '打包失败，请返回重新打包,成功后再进行退稿操作';
					exit;
				}


			} else {
				print_r($rst);
			}
		} else {
			echo $content;
		}
	} else {
		echo ($content);
	}
	
	//$zip->close();//关闭
}else{
	//判断是否在打包中
	if(!file_exists($path.'/GraphEditorScript.ges')){
		$json['status'] = 2;
		$json['msg'] = '打包进行中，请稍后再试';
		echo json_encode($json);
		//echo '打包进行中，请稍后再试';
		exit;
	}else{
		//包已生成
		$url = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"];
		$url = str_replace(basename($_SERVER["REQUEST_URI"]), '', $url);
		$json['status'] = 1;

		//获取作品名
		/*
		 include 'inc/HttpClient.class.php';
		 $url = 'http://ser3.graphmovie.com/gmspanel/interface/zh-cn/3.1/PCM_OFC_CreatScriptGms.php';
		$data = array('workkey'=>$work_key, 'ck'=>md5($work_key.'graphmoviestudiosapi'));
		$content = HttpClient::quickPost($url, $data);
		$rst = json_decode($content, true);
		if (is_array($rst)) {
			if ($rst['status'] == 1) {
				$work_name = $rst['work_name'];
			}
		}*/
		if(isset($work_name)){
			$json['name'] = $work_name;
		}else{
			$json['name'] = '退稿作品';
		}
		$json['downUrl'] = $url.'work_imgs/'.$work_key.'/'.$zip_key.'_gms.gms';
		echo json_encode($json);
		//echo '打包成功o(≧v≦)o~~好棒';
	}
}
if(!file_exists($path)){
	//即使创建，仍有可能失败。。。。
	$json['status'] = 2;
	$json['msg'] = '打包失败，请稍后再试';
	echo json_encode($json);
	//echo '打包失败，请返回重新打包,成功后再进行退稿操作';
	exit;

}

//header('Location:'.$url);
//exit($url);

