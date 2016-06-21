<?php
/**
* @api {post} /gms_works/interface/PCM_W_UploadPic.php 上传一张图片
* @apiPermission pxseven
* @apiVersion 0.1.0
* @apiName UploadPic
* @apiGroup Work
* @apiSampleRequest http://imgs4.graphmovie.com/gms_works/interface/PCM_W_UploadPic.php

* @apiDescription 请求SetUpUpload接口获取签名regcer后,请求该接口上传一张图片

* @apiParam (POST) {String} [pk=""] 用户PC的机器码(MAC地址)
* @apiParam (POST) {Integer} [v=0] 用户GraphMovieStudios的内部版本号
* @apiParam (POST) {String} userid 用户的userid，必须为登录用户才可访问此接口
* @apiParam (POST) {String} token 用户的登录时返回的token，用于验证用户是否合法
* @apiParam (POST) {String} workid 用户正在创作作品的作品ID
* @apiParam (POST) {String} uploadkey 用户作品对应的版本标识
* @apiParam (POST) {String} regcer 之前SetUpUpload接口请求到的签名证书
* @apiParam (POST) {Integer} pageindex 该图片的页序，注意由于封面图也需要上传，小封面图spic(图片名03.png)页序编号为-3，大封面图bpic(图片名02.png)页序编号为-2，图解第一页fpic(图片名01.png)页序编号为-1，其余正常解说图片从0开始编号，如果没有指定封面不发即可
* @apiParam (POST) {String} [intro=".."] 该页图片的解说，按照页序每次覆写，如第7页的解说和图片已发过一次，再次上传第7页将会覆盖之前的第7页数据
* @apiParam (POST) {FILE} imgdata 图解的图片的数据，无需压缩，高清图即可.

*
* @apiSuccess (ResponseJSON) {Integer} status 接口响应状态（0-失败,1-成功,2-需要弹出提示框,提示desc内容）.
* @apiSuccess (ResponseJSON) {Float} usetime 接口响应时间,调试用.
* @apiSuccess (ResponseJSON) {String} error 接口响应出错时的错误描述.
* @apiSuccess (ResponseJSON) {String} debug 接口响应出错时的过程描述,调试用.
* @apiSuccess (ResponseJSON) {String} desc status=2时需要弹窗提示此内容.

*
* @apiSuccessExample Success-Response[提交成功]:

*     {
*       "status": 1,
*       "usetime": 0.0024,
*       "error": "",
*       "debug": "",
*       "desc": ""
*     }

*
* @apiError PostError 请求的参数缺失或者参数格式错误，必须终止上传.
* @apiError ServerError 服务器状态异常，必须终止上传.
* @apiError TokenTimeOut 用户会话超时或非法，必须终止上传.
* @apiError IllegalRegCer 证书非法，必须终止上传.
* @apiError UploadTimeOut 由于未知原因该页上传失败，不必弹窗直接重新尝试即可，如果连续多次都上传失败可以跳过此页.
* @apiError ErrorUpload 由于验证异常上传必须终止(该错误出现在通过了证书验证但是上传信息有误的情况，出现此情况不必再继续上传了).
* @apiError IllegalImgType 不能识别的图片格式，不必弹窗警告直接跳过该页即可.

*
* @apiErrorExample Error-Response:

*     PostError:

*     {
*       "status": 0,
*       "usetime": 0.0024,
*       "error": "PostError",
*       "debug": "",
*       "desc": ""
*     }

*     ServerError:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "ServerError",
*       "debug": "",
*       "desc": "额服务器开小差了,请稍后重试..."
*     }

*     TokenTimeOut:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "TokenTimeOut",
*       "debug": "",
*       "desc": "会话超时,请重新登录"
*     }

*     IllegalRegCer:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "IllegalRegCer",
*       "debug": "",
*       "desc": "证书非法上传失败"
*     }

*     UploadTimeOut:

*     {
*       "status": 0,
*       "usetime": 0.0024,
*       "error": "UploadTimeOut",
*       "debug": "",
*       "desc": "上传超时,请重新尝试"
*     }

*     ErrorUpload:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "ErrorUpload",
*       "debug": "",
*       "desc": "发生错误上传终止"
*     }

*     IllegalImgType:

*     {
*       "status": 0,
*       "usetime": 0.0024,
*       "error": "IllegalImgType",
*       "debug": "",
*       "desc": "不能识别的图片格式"
*     }

*/


	//config
	require_once('interface.json.inc.php');
	require_once('methods.inc.php');
	require_once('FileUtil.inc.php');
	require_once('post.methods.inc.php');
	
	//时间记录
	$start_time = starttime();
	
	//获取JSON基本模板
	$json = getBasicJsonModel();
	$json["desc"] = "";
	
	//初始化POST参数
	$post_pk = 'na';
	$post_v = 0;
	$post_userid = '0';
	$post_token = '';
	$post_workid = '';
	$post_uploadkey = '';
	$post_regcer = '';
	$post_pageindex = -9999;
	$post_intro = '..';
	
	//debug
	//file_put_contents('0.txt',json_encode($json).PHP_EOL,FILE_APPEND);
	
	//获取Header
	//模拟请求采用的是request payload 发上来的参数
	$request_body = file_get_contents('php://input');
	$data = json_decode($request_body);
	if($data && count($data)>0 && (!isset($_POST) || count($_POST)==0) && isset($data->userid) && strlen($data->userid)>0 && isset($data->token) && strlen($data->token)>0 && isset($data->workid) && strlen($data->workid)>0 && isset($data->uploadkey) && strlen($data->uploadkey)>0 && isset($data->regcer) && strlen($data->regcer)>0 ){
		//curl提交的参数
		if(isset($data->pk)){
			$post_pk = htmlspecialchars(addslashes($data->pk));
		}
		
		if(isset($data->v)){
			$post_v = htmlspecialchars(addslashes($data->v));
		}
		
		if(isset($data->userid)){
			$post_userid = userIdKeyDecode(htmlspecialchars(addslashes($data->userid)));
		}
		
		if(isset($data->token)){
			$post_token = htmlspecialchars(addslashes($data->token));
		}
		
		if(isset($data->workid)){
			$post_workid = htmlspecialchars(addslashes($data->workid));
		}
		
		if(isset($data->uploadkey)){
			$post_uploadkey = htmlspecialchars(addslashes($data->uploadkey));
		}
		
		if(isset($data->regcer)){
			$post_regcer = htmlspecialchars(addslashes($data->regcer));
		}
		
		if(isset($data->pageindex)){
			$post_pageindex = htmlspecialchars(addslashes($data->pageindex));
		}
		
		if(isset($data->intro)){
			$post_intro = htmlspecialchars(addslashes($data->intro));
		}
		
		
	}else if(
		isset($_POST['userid']) && strlen($_POST['userid'])>0 && 
		isset($_POST['token']) && strlen($_POST['token'])>0 &&
		isset($_POST['workid']) && strlen($_POST['workid'])>0 &&
		isset($_POST['uploadkey']) && strlen($_POST['uploadkey'])>0 &&
		isset($_POST['regcer']) && strlen($_POST['regcer'])>0
		){
		//获取参数
		if(isset($_POST['pk']) && strlen($_POST['pk'])>0){$post_pk = htmlspecialchars(addslashes($_POST['pk']));}
		if(isset($_POST['v'])){$post_v = htmlspecialchars(addslashes($_POST['v']));}
		if(isset($_POST['userid'])){$post_userid = userIdKeyDecode(htmlspecialchars(addslashes($_POST['userid'])));}
		if(isset($_POST['token'])){$post_token = htmlspecialchars(addslashes($_POST['token']));}
		if(isset($_POST['workid'])){$post_workid = htmlspecialchars(addslashes($_POST['workid']));}
		if(isset($_POST['uploadkey'])){$post_uploadkey = htmlspecialchars(addslashes($_POST['uploadkey']));}
		if(isset($_POST['regcer'])){$post_regcer = htmlspecialchars(addslashes($_POST['regcer']));}
		
		if(isset($_POST['pageindex'])){$post_pageindex = htmlspecialchars(addslashes($_POST['pageindex']));}
		if(isset($_POST['intro'])){$post_intro = htmlspecialchars(addslashes($_POST['intro']));}
		
	}else{
		//缺少参数
		$json['status']= 0;
		$json['usetime'] = endtime($start_time);
		$json['error'] = 'PostError';
		$json_code = json_encode($json);
		echo $json_code;
		die();
	}
	
	//debug
	//file_put_contents('1.txt',json_encode($_POST).PHP_EOL,FILE_APPEND);
	
	//验证证书是否合法
	//生成通信证书
	//生成规则 根据本次的userid token workid uploadkey 以及一个秘钥来md5
	$regcer = 'graphmoviestudios-'.$post_userid.'-image-'.$post_token.'-server-'.$post_workid.'-register-'.$post_uploadkey.'-certificate';
	$regcer = md5($regcer);
	if($regcer != $post_regcer){
		//证书非法
		$json['status']= 2;
		$json['usetime'] = endtime($start_time);
		$json['error'] = 'IllegalRegCer';
		$json['desc'] = "证书非法上传失败";
		$json_code = json_encode($json);
		echo $json_code;
		die();
	}
	//注意这样验证有个BUG 就是一旦生成一个合法证书 则一直是有效的 token在这边改post过去的时候会再次验证一次 保证错误的证书最多也就只能上传一张
	//证书合法了
	
	//验证上传目录是否存在
	$fu = new FileUtil();
	
	//不存在就创建文件夹 并且设置为777权限
	$root_path = '../work_imgs/';
	$work_dir = $root_path.$post_workid;
	$img_dir = $work_dir.'/'.$post_uploadkey;
	if(!is_dir($work_dir)){
		$fu->createDir($work_dir);
		chmod($work_dir,0777);
	}
	if(!is_dir($img_dir)){
		$fu->createDir($img_dir);
		chmod($img_dir,0777);
	}
	
	//收取图片文件
	$upload_ok = false; 
	$save_img_file = '';
	$save_img_md5 = '';
	$save_img_type = '';
	$save_img_type_id = 0;
	$save_img_width = 0;
	$save_img_height = 0;
	$save_img_size = 0;
	
	//debug
	//file_put_contents('2.txt','ckimgdata...'.PHP_EOL,FILE_APPEND);
	
	if(isset($_FILES['imgdata']) && isset($_FILES['imgdata']['tmp_name']) && $_FILES['imgdata']['tmp_name']){
		if($_FILES['imgdata']['error'] == UPLOAD_ERR_OK ){
			
			//图片的信息
			$img_details = getimagesize($_FILES['imgdata']['tmp_name']);
			
			//file_put_contents('image.txt',json_encode($_FILES).PHP_EOL,FILE_APPEND);
			
			$save_img_size = filesize($_FILES['imgdata']['tmp_name']);
			
			if(!$img_details || count($img_details)==0 || $save_img_size <=0){
				//错误的图片
				$upload_ok = false;
			}else{
				$save_img_type_id = $img_details[2];
				$save_img_width = $img_details[0];
				$save_img_height = $img_details[1];	
				
				switch ($save_img_type_id){
					case 1:		$save_img_type = 'GIF';break;
					case 2:		$save_img_type = 'JPG';break;
					case 3:		$save_img_type = 'PNG';break;
					case 4:		$save_img_type = 'SWF';break;
					case 5:		$save_img_type = 'PSD';break;
					case 6:		$save_img_type = 'BMP';break;
					case 7:		$save_img_type = 'TIFF(intel byte order)';break;
					case 8:		$save_img_type = 'TIFF(motorola byte order)';break;
					case 9:		$save_img_type = 'JPC';break;
					case 10:	$save_img_type = 'JP2';break;
					case 11:	$save_img_type = 'JPX';break;
					case 12:	$save_img_type = 'JB2';break;
					case 13:	$save_img_type = 'SWC';break;
					case 14:	$save_img_type = 'IFF';break;
					case 15:	$save_img_type = 'WBMP';break;
					case 16:	$save_img_type = 'XBM';break;
					default:	break;
				}
				
				//debug
				//file_put_contents('3.txt',$save_img_type_id.'-'.$save_img_width.'-'.$save_img_height.PHP_EOL,FILE_APPEND);
				
				//目前只接受:GIF,JPG,PNG,BMP四种格式
				if($save_img_type_id==1 || $save_img_type_id==2 || $save_img_type_id==3 || $save_img_type_id==6){
					
					//上传成功
					$filepath= $img_dir.'/';            //定义缓存存储路径
					$save_img_md5 = md5($_FILES['imgdata']['tmp_name']);
					$image_name = md5($_FILES['imgdata']['tmp_name']).'.'.strtolower($save_img_type);
					
					//$image_name = md5($_FILES['imgdata']['tmp_name']).'.jpg';
					
					$filename = $filepath.$image_name;         //新的路径及文件名
					
					//如果图片已经存在就删除
					if(file_exists($filename)){
						$fu->unlinkFile($filename);
					}
					
					//file_put_contents('3_1.txt',$_FILES['imgdata']['tmp_name'].'-'.$filename.PHP_EOL,FILE_APPEND);
					
					if(copy($_FILES['imgdata']['tmp_name'],$filename))           //复制文件的目标路径
					{
					   unlink($_FILES['imgdata']['tmp_name']);            //删除原有文件
					   $upload_ok = true; 
					   $save_img_file = $filename;
					}
					else
					{
						$upload_ok = false; 
					}
				}else{
					$upload_ok = false; 	
				}
			}
			
		}else{
			$upload_ok = false;	
		}	
	}else{
		$upload_ok = false; 	
	}
	
	//如果上传失败就返回超时
	if(!$upload_ok){
		//UploadTimeOut
		$json['status']= 0;
		$json['usetime'] = endtime($start_time);
		$json['error'] = 'UploadTimeOut';
		$json['desc'] = "上传超时";
		$json_code = json_encode($json);
		echo $json_code;
		die();
	}
	
	//上传成功了 转post ser3 来更新数据库记录
	
	//post PCM_W_UpdatePicMsg
	/*
	pk
	v
	userid 
	token 
	workid
	uploadkey
	regcer
	imgfile
	intro
	imgmd5 图片MD5
	imgw 图片宽
	imgh 图片高
	imgs 图片体积
	imgt 图片类型
	svk
	pageindex
	*/
	$svk = md5($post_workid.'graph'.$post_uploadkey.'movie'.'from imgs.graphmovie.com');
	
	//debug
	//file_put_contents('4.txt',$svk.PHP_EOL,FILE_APPEND);
	
	$post_string = 'pk='.$post_pk.'&v='.$post_v.'&userid='.userIdKeyEncode($post_userid).'&token='.$post_token.'&workid='.$post_workid.'&uploadkey='.$post_uploadkey.'&regcer='.$post_regcer.'&imgfile='.str_replace('../','/',$save_img_file).'&imgmd5='.$save_img_md5.'&imgw='.$save_img_width.'&imgh='.$save_img_height.'&imgs='.$save_img_size.'&imgt='.$save_img_type.'&svk='.$svk.'&pageindex='.$post_pageindex.'&intro='.$post_intro;
	
	//debug
	//file_put_contents('5.txt',$post_string.PHP_EOL,FILE_APPEND);
	
	$response_json = request_by_curl('http://ser3.graphmovie.com/gmspanel/interface/zh-cn/3.1/PCM_W_UpdatePicMsg.php',$post_string);
	
	//debug
	//file_put_contents('6.txt',$response_json.PHP_EOL,FILE_APPEND);
	
	$json_struct = @json_decode($response_json);
	if($json_struct && isset($json_struct->status)){
		if($json_struct->status==1){
			//记录成功了
				
		}else{
			//如果失败了
			if($json_struct->error=='ServerError'){
				//服务器问题
				$json['status']= 2;
				$json['usetime'] = endtime($start_time);
				$json['error'] = 'ServerError';
				$json['desc'] = "额服务器开小差了,请稍后重试...";
				$json_code = json_encode($json);
				echo $json_code;
				die();
			}else if($json_struct->error=='TokenTimeOut'){
				//用户的token超时了
				$json['status']= 2;
				$json['usetime'] = endtime($start_time);
				$json['error'] = 'TokenTimeOut';
				$json['desc'] = "会话超时,请重新登录";
				$json_code = json_encode($json);
				echo $json_code;
				die();	
			}else{
				//再来的其它错误有可能
				//上传的图片作品是已上线的作品
				//上传者的userid和workid和uploadkey不符合
				$json['status']= 2;
				$json['usetime'] = endtime($start_time);
				$json['error'] = 'ErrorUpload:';
				$json['desc'] = "发生错误上传终止";
				$json_code = json_encode($json);
				echo $json_code;
				die();	
			}
		}
	}else{
		//如果没请求通
		//服务器问题
		$json['status']= 2;
		$json['usetime'] = endtime($start_time);
		$json['error'] = 'ServerError';
		$json['desc'] = "额服务器开小差了,请稍后重试...";
		$json_code = json_encode($json);
		echo $json_code;
		die();	
	}
	
	//至此成功上传一张图片
	 
	
	//结束
	$json['status']= 1;
	$json['usetime'] = endtime($start_time);
	$json['error'] = '';
	$json_code = json_encode($json);
	echo $json_code;
	
	//关闭连接
	if($connection)mysqli_close($connection);
	die();		
?>