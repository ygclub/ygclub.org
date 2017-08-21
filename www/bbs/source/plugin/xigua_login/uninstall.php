<?php
/**
 * Created by PhpStorm.
 * User: yangzhiguo
 * Date: 15/6/27
 * Time: 13:51
 */

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}



$finish = TRUE;

@unlink(DISCUZ_ROOT . './source/plugin/xigua_login/discuz_plugin_xigua_login.xml');
@unlink(DISCUZ_ROOT . './source/plugin/xigua_login/discuz_plugin_xigua_login_SC_GBK.xml');
@unlink(DISCUZ_ROOT . './source/plugin/xigua_login/discuz_plugin_xigua_login_SC_UTF8.xml');
@unlink(DISCUZ_ROOT . './source/plugin/xigua_login/discuz_plugin_xigua_login_TC_BIG5.xml');
@unlink(DISCUZ_ROOT . './source/plugin/xigua_login/discuz_plugin_xigua_login_TC_UTF8.xml');
@unlink(DISCUZ_ROOT . './source/plugin/xigua_login/install.php');
@unlink(DISCUZ_ROOT . './source/plugin/xigua_login/login.class.php');
@unlink(DISCUZ_ROOT . './source/plugin/xigua_login/api.class.php');
@unlink(DISCUZ_ROOT . './source/plugin/xigua_login/uninstall.php');
