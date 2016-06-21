<?php
/**
* @api {post} /gmspanel/interface/zh-cn/3.1/PCM_W_UpdateWork.php 修改作品信息
* @apiPermission yongge
* @apiVersion 0.1.0
* @apiName UpdateWork
* @apiGroup Work
* @apiSampleRequest http://ser3.graphmovie.com/gmspanel/interface/zh-cn/3.1/PCM_W_UpdateWork.php

* @apiDescription 通过workid对作品进行修改，必须为用户自己的作品

 * @apiParam (POST) {String} [pk=""] 用户PC的机器码(MAC地址)
 * @apiParam (POST) {Integer} [v=0] 用户GraphMovieStudios的内部版本号
 * @apiParam (POST) {String} userid 用户的userid，必须为登录用户才可访问此接口
 * @apiParam (POST) {String} token 用户的登录时返回的token，用于验证用户是否合法
 * @apiParam (POST) {String} [workid=""] 用户正在创作作品的作品ID

 * @apiParam (POST) {String} movie_name 影片信息-作品名称
 * @apiParam (POST) {String} movie_direct 影片信息-导演名
 * @apiParam (POST) {String} movie_actor 影片信息-主要演员名
 * @apiParam (POST) {String} movie_score 影片信息-影片评分
 * @apiParam (POST) {String} movie_time 影片信息-上映年份
 * @apiParam (POST) {String} movie_countory 影片信息-上映地区
 * @apiParam (POST) {String} subtitle 影片信息-副标题
 * @apiParam (POST) {String} movie_bza 影片信息-编者按
 * @apiParam (POST) {String} movie_intro 影片信息-剧情简介
 * @apiParam (POST) {String} movie_type 影片信息-影片类型 恐怖惊悚等，多个类型之间可用、（顿号）,（逗号）' '(空格隔开)，接口不限制类型数量
 * @apiParam (POST) {String} movie_istv 影片信息-剧别 0-电影 1-单季剧 2-多季剧
 * @apiParam (POST) {String} [movie_snum=""] 影片信息-剧集-第几季
 * @apiParam (POST) {String} [movie_enum=""] 影片信息-剧集-第几集
 * @apiParam (POST) {String} bpic_md5 影片信息-640x460封面的图片的MD5值
 * @apiParam (POST) {String} spic_md5 影片信息-640x230封面的图片的MD5值
 * @apiParam (POST) {String} fpic_md5 影片信息-960x540封面的图片的MD5值

 * @apiSuccess (ResponseJSON) {Integer} status 接口响应状态（0-失败,1-成功,2-需要弹出提示框,提示desc内容）.
 * @apiSuccess (ResponseJSON) {Float} usetime 接口响应时间,调试用.
 * @apiSuccess (ResponseJSON) {String} error 接口响应出错时的错误描述.
 * @apiSuccess (ResponseJSON) {String} debug 接口响应出错时的过程描述,调试用.
 * @apiSuccess (ResponseJSON) {String} desc status=2时需要弹窗提示此内容.
 * @apiSuccess (ResponseJSON) {String} workid 生成的workid加密串,为32位MD5.

* @apiSuccess (ResponseJSON) {Integer} status 接口响应状态（0-失败,1-成功,2-需要弹出提示框,提示desc内容）.
* @apiSuccess (ResponseJSON) {Float} usetime 接口响应时间,调试用.
* @apiSuccess (ResponseJSON) {String} error 接口响应出错时的错误描述.
* @apiSuccess (ResponseJSON) {String} debug 接口响应出错时的过程描述,调试用.
* @apiSuccess (ResponseJSON) {String} desc status=2时需要弹窗提示此内容.
* @apiSuccess (ResponseJSON) {String} workid 操作成功返回该作品workid.

*
* @apiSuccessExample Success-Response[提交成功]:

*     {
*		"status": 1,
*		"usetime": "0.00605",
*		"error": "",
*		"debug": "na",
*		"desc": "",
*		"query": "UPDATE `pcmaker_work` SET `update_time`=now() ,`title`='修改测试' ,`tv_s_num`='1' ,`tv_e_num`='2' WHERE `work_key`='112ce812c55d7a8130e5438a3d828221'",
*		"workid": "112ce812c55d7a8130e5438a3d828221"
*	}

*
* @apiError PostError 请求的参数缺失或者参数格式错误.
* @apiError ServerError 服务器状态异常.
* @apiError TokenTimeOut 用户会话超时或非法.

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

*    TokenTimeOut:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "TokenTimeOut",
*       "debug": "",
*       "desc": "会话超时,请重新登录"
*     }
*

*    WorkIdWrongUser:

*     {
*       "status": 3,
*       "usetime": 0.0024,
*       "error": "WorkIdWrongUser",
*       "debug": "",
*       "desc": "不能操作别人的作品哦"
*     }


*   ErrorWorkId:

*  	{
*       "status": 4,
*       "usetime": 0.0024,
*       "error": "ErrorWorkId",
*       "debug": "",
*       "desc": "作品校验失败"
*  	}
*
*

*   WorkCantModify:

* 	{
*       "status": 5,
*       "usetime": 0.0024,
*       "error": "WorkCantModify",
*       "debug": "",
*       "desc": "作品已收录，无法修改"
*  	}
*
*
*

*   UpdateError:

* 	{
*       "status": 6,
*       "usetime": 0.0024,
*       "error": "UpdateError",
*       "debug": "",
*       "desc": "操作失败,请稍后重试..."
*  	}
*
*
*/


	//config
	require_once(dirname(__FILE__).'/'.'inc/interface.json.inc.php');
	require_once(dirname(__FILE__).'/'.'inc/methods.inc.php');
	require_once(dirname(__FILE__).'/'.'inc/config.inc.php');
	require_once(dirname(__FILE__).'/'.'inc/time.methods.inc.php');
	
	//时间记录
	$start_time = starttime();
	
	//获取JSON基本模板
	$json = getBasicJsonModel();
	$json["desc"] = "";
	
	//20160304 内测结束 
	/*------------------------------------------------------------------------------------------------------------------*/
	/*------------------------------------------------------------------------------------------------------------------*/
	/*------------------------------------------------------------------------------------------------------------------*/
/*	$json['status']= 2;
	$json['usetime'] = endtime($start_time);
	$json['error'] = 'TESTEND';
	$json['desc'] = '本轮内测结束，谢谢大家的踊跃参与！未完成的作品仍然可以继续编辑和投稿，但不能再创建新的作品啦！';
	$json_code = json_encode($json);
	echo $json_code;
	die();*/
	/*------------------------------------------------------------------------------------------------------------------*/
	/*------------------------------------------------------------------------------------------------------------------*/
	/*------------------------------------------------------------------------------------------------------------------*/
	
	//初始化POST参数
	$post_pk = 'na';
	$post_v = 0;
	$post_userid = '0';
	$post_token = '';
	
	//获取Header
	//模拟请求采用的是request payload 发上来的参数
	$request_body = file_get_contents('php://input');
	$data = json_decode($request_body);
	if($data && count($data)>0 && (!isset($_POST) || count($_POST)==0) && isset($data->userid) && strlen($data->userid)>0 && isset($data->token) && strlen($data->token)>0 && isset($data->workid) && strlen($data->workid)>0){
		//curl提交的参数
		if(isset($data->pk)){
			$post_pk = htmlspecialchars(addslashes($data->pk));
		}
		
		if(isset($data->v)){
			$post_v = htmlspecialchars(addslashes($data->v));
		}
		

			$post_userid = htmlspecialchars(userIdKeyDecode(addslashes($data->userid)));
			$post_token = htmlspecialchars(addslashes($data->token));
			$post_workid = htmlspecialchars(addslashes($data->workid));
			$post_operate = htmlspecialchars(addslashes($data->operate));
        if(isset($data->movie_name)){
            $post_movie_name = htmlspecialchars(addslashes($data->movie_name));
        }

        if(isset($data->movie_direct)){
            $post_movie_direct = htmlspecialchars(addslashes($data->movie_direct));
        }

        if(isset($data->movie_actor)){
            $post_movie_actor = htmlspecialchars(addslashes($data->movie_actor));
        }

        if(isset($data->movie_score)){
            $post_movie_score = htmlspecialchars(addslashes($data->movie_score));
        }

        if(isset($data->movie_time)){
            $post_movie_time = htmlspecialchars(addslashes($data->movie_time));
        }

        if(isset($data->movie_count)){
            $post_movie_count = htmlspecialchars(addslashes($data->movie_count));
        }

        if(isset($data->subtitle)){
            $post_subtitle = htmlspecialchars(addslashes($data->subtitle));
        }

        if(isset($data->movie_bza)){
            $post_movie_bza = htmlspecialchars(addslashes($data->movie_bza));
        }

        if(isset($data->movie_intro)){
            $post_movie_intro = htmlspecialchars(addslashes($data->movie_intro));
        }

        if(isset($data->movie_type)){
            $post_movie_type = htmlspecialchars(addslashes($data->movie_type));
        }

        if(isset($data->movie_istv)){
            $post_movie_istv = htmlspecialchars(addslashes($data->movie_istv));
        }

        if(isset($data->movie_snum)){
            $post_movie_snum = htmlspecialchars(addslashes($data->movie_snum));
        }

        if(isset($data->movie_enum)){
            $post_movie_enum = htmlspecialchars(addslashes($data->movie_enum));
        }

        if(isset($data->bpic_md5)){
            $post_bpic_md5 = htmlspecialchars(addslashes($data->bpic_md5));
        }

        if(isset($data->spic_md5)){
            $post_spic_md5 = htmlspecialchars(addslashes($data->spic_md5));
        }

        if(isset($data->fpic_md5)){
            $post_fpic_md5 = htmlspecialchars(addslashes($data->fpic_md5));
        }

        if(isset($data->dburl)){
            $post_dburl = htmlspecialchars(addslashes($data->dburl));
        }
	}else if(
		isset($_POST['userid']) && strlen($_POST['userid'])>0 &&
		isset($_POST['token']) && strlen($_POST['token'])>0 &&
		isset($_POST['workid']) && strlen($_POST['workid'])>0
		){
		//获取参数
		if(isset($_POST['pk']) && strlen($_POST['pk'])>0){$post_pk = htmlspecialchars(addslashes($_POST['pk']));}
		if(isset($_POST['v'])){$post_v = htmlspecialchars(addslashes($_POST['v']));}
		$post_userid = htmlspecialchars(userIdKeyDecode(addslashes($_POST['userid'])));
		$post_token = htmlspecialchars(addslashes($_POST['token']));
		$post_workid = htmlspecialchars(addslashes($_POST['workid']));
		if(isset($_POST['movie_name'])){
			$post_movie_name = htmlspecialchars(addslashes($_POST['movie_name']));
		}

		if(isset($_POST['movie_direct'])){
			$post_movie_direct = htmlspecialchars(addslashes($_POST['movie_direct']));
		}

		if(isset($_POST['movie_actor'])){
			$post_movie_actor = htmlspecialchars(addslashes($_POST['movie_actor']));
		}

		if(isset($_POST['movie_score'])){
			$post_movie_score = htmlspecialchars(addslashes($_POST['movie_score']));
		}

		if(isset($_POST['movie_time'])){
			$post_movie_time = htmlspecialchars(addslashes($_POST['movie_time']));
		}

		if(isset($_POST['movie_count'])){
			$post_movie_count = htmlspecialchars(addslashes($_POST['movie_count']));
		}

		if(isset($_POST['subtitle'])){
			$post_subtitle = htmlspecialchars(addslashes($_POST['subtitle']));
		}

		if(isset($_POST['movie_bza'])){
			$post_movie_bza = htmlspecialchars(addslashes($_POST['movie_bza']));
		}

		if(isset($_POST['movie_intro'])){
			$post_movie_intro = htmlspecialchars(addslashes($_POST['movie_intro']));
		}

		if(isset($_POST['movie_type'])){
			$post_movie_type = htmlspecialchars(addslashes($_POST['movie_type']));
		}

		if(isset($_POST['movie_istv'])){
			$post_movie_istv = htmlspecialchars(addslashes($_POST['movie_istv']));
		}

		if(isset($_POST['movie_snum'])){
			$post_movie_snum = htmlspecialchars(addslashes($_POST['movie_snum']));
		}

		if(isset($_POST['movie_enum'])){
			$post_movie_enum = htmlspecialchars(addslashes($_POST['movie_enum']));
		}

		if(isset($_POST['bpic_md5'])){
			$post_bpic_md5 = htmlspecialchars(addslashes($_POST['bpic_md5']));
		}

		if(isset($_POST['spic_md5'])){
			$post_spic_md5 = htmlspecialchars(addslashes($_POST['spic_md5']));
		}

		if(isset($_POST['fpic_md5'])){
			$post_fpic_md5 = htmlspecialchars(addslashes($_POST['fpic_md5']));
		}

		if(isset($_POST['dburl'])){
			$post_dburl = htmlspecialchars(addslashes($_POST['dburl']));
		}

	}else{
		//缺少参数
		$json['status']= 0;
		$json['usetime'] = endtime($start_time);
		$json['error'] = 'PostError';
		$json_code = json_encode($json);
		echo $json_code;
		die();
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
	
	//查询用户token是否合法
	//token 超时时间是3天
	$okdate = date("Y-m-d H:i:s",strtotime("-3 day"));;
	$query = 'SELECT * FROM `pcmaker_request_token` WHERE `token`=\''.$post_token.'\' AND `userid`=\''.$post_userid.'\' AND `add_time`>\''.$okdate.'\';';
	$result = mysqli_query($connection,$query);
	if($result){
		if(mysqli_num_rows($result)==0){
			//没找到 非法token
			//关闭连接
			if($connection)mysqli_close($connection);
			$json['status']= 2;
			$json['usetime'] = endtime($start_time);
			$json['error'] = 'TokenTimeOut';
			$json['desc'] = "会话超时,请重新登录";
			$json_code = json_encode($json);
			echo $json_code;
			die();
		}
	}
	
	//OK TOKEN 合法
//查询该userid和workid的配对是否存在
//查询该workid是否存在
$query = 'SELECT * FROM `pcmaker_work` WHERE `work_key`=\''.$post_workid.'\' LIMIT 1;';
//$json['query'] =$query;
$result = mysqli_query($connection,$query);
if($result){
	if(mysqli_num_rows($result)>0){
		//找到
		$work = mysqli_fetch_assoc($result);

		//检查状态是否是已上线作品

		if($work['state'] != 3) {
			if ($work['user_id'] == $post_userid) {
				//校验通过可以修改上线状态了
					$query = 'UPDATE `pcmaker_work` SET `update_time`=now()';
				if(strlen($post_movie_name)>0){
                    $query.=' ,`title`=\''.$post_movie_name.'\'';
				}

                if(strlen($post_movie_direct)>0){
                    $query.=' ,`author`=\''.$post_movie_direct.'\'';
                }

                if(strlen($post_movie_actor)>0){
                    $query.=' ,`actor`=\''.$post_movie_actor.'\'';
                }

                if(strlen($post_movie_score)>0){
                    $query.=' ,`score`=\''.$post_movie_score.'\'';
                }

                if(strlen($post_movie_time)>0){
                    $query.=' ,`showtime`=\''.$post_movie_time.'\'';
                }

                if(strlen($post_movie_count)>0){
                    $query.=' ,`page_count`=\''.$post_movie_count.'\'';
                }

                if(strlen($post_subtitle)>0){
                    $query.=' ,`sub_title`=\''.$post_subtitle.'\'';
                }

                if(strlen($post_movie_bza)>0){
                    $query.=' ,`editor_note`=\''.$post_movie_bza.'\'';
                }

                if(strlen($post_movie_intro)>0){
                    $query.=' ,`intro`=\''.$post_movie_intro.'\'';
                }

                if(strlen($post_movie_type)>0){
                    $query.=' ,`tags`=\''.$post_movie_type.'\'';
                }

//                剧别,单季剧or多季剧
                if(strlen($post_movie_istv)>0){
                    $query.=' ,`tv_type`=\''.$post_movie_istv.'\'';
                }

                if(strlen($post_movie_snum)>0){
                    $query.=' ,`tv_s_num`=\''.$post_movie_snum.'\'';
                }

                if(strlen($post_movie_enum)>0){
                    $query.=' ,`tv_e_num`=\''.$post_movie_enum.'\'';
                }

                if(strlen($post_bpic_md5)>0){
                    $query.=' ,`bpic_md5`=\''.$post_bpic_md5.'\'';
                }

                if(strlen($post_spic_md5)>0){
                    $query.=' ,`spic_md5`=\''.$post_spic_md5.'\'';
                }

                if(strlen($post_fpic_md5)>0){
                    $query.=' ,`firstpage_md5`=\''.$post_fpic_md5.'\'';
                }


				$query.=' WHERE `work_key`=\''.$post_workid.'\'';
				$json['query'] = $query;
				$res = mysqli_query($connection,$query);

				if(!$res){
					//服务器问题
					$json['status']= 6;
					$json['usetime'] = endtime($start_time);
					$json['error'] = 'UpdateError';
					$json['desc'] = "操作失败,请稍后重试...";
					$json_code = json_encode($json);
					echo $json_code;
					die();
				}else{
					$json['workid'] = $post_workid;
				}



			} else {
				//关闭连接
				if($connection)mysqli_close($connection);
				$json['status']= 3;
				$json['usetime'] = endtime($start_time);
				$json['error'] = 'WorkIdWrongUser';
				$json['desc'] = '不能操作别人的作品哦';
				$json_code = json_encode($json);
				echo $json_code;
				die();
			}
		}else{
			//关闭连接
			if($connection)mysqli_close($connection);
			$json['status']= 5;
			$json['usetime'] = endtime($start_time);
			$json['error'] = 'WorkCantModify';
			$json['desc'] = "作品已收录无法修改";
			$json_code = json_encode($json);
			echo $json_code;
			die();
		}
	}else{
		//没找到
		//关闭连接
		if($connection)mysqli_close($connection);
		$json['status']= 4;
		$json['usetime'] = endtime($start_time);
		$json['error'] = 'ErrorWorkId';
		$json['desc'] = "作品校验失败";
		$json_code = json_encode($json);
		echo $json_code;
		die();
	}
}
	
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