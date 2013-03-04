<?php
/**
 * The view of productplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     productplan
 * @version     $Id: view.html.php 4307 2013-01-29 03:03:32Z zhujinyonging@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<div id='titlebar' <?php if($plan->deleted) echo "class='deleted'";?>>PLAN #<?php echo $plan->id . ' ' . $plan->title;?></div>
<table class='cont-rt5'>
  <tr valign='top'>
    <td>
      <fieldset>
        <legend><?php echo $lang->productplan->desc;?></legend>
        <div class='content'><?php echo $plan->desc;?></div>
      </fieldset>
      <?php include '../../common/view/action.html.php';?>
      <div class='a-center f-16px strong'>
      <?php          
       $browseLink = $this->session->productPlanList ? $this->session->productPlanList : inlink('browse', "planID=$plan->id");
       if(!$plan->deleted)
       {
          common::printIcon('productplan', 'linkStory',"planID=$plan->id");
          common::printIcon('productplan', 'edit',     "planID=$plan->id");
          common::printIcon('productplan', 'delete',   "planID=$plan->id", '', 'button', '', 'hiddenwin');
       }
       common::printRPN($browseLink);
      ?>
      </div>
      <table class='table-1 tablesorter a-center'>
        <caption class='caption-tl'><?php echo $plan->title .$lang->colon . $lang->productplan->linkedStories;?></caption>
        <thead>
        <tr class='colhead'>
          <th class='w-id'><?php echo $lang->idAB;?></th>
          <th class='w-pri'><?php echo $lang->priAB;?></th>
          <th><?php echo $lang->story->title;?></th>
          <th><?php echo $lang->openedByAB;?></th>
          <th><?php echo $lang->assignedToAB;?></th>
          <th><?php echo $lang->story->estimateAB;?></th>
          <th><?php echo $lang->statusAB;?></th>
          <th><?php echo $lang->story->stageAB;?></th>
          <th class='w-50px {sorter:false}'><?php echo $lang->actions?></th>
        </tr>
        </thead>
        <tbody>
          <?php $totalEstimate = 0.0;?>
          <?php foreach($planStories as $story):?>
          <?php
             $viewLink = $this->createLink('story', 'view', "storyID=$story->id");
             $totalEstimate += $story->estimate;
           ?>
          <tr>
            <td><?php echo html::a($viewLink, $story->id);?></td>
            <td><span class='<?php echo 'pri' . $story->pri?>'><?php echo $story->pri;?></span></td>
            <td class='a-left nobr'><?php echo html::a($viewLink , $story->title);?></td>
            <td><?php echo $users[$story->openedBy];?></td>
            <td><?php echo $users[$story->assignedTo];?></td>
            <td><?php echo $story->estimate;?></td>
            <td><?php echo $lang->story->statusList[$story->status];?></td>
            <td><?php echo $lang->story->stageList[$story->stage];?></td>
            <td><?php common::printIcon('productplan', 'unlinkStory', "story=$story->id", '', 'list', '', 'hiddenwin');?></td>
          </tr>
          <?php endforeach;?>
        </tbody>
        <tfoot><tr><td colspan='9' class='a-right'><?php printf($lang->product->storySummary, count($planStories), $totalEstimate);?> </td></tr></tfoot>
      </table>
    </td>
    <td class="divider"></td>
    <td class="side">
      <fieldset>
        <legend><?php echo $lang->productplan->basicInfo?></legend>
        <table class='table-1 a-left'>
          <tr>
            <th width='25%' class='a-right'><?php echo $lang->productplan->title;?></th> 
            <td><?php echo $plan->title;?></th>
          </tr>
          <tr>
            <th class='rowhead'><?php echo $lang->productplan->begin;?></th>
            <td><?php echo $plan->begin;?></th>
          </tr>
          <tr>
            <th class='rowhead'><?php echo $lang->productplan->end;?></th>
            <td><?php echo $plan->end;?></th>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
</table>
<?php include '../../common/view/footer.html.php';?>
