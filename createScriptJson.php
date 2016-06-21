<?php
/**
 * 2016年6月17日
 * 生成作品信息的GraphEditorScript.json文件
 * 接受del参数删除
 */
// $work_key = '832e3e55fae2c99926149c0232edc898';

$work_key = isset($_GET['k']) ? $_GET['k'] : '';
if (empty($work_key)) {exit('参数缺失');}

if (strlen($work_key) != 32) {
	exit('参数有误!');
}

$originPath = '/mnt/wwwroot/default/gms_works/';
$basePath = $originPath.'work_imgs/';
$path = $basePath.$work_key."/";
$fileName = $path."GraphEditorScript.json";


if (isset($_GET['del'])) {
	if (file_exists($path)) @unlink($fileName);
}

if(!file_exists($fileName)){
	//重新生成文件夹
		include 'inc/HttpClient.class.php';
		$url = 'http://ser3.graphmovie.com/gmspanel/interface/zh-cn/3.1/PCM_OFC_CreatScriptJson.php';
		$data = array('workkey'=>$work_key, 'ck'=>md5($work_key.'graphmoviestudiosapi'));

		$content = HttpClient::quickPost($url, $data);
		$rst = json_decode($content, true);
		if (is_array($rst)) {
			if ($rst['status'] == 1) {
				$res = file_put_contents($fileName, json_encode($rst['scriptJson']));
				if($res){
					$json['status'] = 1;
					$json['msg'] = '打包成功';
					$json['downUrl'] = $fileName;
					echo json_encode($json);
				}else{
					$json['status'] = 2;
					$json['msg'] = 'json文件生成失败，请稍后再试';
					$json['downUrl'] = '';
					echo json_encode($json);
				}


			} else {
				print_r($rst);
			}
		} else {
			echo $content;
		}
	
	//$zip->close();//关闭
}else{
	//包已生成
	$json['status'] = 1;
	$json['msg'] = '打包成功';
	$json['downUrl'] = $fileName;
	echo json_encode($json);
}

if(!file_exists($fileName)){
	//即使创建，仍有可能失败。。。。
	$json['status'] = 2;
	$json['msg'] = 'json文件生成失败，请稍后再试';
	echo json_encode($json);
	exit;

}

//header('Location:'.$url);
//exit($url);

