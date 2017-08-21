<?php
/**
 * Created by PhpStorm.
 * User: yzg
 * Date: 2016/6/20
 * Time: 14:48
 */
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
//ini_set('display_errors', 1);
//error_reporting(E_ALL ^ E_NOTICE);

include_once libfile('function/cache');
include_once libfile('function/member');
@include_once DISCUZ_ROOT.'./source/plugin/mobile/qrcode.class.php';
include_once DISCUZ_ROOT. 'source/plugin/wechat/wechat.lib.class.php';
include_once DISCUZ_ROOT.'source/plugin/xigua_login/function.php';

if(!function_exists('filterEmoji12')){
    function filterEmoji12($str){
        $str = preg_replace_callback( '/./u', 'match412', $str);
        return $str;
    }
}

$config = $_G['cache']['plugin']['xigua_login'];

if(!$config['bgcolor']){
    $config['bgcolor'] ='#f5f5f5';
}
if(!$config['logincolor']){
    $config['logincolor'] = '#EA4F15';
}
if($config['radius']){
    $rds = intval($config['radius']).'px';
    $radius = ".form-control input, .form-control select,.btn{border-radius:$rds}";
}
if ($config['opacity']) {
    $opacity = $config['opacity']/100;
    $opacity = ".login_from{opacity:$opacity}";
}
if($config['logincolor']){
    $logincolor = ".btn-outline{background-color:$config[logincolor]}";
}
if($config['onekeycolor']){
    $onekeycolor = ".btn-orange{background-color:$config[onekeycolor]}";
}
if($config['bgimg']){
    if($zTopbg = array_filter(explode("\n", trim($config['bgimg']))) ){
        $_k = array_rand($zTopbg, 1);
        $topbg = trim($zTopbg[$_k]);
    }

    $bgimg = "html,body{background:url($topbg) no-repeat center center;background-size:cover}";
}
if($config['regcolor']){
    $regcolor = ".reg_link, .reg_link a, .tiph{font-size:14px;color:$config[regcolor]}";
}

$customstyle = "html,body{background-color:$config[bgcolor];}$radius$opacity$logincolor$onekeycolor$bgimg$regcolor";

$authkey = $_G['config']['security']['authkey'];
define('WX_APPID', trim($config['appid']));
define('WX_APPSECRET', trim($config['appsecert']));
define('IN_WECHAT', strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false);

$cookie = substr(md5('wechatdd'.$_G['siteurl'].WX_APPID), 0, 8);

$url = getcookie('backreferer');
if(strpos($url, 'xigua_login')!==false || strpos($url, 'login')!==false || strpos($url, 'reg')!==false){
    $url = $_G['siteurl'];
}
if(!$url){
    $url = $_G['siteurl'];
}
if($config['weret']){ $url = $config['weret']; }

if($_GET['openid']&& $_GET['reg_nickname']&& $_GET['reg_headimgurl']&& $_GET['reg_sid']){
    dsetcookie('reg_nickname', $_GET['reg_nickname'], 7100);
    dsetcookie('reg_headimgurl', $_GET['reg_headimgurl'], 7100);
    dsetcookie('reg_sid', $_GET['reg_sid'] , 7100);
    dsetcookie($cookie, $_GET['openid'], 7100);
}
$reg_nickname   = authcode(getcookie('reg_nickname'), 'DECODE', $authkey);
$reg_headimgurl = authcode(getcookie('reg_headimgurl'), 'DECODE', $authkey);
$reg_sid        = authcode(getcookie('reg_sid'), 'DECODE', $authkey);
$openid         = authcode($_G['cookie'][$cookie], 'DECODE', $authkey);

if($reg_sid){
$arow = C::t('#wechat#mobile_wechat_authcode')->fetch($reg_sid);
if($arow['status']==1||!$arow){
    showmessage(lang('plugin/xigua_login', 'link_error'), $url);
}}

if(!$openid){
    showmessage('openid empty!!!');
}
if(!$reg_nickname){
//    showmessage('username empty!');
}

$navtitle1 = $config['newbtn'];
$tip1      = $config['newtip'];
$navtitle2 = $config['bindexbtn'];
$tip2      = $config['bindexists'];

if(!submitcheck('reg')){
    if($_GET['rb']==0){
        $navtitle = $navtitle2;
        $tip = $tip2;
    }else{
        $navtitle = $navtitle1;
        $tip = $tip1;
    }
    include template('xigua_login:reg');
}else{
    $_GET['username'] = filterEmoji12($_GET['username']);
    $username = trim($_GET['username']);
    $userinfo['nickname'] = str_replace(array(" ", "\t", "\n", "\r"), '', $username);

    if(!$_GET['rb']) {  //直接注册并登录
        $uid = xg_register($username, $reg_headimgurl, 0);
        if($uid <= 0) {
            if($uid == -1) {
                showmessage('profile_username_illegal');
            } elseif($uid == -2) {
                showmessage('profile_username_protect');
            } elseif($uid == -3) {
                showmessage('profile_username_duplicate');
            } elseif($uid == -4) {
                showmessage('profile_email_illegal');
            } elseif($uid == -5) {
                showmessage('profile_email_domain_illegal');
            } elseif($uid == -6) {
                showmessage('profile_email_duplicate');
            } else {
                showmessage($uid.'_undefined_action');
            }
        }
        if ($uid>0) {

            if($_GET['password']){
                loaducenter();
                if($_GET['password'] != addslashes($_GET['password'])) {
                    showmessage('profile_passwd_illegal');
                }
                $ucresult = uc_user_edit(addslashes($username), '', $_GET['password'], '', 1);
            }

            WeChatHook::bindOpenId($uid, $openid, 1);
            C::t('#wechat#mobile_wechat_authcode')->update($reg_sid, array('uid' => $uid, 'status' => 1));
            $member = getuserbyuid($uid, 1);
            setloginstatus($member, 1296000);
            $url = str_replace('-9999', $uid, $url);
            dheader("Location: $url");
        }
    }else{  //绑定已有帐号
        if(!($loginperm = logincheck($_GET['username']))) {
            showmessage('login_strike');
        }
        if(!$_GET['password'] || $_GET['password'] != addslashes($_GET['password'])) {
            showmessage('profile_passwd_illegal');
        }

        $result = userlogin($_GET['username'], $_GET['password'], $_GET['questionid'], $_GET['answer'], $_G['setting']['autoidselect'] ? 'auto' : $_GET['loginfield'], $_G['clientip']);

        if($result['status'] <= 0) {
            loginfailed($_GET['username']);
            failedip();
            showmessage('login_invalid', '', array('loginperm' => $loginperm - 1));
        }
        $uid = $result['member']['uid'];
        if($uid>0){
            WeChatHook::bindOpenId($uid, $openid, 1);
            C::t('#wechat#mobile_wechat_authcode')->update($reg_sid, array('uid' => $uid, 'status' => 1));

            $member = getuserbyuid($uid, 1);
            setloginstatus($member, 1296000);
            $url = str_replace('-9999', $uid, $url);
            dheader("Location: $url");
        }else{
            showmessage('login_invalid', '', array('loginperm' => $loginperm - 1));
        }

    }
}
