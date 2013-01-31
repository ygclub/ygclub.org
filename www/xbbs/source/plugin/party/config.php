<?php
if (!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}
 
if (submitcheck('submit')) {
    $title = dhtmlspecialchars(trim($_G['gp_title']));
    $conent = daddslashes($_G['gp_content']);
    $publish_time = TIMESTAMP;
    DB::query("UPDATE " . DB::table('my_party') . " SET status='0' WHERE status='1'");
    DB::query("INSERT INTO " . DB::table('my_party') . "(title,content,publish_time,status) values('$title','$conent','$publish_time','1')");
    cpmsg("{$slang['success']}", '', 'succeed');
}


showtableheader('');
showsubtitle(array('forums_admin_name',  $lang['config']));

$query = C::t('forum_forum')->fetch_all_forum_for_sub_order();
$groups = $forums = $subs = $fids = $showed = array();
foreach($query as $forum) {
    if($forum['type'] == 'group') {
        $groups[$forum['fid']] = $forum;
    } elseif($forum['type'] == 'sub') {
        $subs[$forum['fup']][] = $forum;
    } else {
        $forums[$forum['fup']][] = $forum;
    }
    $fids[] = $forum['fid'];
}
foreach ($groups as $id => $gforum) {
    if(!empty($forums[$id])) {
        foreach ($forums[$id] as $forum) {
            showtablerow('', array(), array($forum['name'], $forum['fid']));
            $lastfid = 0;
            if(!empty($subs[$forum['fid']])) {
                foreach ($subs[$forum['fid']] as $sub) {
                    showtablerow('', array(), array('&nbsp;|-- ' . $sub['name'], $sub['fid']));
                    $lastfid = $sub['fid'];
                }
            }
        }
    }
}

showtablefooter();
?>
