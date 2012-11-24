<?php

if (!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}
 
$identifier = 'actstat';
$slang = $scriptlang['actstat'];
$operation = $_G['gp_operation'];
echo $operation;
if ($operation == "list") {
    showsubmenu($slang['actstat_manage'], array(
        array($slang['actstat_add'], "plugins&operation=add&do=$pluginid&identifier=$identifier&pmod=admincp", 0),
        array($slang['actstat_list'], "plugins&operation=list&do=$pluginid&identifier=$identifier&pmod=admincp", 1),
    ));
    //include 'list.php';
} else {
    showsubmenu($slang['actstat_manage'], array(
        array($slang['actstat_add'], "plugins&operation=add&do=$pluginid&identifier=$identifier&pmod=admincp", 1),
        array($slang['actstat_list'], "plugins&operation=list&do=$pluginid&identifier=$identifier&pmod=admincp", 0),
    ));
    //include 'add.php';
}

if (submitcheck('submit')) {
    $title = dhtmlspecialchars(trim($_G['gp_title']));
    $conent = daddslashes($_G['gp_content']);
    $publish_time = TIMESTAMP;
    DB::query("UPDATE " . DB::table('my_notice') . " SET status='0' WHERE status='1'");
    DB::query("INSERT INTO " . DB::table('my_notice') . "(title,content,publish_time,status) values('$title','$conent','$publish_time','1')");
    cpmsg("{$slang['success']}", '', 'succeed');
}
 
showformheader("plugins&operation=add&do=$pluginid&identifier=$identifier&pmod=admincp", $extra);
echo "<div>{$slang['title']}</div>";
showsetting('', 'title', '', 'text');
echo "<div>{$slang['content']}</div>";
showsetting('', 'content', '', 'textarea');
showsubmit('submit', $slang['submit']);
showformfooter();

?>

