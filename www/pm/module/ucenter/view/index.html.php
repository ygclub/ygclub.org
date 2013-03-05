<?php
/**
 * The editor view file of dir module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     editor
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>

<?php include '../../common/view/header.html.php';?>
<table class='table-1 bd-none'>
  <tr valign='top'>
    <td width='200'>
      <div class='box-title'><?php echo $lang->ucenter->setlist?></div>
      <div class='box-content'>
        <ul>
            <li><a href="?m=ucenter&f=set&moduleDir=admin" target="editWin"><?php echo $lang->ucenter->settings?></a></li>
            <li><a href="?m=ucenter&f=exportuser&moduleDir=admin" target="editWin"><?php echo $lang->ucenter->exportuser?></a></li>
        </ul>
      </div>
    </td>
    <td width='20'><iframe frameborder='0' name='extendWin' id='extendWin' width='100%'></iframe></td>
    <td><iframe frameborder='0' name='editWin' id='editWin' width='100%' src="?m=ucenter&f=set&moduleDir=admin"></iframe></td>
  </tr>
</table>
<?php include '../../common/view/footer.html.php';?>
