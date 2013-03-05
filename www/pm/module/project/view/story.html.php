<?php
/**
 * The story view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: story.html.php 4391 2013-02-20 00:36:56Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<form method='post' id='projectStoryForm'>
  <table class='table-1 fixed colored tablesorter datatable'>
    <caption class='caption-tl pb-10px'>
      <div class='f-left'><?php echo $lang->project->story;?></div>
      <div class='f-right'>
        <?php 
        common::printIcon('story', 'export', "productID=$productID&orderBy=id_desc");

        $this->lang->story->create = $this->lang->project->createStory;
        if($productID) common::printIcon('story', 'create', "productID=$productID&moduleID=0&story=0&project=$project->id");

        common::printIcon('project', 'linkStory', "project=$project->id");
        ?>
      </div>
    </caption>
    <thead>
      <tr class='colhead'>
      <?php $vars = "projectID={$project->id}&orderBy=%s"; ?>
        <th class='w-id  {sorter:false}'>    <?php common::printOrderLink('id',         $orderBy, $vars, $lang->idAB);?></th>
        <th class='w-pri {sorter:false}'>    <?php common::printOrderLink('pri',        $orderBy, $vars, $lang->priAB);?></th>
        <th class='{sorter:false}'>          <?php common::printOrderLink('title',      $orderBy, $vars, $lang->story->title);?></th>
        <th class='w-user {sorter:false}'>   <?php common::printOrderLink('openedBy',   $orderBy, $vars, $lang->openedByAB);?></th>
        <th class='w-hour {sorter:false}'>   <?php common::printOrderLink('assignedTo', $orderBy, $vars, $lang->assignedToAB);?></th>
        <th class='w-hour {sorter:false}'>   <?php common::printOrderLink('estimate',   $orderBy, $vars, $lang->story->estimateAB);?></th>
        <th class='w-hour {sorter:false}'>   <?php common::printOrderLink('status',     $orderBy, $vars, $lang->statusAB);?></th>
        <th class='w-status {sorter:false}'> <?php common::printOrderLink('stage',      $orderBy, $vars, $lang->story->stageAB);?></th>
        <th class='w-50px'>                  <?php echo $lang->story->taskCount;?></th>
        <th class='w-80px {sorter:false}'>  <?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody>
      <?php $totalEstimate = 0;?>
      <?php foreach($stories as $key => $story):?>
      <?php
      $storyLink      = $this->createLink('story', 'view', "storyID=$story->id");
      $totalEstimate += $story->estimate;
      ?>
      <tr class='a-center'>
        <td>
          <input type='checkbox' name='storyIDList[<?php echo $story->id;?>]' value='<?php echo $story->id;?>' /> 
          <?php echo html::a($storyLink, sprintf('%03d', $story->id));?>
        </td>
        <td><span class='<?php echo 'pri' . $lang->story->priList[$story->pri]?>'><?php echo $lang->story->priList[$story->pri];?></span></td>
        <td class='a-left' title="<?php echo $story->title?>"><?php echo html::a($storyLink,$story->title);?></td>
        <td><?php echo $users[$story->openedBy];?></td>
        <td><?php echo $users[$story->assignedTo];?></td>
        <td><?php echo $story->estimate;?></td>
        <td class='<?php echo $story->status;?>'><?php echo $lang->story->statusList[$story->status];?></td>
        <td><?php echo $lang->story->stageList[$story->stage];?></td>
        <td class='linkbox'>
          <?php
          $tasksLink = $this->createLink('story', 'tasks', "storyID=$story->id&projectID=$project->id");
          $storyTasks[$story->id] > 0 ? print(html::a($tasksLink, $storyTasks[$story->id], '', 'class="iframe"')) : print(0);
          ?> 
        </td>
        <td class='a-center'>
          <?php 
          $param = "projectID={$project->id}&story={$story->id}";

          $lang->task->create = $lang->project->wbs;
          common::printIcon('task', 'create', $param, '', 'list');

          $lang->task->batchCreate = $lang->project->batchWBS;
          common::printIcon('task', 'batchCreate', $param . "&iframe=1", '', 'list', '', '', 'batchWBS', true);

          $lang->project->unlinkStory = $lang->unlink;
          common::printIcon('project', 'unlinkStory', $param, '', 'list', '', 'hiddenwin');
          ?>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan='10'>
          <div class='f-left'>
          <?php
          if(count($stories))
          {
              echo html::selectAll() . html::selectReverse();

              if(common::hasPriv('story', 'batchEdit'))
              {
                  $actionLink = $this->createLink('story', 'batchEdit', "from=projectStory&productID=0&projectID=$project->id&orderBy=$orderBy");
                  echo html::commonButton($lang->edit, "onclick=\"changeAction('projectStoryForm', 'batchEdit', '$actionLink')\"");
              }
              if(common::hasPriv('story', 'batchClose'))
              {
                  $actionLink = $this->createLink('story', 'batchClose', "from=projectStory&productID=0&projectID=$project->id&orderBy=$orderBy");
                  echo html::commonButton($lang->close, "onclick=\"changeAction('projectStoryForm', 'batchClose', '$actionLink')\"");
              }
          }
          printf($lang->product->storySummary, count($stories), $totalEstimate);
          ?>
          </div>
        </td>
      </tr>
    </tfoot>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
