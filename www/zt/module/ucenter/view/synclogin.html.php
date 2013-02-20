<?php
/**
 * The config email view file of mail module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     mail
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>

<table class='table-4' align='center'>
<caption><?php echo $lang->ucenter->loginsuccess ?></caption>
<tr>
<td><?php echo $lang->ucenter->logininfo ?><span style="width:30px;color:red;" id="dvtime">3</span><?php echo $lang->ucenter->logininfo2 ?><br /><br /><a href="<?php echo $linkurl; ?>"><?php echo $lang->ucenter->loginlink ?></a>
<br />
<?php echo "<script type='text/javascript'> var _linkurl='".$linkurl."';</script>"; ?>
</td>
</tr>
</table>
<?php
	if($uid)
	{
		$synjs=$this->ucenter->uc_sysn_login($uid);
		echo $synjs;
	}
?>
<iframe src="?m=ucenter&f=synclogin&hide=1" width="0" height="0" ></iframe>
<?php include '../../common/view/footer.html.php';?>