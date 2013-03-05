<?php
public function deleteUser($usernames)
{
   $this->dao->delete()->from(TABLE_USER)->where('account')->in($usernames)->exec();
}