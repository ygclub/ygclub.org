<table class='table-6 fixed colored'>
  <caption>
    <div class='f-left'><span class='icon-doing'>&nbsp;</span><?php echo $lang->my->task;?></div>
  </caption>
  <?php 
  foreach($tasks as $task)
  {
      echo "<tr><td class='nobr'>" . "#$task->id " . html::a($this->createLink('task', 'view', "id=$task->id"), $task->name) . "</td><td width='5'</td></tr>";
  }
  ?>
</table>
