<?php
if (!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}

$slang = lang('plugin/ygclub_party');
$identifier = 'ygclub_party';
$base_link = "plugins&operation=config&do=$pluginid&identifier=$identifier";

loadcache(array('usergroups','forums'));
//print_r($_G['cache']['forums']);

if($_GET['act'] == "") {
    ygclub_party_showsubmenu();
    
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
                showtablerow('', 
                    array('class="td30"', 'class="td30"'), 
                    array($forum['name'], "<a href='" . ADMINSCRIPT . "?action=$base_link&fid=$forum[fid]&act=set'>" . $forum['fid'] . "</a>"));
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
}
elseif($_GET['act'] == "set"){
    ygclub_party_showsubmenu();
/*
 		sc_admin();
		$confile = DISCUZ_ROOT."./forumdata/party/con_{$fid}_fig.php";
		if (file_exists($confile)){
			$fp = fopen($confile, 'r');
			$condata .= @fread($fp, filesize($confile));
			fclose($fp);
			$condata = unserialize($condata);
			$condata['optionfield'] = sc_order_fields($condata['optionfield']);
			$condata['signfield'] = sc_order_fields($condata['signfield']);
		}
		if ($step=='post'){
			$party['optionfield'] = sc_ck_fields($party['optionfield']);
			$party['signfield'] = sc_ck_fields($party['signfield']);
			$party = serialize($party);
			if ($fp = @fopen($confile,'wb')){
				fwrite($fp,$party);
				fclose($fp);
				showmessage("设置成功","$thisrc?act=set&fid=$fid");
			}else{
				exit('Can not write to cache files, please check directory ./forumdata/ and ./forumdata/party/ .');
			}
		}
		$forumname = $_DCACHE['forums'][$fid]['name'];
		require_once DISCUZ_ROOT."./forumdata/cache/cache_usergroups.php";
		$party_body_display = $condata && $condata['allowed']==1 ? "" : "none" ;
 */
    include template('ygclub_party:admin_party_set');
}

function ygclub_party_showsubmenu()
{
    global $slang, $identifier, $base_link;
    showsubmenu($slang['party_manage'], array(
        array($slang['party_config'], "plugins&operation=config&do=$pluginid&identifier=$identifier", 1)
    ));

}
?>
