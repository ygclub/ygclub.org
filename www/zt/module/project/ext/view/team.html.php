<?php
/**
 * The team view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: team.html.php 2605 2012-02-21 07:22:58Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../../common/view/header.html.php';?>
<?php include '../../../common/view/tablesorter.html.php';?>
<table align='center' class='table-5 tablesorter'>
  <thead>
  <tr class='colhead'>
    <th><?php echo $lang->team->account;?></th>
    <th><?php echo $lang->team->join;?></th>
    <th><?php echo $lang->team->role;?></th>
    <?php if(common::hasPriv('project', 'unlinkmember')) echo "<th>$lang->actions</th>";?>
  </tr>
  </thead>
  <tbody>
  <?php $totalHours = 0;?>
  <?php foreach($teamMembers as $member):?>
  <tr class='a-center'>
    <td>
    <?php 
    common::hasPriv('user', 'view') ? print(html::a($this->createLink('user', 'view', "account=$member->account"), $member->realname)) : print($member->realname);
    $memberHours = $member->days * $member->hours;
    $totalHours  += $memberHours;
    ?>
    </td>
    <td><?php echo $member->join;?></td>
    <td><?php echo $member->role;?></td>
    <?php if(common::hasPriv('project', 'unlinkmember')) echo "<td>" . html::a($this->createLink('project', 'unlinkmember', "projectID=$project->id&account=$member->account"), $lang->project->unlinkMember, 'hiddenwin') . '</td>';?>
  </tr>
  <?php endforeach;?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="3">加入团队后，你的名字会出现在任务的指派人列表中，如果需要，请联系 leeyupeng@gmail.com</td>
</tr>
</tfoot>     
</table>
<?php include '../../../common/view/footer.html.php';?>
