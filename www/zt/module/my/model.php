<?php
/**
 * The model file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id: model.php 3797 2012-12-13 08:44:08Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php
class myModel extends model
{
    /**
     * Set menu.
     * 
     * @access public
     * @return void
     */
    public function setMenu()
    {
        $this->lang->my->menu->account = sprintf($this->lang->my->menu->account, $this->app->user->realname);

        /* Adjust the menu order according to the user role. */
        $role = $this->app->user->role;
        if($role == 'qa')
        {
            unset($this->lang->my->menuOrder[20]);
            $this->lang->my->menuOrder[32] = 'task';
        }
        elseif($role == 'po')
        {
            unset($this->lang->my->menuOrder[35]);
            unset($this->lang->my->menuOrder[20]);
            $this->lang->my->menuOrder[17] = 'story';
            $this->lang->my->menuOrder[42] = 'task';
        }
        elseif($role == 'pm')
        {
            unset($this->lang->my->menuOrder[40]);
            $this->lang->my->menuOrder[17] = 'myProject';
        }
    }
}
