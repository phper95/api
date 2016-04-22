// ------------------------------------------------------------------------------------------
// General apiDoc documentation blocks and old history blocks.
// ------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------
// Current Success.
// ------------------------------------------------------------------------------------------


// ------------------------------------------------------------------------------------------
// Current Errors.
// ------------------------------------------------------------------------------------------


// ------------------------------------------------------------------------------------------
// Current Permissions.
// ------------------------------------------------------------------------------------------


// ------------------------------------------------------------------------------------------
// History.
// ------------------------------------------------------------------------------------------

/**
* @api {post} /gmspanel/interface/zh-cn/3.1/PCM_L_QrcodeCkLogin.php 二维码扫描登录
* @apiPermission pxseven
* @apiVersion 0.2.0
* @apiName QrcodeCkLogin
* @apiGroup User
* @apiSampleRequest http://ser3.graphmovie.com/gmspanel/interface/zh-cn/3.1/PCM_L_QrcodeCkLogin.php

* @apiDescription 客户端上报二维码Key后定时请求该接口检查是否有App扫描了该二维码，如果成功配对则返回同EmailLogin接口同样的登录信息

* @apiParam (POST) {String} [pk=""] 用户PC的机器码(MAC地址)
* @apiParam (POST) {Integer} [v=0] 用户GraphMovieStudios的内部版本号
* @apiParam (POST) {String} qrcode 需要检查的二维码字串(32位).
*
* @apiDescription 返回结果同<code>EmailLogin</code>接口是一样的，只是错误状态多了3个<code>NoMatch</code>,<code>TimeOut</code>和<code>QRCodeInvalid</code>

* @apiSuccess (ResponseJSON) {Integer} status 接口响应状态（0-失败,1-成功,2-需要弹出提示框,提示desc内容）.
* @apiSuccess (ResponseJSON) {Float} usetime 接口响应时间,调试用.
* @apiSuccess (ResponseJSON) {String} error 接口响应出错时的错误描述.
* @apiSuccess (ResponseJSON) {String} debug 接口响应出错时的过程描述,调试用.
* @apiSuccess (ResponseJSON) {String} desc status=2时需要弹窗提示此内容.
* @apiSuccess (ResponseJSON) {String} token 服务器返回的token通信凭证,登录后请求其他接口必须带上token加密后的字串来通过验证.
* @apiSuccess (ResponseJSON) {Object} opt  根据用户情况,下一步的操作.
* @apiSuccess (ResponseJSON) {String} opt.name  进入主面板:main/验证邮箱:ckemail/签署作者协议:signap/提示签约并进入主面板:signvip
* @apiSuccess (ResponseJSON) {String} opt.data  操作数据
* @apiSuccess (ResponseJSON) {String} opt.data.title  当操作为signap时,该title为显示在窗口内的标题内容
* @apiSuccess (ResponseJSON) {String} opt.data.url  当操作为signap或signvip时,该url新协议的URL地址
* @apiSuccess (ResponseJSON) {String} opt.data.msg  当操作为signvip时,该msg为弹出的提示窗中显示的信息
* @apiSuccess (ResponseJSON) {Object} user  用户信息(仅当opt不为"ckemail"时返回).
* @apiSuccess (ResponseJSON) {String} user.userid  用户的userid,经过加密.
* @apiSuccess (ResponseJSON) {String} user.nickname  昵称.
* @apiSuccess (ResponseJSON) {String} user.feeling  心情签名.
* @apiSuccess (ResponseJSON) {String} user.avatar  头像.
* @apiSuccess (ResponseJSON) {String} user.role  身份角色.
* @apiSuccess (ResponseJSON) {String} user.vip  是否签约大V(0-没有,1-是).
* @apiSuccess (ResponseJSON) {String} user.level  等级.
* @apiSuccess (ResponseJSON) {Integer} user.lvexp  当前级的经验原点.
* @apiSuccess (ResponseJSON) {Integer} user.currexp  当前经验.
* @apiSuccess (ResponseJSON) {Integer} user.nextexp  下一级经验(本级经验进度=(nextexp-currexp)/(nextexp-lvexp)).
* @apiSuccess (ResponseJSON) {String} user.email  用户邮箱地址.
* @apiSuccess (ResponseJSON) {Integer} user.gold  用户金币数目.
* @apiSuccess (ResponseJSON) {String} user.beplayed  用户作品被阅读数目.
* @apiSuccess (ResponseJSON) {String} user.belike  用户作品获赞数目.
* @apiSuccess (ResponseJSON) {String} user.fans  用户的粉丝数目.
* @apiSuccess (ResponseJSON) {String} user.follow  用户的关注数目.
* @apiSuccess (ResponseJSON) {Integer} user.work_ing  用户创作中作品数目(云端).
* @apiSuccess (ResponseJSON) {Integer} user.work_online  用户已上线未审核收录作品数目.
* @apiSuccess (ResponseJSON) {Integer} user.work_cked  用户已审核收录作品数目.
* @apiSuccess (ResponseJSON) {Integer} user.work_offline  用户已下线作品数目.
* @apiSuccess (ResponseJSON) {Integer} user.newmsg_count  用户未读消息计数.

*
* @apiSuccessExample Success-Response:

*     直接登录入主面板(opt.name="main"):

*     {
*       "status": 1,
*       "usetime": 0.0024,
*       "error": "",
*       "debug": "",
*       "desc": "",
*		"token": "df60d89def855874",
*       "opt": {
*			"name":"main",
*			"data":{}
*		},
*       "user": {
*			"userid": "9999999H",
*       	"nickname": "佳怡生活",	
*			"feeling": "最近心情不错",	
*       	"avatar": "http://tp1.sinaimg.cn/1750477504/180/40036623679/1",	
*       	"role": "菜鸟",	
*       	"vip": 1,	
*       	"level": "Lv25",
*       	"lvexp": 1000,	
*       	"currexp": 1500,	
*       	"nextexp": 2000,	
*       	"email": "12345678@qq.com",
*       	"gold": 2800,	
*       	"beplayed": "39.4万",
*       	"belike": "2.9万",
*       	"fans": "1.1万",
*       	"follow": "60",
*       	"work_ing": 4,
*       	"work_online": 4,
*       	"work_cked": 4,
*       	"work_offline": 4,
*       	"newmsg_count": 4
*		}
*     }

*     需要验证邮箱(opt.name="ckemail"):

*     {
*       "status": 1,
*       "usetime": 0.0024,
*       "error": "",
*       "debug": "",
*       "desc": "",
*		"token": "df60d89def855874",
*       "opt": {
*			"name":"ckemail",
*			"data":{
*				"title":"",
*				"url":"",
*				"msg":""
*			}
*		},
*       "user": {
*			"userid": "9999999H",
*       	"nickname": "佳怡生活",		
*			"feeling": "最近心情不错",
*       	"avatar": "http://tp1.sinaimg.cn/1750477504/180/40036623679/1",	
*       	"role": "菜鸟",	
*       	"vip": 1,	
*       	"level": "Lv25",
*       	"lvexp": 1000,	
*       	"currexp": 1500,	
*       	"nextexp": 2000,	
*       	"email": "12345678@qq.com",
*       	"gold": 2800,	
*       	"beplayed": "39.4万",
*       	"belike": "2.9万",
*       	"fans": "1.1万",
*       	"follow": "60",
*       	"work_ing": 4,
*       	"work_online": 4,
*       	"work_cked": 4,
*       	"work_offline": 4,
*       	"newmsg_count": 4
*		}
*     }

*     协议有更新,需要签署投稿守则(opt.name="signap"):

*     {
*       "status": 1,
*       "usetime": 0.0024,
*       "error": "",
*       "debug": "",
*       "desc": "",
*		"token": "df60d89def855874",
*		"opt": {
*			"name":"signap",
*			"data":{
*				"title":"图解电影投稿守则",
*				"url":"http://graphmovie.com",
*				"msg":""
*			}
*		},
*       "user": {
*			"userid": "9999999H",
*       	"nickname": "佳怡生活",		
*			"feeling": "最近心情不错",
*       	"avatar": "http://tp1.sinaimg.cn/1750477504/180/40036623679/1",	
*       	"role": "菜鸟",	
*       	"vip": 1,	
*       	"level": "Lv25",
*       	"lvexp": 1000,	
*       	"currexp": 1500,	
*       	"nextexp": 2000,	
*       	"email": "12345678@qq.com",
*       	"gold": 2800,	
*       	"beplayed": "39.4万",
*       	"belike": "2.9万",
*       	"fans": "1.1万",
*       	"follow": "60",
*       	"work_ing": 4,
*       	"work_online": 4,
*       	"work_cked": 4,
*       	"work_offline": 4,
*       	"newmsg_count": 4
*		}
*     }

*     符合签约标准提示签约并进入主窗口(opt.name="signvip"):

*     {
*       "status": 1,
*       "usetime": 0.0024,
*       "error": "",
*       "debug": "",
*       "desc": "",
*		"token": "df60d89def855874",
*		"opt": {
*			"name":"signap",
*			"data":{
*				"title":"",
*				"url":"http://graphmovie.com",
*				"msg":"经过编辑部的一致认同，您已符合签约资格！是否立刻加入我们呢？"
*			}
*		},
*       "user": {
*			"userid": "9999999H",
*       	"nickname": "佳怡生活",	
*			"feeling": "最近心情不错",
*       	"avatar": "http://tp1.sinaimg.cn/1750477504/180/40036623679/1",	
*       	"role": "菜鸟",	
*       	"vip": 1,	
*       	"level": "Lv25",
*       	"lvexp": 1000,	
*       	"currexp": 1500,	
*       	"nextexp": 2000,	
*       	"email": "12345678@qq.com",
*       	"gold": 2800,	
*       	"beplayed": "39.4万",
*       	"belike": "2.9万",
*       	"fans": "1.1万",
*       	"follow": "60",
*       	"work_ing": 4,
*       	"work_online": 4,
*       	"work_cked": 4,
*       	"work_offline": 4,
*       	"newmsg_count": 4
*		}
*     }

*
* @apiError PostError 请求的参数缺失或者参数格式错误.
* @apiError UserNotFound 没有找到给定<code>email</code>的用户账户.
* @apiError PasswordInvalid 密码<code>pwd</code>错误.
* @apiError UserInvalid 该用户账户已被冻结.
* @apiError ServerError 服务器状态异常.
* @apiError NoMatch 尚未配对成功.
* @apiError TimeOut 该二维码已过期(一个小时没人扫描).
* @apiError QRCodeInvalid 非法二维码.

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

*     UserNotFound:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "UserNotFound",
*       "debug": "",
*       "desc": "没有这个用户哎"
*     }

*     PasswordInvalid:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "PasswordInvalid",
*       "debug": "",
*       "desc": "密码错了..."
*     }

*     UserInvalid:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "UserInvalid",
*       "debug": "",
*       "desc": "该账户被冻结,自个儿说说你干了什么坏事吧"
*     }

*     ServerError:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "ServerError",
*       "debug": "",
*       "desc": "额服务器开小差了,请稍后重试..."
*     }

*     NoMatch:

*     {
*       "status": 0,
*       "usetime": 0.0024,
*       "error": "NoMatch",
*       "debug": "",
*       "desc": ""
*     }

*     TimeOut:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "TimeOut",
*       "debug": "",
*       "desc": "该二维码已过期，请重新扫描一下吧！"
*     }

*     QRCodeInvalid:

*     {
*       "status": 2,
*       "usetime": 0.0024,
*       "error": "QRCodeInvalid",
*       "debug": "",
*       "desc": "错误的二维码信息。"
*     }

*/