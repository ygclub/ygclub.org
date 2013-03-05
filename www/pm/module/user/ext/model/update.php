<?php
 public function update($userID)
{
	if(!$this->checkPassword()) return;

	$oldUser = $this->getById($userID);

	//
	
	$userID = (int)$userID;
	$user = fixer::input('post')
		->setDefault('join', '0000-00-00')
		->setIF($this->post->password1 != false, 'password', md5($this->post->password1))
		->remove('password1, password2')
		->specialChars('msn,qq,yahoo,gtalk,wangwang,mobile,phone,address,zipcode')
		->get();

	$this->dao->update(TABLE_USER)->data($user)
		->autoCheck()
		->batchCheck($this->config->user->edit->requiredFields, 'notempty')
		->check('account', 'unique', "id != '$userID'")
		->check('account', 'account')
		->checkIF($this->post->email != false, 'email', 'email')
		->where('id')->eq((int)$userID)
		->exec();

	/* If account changed, update the privilege. */
	if($this->post->account != $oldUser->account)
	{
		$this->dao->update(TABLE_USERGROUP)->set('account')->eq($this->post->account)->where('account')->eq($oldUser->account)->exec();
		if(strpos($this->app->company->admins, ',' . $oldUser->account . ',') !== false)
		{
			$admins = ',' . $this->post->account . ',';
			$this->dao->update(TABLE_COMPANY)->set('admins')->eq($admins)->where('id')->eq($this->app->company->id)->exec(false);
		}
	}
	
	//同步更新ucenter
	$this->loadModel('ucenter');
	//echo $this->post->email;
	$this->ucenter->uc_updateuser($oldUser->account,$this->post->account,$this->post->password1,$this->post->email);
}