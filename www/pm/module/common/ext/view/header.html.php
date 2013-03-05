<?php
if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}
include 'header.lite.html.php';
include 'colorbox.html.php';
include 'chosen.html.php';
//include 'validation.html.php';
?>
<?php if(empty($_GET['onlybody']) or $_GET['onlybody'] != 'yes'):?>
<div id='header'>
  <table class='cont' id='topbar'>
    <tr>
      <td class='w-p50'>
        <?php
        echo "<span id='companyname'>{$app->company->name}</span> ";
        if($app->company->website)  echo html::a($app->company->website,  $lang->company->website,  '_blank');
        if($app->company->backyard) echo html::a($app->company->backyard, $lang->company->backyard, '_blank');
        ?>
      </td>
      <td class='a-right'>
        <?php if(isset($app->user->account) && $app->user->account!= 'guest'): ?>
            <?php 
                echo $app->user->realname . ', ';
                echo html::a(helper::createLink('user', 'logout'), $lang->logout);
            ?>
        <?php else: ?>
            <form method='post' target='hiddenwin' action='index.php?m=user&f=login'>
            <?php echo $lang->user->account;?>：
            <input size='10' type='text' name='account' id='account' />
            <?php echo $lang->user->password;?>：
            <input size='10' type='password' name='password' />
            <!-- <?php echo html::checkBox('keepLogin', $lang->user->keepLogin, $keepLogin);?> -->
            <?php echo html::submitButton($lang->login, "style='height:20px;font-size:11px;padding:0'"); ?>
            </form>
        <?php endif; ?>
      </td>
    </tr>
  </table>
  <table class='cont' id='navbar'>
    <tr><td id='mainmenu'><?php commonModel::printMainmenu($this->moduleName); ?></td></tr>
  </table>
</div>
<table class='cont' id='navbar'><tr><td id='modulemenu'><?php commonModel::printModuleMenu($this->moduleName);?></td></tr></table>
<div id='wrap'>
<?php endif;?>
  <div class='outer'>
