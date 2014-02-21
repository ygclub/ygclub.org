<?php

/** 
 *   [ygclub.org] 
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

include_once('thread.class.php');

// 申请参加活动
if($_POST['action'] == 'partyapplies')
{
    if(!$_G['uid']) {
        showmessage('not_loggedin', '', array(), array('login' => true));
    }
    if(submitcheck('partysubmit')) {

        $tid = $_POST['tid'];

        //$party = C::t('#ygclub_party#party')->fetch($tid);
        $party_thread = new threadplugin_ygclub_party();
        $party = $party_thread->_getpartyinfo($tid);

        if(!$party){
            showmessage('ygclub_party:party_not_valid');
        }
        $condata = $party_thread->_load_forumparty_condata($party['fid']);
        if(isset($condata['bgroup']) && !in_array($_G['groupid'], $condata['bgroup']))
        {
            showmessage('您所在的用户组暂时不能报名');
        }
        if($party['starttimeto'] < time())
        {
            showmessage('报名截止时间已过，下次抓紧哦。');
        }
        if($party['closed']==1){
            showmessage("此活动已结束.");
        }
        if($party['number'] > 0 && ($party['_verified']['4']['count'] + $party['_verified']['4']['followed'] > $party['number']))
        {
            showmessage("通过人数已经达到上限，下次抓紧哦.");
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
            'tid' => $tid,
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

        $thread_subject = DB::result_first("SELECT subject FROM " . DB::table('forum_thread') . " where tid='$tid'");
        $notic = array('subject' => "{$_G['username']}已报名参加：{$thread_subject}", 'message' => $_POST['message'] . ' &nbsp;<a href="forum.php?mod=viewthread&tid=' . $tid . '#ygclub_party_nopassed_partyers">查看&gt;</a>');
        notification_add($party['uid'],'report','system_notice',$notic);

        C::t('#ygclub_party#partyers')->insert($insertData);
        showmessage('ygclub_party:partyapplies_succeed', 'forum.php?mod=viewthread&tid='.$_POST['tid'], array(), array('showdialog' => true, 'locationtime' => true));
    }
}
// 邀请好友参加活动
if($_POST['action'] == 'partyinvite')
{
    if(!$_G['uid']) {
        showmessage('not_loggedin', '', array(), array('login' => true));
    }
    if(submitcheck('partysubmit')) {

        $tid = $_POST['tid'];

        $party_thread = new threadplugin_ygclub_party();
        $party = $party_thread->_getpartyinfo($tid);
    
        if(!ygclub_party_isadmin($party)) {
            showmessage('没有权限邀请好友参加活动');
        }

        if(!$party){
            showmessage('ygclub_party:party_not_valid');
        }
        if($party['closed']==1){
            showmessage("此活动已结束.");
        }
        if($party['number'] > 0 && ($party['_verified']['4']['count'] + $party['_verified']['4']['followed'] > $party['number']))
        {
            showmessage("通过人数已经达到上限，不能再邀请好友加入。");
        }

        $invitee = DB::fetch_first("SELECT uid, username FROM " . DB::table('common_member') . " where username = '$_POST[username]'");

        if($invitee['username'] == '')
        {
            showmessage('该论坛ID不存在，请确认后重新输入');
        }

        $partyer = C::t('#ygclub_party#partyers')->fetch_by_uid_tid($invitee['uid'], $tid);

        if($partyer){
            showmessage($invitee['username'] . 已经报名此次活动);
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

        $insertData = array(
            'tid' => $tid,
            'uid' => $invitee['uid'],
            'username' => $invitee['username'],
            'phone' => $_G['username'] . '邀请',
            'verified' => 4,  // 通过
            'dateline' => $_G['timestamp'],
            'message' => $_G['username'] . '邀请',
            'usertask' => $_POST['usertask'],
            'marks' => $_POST['marks'],
            'followed' => $_POST['followed'],
            'config' => serialize($_POST['SFDC']),
        );

        $thread_subject = DB::result_first("SELECT subject FROM " . DB::table('forum_thread') . " where tid='$tid'");
        $notic = array('subject' => "{$_G['username']}已邀请你参加：{$thread_subject}", 'message' => $_POST['message'] . ' &nbsp;<a href="forum.php?mod=viewthread&tid=' . $tid . '#ygclub_party_passed_partyers">查看&gt;</a>');
        notification_add($invitee['uid'],'system','system_notice',$notic);

        C::t('#ygclub_party#partyers')->insert($insertData);
        showmessage('成功邀请好友', 'forum.php?mod=viewthread&tid='.$_POST['tid'], array(), array('showdialog' => true, 'locationtime' => true));
    }
}

// 编辑活动
elseif($_POST['action'] == 'partyer_edit')
{
    if(!$_G['uid']) {
        showmessage('not_loggedin', '', array(), array('login' => true));
    }
    if(submitcheck('partyereditsubmit')) {

        $tid = $_POST['tid'];
        $pid = $_POST['pid'];

        $party = C::t('#ygclub_party#party')->fetch($tid);

        if(!$party){
            showmessage('ygclub_party:party_not_valid');
        }

        $partyer = C::t('#ygclub_party#partyers')->fetch($pid);

        if(!$partyer){
            showmessage('没有此报名信息');
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

        $updateData = array(
            'phone' => $_POST['phone'],
            'message' => $_POST['message'],
            'usertask' => $_POST['usertask'],
            'marks' => $_POST['marks'],
            'followed' => $_POST['followed'],
            'config' => serialize($_POST['SFDC']),
        );

        C::t('#ygclub_party#partyers')->update($pid, $updateData);
        showmessage('编辑活动成功', 'forum.php?mod=viewthread&tid='.$tid, array(), array('showdialog' => true, 'locationtime' => true));
    }
}
// 关联活动
elseif($_POST['action'] == 'party_relec')
{
    if(!$_G['uid']) {
        showmessage('not_loggedin', '', array(), array('login' => true));
    }
    if(submitcheck('partyrelecsubmit')) {
        $ctinfo = DB::fetch_first("select subject from " . DB::table('forum_thread') . "  where tid='$_POST[ctid]'");
        if(empty($ctinfo))
        {
            showmessage("帖子中没有与您输入ID相符的记录");
        }
        else
        {
            $updateData['ctid'] = $_POST['ctid'];
            C::t('#ygclub_party#party')->update($_POST['tid'], $updateData);
            showmessage('关联总结帖成功', 'forum.php?mod=viewthread&tid='.$_POST[tid], array(), array('showdialog' => true, 'locationtime' => true));
        }
    }
}
// 编辑签到信息
elseif($_POST['action'] == 'party_checkin')
{
    if(!$_G['uid']) {
        showmessage('not_loggedin', '', array(), array('login' => true));
    }
    if(submitcheck('partycheckinsubmit')) {
        foreach($_POST['checkin'] as $uid => $ck)
        {
            DB::query("update " . DB::table('ygclub_partyers') . " set usertask='{$_POST['usertask_list'][$uid]}', checkin='$ck', updatetime='". time() ."' where uid='$uid' and tid='$_POST[tid]'");
        }
        showmessage('设置成功', 'forum.php?mod=viewthread&tid='.$_POST[tid], array(), array('showdialog' => true, 'locationtime' => true));
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
        $result_list = array();
        if($tid > 0 && is_numeric($tid))
        {
            $party_thread = new threadplugin_ygclub_party();
            $party = $party_thread->_getpartysummary($tid);
            $condata = $party_thread->_load_forumparty_condata($party['fid']);

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
            $result_list = C::t('#ygclub_party#partyers')->fetch_all_for_thread($tid, $start, $tpp, 0, $verified);
            $count = DB::result_first("SELECT count(*) AS num FROM %t WHERE tid=%d AND verified IN(" . join(',', $verified) . ")" , array(C::t('#ygclub_party#partyers')->getTable(), $tid));
            $multipage = multi($count,$tpp,$page,"plugin.php?id=ygclub_party&tid=$tid&act=list&step=$_GET[step]",$maxpages);

            $marks_list = explode('|', $party['marks']);
            $apply_list = array();
            foreach($result_list as $key => $partyer)
            {
                $partyer['_dateline'] = dgmdate($partyer['dateline'], 'u');
                $partyer['_avatar'] = avatar($partyer[uid], 'small');
                $partyer['_status']= ygclub_party_getstatus_txt($partyer['verified']);
                $partyer['_marks'] = $marks_list[$partyer['marks']];
                $apply_list[$partyer[pid]] = $partyer;
            }

            if(ygclub_party_isadmin($party)) {
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
    if(!$_G['uid']) {
        showmessage('not_loggedin', '', array(), array('login' => true));
    }
    if(submitcheck('partyer_operate_submit'))
    {
        $tid = $_GET['tid'];
        $pid = $_POST['operate_pid'];
        $for = $_POST['operate_for'];
        if (in_array($for,array('wait','reply','accept','nexttime','edit'))){
            if($tid > 0 && $pid > 0 && $_G['uid'] > 0) {
                $goUrl = 'forum.php?mod=viewthread&tid='.$tid;
                $party = C::t('#ygclub_party#party')->fetch($tid);
                if(ygclub_party_isadmin($party)) {
                    $mPerm = 1;
                }
                if($party['closed']==1){
                    showmessage("此活动已结束，不能进行相关的操作");
                }
                else{
                    $party_thread = new threadplugin_ygclub_party();

                    if($for == 'nexttime')
                    {
                        if(trim($_POST['reply_message']) == '')
                        {
                            showmessage("请输入退出的原因。");
                        }
                        else
                        {
                            $condata = $party_thread->_load_forumparty_condata($party['fid']);
                            if ($condata['limittime'] > 0 && (($party['starttimeto']-$_G['timestamp'])<=60*$condata['limittime'])) {
                                showmessage("报名截至时间前 " .  $condata['limittime'] . " 分钟不能退出活动，请联系召集人或管理员。");
                            }

                            $thread_subject = DB::result_first("SELECT subject FROM " . DB::table('forum_thread') . " where tid='$tid'");
                            $partyer = C::t('#ygclub_party#partyers')->fetch($pid);
                            if($partyer['uid'] != $_G['uid'])
                            {
                                showmessage("非法操作!");
                            }
                            $updateData['verified'] = '5';
                            $updateData['message'] = $_POST['reply_message'];
                            $success_msg = '你已经退出此次活动'; 
                            $notice_subject = "{$_G['username']} 退出了活动：{$thread_subject}";
                            $notice_message = $_POST['reply_message'] . ' &nbsp;<a href="forum.php?mod=viewthread&tid=' . $tid . '">查看&gt;</a>';
                        }
                        $updateData['updatetime'] = $_G['timestamp']; 
                        C::t('#ygclub_party#partyers')->update($pid, $updateData);

                        if($_G['uid'] != $party['uid']){
                            $notic = array('subject' => $notice_subject, 'message' => $notice_message);
                            notification_add($party['uid'],'system','system_notice',$notic);
                        }

                        showmessage($success_msg, $goUrl,  array(), array('showdialog' => true, 'locationtime'=>true));

                    }
                    if($mPerm) {
                        $thread_subject = DB::result_first("SELECT subject FROM " . DB::table('forum_thread') . " where tid='$tid'");
                        $partyer = C::t('#ygclub_party#partyers')->fetch($pid);

                        if($for == 'wait') {
                            $updateData['verified'] = '2';
                            $success_msg = '成功取消了此朋友的活动请求。';
                            $notice_subject = "{$_G['username']} 取消了你在活动：{$thread_subject} 中的报名";
                            $notice_message = $_POST['reply_message'] . ' &nbsp;<a href="forum.php?mod=viewthread&tid=' . $tid . '">查看&gt;</a>';
                        }
                        elseif($for == 'accept') {
                            $party = $party_thread->_getpartyinfo($partyer['tid']);
                            if($party['number'] > 0 && ($party['_verified']['4']['count'] + $party['_verified']['4']['followed'] > $party['number']))
                            {
                                showmessage("通过人数已经达到上限，不能再通过好友加入.");
                            }

                            $updateData['verified'] = '4';
                            $success_msg = '成功接受了此朋友的请求。'; 
                            $notice_subject = "{$_G['username']} 通过了你在活动：{$thread_subject} 中的报名";
                            $notice_message = $_POST['reply_message'] . ' &nbsp;<a href="forum.php?mod=viewthread&tid=' . $tid . '">查看&gt;</a>';
                        }
                        elseif($for == 'reply') {
                            if($_POST['reply_message'] == '') {
                                showmessage("请输入回复留言。");
                            }
                            $success_msg = '回复留言成功。'; 
                            $notice_subject = "{$_G['username']} 在活动：{$thread_subject} 报名中回复了你";
                            $notice_message = $_POST['reply_message'] . ' &nbsp;<a href="forum.php?mod=viewthread&tid=' . $tid . '">查看&gt;</a>';
                        }
                        if($_POST['reply_message'] != '') {
                            $updateData['reply'] = "by {$_G['username']}: " . $_POST['reply_message'];
                        }
                        $updateData['updatetime'] = $_G['timestamp']; 
                        C::t('#ygclub_party#partyers')->update($pid, $updateData);


                        if($_G['uid'] != $partyer['uid']){
                            $notic = array('subject' => $notice_subject, 'message' => $notice_message);
                            notification_add($partyer['uid'],'system','system_notice',$notic);
                        }
                        showmessage($success_msg, $goUrl, array(), array('showdialog'=>1, 'showmsg'=>true, 'login'=>true, 'alert'=>'right', 'closetime'=>2 ));
                    }
                }
            }
        }
    }
}
elseif($_GET['act'] == 'onoff'){
    if(!$_G['uid']) {
        showmessage('not_loggedin', '', array(), array('login' => true));
    }
    if(submitcheck('close_open_party_submit')){
        $tid = $_GET['tid'];
        $party = C::t('#ygclub_party#party')->fetch($tid);
        if(ygclub_party_isadmin($party)) {
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
            showmessage($success_msg, $goUrl, array(), array('showdialog'=>true, 'alert'=>'right', 'locationtime' => true ));
        }
    }
}
elseif($_GET['act'] == 'print'){
    if(!$_G['uid']) {
        showmessage('not_loggedin', '', array(), array('login' => true));
    }

    $tid = $_GET['tid'];
    $party = C::t('#ygclub_party#party')->fetch($tid);
    if(ygclub_party_isadmin($party)) {
        if(!$party){
            showmessage('ygclub_party:party_not_valid');
        }
        else {

            $party['_subject'] = DB::result_first("SELECT subject FROM " . DB::table('forum_thread') . " where tid='$tid'");
            $apply_list = DB::fetch_all("SELECT p.*, m.email FROM %t " 
                . " AS p LEFT JOIN " . DB::table('common_member')
                . " AS m ON m.uid=p.uid "
                . " WHERE p.verified=4 AND p.tid={$tid} " 
                . " ORDER BY p.phone ASC", array(C::t('#ygclub_party#partyers')->getTable()));

            if($_GET['info'] =='all')
            {
                $print_list = array(0 => array("序号","用户名",'联系电话','E-Mail', '个人说明','分工','备注选项','签到'));
            }
            else
            {
                $print_list = array(0 => array("序号","用户名",'联系电话', '分工','备注选项','签到'));
            }

            $marks_list = explode('|', $party['marks']);
            $count = 0;
            $follow_count = 0;
            $email_list = array();
            foreach($apply_list as $key => $partyer)
            {
                $count ++;
                $follow_count += $partyer['followed'];
                $print_list[$partyer['pid']]['no'] = $count;
                $print_list[$partyer['pid']]['username'] = $partyer['username'];
                if($partyer['followed'] > 0)
                    $print_list[$partyer['pid']]['username'] .= "(+{$partyer[followed]})";
                $print_list[$partyer['pid']]['phone'] = $partyer['phone'];
                if($_GET['info'] =='all')
                {
                    $print_list[$partyer['pid']]['email'] = $partyer['email'];
                    $print_list[$partyer['pid']]['message'] = $partyer['message'];
                }
                $email_list[] = $partyer['email'];

                $print_list[$partyer['pid']]['usertask'] = $partyer['usertask'];
                $print_list[$partyer['pid']]['marks'] = $marks_list[$partyer['marks']];
                $print_list[$partyer['pid']]['checkin'] = '';

            }
            $mailto_string = join(', ', $email_list);

            include template('ygclub_party:party_print');
        }
    }
    else
    {
        showmessage('没有权限打印');
    }
}
elseif($_GET['act'] == 'editp')
{
    if(!$_G['uid']) {
        showmessage('not_loggedin', '', array(), array('login' => true));
    }

    $pid = intval($_GET['pid']);
    if($pid > 0) {
        $partyer = C::t('#ygclub_party#partyers')->fetch($pid);
        $party_thread = new threadplugin_ygclub_party();
        $party = $party_thread->_getpartysummary($partyer['tid']);
        if($partyer && (ygclub_party_isadmin($party) || ($partyer['verified'] == 1 && $partyer['uid'] == $_G['uid'])))
        {
            $partyer['_config'] = unserialize($partyer['config']);
	        $partyer['_signfield_list'] = ygclub_party_order_fields($condata['signfield']);
            $YGCLUB_SF_CONFIG_VALUES = $partyer['_config'];
            $condata = $party_thread->_load_forumparty_condata($party['fid']);
            $partyer['_dateline'] = dgmdate($partyer['dateline'], 'u');
            $partyer['_avatar'] = avatar($partyer[uid], 'small');
            $partyer['_status']= ygclub_party_getstatus_txt($partyer['verified']);
            $partyer['_marks'] = $marks_list[$partyer['marks']];

            $navigation = ' <em>&rsaquo;</em> ';
            $navigation .= '编辑报名信息';
            $navigation .= ' <em>&rsaquo;</em> ';
            $navigation .= '<a href="forum.php?mod=viewthread&tid=' . $party['tid'] . '">' . 返回报名帖 . '</a>';

            include template('ygclub_party:partyer_edit');
        }
        else
        {
            showmessage('没有权限查看该报名记录');
        }
    }
    else
    {
        showmessage('参数不合法');
    } 
}
elseif($_GET['act'] == 'relec'){
    if(!$_G['uid']) {
        showmessage('not_loggedin', '', array(), array('login' => true));
    }

    $tid = $_GET['tid'];
    $party = C::t('#ygclub_party#party')->fetch($tid);
    if(ygclub_party_isadmin($party)) {
        $tinfo = DB::fetch_first("select subject from " . DB::table('forum_thread') . " where tid='$tid'");

        if(!$party){
            showmessage('ygclub_party:party_not_valid');
        }
        if($party['ctid'] == 0)
        {
            $rcinfo = array();
            $ctitle_patten = $tinfo['subject'];
            $ctitle_patten = str_replace('【活动召集】','',$ctitle_patten);
            $ctitle_patten = str_replace('召集','',$ctitle_patten);
            $ctitle_patten = trim($ctitle_patten);
            $ctitle_patten = str_replace(' ', '', $ctitle_patten);
            $rcinfo = DB::fetch_first("select tid, subject from " . DB::table('forum_thread') . " where subject like '%{$ctitle_patten}%' and subject like '%总结%'");
            if(empty($rcinfo))
            {
                $location = explode('学校', $ctitle_patten);
                if(count($location) == 2)
                {
                    $rcinfo = DB::fetch_first("select tid, subject from  " . DB::table('forum_thread') . "  where subject like '%{$reg[1]}%' and subject like '%{$location[0]}%' and subject like '%总结%'");
                }
                else
                {
                    $location = explode('社区', $ctitle_patten);
                    if(count($location) == 2)
                    {
                        $rcinfo = DB::fetch_first("select tid, subject from " . DB::table('forum_thread') . "  where subject like '%{$reg[1]}%' and subject like '%{$location[0]}%' and subject like '%总结%'");
                    }
                }
            }
            if(empty($rcinfo))
            {
                if(preg_match('/([\d-.]*)(.*)/', $ctitle_patten, $reg))
                {
                    $reg[1] = trim($reg[1]);
                    $reg[2] = trim($reg[2]);
                    $rcinfo = DB::fetch_first("select tid, subject from " . DB::table('forum_thread') . " where subject like '%{$reg[1]}%' and subject like '%$reg[2]%' and subject like '%总结%'");
                }
            }
        }
        else
        {
            $ctinfo = DB::fetch_first("select subject from " . DB::table('forum_thread') . "  where tid='$party[ctid]'");
        }

        $navigation = ' <em>&rsaquo;</em> ' ;
        $navigation .= '关联总结帖';
        $navigation .= ' <em>&rsaquo;</em> ' ;
        $navigation .= '<a href="forum.php?mod=viewthread&tid=' . $party['tid'] . '">' . $tinfo['subject'] . '</a>';

        include template('ygclub_party:party_relec');
    }
}
elseif($_GET['act']=='checkin'){
    if(!$_G['uid']) {
        showmessage('not_loggedin', '', array(), array('login' => true));
    }
    $tid = $_GET['tid'];
    $tinfo = DB::fetch_first("select subject from " . DB::table('forum_thread') . " where tid='$tid'");
    $party = C::t('#ygclub_party#party')->fetch($tid);
    if(ygclub_party_isadmin($party)) {
        $mPerm = 1;
    }

     if(!$party){
         showmessage('ygclub_party:party_not_valid');
    }

    $ex_workers = empty($party['doworker']) ? array('') : array_merge(array('','召集人') , explode(',',$party['doworker']));

    $checkAttr = array('0'=>'待确认', '1'=>'已参加', '2'=>'未参加');
    $result = DB::fetch_all("select p.*, m.email from " . DB::table('ygclub_partyers') . " p left join " . DB::table('common_member') . " m on m.uid=p.uid where p.tid='$tid' and p.verified='4' order by p.checkin asc, p.pid desc");
    $checkin_count_list = array('0'=>0, '1'=>0, '2'=>0);
    $total_count = 0;
    $result_list = array();
    foreach($result as $rs)
    {
        $rs['v1'] = $verifiedArr[$rs['verified']];
        $rs['m1'] = $party['marks'] ? $partyMarks[$rs['marks']] : "";
        $rs['checkin_txt'] = $checkAttr[$rs['checkin']];
        $rs['config'] = unserialize($rs['config']);
        $checkin_count_list[$rs['checkin']] ++;
        $total_count ++;
        $result_list[] = $rs;
    }

    if($_GET['step']=='list')
    {
        if($party['class'] == '阳光公益活动')
        {
            $other_task = '其他分工';
            $main_count = 0;
            $assis_count = 0;
            $audit_count = 0;
            $sum_list = array($other_task=>array());
            foreach($result_list as $key => $value)
            {
                if($value['checkin'] == 1)
                {
                    if($value['usertask'] == '')
                    {
                        if(preg_match('/主讲/', $value['message']))
                        {
                            $value['usertask'] = '主讲人';
                        }
                        else if(preg_match('/助教/', $value['message']))
                        {
                            $value['usertask'] = '助教';
                        }
                        else if(preg_match('/旁听/', $value['message']))
                        {
                            $value['usertask'] = '旁听';
                        }
                        else
                        {
                            $value['usertask'] = '旁听';
                        }
		    }

		    if(is_array($value['config']['课程']))
		    {
			$value['config']['class'] = $value['config']['课程'];
		    }
                    if(is_array($value['config']['class']))
                    {
                        foreach($value['config']['class'] as $lesson)
                        {
                            $sum_list[$lesson][$value['usertask']]['detail'][$value['uid']] = $value;
                        }
                    }
                    else
                    {
                        $sum_list[$other_task][$other_task]['detail'][$value['uid']] = $value;
                    }
                }
            }
            foreach($sum_list as $lesson => $value)
            {
                foreach($value as $usertask => $detail)
                {
                    foreach($detail['detail'] as $user_id => $user_info)
                    {
                        $temp_sum[] = '<cite><a href="home.php?mod=space&uid=' . $user_info['uid'] . '" c="1">' . $user_info['username'] . '</a></cite>';
                    }
                    $sum_list[$lesson][$usertask]['sum'] = join(', ', $temp_sum);
                    unset($temp_sum);
                }
            }

            $temp = $sum_list[$other_task];
            unset($sum_list[$other_task]);
            $sum_list[$other_task] = $temp;
        }
        $navigation = ' <em>&rsaquo;</em> ' ;
        $navigation .= '签到信息';
        $navigation .= ' <em>&rsaquo;</em> ' ;
        $navigation .= '<a href="forum.php?mod=viewthread&tid=' . $party['tid'] . '">' . $tinfo['subject'] . '</a>';

        include template('ygclub_party:party_checkin_list');
    }
    else
    {
        if(!ygclub_party_isadmin($party)) {
            showmessage('没有权限编辑签到信息');
        }

        $navigation = ' <em>&rsaquo;</em> ' ;
        $navigation .= '编辑签到信息';
        $navigation .= ' <em>&rsaquo;</em> ' ;
        $navigation .= '<a href="forum.php?mod=viewthread&tid=' . $party['tid'] . '">' . $tinfo['subject'] . '</a>';

        include template('ygclub_party:party_checkin');
    }
}
elseif($_GET['act'] == 'repair'){
    $tid = $_GET['tid'];
    $party_thread = new threadplugin_ygclub_party();
    $party = $party_thread->_getpartyinfo($tid);
    
    if(!ygclub_party_isadmin($party)) {
        showmessage('没有权限修复活动');
    }

    $tmp = DB::fetch_first("SELECT pid, tid,first,message FROM %t where tid=%d and first=1 ", array('forum_post', $tid));
    if($tmp['pid'])
    {
        $message = str_replace(chr(0), '', $tmp['message']);
        $message = str_replace('ygclub_party', '', $message);
        $message = addslashes($message) . chr(0).chr(0).chr(0) . 'ygclub_party';
        C::t('forum_thread')->update($tid, array('special'=>'127'));
        DB::query("UPDATE " . DB::table('forum_post'). " set message='$message' WHERE pid=$tmp[pid]");
        showmessage('完成修复!');
    }
    else
    {
        showmessage('post not exists!');
    }
}
else{
    showmessage('参数不合法');
    /*
    $tmp = DB::fetch_all("SELECT tid FROM %t", array('ygclub_party'));
    foreach($tmp as $v)
    {
        $tid = $v['tid'];
        $tmp = DB::fetch_first("SELECT pid, tid,first,message FROM %t where tid=%d and first=1 ", array('forum_post', $tid));
        if($tmp['pid'])
        {
            $message = addslashes($tmp['message']) . chr(0).chr(0).chr(0) . 'ygclub_party';
            //$message = addslashes(str_replace(chr(0).chr(0).chr(0) . 'ygclub_party', '', $tmp['message']));
            C::t('forum_thread')->update($tid, array('special'=>'127'));
            DB::query("UPDATE " . DB::table('forum_post'). " set message='$message' WHERE pid=$tmp[pid]");
        }
    }
    showmessage('upgrade complete!');
     */
}

function ygclub_party_getstatus_txt($verified)
{
    $verifiedArr = array('1'=> '等待确认','2'=> '取消申请','3'=> '拒绝申请','4'=> '已确认','5'=> '下次参加');
    return $verifiedArr[$verified];
}

?>
