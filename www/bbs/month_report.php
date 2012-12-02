<?php
	require_once "include/common.inc.php";
	$thisrc = "month_report.php";

	require_once DISCUZ_ROOT."./forumdata/cache/cache_forums.php";
  if ($discuz_uid){
    $today = strtotime(date('Y-m-d'));
    if ($starttime == '' || $endtime == '')
    {
        $starttime =date('Y-m-01',strtotime(date('Y',$today).'-'.(date('m',$today)-1).'-01'));
        $endtime =date('Y-m-d',strtotime("$starttime +1 month -1 day"));
    }
    $starttime_template = $starttime;
    $endtime_template = $endtime;
       $starttime = strtotime($starttime);
       $endtime = strtotime($endtime);

       if($endtime - $starttime > 31*24*3600) die('Eorror: out of 1 month');

       $sql = '1';
		   $sql .= $starttime != '' ? " AND dateline>'".$starttime."'" : '';
		   $sql .= $endtime != '' ? " AND dateline<='".$endtime."'" : '';
       $fids = array();
       $fids[2] = array();  // 项目活动
       $fids[11] = array(); // 课程建设
       $fids[31] = array(); // 视频集锦
       $fids[28] = array(); // 团队建设
       $fids[10] = array(); // 阳光人物
       $fids[22] = array(); // 月报存档
       $fids[34] = array(); // 阳光招募
       $fids[12] = array(); // 新人请进
       $fids[20] = array(); // 阳光志愿者
       $fids[13] = array(); // 阳光事务
       $fids[32] = array(); // 项目考察
       $fids[33] = array(); // 其他外联
       $fids[4] = array(); // 阳光交流
       $fids[21] = array(); // 阳光交流区
       $fids[30] = array(); // 其他公益活动
       $fids[5] = array(); // 怀旧经典

       $i = 0; 
       $current_fid = 0;
       $query = $db->query("SELECT fid, tid, readperm, subject, authorid, author, dateline FROM {$tablepre}threads WHERE $sql ORDER by fid, dateline");
       while ($thread = $db->fetch_array($query)){
         $fids[$thread[fid]]['fid_name'] = $_DCACHE[forums][$thread[fid]][name];
         $fids[$thread[fid]]['fids'][$i] = array();
         $fids[$thread[fid]]['fids'][$i]['subject_link'] = "<a href=\"viewthread.php?tid=$thread[tid]\" target=\"_blank\">$thread[subject]</a>";
         $fids[$thread[fid]]['fids'][$i]['author_link'] = "<a href=\"space.php?uid=$thread[authorid]\" target=\"_blank\">$thread[author]</a>";
          $i ++;
       }
       foreach($fids as $key => $value)
       {
         if(count($value) == 0) unset($fids[$key]);
       }
       unset($fids[22]);
       $sql = '1';
		   $sql .= $starttime != '' ? " AND regdate>'".$starttime."'" : '';
       $sql .= $endtime != '' ? " AND regdate <='".$endtime."'" : '';
       $sql .= " AND groupid NOT IN (4,5,6,7,8,9) ";
       $users = array();
       $i = 0;
       $query = $db->query("SELECT uid, username FROM {$tablepre}members WHERE $sql ORDER by regdate");
       while ($user = $db->fetch_array($query)){
				 $users[]= "<a href=\"space.php?uid=$user[uid]\" target=\"_blank\">$user[username]</a>";
       }
       $users_count = count($users);
       $users_link = join(', ', $users);

			 include template('month_report');
  }
  else
  {
	showmessage("此功能仅限登录用户查看。","logging.php?action=login");
    //die('<a href="logging.php?action=login">Please login first</a>');
  }
?> 
