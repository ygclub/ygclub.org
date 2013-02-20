<?php
include "../../control.php";
class myaction extends action
{
    public function undelete($actionID)
    {

		$action = $this->loadModel('action')->getById($actionID);
        if($action->action != 'deleted') return;

        $table = $this->config->action->objectTables[$action->objectType];
		$this->action->undelete($actionID);

		//还原后，重新增加数据至ucenter
        $this->loadModel('user');
		$user=$this->user->getById($action->objectID);
		if($user)
		{
			$this->loadModel('ucenter');
			$this->ucenter->uc_adduser($user->account,$user->password,$user->email);
		}
        die(js::locate(inlink('trash'), 'parent'));
    }

}