<?php

/** 
 *   [ygclub.org] (C)北京LEAD阳光志愿者俱乐部
 *
 *   @author leeyupeng
 *   @email  leeyupeng@gmail.com
 */

class threadplugin_ygclub_party {
    var $name = '';
    var $iconfile = 'images/icon.gif';
    var $buttontext = '';
    
    public function __construct() {
        $this->threadplugin_ygclub_party();
    }

    public function threadplugin_ygclub_party() {
        $this->name = lang('plugin/ygclub_party', 'plugin_name');
        $this->buttontext = lang('plugin/ygclub_party', 'plugin_buttontext');
    }
    function newthread($fid) { 
        include_once template('ygclub_party:party_post');
        return tpl_ygclub_party_post();
    } 

    function newthread_submit($fid) { 
        //print_r($_POST);
        //showmessage(lang('plugin/ygclub_party', 'no_permission'), NULL);
    } 

    function newthread_submit_end($fid, $tid) { 
        global $_G;
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
    } 

    function editpost($fid, $tid) {
        $party = C::t('#ygclub_party#party')->fetch($tid);
        $party['showtime']      = gmdate('Y-m-d H:i',$party['showtime']+3600*$_DSESSION['timeoffset']);
        $party['starttimefrom'] = gmdate('Y-m-d H:i',$party['starttimefrom']+3600*$_DSESSION['timeoffset']);
        $party['starttimeto']   = gmdate('Y-m-d H:i',$party['starttimeto']+3600*$_DSESSION['timeoffset']);
        
        include_once template('ygclub_party:party_post');
        return tpl_ygclub_party_post($party);
    } 

    function editpost_submit($fid, $tid) { 

    } 

    function editpost_submit_end($fid, $tid) { 

    } 

    function newreply_submit_end($fid, $tid) { 

    } 

    function viewthread($tid) { 
        $party = C::t('#ygclub_party#party')->fetch($tid);
        $party['showtime']      = gmdate('Y-m-d H:i',$party['showtime']+3600*$_DSESSION['timeoffset']);
        $party['starttimefrom'] = gmdate('Y-m-d H:i',$party['starttimefrom']+3600*$_DSESSION['timeoffset']);
        $party['starttimeto']   = gmdate('Y-m-d H:i',$party['starttimeto']+3600*$_DSESSION['timeoffset']);

        include_once template('ygclub_party:party_info');
        return tpl_ygclub_party_info($party);
    } 
}
?>
