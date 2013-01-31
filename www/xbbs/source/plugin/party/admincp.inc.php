<?php
if (!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}

$identifier = 'party';
$slang = $scriptlang['party'];
$operation = $_G['gp_operation'];

if ($operation == "list") {
    showsubmenu($slang['party_manage'], array(
        array($slang['party_config'], "plugins&operation=config&do=$pluginid&identifier=$identifier&pmod=admincp", 0),
        array($slang['party_list'], "plugins&operation=list&do=$pluginid&identifier=$identifier&pmod=admincp", 1),
    ));
    //include 'list.php';
} else {
    showsubmenu($slang['party_manage'], array(
        array($slang['party_config'], "plugins&operation=config&do=$pluginid&identifier=$identifier&pmod=admincp", 1),
        array($slang['party_list'], "plugins&operation=list&do=$pluginid&identifier=$identifier&pmod=admincp", 0),
    ));
    include 'config.php';
}
?>
