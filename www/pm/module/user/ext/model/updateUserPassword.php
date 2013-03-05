<?php
public function updateUserPassword($account,$password)
{
   $this->dao->update(TABLE_USER)->set('password')->eq(md5($password))->where('account')->eq($account)->exec();
}