<?php

/** 
 *   [ygclub.org] (C)北京LEAD阳光志愿者俱乐部
 *
 *   @author leeyupeng
 *   @email  leeyupeng@gmail.com
 */


if(!defined('IN_DISCUZ')) {
        exit('Access Denied');
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
