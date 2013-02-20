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

// 申请参加活动
if($_POST['action'] == 'partyapplies')
{
	if(!$_G['uid']) {
		showmessage('not_loggedin', NULL, array(), array('login' => 1));
    }
    if(submitcheck('partysubmit')) {
        
        $tid = $_POST['tid'];

        $party = C::t('#ygclub_party#party')->fetch($tid);

        if(!$party){
            showmessage('ygclub_party:party_not_valid');
        }

        $partyer = C::t('#ygclub_party#partyers')->fetch_by_uid_tid($_G['uid'], $tid);

        if($partyer){
            showmessage('ygclub_party:already_applied');
        }
       
        if(empty($_POST['phone'])) {
            showmessage('ygclub_party:error_phone_empty');
        }

        if(empty($_POST['message'])) {
            showmessage('ygclub_party:error_message_empty');
        }

        if(!empty($party['doworker']) && empty($_POST['usertask'])){
            showmessage('ygclub_party:error_empty_usertask');
        }

        if(!empty($party['marks']) && !is_numeric($_POST['marks'])){
            showmessage('ygclub_party:error_empty_marks');
        }
        
        if($party['followed'] == 1 && !is_numeric($_POST['followed'])) {
            showmessage('ygclub_party:error_followed');
        }

        if(isset($_POST['SFDC_LIST'])){
            foreach($_POST['SFDC_LIST'] as $field => $v){
                if($v['must'] == '1' && empty($_POST['SFDC'][$field])) {
                    showmessage('ygclub_party:error_SFDC_field', NULL, array('field_name' => $_POST['SFDC_LIST'][$field]['name']));
                }
            }
        }

        $insertData = array(
            'tid' => $_POST['tid'],
            'uid' => $_G['uid'],
            'username' => $_G['username'],
            'phone' => $_POST['phone'],
            'verified' => 1,  // 等待确认
            'dateline' => $_G['timestamp'],
            'message' => $_POST['message'],
            'usertask' => $_POST['usertask'],
            'marks' => $_POST['marks'],
            'followed' => $_POST['followed'],
            'config' => serialize($_POST['SFDC']),
        );

        C::t('#ygclub_party#partyers')->insert($insertData);

        showmessage('ygclub_party:partyapplies_succeed', 'forum.php?mod=viewthread&tid='.$_POST['tid'], array(), array('showdialog' => true, 'locationtime' => true));
    }
}
// 获取报名列表
elseif($_GET['act'] == 'list')
{
    $tid = $_GET['tid'];
    $page = $_GET['page'];
    $tpp = 10;
    $maxpages = 1000;
    $page = isset($page) ? max(1, intval($page)) : 1;
    $page = $maxpages && $page > $maxpages ? 1 : $page;
    $start = ($page - 1) * $tpp;
    if($_GET['step'] == 'noPassUsers' || $_GET['step'] == 'PassUsers')
    {
        $applylist = array();
        if($tid > 0 && is_numeric($tid))
        {
            $party = C::t('#ygclub_party#party')->fetch($tid);
            $verified = array();
            if($_GET['step'] == 'PassUsers')
            {
                $verified = array(4);
                $hdr = '已通过';
            }
            elseif($_GET['step'] == 'noPassUsers')
            {
                $verified = array(1,2,3,5);
                $hdr = '暂未通过';
            }
            $applylist = C::t('#ygclub_party#partyers')->fetch_all_for_thread($tid, $start, $tpp, 0, $verified);
            $count = DB::result_first("SELECT count(*) AS num FROM %t WHERE tid=%d AND verified IN(" . join(',', $verified) . ")" , array('partyers', $tid));
            $multipage = multi($count,$tpp,$page,"plugin.php?id=ygclub_party&tid=$tid&act=list&step=$_GET[step]",$maxpages);

            $marks_list = explode('|', $party['marks']);
            foreach($applylist as $key => $partyer)
            {
                $partyer['_dateline'] = dgmdate($partyer['dateline'], 'u');
                $partyer['_avatar'] = avatar($partyer[uid], 'small');
                $partyer['_status']= ygclub_party_getstatus_txt($partyer['verified']);
                $partyer['_marks'] = $marks_list[$partyer['marks']];
                $applylist[$key] = $partyer;
            }

            if($_G['adminid'] == 1 || $_G['adminid'] == 2 || $_G['uid'] == $party['uid']) {
                $mPerm = 1;
            }
        }
        
        include template('common/header_ajax');
        include template('ygclub_party:partyers_list');
        include template('common/footer_ajax');
    }
}
// 取消、接受、下次参加、编辑等操作
elseif($_GET['act'] == 'operate'){
    if(submitcheck('partyer_operate_submit'))
    {
        $tid = $_GET['tid'];
        $pid = $_POST['operate_pid'];
        $for = $_POST['operate_for'];

        if (in_array($for,array('wait','reply','accept','nexttime','edit'))){
            if($tid > 0 && $pid > 0 && $_G['uid'] > 0) {
                $goUrl = 'forum.php?mod=viewthread&tid='.$tid;
                $party = C::t('#ygclub_party#party')->fetch($tid);
                if($_G['adminid'] == 1 || $_G['adminid'] == 2 || $_G['uid'] == $party['uid']) {
                    $mPerm = 1;
                }
                if($party['closed']==1){
                    showmessage("此活动已结束，不能进行相关的操作");
                }
                else{
                    if($mPerm) {
                        if($for == 'wait') {
                            $updateData['verified'] = '2';
                            $success_msg = '成功取消了此朋友的活动请求。'; 
                        }
                        elseif($for == 'accept') {
                            $updateData['verified'] = '4';
                            $success_msg = '成功接受了此朋友的请求。'; 
                        }
                        elseif($for == 'reply') {
                            $success_msg = '回复留言成功。'; 
                        }
                        if($_POST['reply_message'] != '') {
                            $updateData['reply'] = "From {$_G['username']}: " . $_POST['reply_message'];
                        }
                        $updateData['updatetime'] = $_G['timestamp']; 
                        C::t('#ygclub_party#partyers')->update($pid, $updateData);
                        showmessage($success_msg, $goUrl,  array(), array('header'=>false, 'showdialog' => false,  'clean_msgforward'=> true ));
                    }
                }
            }
        }
    }
}
elseif($_GET['act'] == 'onoff'){
    if(submitcheck('close_open_party_submit')){
        $tid = $_GET['tid'];
        if($_G['adminid'] == 1 || $_G['adminid'] == 2 || $_G['uid'] == $party['uid']) {
            $goUrl = 'forum.php?mod=viewthread&tid='.$tid;
            if($_GET['for'] == 'on'){
                $updateData['closed'] = 0;
                $success_msg = '活动已开启。';
            }elseif($_GET['for']== 'off'){
                $updateData['closed'] = 1;
                $success_msg = '活动已关闭。';
            }
            $updateData['updatetime'] = $_G['timestamp']; 
            C::t('#ygclub_party#party')->update($tid, $updateData);
            showmessage($success_msg, $goUrl, array(), array('showdialog' => true, 'locationtime' => true));
        }
    }
}
elseif($_GET['act'] == 'print'){
    $tid = $_GET['tid'];
    if($_G['adminid'] == 1 || $_G['adminid'] == 2 || $_G['uid'] == $party['uid']) {
        $party = C::t('#ygclub_party#party')->fetch($tid);

        if(!$party){
            showmessage('ygclub_party:party_not_valid');
        }
        else {
            echo 'to be continued';
        }
    }
}
else{
    die('fdafd');
    $tmp = DB::fetch_all("SELECT tid FROM %t", array('party'));
    foreach($tmp as $v)
    {
        $tid = $v['tid'];
        $tmp = DB::fetch_first("SELECT pid, tid,first,message FROM %t where tid=%d and first=1", array('forum_post', $tid));
        if($tmp['pid'])
        {
        $message = addslashes($tmp['message']) . chr(0).chr(0).chr(0) . 'ygclub_party';
        //$message = addslashes(str_replace(chr(0).chr(0).chr(0) . 'ygclub_party', '', $tmp['message']));
        C::t('forum_thread')->update($tid, array('special'=>'127'));
        DB::query("UPDATE " . DB::table('forum_post'). " set message='$message' WHERE pid=$tmp[pid]");
        }
    }
}

function ygclub_party_getstatus_txt($verified)
{
    $verifiedArr = array('1'=> '等待确认','2'=> '取消申请','3'=> '拒绝申请','4'=> '已确认','5'=> '下次参加');
    return $verifiedArr[$verified];
}

?>
