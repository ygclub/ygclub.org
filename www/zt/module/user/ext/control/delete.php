<?php
include '../../control.php';
class myuser extends user
{
    public function delete($userID, $confirm='no')
    {
         if($confirm == 'no')
        {
            die(js::confirm($this->lang->user->confirmDelete, $this->createLink('user', 'delete', "userID=$userID&confirm=yes")));
        }
        else
        {
			$user=$this->user->getById($userID);
			
            $this->user->delete(TABLE_USER, $userID);

			//此处删除成功,同步删除ucenter数据
			//$this->loadModel('ucenter');
			//$this->ucenter->uc_deleteuser($user->account);
			//删除成功，同步应用
			//$this->ucenter->uc_
            die(js::locate($this->createLink('company', 'browse'), 'parent'));
        }
    }
}
