<?php
/*$ Power By soft.sanchi.com.cn && Author: 神飞 (QQ: 1175132) $*/

require_once './include/common.inc.php';

$db->query("drop table {$tablepre}party");
$db->query("drop table {$tablepre}partyers");

echo '删除成功。记得删除此文件。';

?>