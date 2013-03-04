<?php
/**
 * The html template file of index method of index module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: index.html.php 4129 2013-01-18 01:58:14Z wwccss $
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/sparkline.html.php';?>
<?php include '../../common/view/colorize.html.php';?>
<h3>
  <?php echo html::a(inlink("index", "locate=no&status=all&projectID=$project->id"), $lang->project->all);?>
  <?php echo html::a(inlink("index", "locate=no&status=wait&projectID=$project->id"), $lang->project->statusList['wait']);?>
  <?php echo html::a(inlink("index", "locate=no&status=doing&projectID=$project->id"), $lang->project->statusList['doing']);?>
  <?php echo html::a(inlink("index", "locate=no&status=suspended&projectID=$project->id"), $lang->project->statusList['suspended']);?>
  <?php echo html::a(inlink("index", "locate=no&status=done&projectID=$project->id"), $lang->project->statusList['done']);?>
</h3>
<table class='table-1 fixed colored'>
  <tr class='colhead'>
    <th class='w-150px'><?php echo $lang->project->name;?></th>
    <th><?php echo $lang->project->code;?></th>
    <th><?php echo $lang->project->end;?></th>
    <th><?php echo $lang->project->status;?></th>
    <th><?php echo $lang->project->totalEstimate;?></th>
    <th><?php echo $lang->project->totalConsumed;?></th>
    <th><?php echo $lang->project->totalLeft;?></th>
    <th class='w-150px'><?php echo $lang->project->progess;?></th>
    <th class='w-100px'><?php echo $lang->project->burn;?></th>
  </tr>
  <?php foreach($projectStats as $project):?>
  <tr class='a-center'>
    <td class='a-left'><?php echo html::a($this->createLink('project', 'view', 'project=' . $project->id), $project->name);?></td>
    <td><?php echo $project->code;?></td>
    <td><?php echo $project->end;?></td>
    <td><?php echo $lang->project->statusList[$project->status];?></td>
    <td><?php echo $project->hours->totalEstimate;?></td>
    <td><?php echo $project->hours->totalConsumed;?></td>
    <td><?php echo $project->hours->totalLeft;?></td>
    <td class='a-left w-150px'>
      <img src='<?php echo $defaultTheme;?>images/main/green.png' width=<?php echo $project->hours->progress;?> height='13' text-align: />
      <small><?php echo $project->hours->progress;?>%</small>
    </td>
    <td class='projectline a-left' values='<?php echo join(',', $project->burns);?>'></td>
 </tr>
 <?php endforeach;?>
</table>
<?php include '../../common/view/footer.html.php';?>
