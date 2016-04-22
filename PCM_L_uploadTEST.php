<?php
	require_once(dirname(__FILE__).'/'.'inc/interface.json.inc.php');
	require_once(dirname(__FILE__).'/'.'inc/methods.inc.php');
	require_once(dirname(__FILE__).'/'.'inc/config.inc.php');
	
	//时间记录
	$start_time = starttime();
	
	//获取JSON基本模板
	$json = getBasicJsonModel();
	$json["desc"] = "";
	
	$p_userid = '0';
	$p_avatar = '';
	$upload_failed = FALSE; //头像是否上传失败 失败了也继续完成注册流程 因为已经INSERT了
	if(isset($_FILES['imgdata'])){
		
		$json['debug'] .= '[has imgdata]';
		
		if($_FILES['imgdata']['error'] == UPLOAD_ERR_OK ){
			//上传成功
			
			$json['debug'] .= '[UPLOAD_ERR_OK]';
			
			$filepath= dirname(__FILE__).'/../../../'."appimages/avatars_test/";            //定义路径
			
			$json['debug'] .= '[filepath:'.$filepath.']';
			
			//判断是否存在userid目录
			if(!is_dir($filepath.$p_userid)){
				mkdir($filepath.$p_userid);
			}
			
			$image_name = date('YmdHis').'.jpg';
			$filename = $filepath.$p_userid.'/'.$image_name;         //新的路径及文件名
			
			$json['debug'] .= '[filename:'.$filename.']';
			
			$json['debug'] .= '[tmp_name:'.$_FILES['imgdata']['tmp_name'].']';
			
			if(copy($_FILES['imgdata']['tmp_name'],$filename))           //复制文件的目标路径
			{
			   unlink($_FILES['imgdata']['tmp_name']);            //删除原有文件
			   $p_avatar = "http://".$_SERVER['SERVER_NAME'].'/gmspanel/appimages/avatars_test/'.$p_userid.'/'.$image_name;
			}
			else
			{
			  	$upload_failed = TRUE;
			}
		}	
	}
	
	if(!$upload_failed){
		$json['status'] = 1;
		$json['url'] = $p_avatar;
		
	}
	$json['usetime'] = endtime($start_time);
	$json['error'] = '';
	$json['desc'] = "";
	$json_code = json_encode($json);
	echo $json_code;
?>