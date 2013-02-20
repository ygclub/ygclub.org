<?php

/** 
 *   [ygclub.org] (C)北京LEAD阳光志愿者俱乐部
 *
 *   @author leeyupeng
 *   @email  leeyupeng@gmail.com
 */

include_once('func.inc.php');

class threadplugin_ygclub_party {
    var $name = '';
    var $iconfile = 'images/icon.gif';
    var $buttontext = '';
    var $identifier = 'ygclub_party';
    
    public function __construct() {
        $this->threadplugin_ygclub_party();
    }

    public function threadplugin_ygclub_party() {
        $this->name = lang('plugin/ygclub_party', 'plugin_name');
        $this->buttontext = lang('plugin/ygclub_party', 'plugin_buttontext');
    }


    function newthread($fid) { 
        include_once template('ygclub_party:party_post');
        $condata = $this->_load_forumparty_condata($fid);
        return tpl_ygclub_party_post();
    } 

    function newthread_submit($fid) { 
        //print_r($_POST);
        //showmessage(lang('plugin/ygclub_party', 'no_permission'), NULL);
    } 

    function newthread_submit_end($fid, $tid) { 
        global $_G;

        $_POST['doworker'] = str_replace('，',',', $_POST['doworker']);
        $insertData = array(
            'tid'=>$tid, 
            'fid'=>$fid,
            'uid'=>$_G['uid'],
            'showtime'      =>strtotime($_POST['showtime']),
            'starttimefrom' =>strtotime($_POST['starttimefrom']),
            'starttimeto'   =>strtotime($_POST['starttimeto']),
            'class'    => $_POST['class'],
            'gender'   => $_POST['gender'],
            'number'   => $_POST['number'],
            'followed' => $_POST['followed'],
            'isjoin'   => $_POST['isjoin'],
            'phone'    => $_POST['phone'],
            'doworker' => $_POST['doworker'],
            'marks'    => $_POST['marks'],
        );

        C::t('#ygclub_party#party')->insert($insertData);

        
        $verified = $_POST['isjoin'] == 1 ? 4 : 5;
        $insertData = array(
            'tid' => $tid,
            'uid' => $_G['uid'],
            'username' => $_G['username'],
            'phone' => $_POST['phone'],
            'verified' => $verified, 
            'dateline' => $_G['timestamp'],
            'message' => '欢迎大家来参加活动',
            'usertask' => '召集人',
            'marks' => '',
            'followed' => '',
            'config' => '',
        );

        C::t('#ygclub_party#partyers')->insert($insertData);

    } 

    function editpost($fid, $tid) {
        include_once template('ygclub_party:party_post');
        $condata = $this->_load_forumparty_condata($fid);

        $party = C::t('#ygclub_party#party')->fetch($tid);
        $party['showtime']      = gmdate('Y-m-d H:i',$party['showtime']+3600*$_DSESSION['timeoffset']);
        $party['starttimefrom'] = gmdate('Y-m-d H:i',$party['starttimefrom']+3600*$_DSESSION['timeoffset']);
        $party['starttimeto']   = gmdate('Y-m-d H:i',$party['starttimeto']+3600*$_DSESSION['timeoffset']);
        
        return tpl_ygclub_party_post($party);
    } 

    function editpost_submit($fid, $tid) { 
        global $_G;
        
        $_POST['doworker'] = str_replace('，',',', $_POST['doworker']);
        $updateData = array(
            'showtime'      =>strtotime($_POST['showtime']),
            'starttimefrom' =>strtotime($_POST['starttimefrom']),
            'starttimeto'   =>strtotime($_POST['starttimeto']),
            'class'    => $_POST['class'],
            'gender'   => $_POST['gender'],
            'number'   => $_POST['number'],
            'followed' => $_POST['followed'],
            'isjoin'   => $_POST['isjoin'],
            'phone'    => $_POST['phone'],
            'doworker' => $_POST['doworker'],
            'marks'    => $_POST['marks'],
        );

        C::t('#ygclub_party#party')->update($tid, $updateData);
        showmessage(lang('plugin/ygclub_party', 'update_succeed'), 'forum.php?mod=viewthread&tid='.$tid);
    } 

    function editpost_submit_end($fid, $tid) { 

    } 

    function newreply_submit_end($fid, $tid) { 

    } 

    function viewthread($tid) { 
        global $_G;
        include_once template('ygclub_party:party_info');
        $party = $this->_getpartyinfo($tid);
        return tpl_ygclub_party_info();
    } 

    public function _load_forumparty_condata($fid) {
        @require_once(DISCUZ_ROOT.'./source/discuz_version.php');
        $cachedir_party = DISCUZ_VERSION == "X2" ? './data/cache/cache_' : './data/sysdata/cache_';
        $cachename_party = "{$this->identifier}_forum_{$fid}_config";
        include(DISCUZ_ROOT . $cachedir_party . $cachename_party . '.php');
        $condata['_classes_list'] = explode(',', $condata['classes']);
        $condata['_signfield_list'] = ygclub_party_order_fields($condata['signfield']);
        return $condata;
    }

    public function _getpartyinfo($tid)
    {
        global $_G;
        $party = C::t('#ygclub_party#party')->fetch($tid);
        $party['showtime']      = gmdate('Y-m-d H:i',$party['showtime']+3600*$_DSESSION['timeoffset']);
        $party['starttimefrom'] = gmdate('Y-m-d H:i',$party['starttimefrom']+3600*$_DSESSION['timeoffset']);
        $party['starttimeto']   = gmdate('Y-m-d H:i',$party['starttimeto']+3600*$_DSESSION['timeoffset']);
        
        $party['_doworker_list'] = explode(',', $party['doworker']);
        $party['_marks_list'] = array();
        if($party['marks'] != '')
        {
            $party['_marks_list'] = explode('|', $party['marks']);
        }

        if($party['number'] == 0)
        {
            $party['_number'] = lang('plugin/ygclub_party', 'unlimited');
        }
        if($party['gender'] == 0)
        {
            $party['_gender'] = lang('plugin/ygclub_party', 'unlimited_gender');
        }
        elseif($party['gender'] == 1)
        {
            $party['_gender'] = lang('plugin/ygclub_party', 'male');
        }
        elseif($party['gender'] == 2)
        {
            $party['_gender'] = lang('plugin/ygclub_party', 'female');
        }

        $partyers_list = C::t('#ygclub_party#partyers')->fetch_all_for_thread($tid);
        $party['_verified']['4']['count'] = 0;
        $party['_verified']['4']['followed'] = 0;
        $party['_verified']['un4']['count'] = 0;
        $party['_verified']['un4']['followed'] = 0;
        $party['_verified']['all']['count'] = 0;
        $party['_verified']['all']['followed'] = 0;
        $party['_total_count'] = 0;

        foreach($partyers_list as $key=> $partyer)
        {
            if($partyer['verified'] == 4){
                $party['_verified']['4']['count'] ++;
                $party['_verified']['4']['followed'] += $partyer['followed'];
                $party['_approved_username_list_html'][] = '<a target="_blank" href="home.php?mod=space&uid=' . $partyer[uid] . '">' . $partyer[username] . '</a>';
                $party['_marks_count'][$partyer['marks']] ++;
            }
            else{
                $party['_verified']['un4']['count'] ++;
                $party['_verified']['un4']['followed'] += $partyer['followed'];
            }
            $party['_verified']['all']['count'] ++;
            $party['_verified']['all']['followed'] += $partyer['followed'];
            $party['_total_count'] ++;
            $party['_total_count'] += $partyer['followed'];
        }

        $party['_approved_username_list_html'] = join(', ', $party['_approved_username_list_html']);

        if($_G['adminid'] == 1 || $_G['adminid'] == 2 || $_G['uid'] == $party['uid']) {
            $party['_mPerm'] = 1;
        }
       
        return $party;
    }
}
?>
