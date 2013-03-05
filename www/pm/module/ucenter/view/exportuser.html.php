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

<?php include '../../common/view/header.lite.html.php';?>
<form method='post' target='hiddenwin'>
  <table align='center' class='table-1 a-left'> 
    <caption><?php echo $lang->ucenter->exportuser;?></caption>
    <tr>
      <th class='rowhead'><?php echo $lang->ucenter->alluser;?><input type='checkbox' onclick='checkall(this, "group");'></th>
      <td id='group' class='f-14px pv-10px'><?php $i = 1;?>
        <?php foreach($Users as $account => $realname):?>
        <div class='w-p10 f-left'><?php echo '<span>' . html::checkbox('members', array($account => $realname), $account) . '</span>';?></div>
        <?php if(($i %  8) == 0) echo "<div class='c-both'></div>"; $i ++;?>
        <?php endforeach;?>
      </td>
    </tr>

	<tr>
		<th class='rowhead'></th>
		<td class='f-14px pv-10px' id="msg"></td>
	</tr>
    <tr>
      <th class='rowhead'></th>
      <td class='a-center'>
        <?php 
        echo html::submitButton("导出");

        echo html::hidden('foo'); // Just a var, to make sure $_POST is not empty.
        ?>
      </td>
    </tr>
  </table>
</form>
<?php include '../../common/view/footer.lite.html.php';?>
