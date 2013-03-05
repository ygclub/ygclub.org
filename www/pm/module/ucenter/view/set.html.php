<?php
/**
 * The config eucenter view file of ucenter module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     ucenter
 * @version     $Id$
 * @link        http://www.zentao.net
*/
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php
if(!defined('UC_API')) {
	define('UC_CONNECT','');
	define('UC_DBHOST','');
	define('UC_DBUSER','');
	define('UC_DBPW','');
	define('UC_DBNAME','');
	define('UC_DBCHARSET','');
	define('UC_DBTABLEPRE','');
	define('UC_DBCONNECT','');
	define('UC_KEY','');
	define('UC_API', '');
	define('UC_CHARSET','');
	define('UC_IP','');
	define('UC_APPID','');
	define('UC_PPP','');
}
?>
<form method='post' enctype='multipart/form-data' action='<?php echo inlink('save');?>'>
  <table align='left' class='table-1'>
  <caption><?php echo $lang->ucenter->setParam; ?></caption>
  <tr>
    <th class='rowhead'><?php echo $lang->ucenter->connect; ?></th>
    <td><?php echo html::select('connect', $lang->ucenter->connectList, UC_CONNECT, 'class=select-3'); ?></td>
  </tr>

  <tr>
    <th class='rowhead'><?php echo $lang->ucenter->dbhost; ?></th>
    <td><?php echo html::input('dbhost', UC_DBHOST, 'class=text-3') ?></td>
  </tr>
  <tr>
    <th class='rowhead'><?php echo $lang->ucenter->dbuser; ?></th>
    <td><?php echo html::input('dbuser', UC_DBUSER, 'class=text-3') ?></td>
  </tr>
  <tr>
    <th class='rowhead'><?php echo $lang->ucenter->dbpw; ?></th>
    <td><?php echo html::input('dbpw', UC_DBPW, 'class=text-3') ?></td>
  </tr>
  <tr>
    <th class='rowhead'><?php echo $lang->ucenter->dbname; ?></th>
    <td><?php echo html::input('dbname', UC_DBNAME, 'class=text-3') ?></td>
  </tr>
  <tr>
    <th class='rowhead'><?php echo $lang->ucenter->dbcharset; ?></th>
    <td><?php echo html::input('dbcharset', UC_DBCHARSET, 'class=text-3') ?></td>
  </tr>
  <tr>
    <th class='rowhead'><?php echo $lang->ucenter->dbtablepre; ?></th>
    <td><?php echo html::input('dbtablepre', UC_DBTABLEPRE, 'class=text-3') ?></td>
  </tr>
  <tr>
    <th class='rowhead'><?php echo $lang->ucenter->dbconnect; ?></th>
    <td><?php echo html::input('dbconnect', UC_DBCONNECT, 'class=text-3') ?></td>
  </tr>
  <tr>
    <th class='rowhead'><?php echo $lang->ucenter->key; ?></th>
    <td><?php echo html::input('key', UC_KEY, 'class=text-3') ?></td>
  </tr>
  <tr>
    <th class='rowhead'><?php echo $lang->ucenter->api; ?></th>
    <td><?php echo html::input('api', UC_API, 'class=text-3') ?></td>
  </tr>
  <tr>
    <th class='rowhead'><?php echo $lang->ucenter->charset; ?></th>
    <td><?php echo html::input('charset', UC_CHARSET, 'class=text-3') ?></td>
  </tr>
  <tr>
    <th class='rowhead'><?php echo $lang->ucenter->ip; ?></th>
    <td><?php echo html::input('ip', UC_IP, 'class=text-3') ?></td>
  </tr>
  <tr>
    <th class='rowhead'><?php echo $lang->ucenter->appid; ?></th>
    <td><?php echo html::input('appid', UC_APPID, 'class=text-3') ?></td>
  </tr>
  <tr>
    <th class='rowhead'><?php echo $lang->ucenter->ppp; ?></th>
    <td><?php echo html::input('ppp', UC_PPP, 'class=text-3') ?></td>
  </tr>
  <tr>
    <td colspan='2' class='a-center'><?php echo html::submitButton();?></td>
  </tr>
  </table>
</form>
