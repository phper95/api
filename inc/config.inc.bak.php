<?php
define('DB','graphmovie_db');

define('USER','zhiyou007');
define('PSD','zhiyou007_mobi');
//define('HOST','113.107.88.31');
define('HOST','graphlocal.mysql.rds.aliyuncs.com');
/*
define('USER','pxseven');
define('PSD','pxseven');
define('HOST','192.168.1.131');
*/

//MemCached
define('MEM_HOST','127.0.0.1');
define('MEM_POST','11211');

//Logger
define('LOGGER_ROOT',dirname(__FILE__).'/'.'../../../../../logger/');
define('CKUSER_LOGGER_ROOT',dirname(__FILE__).'/'.'../../../../../ckuser_logger/');
define('SNSSIGNIN_LOGGER_ROOT',dirname(__FILE__).'/'.'../../../../../snssignin_logger/');
define('EMAILLOGIN_LOGGER_ROOT',dirname(__FILE__).'/'.'../../../../../emaillogin_logger/');


//bspic url
define('BSPIC_PREURL','http://avatar.graphmovie.com/boo/');

//配置默认角色 默认心情 默认头像
define('USERDEFAULT_ROLE','神秘人');
define('USERDEFAULT_ROLE_MASTER','究极大BOSS');
define('USERDEFAULT_FEELING','因为太个性，所以没签名');
define('USERDEFAULT_AVATARBG','http://imgs4.graphmovie.com/appimage/avatar_bg/avatarbg_default.jpg');

define('COMMENT_REPAIRED',FALSE);
define('ANDROID_SUPPORT_FLOOR_VER',37);

//加密密钥
define('MD_KEY', '123#@!123');

?>
