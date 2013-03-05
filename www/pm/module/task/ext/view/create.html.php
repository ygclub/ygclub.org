<?php
/**
 * The create view of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     task
 * @version     $Id: create.html.php 3887 2012-12-24 10:06:50Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../../common/view/header.html.php';?>
<?php include '../../../common/view/datepicker.html.php';?>
<?php include '../../../common/view/autocomplete.html.php';?>
<?php include '../../../common/view/chosen.html.php';?>
<?php include '../../../common/view/kindeditor.html.php';?>
<script> var holders = <?php echo json_encode($lang->task->placeholder);?></script>
<script language='javascript'> var userList = "<?php echo join(',', array_keys($users));?>".split(',');</script>
<script language='Javascript'>
$(function()
{
     $("#preview").colorbox({width:960, height:500, iframe:true, transition:'elastic', speed:350, scrolling:true});
})
</script>
<form method='post' enctype='multipart/form-data' target='hiddenwin' id='dataform'>
  <table align='center' class='table-1 a-left'> 
    <caption>
      <?php echo $lang->task->create;?>
    </caption>
    <input type="hidden" name="assignedTo[]" value="" /> 
    <input type="hidden" name="after" value="toStoryList" /> 
    <tr>
      <th class='rowhead'><?php echo $lang->task->name;?></th>
      <td>
      <?php echo html::input('name', '', "class='text-4'"); ?>
      </td>
    </tr>  

    <tr>
      <th class='rowhead'><?php echo $lang->task->type;?></th>
      <td>
<?php echo html::select('type', $lang->task->typeList, '', 'class=select-2 onchange="setOwners(this.value)"');?>
</td>
<tr>
<th class='rowhead'><?php echo $lang->task->pri;?></th>
<td>
<?php echo html::select('pri', $lang->task->priList, '', 'class=select-2');?> 
</td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->task->deadline;?></th>
      <td>
<?php echo html::input('deadline', '', "class='text-2 date'");?>
</td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->task->desc;?></th>
      <td><?php echo html::textarea('desc', '', "rows='15' class='area-1'");?></td>
    </tr>  
    <tr>
      <td colspan='2' class='a-center'><?php echo html::submitButton() . html::resetButton();?></td>
    </tr>
  </table>
</form>
<?php include '../../../common/view/footer.html.php';?>
