<?php
/**
* @api {post} /gmspanel/interface/zh-cn/3.1/PCM_S_SkinList.php 获取皮肤列表
* @apiPermission pxseven
* @apiVersion 0.1.0
* @apiName SkinList
* @apiGroup Skin
* @apiSampleRequest http://ser3.graphmovie.com/gmspanel/interface/zh-cn/3.1/PCM_S_SkinList.php

* @apiDescription 客户端启动时，将会请求服务器拉取最新的皮肤列表，同本地保存的一个皮肤最大ID做比较，如果返回的皮肤列表中有大于本地ID的ID存在，则在皮肤的按钮图片上显示一个红点，未来该皮肤可以做宣发或者运营（如征稿）使用。

* @apiParam (POST) {String} [pk=""] 用户PC的机器码(MAC地址)
* @apiParam (POST) {Integer} [v=0] 用户GraphMovieStudios的内部版本号
*
* @apiSuccess (ResponseJSON) {Integer} status 接口响应状态（0-失败,1-成功,2-需要弹出提示框,提示desc内容）.
* @apiSuccess (ResponseJSON) {Float} usetime 接口响应时间,调试用.
* @apiSuccess (ResponseJSON) {String} error 接口响应出错时的错误描述.
* @apiSuccess (ResponseJSON) {String} debug 接口响应出错时的过程描述,调试用.
* @apiSuccess (ResponseJSON) {String} desc status=2时需要弹窗提示此内容.
* @apiSuccess (ResponseJSON) {Array[]} skins 皮肤列表的数组，一个个皮肤的结构体，按照优先级由高到低排序，也就是索引为0的排第1个.
* @apiSuccess (ResponseJSON) {Object} skins.skin 皮肤的结构体，在JSON中是不存在此key的，这里只是为了说明，具体可参见下面Success-Response中的示例.
* @apiSuccess (ResponseJSON) {Integer} skins.skin.id 皮肤的id，数组中并非索引越小id越大，数组中排列的顺序是按照优先级排列的.
* @apiSuccess (ResponseJSON) {String} skins.skin.title 皮肤的标题.
* @apiSuccess (ResponseJSON) {String} skins.skin.subtitle 皮肤的副标题.
* @apiSuccess (ResponseJSON) {String} skins.skin.bpic 皮肤的封面大图URL，目前皮肤的配置只含这一张图

*
* @apiSuccessExample Success-Response[记录成功]:

*     {
*       "status": 1,
*       "usetime": 0.0024,
*       "error": "",
*       "debug": "",
*       "desc": "",
*		"skins":[
*			{
*				"id":"1",
*				"title":"泰坦尼克号",
*				"subtitle":"推荐指数:9.5分",
*				"bpic":"http://imgs4.graphmovie.com/appimage/gms_skin/1.jpg"	
*			}
*		]
*     }

*
* @apiError PostError 请求的参数缺失或者参数格式错误.
* @apiError ServerError 服务器状态异常.

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

*/


	//config
	require_once(dirname(__FILE__).'/'.'inc/interface.json.inc.php');
	require_once(dirname(__FILE__).'/'.'inc/methods.inc.php');
	require_once(dirname(__FILE__).'/'.'inc/config.inc.php');
	
	//时间记录
	$start_time = starttime();
	
	//获取JSON基本模板
	$json = getBasicJsonModel();
	$json["desc"] = "";
	
	//初始化POST参数
	$post_pk = 'na';
	$post_v = 0;
	
	//获取Header
	//模拟请求采用的是request payload 发上来的参数
	$request_body = file_get_contents('php://input');
	$data = json_decode($request_body);
	if($data && count($data)>0 && (!isset($_POST) || count($_POST)==0) ){
		//curl提交的参数
		if(isset($data->pk)){
			$post_pk = htmlspecialchars(addslashes($data->pk));
		}
		
		if(isset($data->v)){
			$post_v = htmlspecialchars(addslashes($data->v));
		}

	}else{
		//获取参数
		if(isset($_POST['pk']) && strlen($_POST['pk'])>0){$post_pk = htmlspecialchars(addslashes($_POST['pk']));}
		if(isset($_POST['v'])){$post_v = htmlspecialchars(addslashes($_POST['v']));}
		
	}
	
	//链接数据库
	$connection = mysqli_connect(HOST,USER,PSD,DB);
	if(!$connection){ 
		//服务器问题
		$json['status']= 2;
		$json['usetime'] = endtime($start_time);
		$json['error'] = 'ServerError';
		$json['desc'] = "额服务器开小差了,请稍后重试...";
		$json_code = json_encode($json);
		echo $json_code;
		die();
	}
	mysqli_query($connection, "SET NAMES 'UTF8'");
	
	$json['skins'] = array();
	
	//查询皮肤列表
	//暂时不做分页
	$query = 'SELECT * FROM `pcmaker_skin` WHERE `open`=1 ORDER BY `weight` DESC ;';
	$result = mysqli_query($connection,$query);
	if($result){
		if(mysqli_num_rows($result)>0){
			//有
			$i=0;
			$rows_count = mysqli_num_rows($result);
			while($i<$rows_count){
				$record = mysqli_fetch_assoc($result);
				$skindata = json_decode($record['skin_data']);
				if(isset($skindata->bpic)){
					$json['skins'][count($json['skins'])] = array(
						"id"=>$record['id'],
						"title"=>$record['title'],
						"subtitle"=>$record['sub_title'],
						"bpic"=>$skindata->bpic
					);
				}
				$i++;
			}
		}
	}
	
	//OK
	if($connection)mysqli_close($connection);
	$json['status']= 1;
	$json['usetime'] = endtime($start_time);
	$json['error'] = '';
	$json['desc'] = "";
	$json_code = json_encode($json);
	echo $json_code;
	die();	
	
		
?>