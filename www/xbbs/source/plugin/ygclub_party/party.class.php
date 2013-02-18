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
    /*
    function viewthread_title_extra() {
        global $_G;
        $lang = lang('plugin/party');
        return '<a href="party.php?act=complete&tid=' . $_G['tid'] . '"><img src="static/image/party/add-party.gif" border="0" align="absmiddle" alt="' . $lang['consummate_party_data'] . '" /></a>';
    }
     */

    function viewthread_postbottom() {
        global $_G;
        include_once template('ygclub_party:partyers_list');
        include_once template('ygclub_party:party_sign');

        $party_thread = new threadplugin_ygclub_party();
        $party = $party_thread->_getpartyinfo($_G['tid']);
        $condata = $party_thread->_load_forumparty_condata($_G['fid']);

        $query = C::t('#ygclub_party#partyers')->fetch_all_for_thread($_G['tid'], 0, 0, 0, 1);
        $applylistverified_4 = array();
        foreach($query as $partyer)
        {
            $partyer['dateline'] = dgmdate($partyer['dateline'], 'u');
            $partyer['_avatar'] = avatar($partyer[uid], 'small');
            $applylistverified_4[] = $partyer;
        }
        $verifiedArr = array('1'=> '等待确认','2'=> '取消申请','3'=> '拒绝申请','4'=> '已确认','5'=> '下次参加');
        $result = array('4'=>$applylistverified_4);
        return array(tpl_ygclub_party_partyers_list($result) . tpl_ygclub_party_sign());
		//$activityapplies['ufielddata'] = dunserialize($activityapplies['ufielddata']);
    }
}
?>
