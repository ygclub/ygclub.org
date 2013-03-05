<?php
public function updateUserName($oldname,$newname)
{
   $this->dao->update(TABLE_USER)->set('account')->eq($newname)->where('account')->eq($oldname)->exec();
}