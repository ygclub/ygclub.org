<?php
public function addUser($user)
{
   $this->dao->insert(TABLE_USER)->data($user)->exec();
}