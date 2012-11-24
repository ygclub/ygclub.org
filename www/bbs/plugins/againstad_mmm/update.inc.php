<?php


if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

require_once './include/common.inc.php';
require DISCUZ_ROOT.'./forumdata/cache/plugin_promotion.php';
include_once DISCUZ_ROOT . './forumdata/plugins/promotion.lang.php';

$url = "http://topfg.91.tc/hack/update.htm";
$fp = @fopen($url, "r") or die('wrong');
$fcontents = file_get_contents($url);
eregi("againstad_mmm_start(.*)againstad_mmm_end", $fcontents, $regs);
$againstad_mmm_version='1.3';
echo "<hr>";
echo $regs[1];
echo "<br>";
echo "您当前的版本号为";
echo $againstad_mmm_version;
echo "<hr>";
echo "本插件作者为米米猫，版权所有。任何翻改插件将无法获得技术支持。";


?>

