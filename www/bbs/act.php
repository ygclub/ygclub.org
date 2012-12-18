<?php
require_once "include/common.inc.php";
$thisrc = "act.php";

require_once DISCUZ_ROOT."./forumdata/cache/cache_forums.php";

// 展示所有人的活动统计
if ($discuz_uid)
{
    $today = strtotime(date('Y-m-d'));
    if ($starttime == '' || $endtime == '')
    {
        $starttime =date('Y-m-01',strtotime(date('Y',$today).'-'.(date('m',$today)-1).'-01'));
        $endtime = date('Y-m-d');
        $starttime =date('Y-m-d',strtotime("$endtime -6 month"));
    }
    $starttime_template = $starttime;
    $endtime_template = $endtime;
    $starttime = strtotime($starttime);
    $endtime = strtotime($endtime);
    $total_day = floor(($endtime - $starttime)/86400);

    $verifiedArr = array('1'=> '等待确认','2'=> '取消申请','3'=> '拒绝申请','4'=> '已确认','5'=> '下次参加');
    $partyClass = array('1'=>'阳光公益活动', '2'=>'培训交流', '3'=>'阳光例会', '4'=>'外部公益活动', '5'=>'休闲活动');
    $party_date = array('1'=>'星期一','2'=>'星期二','3'=>'星期三','4'=>'星期四','5'=>'星期五','6'=>'星期六','7'=>'星期天');

    if(!$uid)
    {
        if($endtime <= $today)
        {
            $sql = 'SELECT pe.uid, pe.username, p.class, p.tid, t.subject  FROM ';
            $sql .= "{$tablepre}partyers as pe LEFT JOIN {$tablepre}party as p on pe.tid = p.tid LEFT JOIN {$tablepre}threads as t on p.tid = t.tid where 1 ";
            $sql .= "AND pe.verified = 4 ";
            $sql .= "AND t.subject NOT LIKE '%活动取消%' ";
            $sql .= $starttime != '' ? "AND p.showtime>'".$starttime."' " : '';
            $sql .= $endtime != '' ? "AND p.showtime<='".$endtime."' " : '';
            $sql .= $username != '' ? "AND pe.username like '%{$username}%' " : '';
            $act_user_list = array();
            $act_user_total_count = array();
            $party_total_count = 0;

            $party_class_count = array();
            foreach($partyClass as $value) $party_class_count[$value] = 0;

            $query = $db->query($sql);
            while ($act_user = $db->fetch_array($query))
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

        include template("act_index");
    }
    else
    {
        if($endtime <= $today)
        {
            $uid = intval($uid);
            $sql = 'SELECT pe.uid, pe.username, pe.config, pe.checkin, p.class, p.tid, p.ctid, p.showtime, t.subject  FROM ';
            $sql .= "{$tablepre}partyers as pe LEFT JOIN {$tablepre}party as p on pe.tid = p.tid LEFT JOIN {$tablepre}threads as t on p.tid = t.tid where 1 ";
            $sql .= "AND pe.uid = '{$uid}'";
            $sql .= "AND pe.verified = 4 ";
            $sql .= "AND t.subject NOT LIKE '%活动取消%' ";
            $sql .= $starttime != '' ? "AND p.showtime>'".$starttime."' " : '';
            $sql .= $endtime != '' ? "AND p.showtime<='".$endtime."' " : '';
            $sql .= "ORDER BY p.showtime DESC";
            $act_user_list = array();
            $user_name = '';
            $total_count = 0;
            $query = $db->query($sql);
            $party_class_count = array();
            foreach($partyClass as $value) $party_class_count[$value] = 0;
            $checkAttr = array('0'=>'待确认', '1'=>'已参加', '2'=>'未参加');
            while ($act_user = $db->fetch_array($query))
            {
                if(in_array($act_user['class'], $partyClass))
                {
                    $party_class_count[$act_user['class']] ++;
                    $user_name = $act_user['username'];
                    $act_user['config'] = unserialize($act_user['config']); 
                    $act_user['showtime'] = gmdate('Y-m-d H:i',$act_user['showtime']+3600*$_DSESSION['timeoffset'])." (".$party_date[gmdate('N',$act_user['showtime']+3600*$_DSESSION['timeoffset'])].")";
                    $act_user['checkin_txt'] = $checkAttr[$act_user['checkin']];
                    $act_user_list[$act_user['class']]['tlist'][$act_user['tid']] = $act_user;
                    $total_count ++;
                }
            }
        }

        include template('act_user_index');
    }
}
else
{
    include template('act_index');
}
?> 
