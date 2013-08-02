<?php
/******************************************/
define('UC_CONNECT', 'uc_api_post');
define('UC_DBHOST', 'localhost');
define('UC_DBUSER', '');
define('UC_DBPW', '');
define('UC_DBNAME', '');
define('UC_DBCHARSET', 'utf8');
define('UC_DBTABLEPRE', 'ygclub_');
define('UC_DBCONNECT', '0');
define('UC_KEY', '123456789');
define('UC_API', 'http://www.ygclub.org/uc');
define('UC_CHARSET', 'utf-8');
define('UC_IP', '127.0.0.1');
define('UC_APPID', '12');
define('UC_PPP', '20');
/******************************************/

//用到的应用程序数据库连接参数
$dbhost = UC_DBHOST;			// 数据库服务器
$dbuser = UC_DBUSER;			// 数据库用户名
$dbpw = UC_DBPW;				// 数据库密码
$dbname = UC_DBNAME;			// 数据库名
$pconnect = UC_DBCONNECT;				// 数据库持久连接 0=关闭, 1=打开
$tablepre = UC_DBTABLEPRE;   		// 表名前缀, 同一数据库安装多个论坛请修改此处
$dbcharset = UC_CHARSET;			// MySQL 字符集, 可选 'gbk', 'big5', 'utf8', 'latin1', 留空为按照论坛字符集设定

