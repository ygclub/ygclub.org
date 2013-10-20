<?php
if (!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}

include_once('func.inc.php');

$slang = lang('plugin/ygclub_party');
$identifier = 'ygclub_party';
$base_link = "plugins&operation=config&do=$pluginid&identifier=$identifier";

loadcache(array('forums','usergroups'));
if($_GET['act'] == "") {
    
    showtableheader($slang['forum_setting']. '版块设置');
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
                    array($forum['name'], "<a href='" . ADMINSCRIPT . "?action=$base_link&fid=$forum[fid]&act=set'>" . '设置' . "</a>"));
                $lastfid = 0;
                if(!empty($subs[$forum['fid']])) {
                    foreach ($subs[$forum['fid']] as $sub) {
                        showtablerow('', array(), array('&nbsp;|-- ' . $sub['name'], "<a href='" . ADMINSCRIPT . "?action=$base_link&fid=$sub[fid]&act=set'>" . '设置' . "</a>"));
                        $lastfid = $sub['fid'];
                    }
                }
            }
        }
    }
    showtablefooter();
}
elseif($_GET['act'] == "set"){
    $fid = $_GET['fid'];
    require_once (DISCUZ_ROOT.'./source/discuz_version.php');
    $cachedir_party = DISCUZ_VERSION == "X2" ? './data/cache/cache_' : './data/sysdata/cache_';
    $cachename_party = "{$identifier}_forum_{$fid}_config";

    if(file_exists(DISCUZ_ROOT . $cachedir_party . $cachename_party . '.php'))
    {
        include_once (DISCUZ_ROOT . $cachedir_party . $cachename_party . '.php');
        $condata['signfield'] = ygclub_party_order_fields($condata['signfield']);
    }

    if($_POST['partysubmit'])
    {
        $_POST['party']['signfield'] = ygclub_party_ck_fields($_POST['party']['signfield']);
        $cacheArray .= "\$condata = " . arrayeval($_POST['party']) . ";\n"; 
        writetocache($cachename_party, $cacheArray);
        cpmsg("ygclub_party:update_forum_condata_succeed", "action=$base_link&fid=$fid&act=set", 'succeed');
    }
    include_once template('ygclub_party:admin_party_set');
}

?>
