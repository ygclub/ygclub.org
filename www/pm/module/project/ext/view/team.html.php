<?php
/**
 * The team view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: team.html.php 4143 2013-01-18 07:01:06Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../../common/view/header.html.php';?>
<?php include '../../../common/view/tablesorter.html.php';?>
<table align='center' class='table-5 tablesorter'>
  <thead>
  <tr class='colhead'>
    <th><?php echo $lang->team->account;?></th>
    <th><?php echo $lang->team->role;?></th>
    <th><?php echo $lang->team->join;?></th>
    <th><?php echo $lang->actions;?></th>
  </tr>
  </thead>
  <tbody>
  <?php $totalHours = 0;?>
  <?php foreach($teamMembers as $member):?>
  <tr class='a-center'>
    <td>
    <?php 
    if(!common::printLink('user', 'view', "account=$member->account", $member->realname)) print $member->realname;
    $memberHours = $member->days * $member->hours;
    $totalHours  += $memberHours;
    ?>
    </td>
    <td><?php echo $member->role;?></td>
    <td><?php echo substr($member->join, 0, 10);?></td>
    <td><?php common::printIcon('project', 'unlinkMember', "projectID=$project->id&account=$member->account", '', 'list', '', 'hiddenwin');?></td>
  </tr>
  <?php endforeach;?>
  </tbody>     
  <tfoot>
  <tr>
    <td colspan='7'>
      <div class='f-right'><?php common::printLink('project', 'managemembers', "projectID=$project->id", $lang->project->manageMembers);?></div>
      <div class='f-left'>
<strong>什么是项目的团队成员？</strong><br />
成为当前项目的团队成员后，该项目下任务的指派人列表选项中就会出现你的名字，这样你就可以自行认领任务，项目负责人也可以把任务分配给你。<br />
<strong>如何成为该项目的团队成员？</strong><br />
如果需要，请点击<a href="index.php?m=task&f=create&project=5" target="_blank" style="color:#67B400;font-weight:bold;">申请加入</a>，在任务名称注明“申请加入XXX项目团队”，任务类型选择“日常事务”即可，管理员会尽快处理你的请求。
</div>
    </td>
  </tr>
  </tfoot>
</table>
<?php include '../../../common/view/footer.html.php';?>
