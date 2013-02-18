<?php

/** 
 *   [ygclub.org] (C)北京LEAD阳光志愿者俱乐部
 *
 *   该页用于处理前台针对活动的所有请求
 *
 *   @author leeyupeng
 *   @email  leeyupeng@gmail.com
 */


if(!defined('IN_DISCUZ')) {
        exit('Access Denied');
}

define('NOROBOT', TRUE);

if($_POST['action'] == 'partyapplies')
{
	if(!$_G['uid']) {
		showmessage('not_loggedin', NULL, array(), array('login' => 1));
    }
    if(submitcheck('partysubmit')) {

        if(empty($_POST['phone']))
        {
            showmessage('ygclub_party:phone_not_valid', NULL, array());
        }
        $insertData = array(
            'tid' => $_POST['tid'],
            'uid' => $_G['uid'],
            'username' => $_G['username'],
            'phone' => $_POST['phone'],
            'verified' => 4,
            'dateline' => $_G['timestamp'],
            'message' => $_POST['message'],
            'usertask' => $_POST['usertask'],
        );

        C::t('#ygclub_party#partyers')->insert($insertData);

        showmessage('partyapplies_succeed', 'forum.php?mod=viewthread&tid='.$_POST['tid'], array(), array('showdialog' => true, 'locationtime' => true));
    }
}

class plugin_ygclub_party {
    public function __construct() {
        $this->plugin_ygclub_party();
    }

    public function plugin_ygclub_party() {
    }
}

class plugin_ygclub_party_forum extends plugin_ygclub_party {
    function viewthread_title_extra() {
        global $_G;
        $lang = lang('plugin/party');
        return '<a href="party.php?act=complete&tid=' . $_G['tid'] . '"><img src="static/image/party/add-party.gif" border="0" align="absmiddle" alt="' . $lang['consummate_party_data'] . '" /></a>';
    } 
}
?>
