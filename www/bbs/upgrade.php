<?php
/*$ Power By http://www.sanchi.com.cn/1175132/ && Author: 神飞 (QQ: 1175132) $*/

require_once './include/common.inc.php';

$db->query("ALTER TABLE {$tablepre}party ADD config MEDIUMTEXT NOT NULL;");
$db->query("ALTER TABLE {$tablepre}party ADD followed TINYINT(1) DEFAULT '0' NOT NULL;");
$db->query("ALTER TABLE {$tablepre}partyers ADD followed SMALLINT(6) DEFAULT '0' NOT NULL;");

echo "升级数据表成功，记得删除本文件！！";
?>