<?php
/**
 * Created by PhpStorm.
 * User: yangzhiguo
 * Date: 15/7/18
 * Time: 20:02
 */
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}
require_once DISCUZ_ROOT.'./source/plugin/wechat/wechat.lib.class.php';
include_once libfile('function/forumlist');
$wechat = unserialize($_G['setting']['mobilewechat']);

//function xgl($lang){
//    return lang('plugin/xigua_navigation_wsq', $lang);
//}
echo <<<HTML
<style>
#tips1lis li{display:block!important;}
#tips1lis li#tips1_more{display:none!important;}
#tips1lis img{vertical-align: middle;}
#tips1lis img.w{height:24px;vertical-align: middle;}
#tips1lis .b{margin-right:5px;color:#578CC0;font-weight:bolder}
#tips1lis .g{margin-right:5px;color:forestgreen;font-weight:bolder}
#tips1lis .t{margin-right:5px;color:orangered;font-weight:bolder}
#tips1lis .n{margin-right:5px;color:black;font-weight:bolder}
</style>
HTML;

$message = str_replace(
    array('{url}'),
    array($_G['siteurl'].'member.php?mod=logging&action=login&mobile=2'),
    lang('plugin/xigua_login', 'notice1'));
showtips($message, 'tips1', TRUE);

