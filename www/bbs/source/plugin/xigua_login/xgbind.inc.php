<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/11
 * Time: 1:42
 */

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
$_G['wechatuser'] = $fetch = C::t('#wechat#common_member_wechat')->fetch($_G['uid']);
$tiptip = lang('plugin/xigua_login', 'tiptip');

define('QRCODE_EXPIRE', 1800);

$inspacecp = true;
$rfr = $_GET['rfr'] ? $_GET['rfr'] : dreferer();

if(submitcheck('resetpwsubmit') && $_G['wechatuser']['isregister']) {
    if($_G['setting']['strongpw']) {
        $strongpw_str = array();
        if(in_array(1, $_G['setting']['strongpw']) && !preg_match("/\d+/", $_GET['newpassword1'])) {
            $strongpw_str[] = lang('member/template', 'strongpw_1');
        }
        if(in_array(2, $_G['setting']['strongpw']) && !preg_match("/[a-z]+/", $_GET['newpassword1'])) {
            $strongpw_str[] = lang('member/template', 'strongpw_2');
        }
        if(in_array(3, $_G['setting']['strongpw']) && !preg_match("/[A-Z]+/", $_GET['newpassword1'])) {
            $strongpw_str[] = lang('member/template', 'strongpw_3');
        }
        if(in_array(4, $_G['setting']['strongpw']) && !preg_match("/[^a-zA-z0-9]+/", $_GET['newpassword1'])) {
            $strongpw_str[] = lang('member/template', 'strongpw_4');
        }
        if($strongpw_str) {
            showmessage(lang('member/template', 'password_weak').implode(',', $strongpw_str));
        }
    }
    if($_GET['newpassword1'] !== $_GET['newpassword2']) {
        showmessage('profile_passwd_notmatch');
    }
    if(!$_GET['newpassword1'] || $_GET['newpassword1'] != addslashes($_GET['newpassword1'])) {
        showmessage('profile_passwd_illegal');
    }

    loaducenter();
    uc_user_edit(addslashes($_G['member']['username']), null, $_GET['newpassword1'], null, 1);

    C::t('common_member')->update($_G['uid'], array('password' => md5(random(10))));

    C::t('#wechat#common_member_wechat')->update($_G['uid'], array('isregister' => 0));

    showmessage('wechat:wsq_password_reset', $rfr);
} elseif(submitcheck('unbindsubmit')) {
    require_once libfile('function/member');

    C::t('#wechat#common_member_wechat')->delete($_G['uid']);

//    clearcookies();
    showmessage('wechat:wechat_message_unbinded', $rfr);
}

if(checkmobile()){
    include_once template('xigua_login:xgbind');
    exit;
}