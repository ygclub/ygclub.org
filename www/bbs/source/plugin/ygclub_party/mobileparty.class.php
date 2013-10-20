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

include_once('thread.class.php');

class mobileplugin_ygclub_party {
    public function __construct() {
        $this->mobileplugin_ygclub_party();
    }

    public function mobileplugin_ygclub_party() {
    }
}

class mobileplugin_ygclub_party_forum extends mobileplugin_ygclub_party {

    function viewthread_postbottom_mobile() {
        global $_G;
        if($_GET['page']  > 1) return array(0=>'');
        include_once template('ygclub_party:party_info');
        include_once template('ygclub_party:party_sign');

        $party_thread = new threadplugin_ygclub_party();
        $party = $party_thread->_getpartyinfo($_G['tid']);
        if(!$party['tid']) return array('');

        $partyer = C::t('#ygclub_party#partyers')->fetch_by_uid_tid($_G['uid'], $party['tid']);

        $condata = $party_thread->_load_forumparty_condata($_G['fid']);
        return array(tpl_ygclub_party_sign());
        return array(tpl_ygclub_party_partyers_list() . tpl_ygclub_party_sign());
    }

    function viewthread_title_extra() {
        return 'fffffffffffffffffffff';
    }
}
?>
