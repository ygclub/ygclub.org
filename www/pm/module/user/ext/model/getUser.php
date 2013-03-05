
    public function getUser($account)
    {
         $user = $this->dao->select('*')->from(TABLE_USER)
            ->where('account')->eq($account)
            ->fetch();

         return $user;
    }