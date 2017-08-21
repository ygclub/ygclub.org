<?php
/**
 * Created by PhpStorm.
 * User: yzg
 * Date: 2017/3/29
 * Time: 13:03
 */
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

$config = $_G['cache']['plugin']['xigua_login'];
$authkey        = $_G['config']['security']['authkey'];
$reg_nickname   = authcode($_GET['reg_nickname'], 'DECODE', $authkey);
$reg_headimgurl = authcode($_GET['reg_headimgurl'], 'DECODE', $authkey);
$reg_sid        = authcode($_GET['reg_sid'], 'DECODE', $authkey);
$openid         = authcode($_GET['openid'], 'DECODE', $authkey);

$fetch = C::t('#wechat#common_member_wechat')->fetch_by_openid($openid);

if(!$openid || !$fetch['uid']){
    showmessage('info empty!!!');
}
$uid = intval($fetch['uid']);

require_once libfile('function/member');
$member = getuserbyuid($uid, 1);
setloginstatus($member, 1296000);

C::t('common_member_status')->update($uid, array('lastip'=>$_G['clientip'], 'lastvisit'=>TIMESTAMP, 'lastactivity' => TIMESTAMP));
$ucsynlogin = '';
if($_G['setting']['allowsynlogin']) {
    loaducenter();
    $ucsynlogin = uc_user_synlogin($uid);
}

$url = $_GET['backreferer'] ? $_GET['backreferer']: $_G['siteurl'];

if($config['weret']){ $url = $config['weret']; }


if($ucsynlogin){
    loadcache('usergroup_'.$member['groupid']);
    $_G['group'] = $_G['cache']['usergroup_'.$member['groupid']];
    $_G['group']['grouptitle'] = $_G['cache']['usergroup_'.$_G['groupid']]['grouptitle'];
    $param = array('username' => $member['username'], 'usergroup' => $_G['group']['grouptitle']);
    if($config['showscmsg']){
        showmessage('login_succeed', $url, $param, array('extrajs' => $ucsynlogin));
    }
}
if(strpos($url, 'oauthbase=yes')!==false || strpos($url, 'oauth=yes')!==false){
    $url = $_G['siteurl'];
}
if($url_cookie = getcookie('page_front')){
    $url = $url_cookie;
}
if($_GET['custom_url']){
    dheader("Location: {$_GET['custom_url']}");
}else{
    dheader("Location: $url");
}