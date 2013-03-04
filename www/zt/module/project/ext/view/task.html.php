<?php
/**
 * The task view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: task.html.php 3857 2012-12-19 08:05:30Z zhujinyonging@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../../common/view/header.html.php';?>
<?php include '../../../common/view/datepicker.html.php';?>
<?php include '../../../common/view/colorize.html.php';?>
<?php include '../../../common/view/treeview.html.php';?>
<?php include './taskheader.html.php';?>
<script language='Javascript'>
var browseType  = '<?php echo $browseType;?>';
</script>
<div id='querybox' class='<?php if($browseType !='bysearch') echo 'hidden';?>'></div>
<table class='cont-lt1'>
  <tr valign='top'>
    <?php if($browseType =='byproject'):?>
    <td class='side'>
      <div class='box-title'><?php echo $lang->project->projectTasks;?></div>
      <div class='box-content'><?php echo $projectTree;?></div>
    </td>
    <td class='divider'></td>
    <?php endif?>
    <?php if($browseType =='bymodule'):?>
    <td class='side'>
      <div class='box-title'><?php echo $project->name;?></div>
      <div class='box-content'>
        <?php echo $moduleTree;?>
        <div class='a-right'>
          <?php common::printLink('project', 'edit',   "projectID=$projectID", $lang->edit);?>
          <?php common::printLink('project', 'delete', "projectID=$projectID&confirm=no",   $lang->delete, 'hiddenwin');?>
          <?php common::printLink('tree', 'browse',    "rootID=$projectID&view=task", $lang->tree->manage);?>
          <?php common::printLink('tree', 'fix',       "root=$projectID&type=task", $lang->tree->fix, 'hiddenwin');?>
        </div>
      </div>
    </td>
    <td class='divider'></td>
    <?php endif;?>
    <td>
      <form method='post' action='<?php echo $this->createLink('task', 'batchEdit', "projectID=$project->id&from=projectTask&orderBy=$orderBy");?>'>
        <table class='table-1 fixed colored tablesorter datatable'>
          <?php $vars = "projectID=$project->id&status=$status&parma=$param&orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage"; ?>
          <thead>
          <tr class='colhead'>
            <th class='w-pri'>    <?php common::printOrderLink('id',        $orderBy, $vars, $lang->idAB);?></th>
            <th class='w-pri'>   <?php common::printOrderLink('pri',       $orderBy, $vars, $lang->task->pri);?></th>
            <th class='w-p30'>   <?php common::printOrderLink('name',      $orderBy, $vars, $lang->task->name);?></th>
            <th class='w-status'><?php common::printOrderLink('status',    $orderBy, $vars, $lang->statusAB);?></th>

            <th class='w-user'>  <?php common::printOrderLink('assignedTo',$orderBy, $vars, $lang->task->assignedToAB);?></th>
            <?php if($this->cookie->windowWidth > $this->config->wideSize):?>
            <th class='w-id'>    <?php common::printOrderLink('openedDate',$orderBy, $vars, $lang->task->openedDate);?></th>
            <?php endif;?>

            <th class='w-70px'>  <?php common::printOrderLink('deadline',  $orderBy, $vars, $lang->task->deadline);?></th>


            <?php if($app->user->account != 'guest'): ?>
            <th class='w-100px {sorter:false}'><?php echo $lang->actions;?></th>
            <?php endif;?>
          </tr>
          </thead>
          <?php  
            $taskSum = $statusWait = $statusDone = $statusDoing = $statusClosed = $statusCancel = 0;  
            $totalEstimate = $totalConsumed = $totalLeft = 0.0;
          ?>
          <tbody>
          <?php foreach($tasks as $task):?>
          <?php $class = $task->assignedTo == $app->user->account ? 'style=color:red' : '';?>
          <?php  
          $totalEstimate  += $task->estimate;
          $totalConsumed  += $task->consumed;
          $totalLeft      += (($task->status == 'cancel' or $task->closedReason == 'cancel') ? 0 : $task->left);
          $statusVar      = 'status' . ucfirst($task->status);
          $$statusVar ++;
          ?>
          <tr class='a-center'>
            <td>
              <input type='checkbox' name='taskIDList[]'  value='<?php echo $task->id;?>'/> 
              <?php if(!common::printLink('task', 'view', "task=$task->id", sprintf('%03d', $task->id))) printf('%03d', $task->id);?>
            </td>
            <td><span class='<?php echo 'pri'. $lang->task->priList[$task->pri]?>'><?php echo $lang->task->priList[$task->pri];?></span></td>
            <td class='a-left nobr'>
              <?php 
              if(!common::printLink('task', 'view', "task=$task->id", $task->name)) echo $task->name;
              if($task->fromBug) echo html::a($this->createLink('bug', 'view', "id=$task->fromBug"), "[BUG#$task->fromBug]", '_blank', "class='bug'");
              ?>
            </td>
            <td class=<?php echo $task->status;?> >
              <?php
              $storyChanged = ($task->storyStatus == 'active' and $task->latestStoryVersion > $task->storyVersion);
              $storyChanged ? print("<span class='warning'>{$lang->story->changed}</span> ") : print($lang->task->statusList[$task->status]);
              ?>
            </td>

            <td <?php echo $class;?>><?php echo $task->assignedTo == 'closed' ? 'Closed' : $task->assignedToRealName;?></td>

            <?php if($this->cookie->windowWidth > $this->config->wideSize):?>
            <td><?php echo substr($task->openedDate, 0, 10);?></th>
            <?php endif;?>

            <td class=<?php if(isset($task->delay)) echo 'delayed';?>><?php if(substr($task->deadline, 0, 10) > 0) echo substr($task->deadline, 0, 10);?></td>

            <?php if($app->user->account != 'guest'): ?>
            <td class='a-center'>
              <?php
              if($browseType == 'needconfirm') common::printLink('task', 'confirmStoryChange', "taskid=$task->id", $lang->confirm, 'hiddenwin');
              common::printIcon('task', 'assignTo', "projectID=$task->project&taskID=$task->id", $task, 'list');
              echo "&nbsp;";
              common::printIcon('task', 'finish', "taskID=$task->id", $task, 'list');
              echo "&nbsp;";
              common::printIcon('task', 'close',  "taskID=$task->id", $task, 'list');
              if($this->task->isClickable($task, 'edit'))   common::printIcon('task', 'edit',"taskID=$task->id", '', 'list');
              ?>
            </td>
            <?php endif;?>
          </tr>
          <?php endforeach;?>
          </tbody>
          <tfoot>
            <tr>
              <?php if($app->user->account != 'guest'): ?>
              <?php $columns = $this->cookie->windowWidth > $this->config->wideSize ? 8 : 6;?>
              <?php else:?>
              <?php $columns = $this->cookie->windowWidth > $this->config->wideSize ? 7 : 5;?>
              <?php endif;?>
              <td colspan='<?php echo $columns;?>'>
                <div class='f-left'>
                <?php
                if(common::hasPriv('task', 'batchEdit') and count($tasks))echo html::selectAll() . html::selectReverse() . html::submitButton($lang->task->batchEdit);
                printf($lang->project->taskSummary, count($tasks), $statusWait, $statusDoing, $totalEstimate, $totalConsumed, $totalLeft);
                ?>
                </div>
                <?php $pager->show();?>
              </td>
            </tr>
          </tfoot>
        </table>
      </form>
    </td>
  </tr>
</table>
<script language='javascript'>
$('#project<?php echo $projectID;?>').addClass('active')
$('#<?php echo $browseType;?>Tab').addClass('active')
statusActive = '<?php echo isset($lang->project->statusSelects[$browseType]);?>';
if(statusActive) $('#statusTab').addClass('active')
</script>
<?php include '../../../common/view/footer.html.php';?>
