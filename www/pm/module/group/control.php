<?php
/**
 * The control file of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     group
 * @version     $Id: control.php 4364 2013-02-19 00:36:09Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
class group extends control
{
    /**
     * Construct function.
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('company')->setMenu();
        $this->loadModel('user');
    }

    /**
     * Browse groups.
     * 
     * @param  int    $companyID 
     * @access public
     * @return void
     */
    public function browse($companyID = 0)
    {
        if($companyID == 0) $companyID = $this->app->company->id;

        $title      = $this->lang->company->orgView . $this->lang->colon . $this->lang->group->browse;
        $position[] = $this->lang->group->browse;

        $groups = $this->group->getList($companyID);

        $this->view->title    = $title;
        $this->view->position = $position;
        $this->view->groups   = $groups;

        $this->display();
    }

    /**
     * Create a group.
     * 
     * @access public
     * @return void
     */
    public function create()
    {
        if(!empty($_POST))
        {
            $this->group->create();
            if(dao::isError()) die(js::error(dao::getError()));
            die(js::locate($this->createLink('group', 'browse'), 'parent'));
        }

        $this->view->title      = $this->lang->company->orgView . $this->lang->colon . $this->lang->group->create;
        $this->view->position[] = $this->lang->group->create;
        $this->display();
    }

    /**
     * Edit a group.
     * 
     * @param  int    $groupID 
     * @access public
     * @return void
     */
    public function edit($groupID)
    {
       if(!empty($_POST))
        {
            $this->group->update($groupID);
            die(js::locate($this->createLink('group', 'browse'), 'parent'));
        }

        $title      = $this->lang->company->orgView . $this->lang->colon . $this->lang->group->edit;
        $position[] = $this->lang->group->edit;
        $this->view->title    = $title;
        $this->view->position = $position;
        $this->view->group    = $this->group->getById($groupID);

        $this->display();
    }

    /**
     * Copy a group.
     * 
     * @param  int    $groupID 
     * @access public
     * @return void
     */
    public function copy($groupID)
    {
       if(!empty($_POST))
        {
            $this->group->copy($groupID);
            if(dao::isError()) die(js::error(dao::getError()));
            die(js::locate($this->createLink('group', 'browse'), 'parent'));
        }

        $this->view->title      = $this->lang->company->orgView . $this->lang->colon . $this->lang->group->copy;
        $this->view->position[] = $this->lang->group->copy;
        $this->view->group      = $this->group->getById($groupID);
        $this->display();
    }

    /**
     * Manage privleges of a group. 
     * 
     * @param  int    $groupID 
     * @access public
     * @return void
     */
    public function managePriv($type = 'byGroup', $param = 0, $menu = '', $version = '')
    {
        if($type == 'byGroup') $groupID = $param;
        $this->view->type = $type;
        foreach($this->lang->resource as $moduleName => $action)
        {
            if($this->group->checkMenuModule($menu, $moduleName) or $type != 'byGroup') $this->app->loadLang($moduleName);
        }

        if(!empty($_POST))
        {
            if($type == 'byGroup')  $result = $this->group->updatePrivByGroup($groupID, $menu, $version);
            if($type == 'byModule') $result = $this->group->updatePrivByModule();
            print(js::alert($result ? $this->lang->group->successSaved : $this->lang->group->errorNotSaved));
            exit;
        }

        if($type == 'byGroup')
        {
            $this->group->sortResource();
            $group      = $this->group->getById($groupID);
            $groupPrivs = $this->group->getPrivs($groupID);

            $this->view->title      = $this->lang->company->common . $this->lang->colon . $group->name . $this->lang->colon . $this->lang->group->managePriv;
            $this->view->position[] = $group->name . $this->lang->colon . $this->lang->group->managePriv;

            /* Join changelog when be equal or greater than this version.*/
            $realVersion = str_replace('_', '.', $version);
            $changelog = array();
            foreach($this->lang->changelog as $currentVersion => $currentChangeLog)
            {
                if(version_compare($currentVersion, $realVersion, '>=')) $changelog[] = join($currentChangeLog, ',');
            }

            $this->view->group      = $group;
            $this->view->changelogs = ',' . join($changelog, ',') . ',';
            $this->view->groupPrivs = $groupPrivs;
            $this->view->groupID    = $groupID;
            $this->view->menu       = $menu;
            $this->view->version    = $version;
        }
        elseif($type == 'byModule')
        {
            $this->group->sortResource();
            $this->view->title      = $this->lang->company->common . $this->lang->colon . $this->lang->group->managePriv;
            $this->view->position[] = $this->lang->group->managePriv;

            foreach($this->lang->resource as $module => $moduleActions)
            {
                $modules[$module] = $this->lang->$module->common;
                foreach($moduleActions as $action)
                {
                    $actions[$module][$action] = $this->lang->$module->$action;
                }
            }
            $this->view->groups  = $this->group->getPairs();
            $this->view->modules = $modules;
            $this->view->actions = $actions;
        }
        $this->display();
    }

    /**
     * Manage members of a group.
     * 
     * @param  int    $groupID 
     * @access public
     * @return void
     */
    public function manageMember($groupID)
    {
        if(!empty($_POST))
        {
            $this->group->updateUser($groupID);
            die(js::locate($this->createLink('group', 'browse'), 'parent'));
        }
        $group      = $this->group->getById($groupID);
        $groupUsers = $this->group->getUserPairs($groupID);
        $allUsers   = $this->user->getPairs('nodeleted|noclosed|noempty|noletter');
        $otherUsers = array_diff_assoc($allUsers, $groupUsers);

        $title      = $this->lang->company->common . $this->lang->colon . $group->name . $this->lang->colon . $this->lang->group->manageMember;
        $position[] = $group->name . $this->lang->colon . $this->lang->group->manageMember;

        $this->view->title      = $title;
        $this->view->position   = $position;
        $this->view->group      = $group;
        $this->view->groupUsers = $groupUsers;
        $this->view->otherUsers = $otherUsers;

        $this->display();
    }

    /**
     * Delete a group.
     * 
     * @param  int    $groupID 
     * @param  string $confirm  yes|no
     * @access public
     * @return void
     */
    public function delete($groupID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->group->confirmDelete, $this->createLink('group', 'delete', "groupID=$groupID&confirm=yes")));
        }
        else
        {
            $this->group->delete($groupID);
            die(js::locate($this->createLink('group', 'browse'), 'parent'));
        }
    }
}
