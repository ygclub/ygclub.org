<?php

/** 
 *   [ygclub.org] (C)北京LEAD阳光志愿者俱乐部
 *
 *   @author leeyupeng
 *   @email  leeyupeng@gmail.com
 */

include_once('thread.class.php');

class plugin_ygclub_party {
    public function __construct() {
        $this->plugin_ygclub_party();
    }

    public function plugin_ygclub_party() {
    }
}

class plugin_ygclub_party_forum extends plugin_ygclub_party {

    function viewthread_postbottom() {
        global $_G;
        include_once template('ygclub_party:party_info');
        include_once template('ygclub_party:party_sign');

        $party_thread = new threadplugin_ygclub_party();
        $party = $party_thread->_getpartyinfo($_G['tid']);
        if(!$party['tid']) return '';

        $condata = $party_thread->_load_forumparty_condata($_G['fid']);
        return array(tpl_ygclub_party_partyers_list() . tpl_ygclub_party_sign());
    }
}
?>
