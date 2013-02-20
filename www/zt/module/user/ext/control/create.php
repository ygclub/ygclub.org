<?php
include "../../control.php";

class myuser extends user
{
    public function create($deptID = 0)
    {
        $this->lang->set('menugroup.user', 'company');
        $this->lang->user->menu = $this->lang->company->menu;

        if(!empty($_POST))
        {
            $this->user->create();
            if(dao::isError()) 
			{
				die(js::error(dao::getError()));
			}
			else
			{
				//向ucenter同步添加用户
				$this->loadModel('ucenter');

				$uid = $this->ucenter->uc_adduser($this->post->account, $this->post->password1, $this->post->email);

				die(js::locate($this->createLink('company', 'browse'), 'parent'));
			}
        }

        $header['title'] = $this->lang->company->common . $this->lang->colon . $this->lang->user->create;
        $position[]      = $this->lang->user->create;
        $this->view->header   = $header;
        $this->view->position = $position;
        $this->view->depts    = $this->dept->getOptionMenu();
        $this->view->deptID   = $deptID;

        $this->display();
    }


}