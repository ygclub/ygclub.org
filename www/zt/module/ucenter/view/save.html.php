<?php
/**
 * The config email view file of mail module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     mail
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>

<table class='table-6' align='center'>
<caption><?php echo $lang->ucenter->configInfo ?></caption>
<tr>
<td>
<?php echo html::textArea('config', $configstr, "rows='15' class='area-1 f-12px'");?>
</tr>
<tr>
<td><?php echo $lang->ucenter->saveConfig . $configPath . '\config.inc.php' ?></td>
</tr>
<tr>
<td><?php echo $lang->ucenter->createFile ?></td>
</tr>
</table>
