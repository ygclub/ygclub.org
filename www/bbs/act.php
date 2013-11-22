<?php
define('APPTYPEID', 0);
require './source/class/class_core.php';
define('CURSCRIPT', 'act');

$discuz = C::app();
$discuz->init();
if(!$_G['uid']) {
    showmessage('not_loggedin', '', array(), array('login' => true));
}

$navigation = ' <em>&rsaquo;</em> <a href="act.php">阳光活动统计<a/>';
$starttime = $_GET['starttime'];
$endtime = $_GET['endtime'];
$uid = $_GET['uid'];
$username = $_GET['username'];
$list_type = $_GET['type'];
$typeid = $_GET['typeid'];
$today = strtotime(date('Y-m-d')) + 86399;

if($list_type == 'project')
{
    $navigation .= ' <em>&rsaquo;</em> <a href="act.php?type=project">项目活动统计<a/>';
    if($typeid > 0)
    {
        $this_year = date('Y');
        $param_list = array();
        $index = 0;
        for($i=$this_year; $i>=2010; $i --)
        {
            if($i == $this_year)
            {
                if(date('m') > 9)
                {
                    $param_list[$index]['param'] = "&year=$i&half=f";
                    $param_list[$index]['name'] = "{$i}年秋季学期";
                }
                $index ++;
                $param_list[$index]['param'] = "&year=$i&half=s";
                $param_list[$index]['name'] = "{$i}年春季学期";
                $index ++;
            }
            else
            {
                $index ++;
                $param_list[$index]['param'] = "&year=$i&half=f";
                $param_list[$index]['name'] = "{$i}年秋季学期";
                $index ++;
                $param_list[$index]['param'] = "&year=$i&half=s";
                $param_list[$index]['name'] = "{$i}年春季学期";
            }
        }
        if($_GET['year'] == '')
        {
            $current_time = time();
        }
        else
        {
            if($_GET['half'] == 's')
            {
                $current_time = strtotime($_GET['year'] . '-03-01');
            }
            else
            {
                $current_time = strtotime($_GET['year'] . '-09-01');
            }
        }
        $current_month = date('m', $current_time);
        $spring_half_start = strtotime(date('Y',$current_time).'-03-01');
        $spring_half_end = strtotime(date('Y',$current_time).'-08-31');
        $fall_half_start = strtotime(date('Y',$current_time).'-09-01');
        $fall_half_end = strtotime(date('Y',$current_time).'-02-28' . ' +1 year');
        $sql = 'SELECT p.tid, p.showtime, t.typeid, t.subject, c.name  FROM ';
        $sql .= DB::table('ygclub_party') . " as p ";
        $sql .= "LEFT JOIN " . DB::table('forum_thread') . " as t on p.tid = t.tid ";
        $sql .= "LEFT JOIN " . DB::table('forum_threadclass') . " as c on t.typeid = c.typeid where 1 ";
        $sql .= "AND t.fid=2 ";
        $sql .= "AND t.subject NOT LIKE '%活动取消%' ";
        $sql .= "AND p.class = '阳光公益活动' ";
        $sql .= "AND t.typeid = '$typeid' ";

        if($current_month  >=3 && $current_month <= 8) 
        {
            $season = '春季';
            $sql .= "AND p.showtime >= $spring_half_start ";
            $sql .= "AND p.showtime <= $spring_half_end ";
            $current_year = date('Y', $spring_half_start);
        }
        else
        {
            $season = '秋季';
            $sql .= "AND p.showtime >= $fall_half_start ";
            $sql .= "AND p.showtime <= $fall_half_end ";
            $current_year = date('Y', $fall_half_start);
        }

        $sql .= "ORDER BY p.showtime ASC ";

        $result = DB::fetch_all($sql);
        $project_act = array();
        $party_tid_array = array(0);
        $project_name = '';
        foreach($result as $act_info)
        {
            $project_name = $act_info['name'];
            $party_tid_array[] = $act_info['tid'];
            $project_act[$act_info['tid']] = $act_info;
            $project_act[$act_info['tid']]['date_time'] = date('Y-m-d',$act_info['showtime']);
        }
        $party_tids = join(',', $party_tid_array);
        $project_act_list_count = count($project_act);
        
        $sql = 'SELECT uid, username, usertask, tid FROM ';
        $sql .= DB::table('ygclub_partyers') . ' WHERE 1 ';
        $sql .= "AND verified = 4 ";
        $sql .= "AND checkin = 1 ";
        $sql .= "AND tid IN ($party_tids) ";
        $sql .= "ORDER BY tid ASC, dateline ASC, uid ASC ";
        $result = DB::fetch_all($sql);
        $member_act = array();
        $member_act_total_count = array();
        foreach($result as $act_info)
        {
            if(trim($act_info['usertask']) == '') $act_info['usertask'] = '未指定分工';
            $member_act[$act_info['uid']]['username']= $act_info['username'];
            $member_act[$act_info['uid']]['total_act_count']  ++;
            if($project_act[$act_info['tid']]['usertask'][$act_info['usertask']] == '')
            { 
                $project_act[$act_info['tid']]['usertask'][$act_info['usertask']] .= $act_info['username'];
            }
            else
            {
                $project_act[$act_info['tid']]['usertask'][$act_info['usertask']] .= ", " . $act_info['username'];
            }
            $project_act[$act_info['tid']]['user_act_count'] ++;
            $weight = 0;
            if($act_info['usertask'] == '主讲人' || $act_info['usertask'] == '项目负责人')
            {
                $weight = 3;
            }
            elseif($act_info['usertask'] == '助教' || $act_info['usertask'] == '召集人')
            {
                $weight = 1;
            }
            if($act_info['usertask'] == '主讲人')
            {
                $weight = 3;
                $act_info['html_class'] = "ut1";
            }
            elseif($act_info['usertask'] == '助教')
            {
                $weight = 1;
                $act_info['html_class'] = "ut2";
            }
            $member_act_total_count[$act_info['uid']]['uid'] = $act_info['uid'];
            $member_act_total_count[$act_info['uid']]['user_act_count'] ++; 
            $member_act_total_count[$act_info['uid']]['weight'] += $weight; 
            $r1[$act_info['uid']] ++;
            $r2[$act_info['uid']] += $weight;
            $member_act[$act_info['uid']]['act'][$act_info['tid']]= $act_info;
        }
        $member_act_list_count = count($member_act);
        array_multisort($r1, SORT_DESC, $r2, SORT_DESC, $member_act_total_count);
        include template("ygclub/act_type_project_index");
        exit;
    }
    else
    {
        $sql = 'SELECT count(p.tid) as p_count, p.class, t.typeid, c.name, c.displayorder  FROM ';
        $sql .= DB::table('ygclub_party') . " as p ";
        $sql .= "LEFT JOIN " . DB::table('forum_thread') . " as t on p.tid = t.tid ";
        $sql .= "LEFT JOIN " . DB::table('forum_threadclass') . " as c on t.typeid = c.typeid where 1 ";
        $sql .= "AND t.fid=2 ";
        $sql .= "AND t.subject NOT LIKE '%活动取消%' ";
        $sql .= "AND p.class = '阳光公益活动' ";
        $sql .= "GROUP BY c.typeid ";
        $sql .= "ORDER BY c.displayorder, p_count desc ";
        $result = DB::fetch_all($sql);
        
        include template("ygclub/act_type_index");
        exit;
    }
}

// 展示所有人的活动统计
if ($starttime == '' || $endtime == '')
{
    $starttime =date('Y-m-01',strtotime(date('Y',$today).'-'.(date('m',$today)-1).'-01'));
    $endtime = date('Y-m-d');
    $starttime =date('Y-m-d',strtotime("$endtime -6 month"));
}
$starttime_template = $starttime;
$endtime_template = $endtime;
$starttime = strtotime($starttime);
$endtime = strtotime($endtime) + 86399;
$total_day = floor(($endtime - $starttime)/86400);

$verifiedArr = array('1'=> '等待确认','2'=> '取消申请','3'=> '拒绝申请','4'=> '已确认','5'=> '下次参加');
$partyClass = array('1'=>'阳光公益活动', '2'=>'培训交流', '3'=>'阳光例会', '4'=>'外部公益活动', '5'=>'休闲活动');
$party_date = array('1'=>'星期一','2'=>'星期二','3'=>'星期三','4'=>'星期四','5'=>'星期五','6'=>'星期六','7'=>'星期天');

if(!$uid)
{
    if($endtime <= $today)
    {
        $sql = 'SELECT pe.uid, pe.username, p.class, p.tid, t.subject  FROM ';
        $sql .= DB::table('ygclub_partyers') . " as pe LEFT JOIN " . DB::table('ygclub_party') . " as p on pe.tid = p.tid ";
        $sql .= "LEFT JOIN " . DB::table('forum_thread') . " as t on p.tid = t.tid where 1 ";
        $sql .= "AND pe.verified = 4 ";
        $sql .= "AND t.subject NOT LIKE '%活动取消%' ";
        $sql .= $starttime != '' ? "AND p.showtime>'".$starttime."' " : '';
        $sql .= $endtime != '' ? "AND pe.dateline<='".$endtime."' " : '';
        $sql .= $username != '' ? "AND pe.username like '%{$username}%' " : '';
        $act_user_list = array();
        $act_user_total_count = array();
        $party_total_count = 0;

        $party_class_count = array();
        foreach($partyClass as $value) $party_class_count[$value] = 0;
        $result = DB::fetch_all($sql);
        foreach($result as $act_user)
        {
            if(in_array($act_user['class'], $partyClass))
            {
                $party_class_count[$act_user['class']] ++;
                if(!isset($act_user_total_count[$act_user['uid']])) $act_user_total_count[$act_user['uid']] = 0;
                if($act_user['class'] == '阳光公益活动') $act_user_total_count[$act_user['uid']] ++;

                $act_user_list[$act_user['uid']]['total'] ++;
                $act_user_list[$act_user['uid']][$act_user['class']] ++;
                $act_user_list[$act_user['uid']]['username'] = $act_user['username'];
                $party_total_count ++;
            }
        }
        arsort($act_user_total_count);
        $party_uniq_user_count = count($act_user_total_count);
    }

    include template("ygclub/act_index");
}
else
{
    if($endtime <= $today)
    {
        $uid = intval($uid);
        $sql = 'SELECT pe.uid, pe.username, pe.config, pe.checkin, p.class, p.tid, p.ctid, p.showtime, t.subject  FROM ';
        $sql .= DB::table('ygclub_partyers') . " as pe LEFT JOIN " . DB::table('ygclub_party') . " as p on pe.tid = p.tid ";
        $sql .= "LEFT JOIN " . DB::table('forum_thread') . " as t on p.tid = t.tid where 1 ";
        $sql .= "AND pe.uid = '{$uid}'";
        $sql .= "AND pe.verified = 4 ";
        $sql .= "AND t.subject NOT LIKE '%活动取消%' ";
        $sql .= $starttime != '' ? "AND p.showtime>'".$starttime."' " : '';
        $sql .= $endtime != '' ? "AND pe.dateline<='".$endtime."' " : '';
        $sql .= "ORDER BY p.showtime DESC";
        $act_user_list = array();
        $user_name = '';
        $total_count = 0;
        $total_checkin_count = 0;
        $party_class_count = array();
        foreach($partyClass as $value) $party_class_count[$value] = 0;
        $checkAttr = array('0'=>'待确认', '1'=>'已参加', '2'=>'未参加');
        $result = DB::fetch_all($sql);
        
        foreach($result as $act_user)
        {
            if(in_array($act_user['class'], $partyClass))
            {
                $party_class_count[$act_user['class']] ++;
                if(!isset($party_class_checkin_count[$act_user['class']]))
                {
                    $party_class_checkin_count[$act_user['class']] = 0;
                }
                if($act_user['checkin'] == 1)
                {
                    $total_checkin_count ++;
                    $party_class_checkin_count[$act_user['class']] ++;
                }
                $user_name = $act_user['username'];
                $act_user['config'] = unserialize($act_user['config']); 
                $act_user['showtime_gm'] = gmdate('Y-m-d H:i',$act_user['showtime']+3600*$_DSESSION['timeoffset'])." (".$party_date[gmdate('N',$act_user['showtime']+3600*$_DSESSION['timeoffset'])].")";
                $act_user['checkin_txt'] = $checkAttr[$act_user['checkin']];
                $act_user_list[$act_user['class']]['tlist'][$act_user['tid']] = $act_user;
                $total_count ++;
            }
        }
    }
    $navigation .= ' <em>&rsaquo;</em> ' . $user_name;

    include template('ygclub/act_user_index');
}
?> 
